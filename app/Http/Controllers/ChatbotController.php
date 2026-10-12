<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRequest;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\AIService;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle incoming chat messages.
     */
    public function chat(ChatRequest $request)
    {
        $session = $this->getOrCreateSession();
        $userMessage = $request->input('message');

        // If session was resolved, reject — user must click "Start New Chat"
        if ($session->status === 'resolved') {
            return response()->json([
                'status' => 'conversation_ended',
                'message' => 'This conversation has ended. You can start a new chat anytime.',
            ]);
        }

        // 1. Store User Message
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'user',
            'message' => $userMessage,
        ]);

        // 2. If in Live Agent mode, don't trigger AI (unless paused)
        if (($session->status === 'handover' || $session->admin_id) && $session->status !== 'paused') {
            return response()->json([
                'status' => 'waiting_for_agent',
                'message' => '',
            ]);
        }

        // 3. Simple Agent Intent Detection
        if (Str::contains(strtolower($userMessage), ['live agent', 'human', 'talk to person', 'speak with someone', 'admin'])) {
            return response()->json([
                'status' => 'suggest_handover',
                'message' => "I understand you'd like to speak with a person. For formal requests (Baptism, Wedding, etc.), please [Submit an Inquiry](/inquiry). You can also message us directly on [Facebook Messenger](https://m.me/storosarioparishpacita1) or wait for a Live Agent here.",
            ]);
        }

        // 4. AIService Response
        try {
            $history = $session->messages()
                ->latest()
                ->take(10)
                ->get()
                ->reverse()
                ->map(fn ($m) => [
                    'role' => $m->sender === 'user' ? 'user' : 'assistant',
                    'content' => $m->message,
                ])
                ->toArray();

            $aiResponse = $this->aiService->getResponse($history);

            // 5. Store AI Response
            $aiMsg = ChatMessage::create([
                'chat_session_id' => $session->id,
                'sender' => 'ai',
                'message' => $aiResponse,
            ]);

            $suggestions = $this->getDynamicSuggestions($userMessage);

            return response()->json([
                'status' => 'success',
                'message' => $aiResponse,
                'id' => $aiMsg->id,
                'suggestions' => $suggestions,
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot AI Service failed: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'I am having a brief connection issue with the parish servers. Please try again in a moment.',
            ], 500);
        }
    }

    /**
     * Request a live agent handover.
     */
    public function requestAgent()
    {
        $session = $this->getOrCreateSession();
        $session->update([
            'status' => 'handover',
            'live_agent_requested_at' => now(),
        ]);

        return response()->json([
            'status' => 'requested',
            'message' => 'Waiting for a parish representative to connect...',
        ]);
    }

    /**
     * Reset a resolved session and start fresh.
     */
    public function startNewChat()
    {
        $session = $this->getOrCreateSession();
        $session->update(['status' => 'active', 'admin_id' => null]);
        $session->messages()->delete();

        $welcomeMsg = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'ai',
            'message' => 'Peace be with you! Welcome back to Paroisse Saint-Joseph Artisan. What can I help you with — schedules, intentions, sacraments, or events?',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $welcomeMsg->message,
            'id' => $welcomeMsg->id,
            'suggestions' => [
                '⛪ Mass Schedules',
                '🕯️ Offer Mass Intention',
                '💬 Talk to a Human',
            ],
        ]);
    }

    /**
     * Poll for new messages (especially from admin).
     */
    public function poll(Request $request)
    {
        $session = $this->getOrCreateSession();
        $lastId = $request->input('last_id', 0);

        $newMessages = $session->messages()
            ->where('id', '>', $lastId)
            ->whereIn('sender', ['admin', 'ai'])
            ->get();

        return response()->json([
            'messages' => $newMessages,
            'agent_connected' => $session->admin_id !== null,
            'agent_typing' => Cache::has('chat_typing_'.$session->id),
            'status' => $session->status,
        ]);
    }

    /**
     * Get the current session status (used by frontend to detect resolved state).
     */
    public function sessionStatus()
    {
        $session = $this->getOrCreateSession();

        return response()->json([
            'status' => $session->status,
        ]);
    }

    public function adminSessionsHtml(Request $request)
    {
        $status = $request->input('status', 'handover');
        $search = $request->input('search');

        $sessions = ChatSession::withCount('messages')
            ->addSelect(['last_message_sender' => ChatMessage::select('sender')
                ->whereColumn('chat_session_id', 'chat_sessions.id')
                ->latest('id')
                ->limit(1),
            ])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where('user_ip', 'LIKE', "%{$search}%"))
            ->orderBy('live_agent_requested_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        $html = view('admin.chats._sessions_rows', compact('sessions'))->render();
        $pagination = $sessions->hasPages()
            ? $sessions->appends(request()->query())->links()->toHtml()
            : '';

        return response()->json([
            'html' => $html,
            'pagination' => $pagination,
        ]);
    }

    public function adminIndex(Request $request)
    {
        $status = $request->input('status', 'handover');
        $search = $request->input('search');

        $sessions = ChatSession::withCount('messages')
            ->addSelect(['last_message_sender' => ChatMessage::select('sender')
                ->whereColumn('chat_session_id', 'chat_sessions.id')
                ->latest('id')
                ->limit(1),
            ])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where('user_ip', 'LIKE', "%{$search}%"))
            ->orderBy('live_agent_requested_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.chats.index', compact('sessions', 'status', 'search'));
    }

    public function resolve($id)
    {
        $chat = ChatSession::findOrFail($id);
        $chat->update(['status' => 'resolved']);
        LogService::log('chat_resolved', $chat, ['session_id' => $chat->session_id]);

        return redirect()->route('admin.chats.index', ['status' => 'resolved'])->with('success', 'Conversation marked as resolved.');
    }

    public function pause($id)
    {
        $chat = ChatSession::findOrFail($id);
        $chat->update(['status' => 'paused']);
        LogService::log('chat_paused', $chat, ['session_id' => $chat->session_id]);

        return back()->with('success', 'Conversation paused. AI will now handle responses.');
    }

    public function resume($id)
    {
        $chat = ChatSession::findOrFail($id);
        $chat->update(['status' => 'active']);
        LogService::log('chat_resumed', $chat, ['session_id' => $chat->session_id]);

        return back()->with('success', 'Conversation resumed. AI is now disabled.');
    }

    public function adminShow($id)
    {
        $chat = ChatSession::with('messages', 'admin')->findOrFail($id);

        // Mark as connected if not already (only if not already resolved/paused)
        if (! $chat->admin_id && auth()->check() && ! in_array($chat->status, ['resolved', 'paused'])) {
            $chat->update([
                'admin_id' => auth()->id(),
                'status' => 'active',
            ]);
            LogService::log('chat_assigned', $chat, ['session_id' => $chat->session_id]);
        }

        return view('admin.chats.show', compact('chat'));
    }

    public function adminReply(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);
        $chat = ChatSession::findOrFail($id);

        // Clear typing indicator on send
        Cache::forget('chat_typing_'.$chat->id);

        ChatMessage::create([
            'chat_session_id' => $chat->id,
            'sender' => 'admin',
            'message' => $request->message,
        ]);

        LogService::log('chat_admin_reply', $chat, ['session_id' => $chat->session_id, 'message_length' => strlen($request->message)]);

        return back()->with('success', 'Reply sent!');
    }

    /**
     * Signal that admin is typing (called via AJAX from admin chat view).
     */
    public function adminTyping($id)
    {
        $chat = ChatSession::findOrFail($id);
        Cache::put('chat_typing_'.$chat->id, true, now()->addSeconds(5));

        return response()->json(['status' => 'ok']);
    }

    public function adminPoll(Request $request, $id)
    {
        $chat = ChatSession::findOrFail($id);
        $lastId = $request->input('last_id', 0);

        $newMessages = $chat->messages()
            ->where('id', '>', $lastId)
            ->get();

        return response()->json([
            'messages' => $newMessages,
            'status' => $chat->status,
        ]);
    }

    protected function getOrCreateSession()
    {
        $this->pruneStaleSessions();

        $id = session()->getId();

        return ChatSession::firstOrCreate(
            ['session_id' => $id],
            [
                'user_ip' => request()->ip(),
                'status' => 'active',
            ]
        );
    }

    /**
     * Resolve abandoned chat sessions so the admin queue never fills with
     * stale conversations. Runs at most once per hour (guarded by cache)
     * so the extra query cost per chat request stays negligible.
     */
    protected function pruneStaleSessions(): void
    {
        if (Cache::has('chat_prune_last_run')) {
            return;
        }

        Cache::put('chat_prune_last_run', true, now()->addHour());

        ChatSession::whereIn('status', ['active', 'handover'])
            ->where('updated_at', '<', now()->subDays(2))
            ->update(['status' => 'resolved']);
    }

    /**
     * Get dynamic, context-aware suggestions based on user query and topic.
     */
    /**
     * Get dynamic, context-aware suggestions based on user query and topic.
     */
    protected function getDynamicSuggestions(string $message, string $detectedTopic = ''): array
    {
        $lower = strtolower($message);

        if (Str::contains($lower, ['intention', 'alay', 'panalangin', 'offering'])) {
            return [
                '🔍 Track Intention Status',
                '⛪ Mass Schedules',
                '💬 Talk to a Human',
            ];
        }

        if (Str::contains($lower, ['mass', 'misa', 'schedule', 'oras', 'time'])) {
            return [
                '🕯️ Offer Mass Intention',
                '💬 Talk to a Human',
                '📅 Upcoming Events',
            ];
        }

        if (Str::contains($lower, ['inquiry', 'sacrament', 'baptis', 'baptize', 'baptized', 'wedding', 'kasal', 'binyag', 'confirmation', 'kumpil', 'funeral'])) {
            return [
                '🔍 Check Inquiry Status',
                '📍 Location & Office Hours',
                '💬 Talk to a Human',
            ];
        }

        if (Str::contains($lower, ['donate', 'donation', 'ambag', 'tulong', 'gcash'])) {
            return [
                '⛪ Mass Schedules',
                '🕯️ Offer Mass Intention',
                '💬 Talk to a Human',
            ];
        }

        if (Str::contains($lower, ['event', 'events', 'activity', 'activities'])) {
            return [
                '⛪ Mass Schedules',
                '📝 Sacramental Inquiry',
                '📜 Parish History',
            ];
        }

        if (Str::contains($lower, ['contact', 'phone', 'email', 'address', 'location', 'office'])) {
            return [
                '⛪ Mass Schedules',
                '📝 Sacramental Inquiry',
                '📅 Upcoming Events',
            ];
        }

        // Default follow-up suggestions
        return [
            '⛪ Mass Schedules',
            '🕯️ Offer Mass Intention',
            '💬 Talk to a Human',
        ];
    }
}
