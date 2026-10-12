<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\MassSchedule;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class RAGService
{
    /**
     * Retrieve relevant parish context snippets based on user messages.
     * Uses hybrid keyword scoring over structured data and documents.
     */
    public function retrieveContext(string $query, int $topK = 5): string
    {
        $allChunks = $this->getAllKnowledgeChunks();
        $queryKeywords = $this->tokenize($query);

        if (empty($queryKeywords)) {
            return $this->getDefaultSummaryContext();
        }

        $scoredChunks = [];
        foreach ($allChunks as $chunk) {
            $score = $this->scoreChunk($chunk, $queryKeywords);
            if ($score > 0) {
                $scoredChunks[] = [
                    'chunk' => $chunk,
                    'score' => $score + ($chunk['priority'] ?? 0),
                ];
            }
        }

        // Sort chunks by highest score
        usort($scoredChunks, fn ($a, $b) => $b['score'] <=> $a['score']);

        $selected = array_slice($scoredChunks, 0, $topK);

        // If nothing specifically matched high threshold, include general parish info + schedules
        if (empty($selected)) {
            return $this->getDefaultSummaryContext();
        }

        $contextOutput = "CURRENT DATE & TIME: " . now('Asia/Manila')->format('l, F j, Y h:i A') . " (Philippine Time)\n\n";
        $contextOutput .= "### RELEVANT PARISH INFORMATION:\n";

        foreach ($selected as $item) {
            $contextOutput .= "- [" . strtoupper($item['chunk']['category']) . "] " . $item['chunk']['title'] . ":\n  " . $item['chunk']['content'] . "\n\n";
        }

        return trim($contextOutput);
    }

    /**
     * Get all knowledge chunks cached for fast hybrid retrieval.
     */
    public function getAllKnowledgeChunks(): array
    {
        return Cache::remember('parish_rag_knowledge_chunks', 300, function () {
            $chunks = [];

            // 1. Office Hours & Location
            $chunks[] = [
                'id' => 'office_hours',
                'category' => 'office',
                'title' => 'Office Hours and Location',
                'keywords' => ['office', 'hours', 'open', 'close', 'closed', 'location', 'address', 'saan', 'oras', 'bukas', 'pacita', 'san pedro', 'laguna'],
                'priority' => 1,
                'content' => "Address: Paroisse St Joseph https://maps.app.goo.gl/ineJKGS5ux346zJR9 4023.\nOffice Hours: Tuesday to Saturday: 6:00 AM – 12:00 NN, 1:30 PM – 6:00 PM; Sunday: 6:00 AM – 12:00 NN, 3:00 PM – 6:00 PM; Monday: Closed.",
            ];

            // 2. Donation & GCash Info
            $gcashNumber = Setting::where('key', 'gcash_number')->value('value') ?? '09123456789';
            $gcashName = Setting::where('key', 'gcash_name')->value('value') ?? 'Paroisse Saint-Joseph Artisan';
            $chunks[] = [
                'id' => 'donations',
                'category' => 'donations',
                'title' => 'Donation & GCash Details',
                'keywords' => ['donate', 'donation', 'gcash', 'bank', 'alay', 'tulong', 'ambag', 'bayad', 'payment'],
                'priority' => 1,
                'content' => "GCash Number: {$gcashNumber} (Account Name: {$gcashName}). Donations are voluntary and directly support parish pastoral operations, outreach, and church maintenance.",
            ];

            // 3. Sacramental Requirements
            $sacraments = [
                'baptism' => [
                    'title' => 'Baptism Requirements & Process',
                    'keywords' => ['baptism', 'binyag', 'baptize', 'godparent', 'ninong', 'ninang', 'birth certificate'],
                    'content' => "Requirements: Child's PSA/Local Civil Registrar Birth Certificate, Baptismal Permit (if outside Pacita Parish jurisdiction), Pre-Jordan seminar for parents and godparents. Processing fee: ₱500.00.",
                ],
                'wedding' => [
                    'title' => 'Wedding (Matrimony) Requirements & Process',
                    'keywords' => ['wedding', 'kasal', 'matrimony', 'marriage', 'cenomar', 'banns', 'license', 'seminar'],
                    'content' => "Requirements: Recent Baptismal & Confirmation Certificates with annotation 'For Marriage Purpose' (issued within 6 months), PSA Birth Certificate & CENOMAR, Marriage License or Civil Marriage Contract, Pre-Cana seminar, Canonical Interview. Coordinate with office at least 2-3 months prior.",
                ],
                'confirmation' => [
                    'title' => 'Confirmation (Kumpil) Requirements',
                    'keywords' => ['confirmation', 'kumpil', 'kumpil requirements'],
                    'content' => "Requirements: Photocopy of Baptismal Certificate, completion of Catechetical preparation/seminar.",
                ],
                'first_communion' => [
                    'title' => 'First Holy Communion Requirements',
                    'keywords' => ['communion', 'first communion', 'komunyon'],
                    'content' => "Requirements: Photocopy of Baptismal Certificate, catechism preparation through parish Sunday school or Catholic school program.",
                ],
                'funeral' => [
                    'title' => 'Funeral Mass & Blessing Requirements',
                    'keywords' => ['funeral', 'libing', 'patay', 'death certificate', 'cremation', 'wake blessing'],
                    'content' => "Requirements: Photocopy of Death Certificate, schedule coordination with parish office.",
                ],
                'mass_intention' => [
                    'title' => 'Mass Intentions Guidelines & Stipend',
                    'keywords' => ['mass intention', 'intention', 'pamisa', 'alay', 'thanksgiving', 'healing', 'repose', 'soul'],
                    'content' => "Stipend: ₱500.00 per mass intention. Types: Thanksgiving, Healing, Special Intention, Repose of the Soul. Submit online via /submit-intention.",
                ],
                'certificates' => [
                    'title' => 'Sacramental Records & Certificates Request',
                    'keywords' => ['certificate', 'katibayan', 'record', 'baptismal certificate', 'wedding certificate', 'confirmation certificate'],
                    'content' => "Request fee: ₱100.00 per certificate. Provide full name, date of sacrament, parents' names. Available for pick-up during office hours.",
                ],
            ];

            foreach ($sacraments as $key => $sac) {
                $chunks[] = [
                    'id' => "sacrament_{$key}",
                    'category' => 'sacrament',
                    'title' => $sac['title'],
                    'keywords' => array_merge([$key, 'sacrament', 'requirements', 'fee', 'bayad'], $sac['keywords']),
                    'priority' => 2,
                    'content' => $sac['content'],
                ];
            }

            // 4. Active Mass Schedules
            $schedules = MassSchedule::where('is_active', true)->get();
            if ($schedules->isNotEmpty()) {
                $schedLines = [];
                foreach ($schedules as $s) {
                    $days = is_array($s->day_of_week) ? implode(', ', $s->day_of_week) : $s->day_of_week;
                    $times = is_array($s->time) ? implode(', ', $s->time) : $s->time;
                    $schedLines[] = "{$s->title} ({$s->mass_type}): {$days} at {$times} [Location: {$s->location}]";
                }
                $chunks[] = [
                    'id' => 'active_mass_schedules',
                    'category' => 'schedules',
                    'title' => 'Active Regular Mass Schedules',
                    'keywords' => ['mass', 'schedule', 'misa', 'oras', 'sunday', 'daily', 'simba', 'time', 'english', 'tagalog', 'filipino'],
                    'priority' => 3,
                    'content' => implode("\n", $schedLines),
                ];
            }

            // 5. Recent Published Announcements
            $announcements = Announcement::where('is_published', true)->orderBy('published_at', 'desc')->take(6)->get();
            foreach ($announcements as $a) {
                $chunks[] = [
                    'id' => "announcement_{$a->id}",
                    'category' => 'announcement',
                    'title' => "Announcement: " . $a->title,
                    'keywords' => array_merge(['announcement', 'balita', 'anunsyo', 'bulletin', 'news'], $this->tokenize($a->title . ' ' . strip_tags($a->content))),
                    'priority' => 1,
                    'content' => strip_tags($a->content) . ' (Published: ' . ($a->published_at ? $a->published_at->format('M d, Y') : 'N/A') . ')',
                ];
            }

            // 6. Upcoming Events
            $events = Event::where('is_published', true)->where('event_date', '>=', now()->toDateString())->orderBy('event_date', 'asc')->take(6)->get();
            foreach ($events as $e) {
                $timeStr = is_array($e->event_time) ? json_encode($e->event_time) : (string)$e->event_time;
                $chunks[] = [
                    'id' => "event_{$e->id}",
                    'category' => 'event',
                    'title' => "Event: " . $e->title,
                    'keywords' => array_merge(['event', 'activity', 'aktibidad', 'palatuntunan', 'date'], $this->tokenize($e->title . ' ' . $e->description)),
                    'priority' => 2,
                    'content' => "Date: " . ($e->event_date ? $e->event_date->format('F d, Y') : 'TBA') . " | Time: {$timeStr} | Details: " . strip_tags($e->description),
                ];
            }

            // 7. Parish History & Patroness
            $priestName = Setting::where('key', 'priest_name')->value('value') ?? 'Rev. Fr. Parish Priest';
            $assistantPriestName = Setting::where('key', 'assistant_priest_name')->value('value');
            $chunks[] = [
                'id' => 'parish_history',
                'category' => 'history',
                'title' => 'Parish History and Patroness',
                'keywords' => ['history', 'about', 'patroness', 'rosary', 'pacita', 'priest', 'pari', 'simbahan', 'church', 'founded'],
                'priority' => 1,
                'content' => "Paroisse Saint-Joseph Artisan in Riviera Bonoumin Cocody, Abidjan, Côte d'Ivoire was canonically erected in 2010. Patroness: Queen of the Most Holy Rosary of Pacita. Parish Priest: {$priestName}." . ($assistantPriestName ? " Assistant Parish Priest: {$assistantPriestName}." : "") . " Image declared Important Cultural Property of San Pedro (2024) and 'Queen of the City of San Pedro' (2025).",
            ];

            return $chunks;
        });
    }

    /**
     * Score a chunk against query keywords (TF / Keyword Overlap).
     */
    protected function scoreChunk(array $chunk, array $queryKeywords): float
    {
        $score = 0.0;
        $titleLower = mb_strtolower($chunk['title']);
        $contentLower = mb_strtolower($chunk['content']);
        $chunkKeywords = array_map('mb_strtolower', $chunk['keywords'] ?? []);

        foreach ($queryKeywords as $word) {
            if (strlen($word) < 3) continue;

            // Direct match in keywords list (high weight)
            if (in_array($word, $chunkKeywords, true)) {
                $score += 3.0;
            }

            // Match in Title
            if (str_contains($titleLower, $word)) {
                $score += 2.5;
            }

            // Match in Content
            if (str_contains($contentLower, $word)) {
                $score += 1.0;
            }
        }

        return $score;
    }

    /**
     * Tokenize and normalize query words.
     */
    protected function tokenize(string $text): array
    {
        $cleaned = mb_strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text));
        $words = preg_split('/\s+/', $cleaned, -1, PREG_SPLIT_NO_EMPTY);

        $stopWords = [
            'ang', 'mga', 'ng', 'sa', 'at', 'o', 'ko', 'mo', 'niya', 'kami', 'tayo', 'sila', 'ito', 'iyan',
            'iyon', 'paano', 'ano', 'kailan', 'saan', 'sino', 'bakit', 'the', 'a', 'an', 'and', 'or', 'is',
            'are', 'was', 'were', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'about', 'can', 'you',
            'please', 'how', 'what', 'when', 'where', 'who', 'which', 'do', 'does', 'did'
        ];

        return array_values(array_diff($words, $stopWords));
    }

    /**
     * Default summary context when query is open-ended or greeting.
     */
    protected function getDefaultSummaryContext(): string
    {
        $contact = Setting::where('key', 'parish_contact')->value('value') ?? '+63 2 8869 2742';
        $priest = Setting::where('key', 'priest_name')->value('value') ?? 'Rev. Fr. Parish Priest';

        return "CURRENT DATE & TIME: " . now('Asia/Manila')->format('l, F j, Y h:i A') . " (Philippine Time)\n\n" .
            "Paroisse Saint-Joseph Artisan (Riviera Bonoumin, Abidjan, Côte d'Ivoire).\n" .
            "- Parish Priest: {$priest}\n" .
            "- Office Hours: Tue-Sat 6:00 AM–12:00 NN & 1:30–6:00 PM; Sun 6:00 AM–12:00 NN & 3:00–6:00 PM; Mon Closed.\n" .
            "- Contact: {$contact}\n" .
            "- Mass Intentions stipend: ₱500.00 per intention.\n" .
            "- Certificates processing fee: ₱100.00.\n";
    }

    /**
     * Clear RAG cache on data changes.
     */
    public static function clearCache(): void
    {
        Cache::forget('parish_rag_knowledge_chunks');
    }
}
