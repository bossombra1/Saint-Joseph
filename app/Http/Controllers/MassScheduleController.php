<?php

namespace App\Http\Controllers;

use App\Models\MassSchedule;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;

class MassScheduleController extends Controller
{
    public function index()
    {
        $schedules = Cache::remember('public_mass_schedules', now()->addHours(24), function () {
            return MassSchedule::where('is_active', true)
                ->orderByRaw('time->>0 asc')
                ->get()
                ->groupBy('mass_type');
        });

        return view('mass-schedule', compact('schedules'));
    }

    public function generateICal($id)
    {
        $schedule = MassSchedule::findOrFail($id);

        $title = $schedule->title ?: 'Mass Schedule';
        $location = 'Paroisse Saint-Joseph Artisan, Pacita';

        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//Sto Rosario Parish//NONSGML Event Calendar//EN\r\n";

        $times = is_array($schedule->time) ? $schedule->time : [$schedule->time];
        $byDay = $this->getByDay($schedule);

        foreach ($times as $time) {
            $startTime = Carbon::parse($time)->setTimezone('Asia/Manila');

            // A specific-date schedule is a one-off event; a day-of-week
            // schedule is a weekly recurrence that starts on its next
            // matching weekday (deterministic per schedule).
            $base = $schedule->specific_date
                ? Carbon::parse($schedule->specific_date)->toImmutable()
                : $this->nextOccurrence($byDay);

            $dtStart = $base->setTime($startTime->hour, $startTime->minute);
            $dtEnd = $dtStart->addHour();

            // Deterministic UID so calendar clients dedupe across refreshes.
            $uid = 'mass-schedule-'.$schedule->id.'-'.substr($startTime->format('Hi'), 0, 4).'@storosario.ph';

            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= 'UID:'.$uid."\r\n";
            $ical .= 'DTSTAMP:'.now()->format('Ymd\THis\Z')."\r\n";
            $ical .= 'DTSTART:'.$dtStart->format('Ymd\THis')."\r\n";
            $ical .= 'DTEND:'.$dtEnd->format('Ymd\THis')."\r\n";
            $ical .= 'SUMMARY:'.$this->escapeICal($title)."\r\n";
            $ical .= 'LOCATION:'.$this->escapeICal($location)."\r\n";

            if (! empty($byDay) && ! $schedule->specific_date) {
                $ical .= 'RRULE:FREQ=WEEKLY;BYDAY='.implode(',', $byDay)."\r\n";
            }

            $ical .= "END:VEVENT\r\n";
        }

        $ical .= 'END:VCALENDAR';

        return response($ical)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="mass-schedule-'.$id.'.ics"');
    }

    /**
     * RFC 5545-escaped text value.
     */
    protected function escapeICal(string $value): string
    {
        return str_replace(
            ['\\', "\r", "\n", ';', ','],
            ['\\\\', '\\r', '\\n', '\\;', '\\,'],
            $value
        );
    }

    /**
     * Map stored day names to RFC 5545 BYDAY tokens.
     */
    protected function getByDay(MassSchedule $schedule): array
    {
        $days = is_array($schedule->day_of_week) ? $schedule->day_of_week : (is_null($schedule->day_of_week) ? [] : [$schedule->day_of_week]);

        $dayMap = [
            'sunday' => 'SU', 'monday' => 'MO', 'tuesday' => 'TU',
            'wednesday' => 'WE', 'thursday' => 'TH', 'friday' => 'FR', 'saturday' => 'SA',
            'Sunday' => 'SU', 'Monday' => 'MO', 'Tuesday' => 'TU',
            'Wednesday' => 'WE', 'Thursday' => 'TH', 'Friday' => 'FR', 'Saturday' => 'SA',
        ];

        $byDay = [];
        foreach ($days as $d) {
            if (isset($dayMap[$d]) && ! in_array($dayMap[$d], $byDay, true)) {
                $byDay[] = $dayMap[$d];
            }
        }

        return $byDay;
    }

    /**
     * The next date that falls on one of the recurrence weekdays.
     */
    protected function nextOccurrence(array $byDay): CarbonInterface
    {
        $today = now('Asia/Manila')->startOfDay();

        foreach (range(0, 6) as $offset) {
            $candidate = $today->copy()->addDays($offset);
            $candidateDay = strtoupper(substr($candidate->format('l'), 0, 2));

            if (in_array($candidateDay, array_map('strtoupper', $byDay), true)) {
                return $candidate->toImmutable();
            }
        }

        return $today->addDays(7)->toImmutable();
    }
}
