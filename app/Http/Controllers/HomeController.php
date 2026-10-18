<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\MassSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $announcements = \Illuminate\Support\Facades\Cache::remember('home_announcements', now()->addMinutes(30), function() {
            return Announcement::active()
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        $now = Carbon::now('Africa/Abidjan');
        $nextMass = $this->findNextMass($now);

        $upcomingEvents = Event::where('is_published', true)
            ->get()
            ->map(function (Event $event) use ($now) {
                $occurrence = $this->nextEventOccurrence($event, $now);
                if ($occurrence === null) {
                    return null;
                }

                $event->setAttribute('home_occurrence_date', $occurrence['date']->toDateString());
                $event->setAttribute('home_occurrence_at', $occurrence['at']->timestamp);
                $event->setAttribute('home_transition_at', $occurrence['transition']->timestamp);

                return $event;
            })
            ->filter()
            ->sortBy('home_occurrence_at')
            ->take(2)
            ->values();

        $refreshTimes = $upcomingEvents->pluck('home_transition_at')->all();
        if ($nextMass) {
            $refreshTimes[] = $nextMass->home_transition_at;
        }
        $nextHomeRefreshAt = $refreshTimes ? min($refreshTimes) : null;

        return view('home', compact('announcements', 'nextMass', 'upcomingEvents', 'nextHomeRefreshAt'));
    }

    private function findNextMass(Carbon $now): ?MassSchedule
    {
        $timezone = $now->getTimezone()->getName();
        $nextMass = null;
        $nextMassAt = null;

        foreach (MassSchedule::where('is_active', true)->get() as $mass) {
            $days = is_array($mass->day_of_week) ? $mass->day_of_week : (array) $mass->day_of_week;
            $days = array_map(fn ($day) => strtolower(trim((string) $day)), $days);

            if ($mass->mass_type === 'sunday') {
                $days[] = 'sunday';
            } elseif (in_array($mass->mass_type, ['saturday', 'anticipated'], true)) {
                $days[] = 'saturday';
            }

            if ($mass->specific_date) {
                $dates = [Carbon::parse($mass->specific_date->toDateString(), $timezone)->startOfDay()];
            } else {
                $dates = [];
                for ($offset = 0; $offset <= 7; $offset++) {
                    $date = $now->copy()->startOfDay()->addDays($offset);
                    if (in_array(strtolower($date->format('l')), $days, true)) {
                        $dates[] = $date;
                    }
                }
            }

            $times = is_array($mass->time) ? $mass->time : [$mass->time];
            foreach ($dates as $date) {
                foreach ($times as $time) {
                    if (is_array($time)) {
                        $time = $time['time'] ?? null;
                    }
                    if (! is_string($time) || trim($time) === '') {
                        continue;
                    }

                    try {
                        $parsedTime = Carbon::parse(trim($time), $timezone);
                    } catch (\Throwable $exception) {
                        continue;
                    }

                    $candidate = $date->copy()->setTime($parsedTime->hour, $parsedTime->minute);
                    if ($candidate->lessThanOrEqualTo($now)) {
                        continue;
                    }

                    if ($nextMassAt === null || $candidate->lessThan($nextMassAt)) {
                        $locale = app()->getLocale();
                        $localizedDate = $candidate->copy()->locale($locale);
                        $day = $candidate->isSameDay($now)
                            ? ($locale === 'fr' ? 'Aujourd’hui' : 'Today')
                            : ($candidate->isSameDay($now->copy()->addDay())
                                ? ($locale === 'fr' ? 'Demain' : 'Tomorrow')
                                : ucfirst($localizedDate->isoFormat('dddd')));

                        $nextMass = clone $mass;
                        $nextMass->calculated_day = $day;
                        $nextMass->calculated_time = $localizedDate->isoFormat($locale === 'fr' ? 'HH:mm' : 'h:mm A');
                        $nextMass->home_transition_at = $candidate->copy()->addSecond()->timestamp;
                        $nextMassAt = $candidate;
                    }
                }
            }
        }

        return $nextMass;
    }

    private function nextEventOccurrence(Event $event, Carbon $now): ?array
    {
        if (! $event->event_date) {
            return null;
        }

        $timezone = $now->getTimezone()->getName();
        $eventDate = Carbon::parse($event->event_date->toDateString(), $timezone)->startOfDay();
        $slots = is_array($event->event_time) ? $event->event_time : ($event->event_time ? [$event->event_time] : []);
        if (isset($slots['time'])) {
            $slots = [$slots];
        }

        if ($slots === []) {
            if ($eventDate->copy()->endOfDay()->lessThanOrEqualTo($now)) {
                return null;
            }

            return [
                'date' => $eventDate->greaterThan($now) ? $eventDate : $now->copy()->startOfDay(),
                'at' => $eventDate->greaterThan($now) ? $eventDate : $now,
                'transition' => $eventDate->copy()->endOfDay()->addSecond(),
            ];
        }

        $nextOccurrence = null;
        foreach ($slots as $slot) {
            $details = is_array($slot) ? $slot : ['time' => $slot];
            try {
                $startDate = ! empty($details['date'])
                    ? Carbon::parse($details['date'], $timezone)->startOfDay()
                    : $eventDate->copy();
                $endDate = ! empty($details['date_to'])
                    ? Carbon::parse($details['date_to'], $timezone)->startOfDay()
                    : $startDate->copy();
            } catch (\Throwable $exception) {
                continue;
            }

            if ($endDate->copy()->endOfDay()->lessThanOrEqualTo($now)) {
                continue;
            }

            if ($endDate->greaterThan($startDate)) {
                $occurrence = [
                    'date' => $startDate->greaterThan($now) ? $startDate : $now->copy()->startOfDay(),
                    'at' => $startDate->greaterThan($now) ? $startDate : $now,
                    'transition' => $endDate->copy()->endOfDay()->addSecond(),
                ];
            } elseif (empty($details['time'])) {
                $occurrence = [
                    'date' => $startDate->greaterThan($now) ? $startDate : $now->copy()->startOfDay(),
                    'at' => $startDate->greaterThan($now) ? $startDate : $now,
                    'transition' => $startDate->copy()->endOfDay()->addSecond(),
                ];
            } else {
                try {
                    $parsedTime = Carbon::parse(trim((string) $details['time']), $timezone);
                } catch (\Throwable $exception) {
                    continue;
                }

                $occurrenceAt = $startDate->copy()->setTime($parsedTime->hour, $parsedTime->minute);
                if ($occurrenceAt->lessThanOrEqualTo($now)) {
                    continue;
                }

                $occurrence = [
                    'date' => $startDate,
                    'at' => $occurrenceAt,
                    'transition' => $occurrenceAt->copy()->addSecond(),
                ];
            }

            if ($nextOccurrence === null || $occurrence['at']->lessThan($nextOccurrence['at'])) {
                $nextOccurrence = $occurrence;
            }
        }

        return $nextOccurrence;
    }
}
