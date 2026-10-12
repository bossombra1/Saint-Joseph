<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Inquiry;
use App\Models\MassIntention;
use App\Models\MassSchedule;
use Illuminate\Support\Carbon;

class ChatbotTools
{
    /**
     * Return JSON schema definitions for OpenAI/OpenRouter/Groq tool calling.
     */
    public static function getToolDefinitions(): array
    {
        return [
            [
                'type' => 'function',
                'function' => [
                    'name' => 'check_intention_status',
                    'description' => 'Look up the live review status, details, and schedule of a Mass Intention by reference number (e.g. INT-2026-0001 or UUID).',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'reference_id' => [
                                'type' => 'string',
                                'description' => 'The reference number of the mass intention, e.g. INT-2026-0001 or SRP-2026-0001.',
                            ],
                        ],
                        'required' => ['reference_id'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'check_inquiry_status',
                    'description' => 'Look up the live status and updates of a submitted inquiry (Baptism, Wedding, etc.) by tracking reference ID (e.g. INQ-2026-0001).',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'reference_id' => [
                                'type' => 'string',
                                'description' => 'The reference ID of the inquiry, e.g. INQ-2026-0001.',
                            ],
                        ],
                        'required' => ['reference_id'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_mass_schedules',
                    'description' => 'Search and filter active mass schedules by day of week or mass type (e.g. Sunday, Weekday, Anticipated, English, Tagalog).',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'day_of_week' => [
                                'type' => 'string',
                                'description' => 'Filter by day of the week, e.g. Sunday, Monday, Wednesday, Saturday.',
                            ],
                            'mass_type' => [
                                'type' => 'string',
                                'description' => 'Optional mass type e.g. Sunday Mass, Weekday Mass, Anticipated Mass.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_upcoming_events',
                    'description' => 'Get upcoming parish activities, fiestas, seminars, or liturgical events.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'search' => [
                                'type' => 'string',
                                'description' => 'Optional keyword to search event titles or descriptions.',
                            ],
                            'limit' => [
                                'type' => 'integer',
                                'description' => 'Maximum number of events to return (default 5).',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'search_announcements',
                    'description' => 'Search recent parish news, bulletins, and public announcements.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'query' => [
                                'type' => 'string',
                                'description' => 'Keyword to search inside announcement title or body.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_sacrament_requirements',
                    'description' => 'Retrieve the exact document checklist, guidelines, and processing fees for a specific sacrament.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'sacrament_type' => [
                                'type' => 'string',
                                'enum' => ['baptism', 'wedding', 'confirmation', 'first_communion', 'funeral', 'mass_intention', 'certificate'],
                                'description' => 'The specific sacrament or service requested.',
                            ],
                        ],
                        'required' => ['sacrament_type'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Execute a tool call by name with provided arguments.
     */
    public function execute(string $name, array $args): array
    {
        switch ($name) {
            case 'check_intention_status':
                return $this->checkIntentionStatus($args['reference_id'] ?? '');

            case 'check_inquiry_status':
                return $this->checkInquiryStatus($args['reference_id'] ?? '');

            case 'get_mass_schedules':
                return $this->getMassSchedules($args['day_of_week'] ?? null, $args['mass_type'] ?? null);

            case 'get_upcoming_events':
                return $this->getUpcomingEvents($args['search'] ?? null, $args['limit'] ?? 5);

            case 'search_announcements':
                return $this->searchAnnouncements($args['query'] ?? null);

            case 'get_sacrament_requirements':
                return $this->getSacramentRequirements($args['sacrament_type'] ?? '');

            default:
                return ['error' => "Unknown tool: {$name}"];
        }
    }

    protected function checkIntentionStatus(string $refId): array
    {
        $refId = trim($refId);
        if (empty($refId)) {
            return ['found' => false, 'message' => 'Reference ID is required.'];
        }

        $query = MassIntention::where('reference_number', $refId);
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $refId)) {
            $query->orWhere('id', $refId);
        }
        $intention = $query->first();

        if (!$intention) {
            return [
                'found' => false,
                'message' => "No mass intention found with reference '{$refId}'. Please verify the reference code (e.g., INT-2026-0001).",
            ];
        }

        return [
            'found' => true,
            'reference_number' => $intention->reference_number,
            'intention_type' => $intention->intention_type,
            'status' => $intention->status,
            'preferred_date' => $intention->preferred_date ? $intention->preferred_date->format('F d, Y') : 'N/A',
            'mass_time' => $intention->mass_time ?? 'N/A',
            'raw_message' => $intention->raw_message,
            'payment_status' => $intention->payment_status ?? 'N/A',
            'rejection_reason' => $intention->rejection_reason,
            'track_url' => '/track',
        ];
    }

    protected function checkInquiryStatus(string $refId): array
    {
        $refId = trim($refId);
        if (empty($refId)) {
            return ['found' => false, 'message' => 'Inquiry reference ID is required.'];
        }

        $query = Inquiry::where('reference_id', $refId);
        if (is_numeric($refId)) {
            $query->orWhere('id', $refId);
        }
        $inquiry = $query->first();

        if (!$inquiry) {
            return [
                'found' => false,
                'message' => "No inquiry found matching '{$refId}'. Please double-check your tracking code (e.g. INQ-2026-0001).",
            ];
        }

        return [
            'found' => true,
            'reference_id' => $inquiry->reference_id,
            'inquiry_type' => $inquiry->inquiry_type,
            'status' => $inquiry->status,
            'preferred_date' => $inquiry->preferred_date ? $inquiry->preferred_date->format('F d, Y') : 'N/A',
            'message' => $inquiry->message,
            'rejection_reason' => $inquiry->rejection_reason,
            'track_url' => '/track',
        ];
    }

    protected function getMassSchedules(?string $day = null, ?string $massType = null): array
    {
        $query = MassSchedule::where('is_active', true);

        if ($massType) {
            $query->where('mass_type', 'like', "%{$massType}%");
        }

        $schedules = $query->get();

        if ($day) {
            $dayLower = strtolower(trim($day));
            $schedules = $schedules->filter(function ($s) use ($dayLower) {
                $days = is_array($s->day_of_week) ? $s->day_of_week : [$s->day_of_week];
                foreach ($days as $d) {
                    if (str_contains(strtolower((string)$d), $dayLower)) {
                        return true;
                    }
                }
                return false;
            });
        }

        if ($schedules->isEmpty()) {
            return [
                'count' => 0,
                'message' => 'No active mass schedules found for the specified criteria.',
                'link' => '/mass-schedule',
            ];
        }

        $results = [];
        foreach ($schedules as $s) {
            $results[] = [
                'title' => $s->title,
                'mass_type' => $s->mass_type,
                'days' => is_array($s->day_of_week) ? implode(', ', $s->day_of_week) : $s->day_of_week,
                'times' => is_array($s->time) ? implode(', ', $s->time) : $s->time,
                'location' => $s->location,
            ];
        }

        return [
            'count' => count($results),
            'schedules' => $results,
            'link' => '/mass-schedule',
        ];
    }

    protected function getUpcomingEvents(?string $search = null, int $limit = 5): array
    {
        $query = Event::where('is_published', true)
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $events = $query->take($limit)->get();

        if ($events->isEmpty()) {
            return [
                'count' => 0,
                'message' => 'No upcoming events found matching the criteria.',
                'link' => '/events',
            ];
        }

        $results = [];
        foreach ($events as $e) {
            $timeStr = is_array($e->event_time) ? json_encode($e->event_time) : (string)$e->event_time;
            $results[] = [
                'title' => $e->title,
                'date' => $e->event_date ? $e->event_date->format('F d, Y') : 'TBA',
                'time' => $timeStr,
                'description' => strip_tags($e->description),
            ];
        }

        return [
            'count' => count($results),
            'events' => $results,
            'link' => '/events',
        ];
    }

    protected function searchAnnouncements(?string $query = null): array
    {
        $q = Announcement::where('is_published', true)->orderBy('published_at', 'desc');

        if ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            });
        }

