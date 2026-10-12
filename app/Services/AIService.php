<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $groqKey;
    protected $openRouterKey;
    protected RAGService $ragService;
    protected ChatbotTools $chatbotTools;

    public function __construct(RAGService $ragService, ChatbotTools $chatbotTools)
    {
        $this->groqKey = config('services.groq.key');
        $this->openRouterKey = config('services.openrouter.key');
        $this->ragService = $ragService;
        $this->chatbotTools = $chatbotTools;
    }

    /**
     * Get response from AI with RAG retrieval, native tool calling, and resilient fallbacks.
     */
    public function getResponse(array $messages)
    {
        $messages = $this->sanitizeMessages($messages);

        $userMessage = '';
        foreach (array_reverse($messages) as $m) {
            if ($m['role'] === 'user') {
                $userMessage = $m['content'];
                break;
            }
        }

        // --- Response caching: only for self-contained first queries ---
        $userCount = collect($messages)->filter(fn ($m) => ($m['role'] ?? '') === 'user')->count();
        $cacheKey = ($userCount <= 1 && strlen($userMessage) >= 15 && !preg_match('/\b(INT|INQ|SRP)-\d{4}-\d+\b/i', $userMessage))
            ? 'chatbot_response_' . md5($userMessage)
            : null;

        if ($cacheKey && $cached = Cache::get($cacheKey)) {
            return $cached;
        }

        // --- Retrieve RAG context tailored to current query ---
        if (!collect($messages)->contains('role', 'system')) {
            $context = $this->ragService->retrieveContext($userMessage);
            array_unshift($messages, [
                'role' => 'system',
                'content' => $this->getSystemPrompt($context),
            ]);
        }

        $tools = ChatbotTools::getToolDefinitions();
        $aiResponse = null;

        // Try primary provider with Tool Calling (OpenRouter or Groq)
        if ($this->openRouterKey) {
            $aiResponse = $this->runChatWithTools(
                'https://openrouter.ai/api/v1/chat/completions',
                [
                    'Authorization' => 'Bearer ' . $this->openRouterKey,
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('app.name'),
                    'Content-Type' => 'application/json',
                ],
                'google/gemini-2.5-flash',
                $messages,
                $tools
            );
        }

        if ($aiResponse === null && $this->groqKey) {
            $aiResponse = $this->runChatWithTools(
                'https://api.groq.com/openai/v1/chat/completions',
                [
                    'Authorization' => 'Bearer ' . $this->groqKey,
                    'Content-Type' => 'application/json',
                ],
                'llama-3.1-8b-instant',
                $messages,
                $tools
            );
        }

        // If tools / API call succeeded
        if ($aiResponse !== null) {
            $aiResponse = $this->cleanAiOutput($aiResponse);
            if ($cacheKey) {
                Cache::put($cacheKey, $aiResponse, 600);
            }
            return $aiResponse;
        }

        // Fallback: Check local heuristic tool calling before local response
        $localToolResponse = $this->tryLocalToolExecution($userMessage);
        if ($localToolResponse !== null) {
            return $localToolResponse;
        }

        return $this->localFallback($userMessage);
    }

    /**
     * Executes OpenAI-standard tool calling loop (max 3 tool iterations).
     */
    protected function runChatWithTools(string $url, array $headers, string $model, array $messages, array $tools): ?string
    {
        $currentMessages = $messages;
        $maxIterations = 3;

        for ($iteration = 0; $iteration < $maxIterations; $iteration++) {
            try {
                $payload = [
                    'model' => $model,
                    'messages' => $currentMessages,
                    'max_tokens' => 800,
                    'tools' => $tools,
                    'tool_choice' => 'auto',
                ];

                $response = Http::withHeaders($headers)->timeout(12)->post($url, $payload);

                if (!$response->successful()) {
                    Log::warning("AI Provider Tool Calling failed ({$model}): " . $response->body());
                    return null;
                }

                $choice = $response->json()['choices'][0] ?? null;
                if (!$choice) {
                    return null;
                }

                $messageObj = $choice['message'] ?? [];
                $content = $messageObj['content'] ?? null;
                $toolCalls = $messageObj['tool_calls'] ?? [];

                // If no tool calls, return final response text
                if (empty($toolCalls)) {
                    return $content ?: null;
                }

                // Add assistant's tool-calling message into history
                $currentMessages[] = $messageObj;

                // Execute each tool and append tool result
                foreach ($toolCalls as $call) {
                    $toolName = $call['function']['name'] ?? '';
                    $arguments = json_decode($call['function']['arguments'] ?? '{}', true) ?: [];
                    $callId = $call['id'] ?? ('call_' . uniqid());

                    $toolResult = $this->chatbotTools->execute($toolName, $arguments);

                    $currentMessages[] = [
                        'role' => 'tool',
                        'tool_call_id' => $callId,
                        'name' => $toolName,
                        'content' => json_encode($toolResult, JSON_UNESCAPED_UNICODE),
                    ];
                }

                // Loop will now call the LLM again with tool outputs
            } catch (\Exception $e) {
                Log::error("Error in runChatWithTools iteration: " . $e->getMessage());
                return null;
            }
        }

        return null;
    }

    /**
     * Direct local execution fallback if reference ID is in the prompt.
     */
    protected function tryLocalToolExecution(string $message): ?string
    {
        // Check for Intention Reference ID (e.g. INT-2026-0001 or SRP-2026-0001)
        if (preg_match('/\b(INT-\d{4}-\d+|SRP-\d{4}-\d+)\b/i', $message, $m)) {
            $result = $this->chatbotTools->execute('check_intention_status', ['reference_id' => $m[1]]);
            if ($result['found']) {
                $statusText = strtoupper($result['status']);
                return "Here is the status for Mass Intention **{$result['reference_number']}**:\n\n" .
                    "- **Status:** {$statusText}\n" .
                    "- **Type:** {$result['intention_type']}\n" .
                    "- **Preferred Date:** {$result['preferred_date']}\n" .
                    "- **Mass Time:** {$result['mass_time']}\n" .
                    "- **Details:** {$result['raw_message']}\n\n" .
                    "You can also monitor this on our [Track Request](/track) page.";
            }
        }

        // Check for Inquiry Reference ID (e.g. INQ-2026-0001)
        if (preg_match('/\b(INQ-\d{4}-\d+)\b/i', $message, $m)) {
            $result = $this->chatbotTools->execute('check_inquiry_status', ['reference_id' => $m[1]]);
            if ($result['found']) {
                $statusText = strtoupper($result['status']);
                return "Here is the status for Inquiry **{$result['reference_id']}**:\n\n" .
                    "- **Status:** {$statusText}\n" .
                    "- **Inquiry Type:** {$result['inquiry_type']}\n" .
                    "- **Preferred Date:** {$result['preferred_date']}\n" .
                    "- **Message:** {$result['message']}\n\n" .
                    "You can track updates anytime via [Track Your Request](/track).";
            }
        }

        return null;
    }

    /**
     * Strip any HTML tags or entities. Plain markdown ONLY.
     */
    protected function cleanAiOutput(string $text): string
    {
        $cleaned = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        for ($i = 0; $i < 3 && $cleaned !== $text; $i++) {
            $text = $cleaned;
            $cleaned = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        $text = $cleaned;

        $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
        $text = preg_replace('/<\/?(p|div|ul|ol|li|blockquote|h[1-6])[^>]*>(\n?)/i', "\n", $text);
        $text = preg_replace('/<[^>]+>/', '', $text);
        $text = preg_replace('/[ \t]+\n/', "\n", $text);

        return trim($text);
    }

    protected function sanitizeMessages(array $messages): array
    {
        $patterns = [
            '/ignore\s+(all\s+)?(previous|prior|above)\s+(instructions|prompts|rules)/i',
            '/you\s+are\s+now\s+(a|an|the)\s+/i',
            '/new\s+instructions?:/i',
            '/system\s*:\s*/i',
            '/override\s+(previous|prior|all)\s+/i',
            '/disregard\s+(previous|prior|all)\s+/i',
            '/forget\s+(previous|prior|all)\s+/i',
            '/act\s+as\s+if\s+you\s+have\s+no\s+(rules|restrictions|guidelines)/i',
            '/pretend\s+you\s+are\s+(a|an|the)\s+/i',
            '/role\s*play\s+as\s+/i',
            '/jailbreak/i',
            '/DAN\s+mode/i',
            '/developer\s+mode/i',
        ];

        return array_map(function ($message) use ($patterns) {
            if (($message['role'] ?? '') !== 'user') {
                return $message;
            }
            $content = $message['content'] ?? '';
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $content = '[Message filtered for security]';
                    break;
                }
            }

            return ['role' => 'user', 'content' => trim($content)];
        }, $messages);
    }

    protected function getSystemPrompt(string $context = ''): string
    {
        $contactRaw = Cache::remember('chatbot_settings_parish_contact', 300, fn () => Setting::where('key', 'parish_contact')->value('value'));
        $contactRaw = $contactRaw ?? '+63 2 8869 2742';
        $contactNumbers = is_string($contactRaw) && $contactRaw !== ''
            ? (json_decode($contactRaw, true) ?: [$contactRaw])
            : (is_array($contactRaw) ? $contactRaw : ['+63 2 8869 2742']);
        $contactLine = implode(' | ', $contactNumbers);
        $email = Cache::remember('chatbot_settings_parish_email', 300, fn () => Setting::where('key', 'parish_email')->value('value')) ?? 'paroisse.stjoseph.bonoumin@gmail.com';

        return "You are the official AI concierge of Paroisse Saint-Joseph Artisan (Riviera Bonoumin, Abidjan, Côte d'Ivoire, Philippines). You speak with the warmth of a neighbor and the authority of the parish office — approachable, never robotic, never like a brochure.

## TOOLS & DYNAMIC DATA
- You have access to real-time tools: `check_intention_status`, `check_inquiry_status`, `get_mass_schedules`, `get_upcoming_events`, `search_announcements`, and `get_sacrament_requirements`.
- ALWAYS call the appropriate tool when the user asks to look up a reference number (e.g. INT-2026-0001, INQ-2026-0001), specific mass schedules, upcoming events, or requirements.
- After calling a tool, synthesize the returned JSON data into a helpful, natural response.

## HOW TO TALK (conversational, not formal)
- Sound like a real, friendly parish volunteer having a conversation.
- Answer in a few warm sentences, then give the useful details. Do NOT open with a formulaic acknowledgment, and do NOT end with a generic \"How can I help?\".
- When an answer is naturally a list (schedules, fees, requirements), use short, clean bullets.
- Prove you know the parish: mention exact Mass times, actual events, and exact fees (₱500 Mass intention, ₱100 certificate).
- Ask a clarifying question when the request is vague instead of guessing.

## FORMATTING RULES (CRITICAL)
- NEVER output HTML tags — no <strong>, <b>, <i>, <a>, <br>, <p>. Use plain markdown ONLY: **bold**, *italic*, and [link text](/path) for links.
- At most 1 emoji per message; usually none.
- For links, write them naturally inside a sentence as [text](/path) — never as raw URLs and never with arrows.
- Never reference \"the knowledge base\", \"my tools\", \"as an AI\", or \"according to our records\".

## LANGUAGE
- CRITICAL: Match the user's language exactly. English → English, Tagalog → Tagalog, Taglish → Taglish.

## RETRIEVED PARISH CONTEXT:
{$context}

## CONTACT INFO:
- Address: Paroisse St Joseph https://maps.app.goo.gl/ineJKGS5ux346zJR9 4023
- Phone: {$contactLine}
- Email: {$email}
- Website: https://storosario.ph

## ALLOWED LINKS (use ONLY these, always as [text](/path) inside a sentence)
[/] [/mass-schedule] [/submit-intention] [/inquiry] [/events] [/gallery] [/bulletins] [/track] [/about] [/donate]

## BOUNDARIES
- Never fabricate details. If unsure, say so and route to the office or inquiry form.";
    }

    /**
     * Local keyword-based fallback engine when external AI is unavailable.
     */
    protected function localFallback(string $message): string
    {
        $lower = mb_strtolower(trim($message));
        $name = Setting::where('key', 'parish_name')->value('value') ?? 'Paroisse Saint-Joseph Artisan';
        $contact = Setting::where('key', 'parish_contact')->value('value') ?? '+63 2 8869 2742';

        if (str_contains($lower, 'mass') || str_contains($lower, 'misa') || str_contains($lower, 'schedule') || str_contains($lower, 'oras')) {
            $tool = $this->chatbotTools->execute('get_mass_schedules', []);
            if (!empty($tool['schedules'])) {
                $schedText = "**Mass Schedules:**\n\n";
                foreach ($tool['schedules'] as $s) {
                    $schedText .= "- **{$s['title']}** ({$s['mass_type']}): {$s['days']} at {$s['times']}\n";
                }
                return $schedText . "\nSee our [Mass Schedule page](/mass-schedule) for more details.";
            }
        }

        if (str_contains($lower, 'intention') || str_contains($lower, 'pamisa') || str_contains($lower, 'alay')) {
            return "We'd love to offer a Mass intention with you. It's **₱500.00 per intention** for thanksgiving, healing, or repose of the soul. Fill out the [Mass Intention Form](/submit-intention) and you'll receive a reference ID to [track your request](/track).";
        }

        if (str_contains($lower, 'event') || str_contains($lower, 'activity')) {
            $tool = $this->chatbotTools->execute('get_upcoming_events', ['limit' => 3]);
            if (!empty($tool['events'])) {
                $evText = "**Upcoming Events:**\n\n";
                foreach ($tool['events'] as $e) {
                    $evText .= "- **{$e['title']}** ({$e['date']})\n";
                }
                return $evText . "\nCheck out our [Events page](/events) for full details.";
            }
        }

        if (str_contains($lower, 'binyag') || str_contains($lower, 'baptism')) {
            $tool = $this->chatbotTools->execute('get_sacrament_requirements', ['sacrament_type' => 'baptism']);
            $reqs = implode("\n- ", $tool['requirements'] ?? []);
            return "Para sa **Holy Baptism (Binyag)**:\n\n- **Registration Fee:** {$tool['fee']}\n- **Requirements:**\n- {$reqs}\n\nPwede kang mag-inquire online sa pamamagitan ng aming [Inquiry Form](/inquiry) o bumisita sa opisina ng parokya.";
        }

        if (str_contains($lower, 'kasal') || str_contains($lower, 'wedding') || str_contains($lower, 'matrimony')) {
            $tool = $this->chatbotTools->execute('get_sacrament_requirements', ['sacrament_type' => 'wedding']);
            $reqs = implode("\n- ", $tool['requirements'] ?? []);
            return "Para sa **Holy Matrimony (Kasal)**:\n\n- **Requirements:**\n- {$reqs}\n\nMag-inquire sa [Inquiry Form](/inquiry) o magsadya sa opisina 2-3 buwan bago ang kasal.";
        }

        return "Peace be with you, and welcome to {$name}! \u{1F64F} I can help you with Mass schedules, intentions, sacramental requirements, events, and parish information. How may I assist you today?";
    }
}