        $announcements = $q->take(5)->get();

        if ($announcements->isEmpty()) {
            return [
                'count' => 0,
                'message' => 'No recent announcements found.',
                'link' => '/bulletins',
            ];
        }

        $results = [];
        foreach ($announcements as $a) {
            $results[] = [
                'title' => $a->title,
                'published_at' => $a->published_at ? $a->published_at->format('M d, Y') : 'N/A',
                'summary' => strip_tags(mb_strimwidth($a->content, 0, 200, '...')),
            ];
        }

        return [
            'count' => count($results),
            'announcements' => $results,
            'link' => '/bulletins',
        ];
    }

    protected function getSacramentRequirements(string $type): array
    {
        $normalized = strtolower(str_replace([' ', '-'], '_', trim($type)));

        $data = [
            'baptism' => [
                'sacrament' => 'Holy Baptism (Binyag)',
                'fee' => '₱500.00 Registration Fee',
                'requirements' => [
                    "Child's PSA or Local Civil Registrar (LCR) Birth Certificate",
                    "Baptismal Permit (from parish of residence if residing outside Paroisse Saint-Joseph Artisan Pacita)",
                    "Pre-Jordan Catechetical Seminar for parents and primary sponsors/godparents",
                ],
                'schedule' => 'Regular Baptisms scheduled every Sunday; Special Baptisms by appointment with parish office.',
                'action_link' => '/inquiry',
            ],
            'wedding' => [
                'sacrament' => 'Holy Matrimony (Kasal)',
                'fee' => 'Contact parish office for package details and church stipend',
                'requirements' => [
                    "Recent Baptismal Certificate with annotation 'For Marriage Purpose' (valid within 6 months)",
                    "Recent Confirmation Certificate with annotation 'For Marriage Purpose' (valid within 6 months)",
                    "PSA Birth Certificate and CENOMAR (Certificate of No Marriage)",
                    "Marriage License (from Municipal Hall) or Civil Marriage Certificate (if already civilly married)",
                    "Pre-Cana / Marriage Preparation Seminar Certificate",
                    "Canonical Interview with the Parish Priest (at least 1-2 months before wedding)",
                    "Marriage Banns publication for 3 consecutive Sundays",
                ],
                'note' => 'Please coordinate and reserve at the parish office at least 3 months in advance.',
                'action_link' => '/inquiry',
            ],
            'confirmation' => [
                'sacrament' => 'Confirmation (Kumpil)',
                'fee' => 'Minimal administrative fee',
                'requirements' => [
                    'Photocopy of Baptismal Certificate',
                    'Parish Confirmation Seminar / Catechesis attendance',
                ],
                'action_link' => '/inquiry',
            ],
            'first_communion' => [
                'sacrament' => 'First Holy Communion (Unang Komunyon)',
                'fee' => 'Free / Covered in parish formation',
                'requirements' => [
                    'Photocopy of Baptismal Certificate',
                    'Completion of First Reconciliation and Catechetical preparation',
                ],
                'action_link' => '/inquiry',
            ],
            'funeral' => [
                'sacrament' => 'Funeral Mass & Wake Blessing',
                'fee' => 'Standard church stipend',
                'requirements' => [
                    'Photocopy of Certified Death Certificate',
                    'Schedule arrangement directly with Parish Office',
                ],
                'action_link' => '/inquiry',
            ],
            'mass_intention' => [
                'sacrament' => 'Mass Intention Offering (Pamisa)',
                'fee' => '₱500.00 per intention',
                'types' => ['Thanksgiving (Pasasalamat)', 'Healing / Recovery', 'Special Intentions', 'Repose of the Soul (Para sa Yumao)'],
                'action_link' => '/submit-intention',
            ],
            'certificate' => [
                'sacrament' => 'Sacramental Certificate Request (Baptismal / Confirmation / Marriage Record)',
                'fee' => '₱100.00 processing fee per copy',
                'requirements' => [
                    'Full Name of certificate holder',
                    'Approximate date or year of sacrament',
                    'Parents’ names',
                    'Valid ID upon claiming during office hours',
                ],
                'action_link' => '/inquiry',
            ],
        ];

        return $data[$normalized] ?? [
            'sacrament' => $type,
            'message' => 'Please contact the parish office for specific guidelines and checklist.',
            'office_hours' => 'Tue–Sat 6AM–12NN & 1:30–6PM | Sun 6AM–12NN & 3–6PM | Mon Closed',
            'action_link' => '/inquiry',
        ];
    }
}
