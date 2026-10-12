<div id="chatbot-widget" x-data="chatbot()" x-init="init()" class="fixed bottom-6 left-6 z-[100]" @keydown.escape.window="open = false">
    <!-- Trigger Button -->
    <button 
        @click="toggle()" 
        class="h-14 w-14 rounded-full bg-accent text-accent-foreground shadow-2xl flex items-center justify-center hover:scale-110 transition-transform active:scale-95 border-2 border-white/20 relative"
        aria-label="Toggle Chatbot"
    >
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-heart"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M12 15a3.2 3.2 0 0 0 4.5-4.5l-4.5-4.5-4.5 4.5a3.2 3.2 0 0 0 4.5 4.5Z"/></svg>
        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        <!-- Unread badge -->
        <span x-show="!open && unreadCount > 0" x-cloak
            class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center animate-pulse"
            x-text="unreadCount"></span>
    </button>

    <!-- Chat Window -->
    <div 
        x-show="open"
        x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-95"
        class="absolute bottom-20 left-0 bg-white rounded-2xl shadow-2xl border flex flex-col overflow-hidden"
        style="width: min(410px, calc(100vw - 32px)); height: min(600px, calc(100vh - 160px));"
    >
        <!-- Header -->
        <div class="p-4 backdrop-blur-md border-b border-white/10 text-white flex items-center gap-3 relative z-10 shadow-md" style="background: rgba(8, 20, 45, 0.95);">
            <div class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center text-xl font-bold relative border border-white/20 shadow-inner">
                ✝
                <!-- Status dot -->
                <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-primary animate-pulse"
                    :class="{
                        'bg-emerald-400': liveAgentStatus === 'none' || liveAgentStatus === 'suggesting',
                        'bg-amber-400': liveAgentStatus === 'waiting',
                        'bg-blue-400': liveAgentStatus === 'connected'
                    }"></span>
            </div>
            <div>
                <h3 class="font-extrabold text-sm leading-none tracking-wide">Paroisse Saint-Joseph Artisan AI</h3>
                <p class="text-[9px] mt-1.5 uppercase tracking-widest font-extrabold flex items-center gap-1"
                   :class="{
                        'text-emerald-300': liveAgentStatus === 'none' || liveAgentStatus === 'suggesting',
                        'text-amber-200 animate-pulse': liveAgentStatus === 'waiting',
                        'text-blue-200': liveAgentStatus === 'connected'
                    }"
                   x-text="statusLabel"></p>
            </div>
        </div>

        <!-- Messages -->
        <div x-ref="msgContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-muted/10">
            <template x-for="(msg, index) in messages" :key="msg._id">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div class="max-w-[85%] space-y-1">
                        <div 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            :class="msg.role === 'user' ? 'bg-primary text-primary-foreground rounded-tr-none ml-auto' : (msg.type === 'system' ? 'bg-muted text-muted-foreground text-[10px] text-center italic border-none shadow-none mx-auto py-1' : 'bg-white border text-primary rounded-tl-none font-medium')"
                            class="p-3 rounded-2xl text-sm shadow-sm leading-relaxed"
                            x-html="formatMessage(msg.content, msg.role)"
                        ></div>

                        <!-- Timestamp & Status -->
                        <div class="flex items-center gap-1.5 px-1 opacity-60 font-medium"
                             :class="msg.role === 'user' ? 'justify-end' : 'justify-start'"
                             x-show="msg.type !== 'system'">
                            <span class="text-[10px]" x-text="formatTime(msg.time)"></span>
                            <template x-if="msg.role === 'user' && msg.status === 'sent'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500"><path d="M20 6 9 17l-5-5"/></svg>
                            </template>
                        </div>

                        <!-- Special Interaction: Handover Prompt -->
                        <template x-if="msg.type === 'handover_prompt' && !msg.handoverActioned">
                            <div class="flex flex-col gap-2 mt-2">
                                <a href="{{ route('inquiry') }}" class="text-center px-4 py-2 border border-primary text-primary rounded-xl text-xs font-bold hover:bg-primary hover:text-white transition-all">
                                    Submit Inquiry Form
                                </a>
                                <button @click="requestAgent(msg)" class="px-4 py-2 bg-accent text-accent-foreground rounded-xl text-xs font-bold shadow-md hover:opacity-90 transition-all">
                                    Wait for Live Agent
                                </button>
                            </div>
                        </template>

                        <!-- Special Interaction: Error with Retry -->
                        <template x-if="msg.type === 'error'">
                            <div class="mt-2">
                                <button @click="retryLastMessage(msg)" class="px-4 py-2 border border-red-300 text-red-600 rounded-xl text-xs font-bold hover:bg-red-50 transition-all flex items-center gap-1.5 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                                    Retry
                                </button>
                            </div>
                        </template>

                        <!-- Special Interaction: Timeout -->
                        <template x-if="msg.type === 'timeout'">
                            <div class="mt-2">
                                <a href="{{ route('inquiry') }}" class="block text-center px-4 py-2 bg-primary text-primary-foreground rounded-xl text-xs font-bold shadow-md">
                                    Go to Inquiry Page
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- AI/Agent Typing Indicator -->
            <div x-show="loading || agentTyping" x-cloak class="flex justify-start">
                <div class="bg-white border p-3 rounded-2xl rounded-tl-none flex items-center gap-2 skeleton">
                    <span class="h-1.5 w-1.5 rounded-full bg-accent animate-bounce"></span>
                    <span class="h-1.5 w-1.5 rounded-full bg-accent animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="h-1.5 w-1.5 rounded-full bg-accent animate-bounce" style="animation-delay: 0.2s"></span>
                    <span x-show="agentTyping" x-cloak class="text-[10px] text-muted-foreground ml-1 italic">Agent is typing</span>
                </div>
            </div>

            <!-- Dynamic context-aware suggested follow-up chips -->
            <div x-show="currentSuggestions && currentSuggestions.length > 0" x-cloak class="flex flex-wrap gap-2 pt-1 pb-2">
                <template x-for="suggestion in currentSuggestions" :key="suggestion">
                    <button 
                        @click="sendChip(suggestion)" 
                        class="px-3 py-1.5 bg-white border border-primary/10 text-primary hover:text-white hover:bg-primary rounded-full text-[10px] font-extrabold transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md hover:border-primary shrink-0 flex items-center gap-1 active:scale-95 shadow-sm"
                    >
                        <span x-text="suggestion"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Input -->
        <div class="p-3 bg-white border-t">
            <!-- Waiting for agent banner -->
            <div x-show="liveAgentStatus === 'waiting'" x-cloak
                 class="mb-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-amber-400 animate-pulse shrink-0"></span>
                <span class="text-[11px] text-amber-700 font-medium">Waiting for a parish representative to connect…</span>
            </div>

            <!-- Start new chat after resolved -->
            <div x-show="_resolved" x-cloak class="flex flex-col gap-2">
                <p class="text-[10px] text-muted-foreground text-center font-medium">This conversation has ended. Feel free to start a new one anytime.</p>
                <button @click="startNewChat()" :disabled="loading"
                    class="w-full py-2.5 bg-accent text-accent-foreground rounded-xl text-xs font-bold shadow-md hover:opacity-90 transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
                    Start New Chat
                </button>
            </div>

            <form x-show="!_resolved" @submit.prevent="sendMessage" class="flex gap-2">
                <div class="flex-1 relative">
                    <input 
                        x-model="userInput" 
                        x-ref="chatInput"
                        type="text"
                        maxlength="1000"
                        :placeholder="inputPlaceholder"
                        :disabled="inputDisabled"
                        class="w-full bg-muted/30 border-none focus:ring-2 focus:ring-accent rounded-xl px-4 py-2 text-sm text-primary font-medium disabled:opacity-50 disabled:cursor-not-allowed pr-14"
                    />
                    <!-- Character counter -->
                    <span x-show="userInput.length > 0" x-cloak
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-mono tabular-nums"
                        :class="userInput.length > 900 ? (userInput.length > 980 ? 'text-red-500 font-bold' : 'text-amber-500') : 'text-muted-foreground/50'"
                        x-text="userInput.length + '/1000'"></span>
                </div>
                <button type="submit" 
                    :disabled="!canSend"
                    class="p-2 bg-accent text-accent-foreground rounded-xl shadow-lg transition-all"
                    :class="canSend ? 'hover:opacity-90 hover:scale-105' : 'opacity-40 cursor-not-allowed'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-horizontal"><path d="m3 3 3 9-3 9 19-9Z"/><path d="M6 12h16"/></svg>
                </button>
            </form>
        </div>
    </div>

    <script>/* v2.1 */
        function chatbot() {
            let _msgId = 0;

            return {
                open: false,
                messages: [],
                userInput: '',
                loading: false,
                liveAgentStatus: 'none',
                agentName: '',
                agentTyping: false,
                lastMessageId: 0,
                pollInterval: null,
                waitTimer: null,
                waitCounter: 0,
                unreadCount: 0,
                showChips: true,
                currentSuggestions: [],
                isPolling: false,
                _lastFailedMessage: null,
                _prevStatus: null,
                _resolved: false,
                _sessionChecked: false,
                _sessionKey: 'srp_chatbot_state',

                async init() {
                    this.loadState();

                    if (this.messages.length > 0) {
                        this.showChips = false;
                    }
                    // Resume polling if we were in a live agent state
                    if (this.liveAgentStatus === 'waiting' || this.liveAgentStatus === 'connected') {
                        this.startPolling();
                    }
                    if (this.liveAgentStatus === 'waiting') {
                        this.startTimeout();
                    }

                    this.$watch('messages', () => this.saveState(), { deep: true });
                    this.$watch('liveAgentStatus', () => this.saveState());
                    this.$watch('lastMessageId', () => this.saveState());
                },

                async ensureSessionChecked() {
                    if (this._sessionChecked) return;
                    this._sessionChecked = true;
                    try {
                        const res = await fetch('/api/chatbot/session-status');
                        const d = await res.json();
                        if (d.status === 'resolved') {
                            this._resolved = true;
                            this.saveState();
                        }
                    } catch (e) {}
                },

                async pushWelcome() {
                    if (this.messages.length > 0) return;
                    this.messages.push(this._makeMsg('assistant', 'Peace be with you! Welcome to Paroisse Saint-Joseph Artisan. I\'m here to help with anything — Mass schedules, intentions, sacraments, events, donations, or just about the faith. What\'s on your mind?'));
                    this.currentSuggestions = [
                        '⛪ Mass Schedules',
                        '🕯️ Offer Mass Intention',
                        '📝 Sacramental Inquiry'
                    ];
                    this.showChips = true;
                },

                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.unreadCount = 0;
                        this.$nextTick(() => {
                            this.scrollToBottom();
                            this.$refs.chatInput.focus();
                        });
                        if (this.messages.length === 0) {
                            this.ensureSessionChecked().then(() => {
                                if (!this._resolved) this.pushWelcome();
                            });
                        }
                    }
                },

                get statusLabel() {
                    switch (this.liveAgentStatus) {
                        case 'waiting': return 'Connecting to agent…';
                        case 'connected': return 'Live Agent Connected';
                        default: return 'AI Concierge • Online';
                    }
                },

                get inputPlaceholder() {
                    if (this._resolved) return 'Conversation ended — start a new chat';
                    if (this.loading) return 'Processing…';
                    if (this.liveAgentStatus === 'waiting') return 'Waiting for agent to connect…';
                    return 'Ask something…';
                },

                get inputDisabled() {
                    return this._resolved || this.loading || this.liveAgentStatus === 'waiting';
                },

                get canSend() {
                    return !this._resolved && this.userInput.trim().length > 0 && !this.loading && this.liveAgentStatus !== 'waiting';
                },

                _makeMsg(role, content, type = null) {
                    return { _id: ++_msgId, role, content, type, time: Date.now(), handoverActioned: false, status: 'pending' };
                },

                sendChip(text) {
                    this.currentSuggestions = [];
                    this.userInput = text;
                    this.sendMessage();
                },

                async sendMessage() {
                    if (!this.canSend) return;
                    this.currentSuggestions = [];
                    const text = this.userInput.trim();
                    const msg = this._makeMsg('user', text);
                    const currentMsgId = msg._id;
                    this.messages.push(msg);
                    this.userInput = '';
                    this._lastFailedMessage = text;
                    this.loading = true;
                    this.scrollToBottom();
                    try {
                        const response = await fetch('/api/chatbot', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content 
                            },
                            body: JSON.stringify({ message: text })
                        });
                        if (!response.ok) throw new Error('HTTP ' + response.status);
                        const data = await response.json();
                        
                        // Mark user message as sent
                        const userMsg = this.messages.find(m => m._id === currentMsgId);
                        if (userMsg) userMsg.status = 'sent';

                        if (data.status === 'conversation_ended') {
                            this._resolved = true;
                            if (this.pollInterval) clearInterval(this.pollInterval);
                            this.isPolling = false;
                            const systemMsg = this._makeMsg('assistant', data.message || 'This conversation has ended. You can start a new chat anytime.', 'system');
                            this.messages.push(systemMsg);
                        } else if (data.status === 'suggest_handover') {
                            this.liveAgentStatus = 'suggesting';
                            this.messages.push(this._makeMsg('assistant', data.message, 'handover_prompt'));
                        } else if (data.status === 'waiting_for_agent') {
                            // No bubble added, status already updated to sent
                        } else                         if (data.message) {
                            let newMsg = this._makeMsg('assistant', data.message);
                            if (data.id) {
                                newMsg.id = data.id;
                                this.lastMessageId = Math.max(this.lastMessageId, data.id);
                            }
                            this.messages.push(newMsg);
                        }
                        
                        // Assign the dynamic context-aware suggestions returned by the API
                        this.currentSuggestions = data.suggestions || [];
                        this._lastFailedMessage = null;
                    } catch (e) {
                        this.messages.push(this._makeMsg('assistant', 'I am having a brief connection issue. Please try again in a moment.', 'error'));
                    } finally {
                        this.loading = false;
                        this.scrollToBottom();
                    }
                },

                async startNewChat() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/chatbot/start-new-chat', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                        });
                        if (!response.ok) throw new Error('HTTP ' + response.status);
                        const data = await response.json();
                        this.messages = [];
                        this.liveAgentStatus = 'none';
                        this._resolved = false;
                        this.lastMessageId = data.id || 0;
                        const welcomeMsg = this._makeMsg('assistant', data.message);
                        if (data.id) welcomeMsg.id = data.id;
                        this.messages.push(welcomeMsg);
                        this.currentSuggestions = data.suggestions || [];
                        if (this.pollInterval) clearInterval(this.pollInterval);
                        this.isPolling = false;
                        this.scrollToBottom();
                    } catch (e) {
                        this.messages.push(this._makeMsg('assistant', 'Unable to start a new chat. Please try again.', 'error'));
                    } finally {
                        this.loading = false;
                        this.scrollToBottom();
                    }
                },

                async retryLastMessage(errorMsg) {
                    // Remove the error message
                    this.messages = this.messages.filter(m => m._id !== errorMsg._id);
                    if (this._lastFailedMessage) {
                        this.userInput = this._lastFailedMessage;
                        await this.sendMessage();
                    }
                },

                async requestAgent(handoverMsg) {
                    // Mark the handover prompt as actioned so buttons disappear
                    if (handoverMsg) handoverMsg.handoverActioned = true;
                    this.loading = true;
                    try {
                        await fetch('/api/chatbot/request-agent', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                        });
                        this.liveAgentStatus = 'waiting';
                        this.messages.push(this._makeMsg('assistant', 'Connecting you to a parish representative. Please stay on the line.', 'system'));
                        this.startPolling();
                        this.startTimeout();
                    } catch (e) {
                        this.messages.push(this._makeMsg('assistant', 'Unable to request a live agent. Please try again.', 'error'));
                    } finally {
                        this.loading = false;
                        this.scrollToBottom();
                    }
                },

                startPolling() {
                    if (this.pollInterval) clearInterval(this.pollInterval);
                    this.isPolling = false;
                    this.pollInterval = setInterval(async () => {
                        if (this.isPolling) return;
                        this.isPolling = true;
                        try {
                            const response = await fetch('/api/chatbot/poll?last_id=' + this.lastMessageId);
                            const data = await response.json();
                            if (data.messages && data.messages.length > 0) {
                                data.messages.forEach(msg => {
                                    if (!this.messages.find(m => m.id === msg.id)) {
                                        let newMsg = this._makeMsg(
                                            msg.sender === 'user' ? 'user' : 'assistant',
                                            msg.message
                                        );
                                        newMsg.id = msg.id;
                                        newMsg.time = msg.created_at ? new Date(msg.created_at).getTime() : Date.now();
                                        this.messages.push(newMsg);
                                        this.lastMessageId = Math.max(this.lastMessageId, msg.id);
                                    }
                                });
                                if (!this.open) this.unreadCount += data.messages.length;
                                this.scrollToBottom();
                            }
                            const prevStatus = this._prevStatus;
                            this._prevStatus = data.status;

                            if (data.agent_connected && data.status !== 'paused' && this.liveAgentStatus !== 'connected') {
                                this.liveAgentStatus = 'connected';
                                this.messages.push(this._makeMsg('assistant', 'A parish representative has joined the conversation.', 'system'));
                                clearInterval(this.waitTimer);
                                this.scrollToBottom();
                            }
                            // Notify user when admin pauses or resolves the chat
                            if (data.status === 'paused' && prevStatus !== 'paused') {
                                this.liveAgentStatus = 'none';
                                this.messages.push(this._makeMsg('assistant', 'The live agent has paused this conversation. Our AI assistant is here to help. An agent will return shortly.', 'system'));
                                this.scrollToBottom();
                            }
                            if (data.status === 'resolved' && prevStatus !== 'resolved') {
                                this.liveAgentStatus = 'none';
                                this._resolved = true;
                                this.messages.push(this._makeMsg('assistant', 'This conversation has been resolved. Thank you for reaching out to Paroisse Saint-Joseph Artisan! Feel free to start a new conversation anytime.', 'system'));
                                this.scrollToBottom();
                                if (this.pollInterval) clearInterval(this.pollInterval);
                            }
                            // Agent typing indicator from backend
                            this.agentTyping = !!data.agent_typing;
                        } catch (e) {} finally {
                            this.isPolling = false;
                        }
                    }, 3000);
                },

                startTimeout() {
                    this.waitCounter = 0;
                    if (this.waitTimer) clearInterval(this.waitTimer);
                    this.waitTimer = setInterval(() => {
                        this.waitCounter += 5;
                        if (this.waitCounter >= 120 && this.liveAgentStatus === 'waiting') {
                            this.messages.push(this._makeMsg('assistant', 'No agents are available right now. You can [submit an inquiry](/inquiry) and we will get back to you.', 'timeout'));
                            this.liveAgentStatus = 'none';
                            clearInterval(this.waitTimer);
                            clearInterval(this.pollInterval);
                            this.scrollToBottom();
                        }
                    }, 5000);
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.msgContainer;
                        if (container) container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
                    });
                },

                formatTime(timestamp) {
                    if (!timestamp) return '';
                    const d = new Date(timestamp);
                    const now = new Date();
                    const time = d.toLocaleTimeString(document.documentElement.lang, { hour: 'numeric', minute: '2-digit' });
                    // Show date if not today
                    if (d.toDateString() !== now.toDateString()) {
                        return d.toLocaleDateString(document.documentElement.lang, { month: 'short', day: 'numeric' }) + ', ' + time;
                    }
                    return time;
                },

                formatMessage(content, role) {
                    if (!content) return '';
                    if (role === 'user') {
                        let text = content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        text = text.replace(/\n/g, '<br>');
                        return '<p>' + text + '</p>';
                    }
                    // Assistant: decode HTML entities FIRST, in repeated passes so that
                    // single- AND double-encoded tags from the AI all collapse to raw text.
                    let text = content.replace(/&#(\d+);/g, (m, n) => String.fromCharCode(parseInt(n, 10)))
                                      .replace(/&#x([0-9a-f]+);/gi, (m, n) => String.fromCharCode(parseInt(n, 16)));
                    let prev;
                    do {
                        prev = text;
                        text = text.replace(/&nbsp;/gi, ' ');
                        text = text.replace(/&quot;/gi, '"');
                        text = text.replace(/&#39;/gi, "'");
                        text = text.replace(/&lt;/gi, '<');
                        text = text.replace(/&gt;/gi, '>');
                        text = text.replace(/&amp;/gi, '&');
                    } while (text !== prev);

                    if (text.includes('[[HANDOVER]]')) {
                        this.liveAgentStatus = 'suggesting';
                        text = text.replace('[[HANDOVER]]', '');
                    }
                    // Convert HTML links into markdown so they become clickable, not stripped text
                    text = text.replace(/<a[^>]*href=["']([^"']*)["'][^>]*>([\s\S]*?)<\/a>/gi, '[$2]($1)');
                    // Convert structural tags to newlines/bullets BEFORE stripping
                    text = text.replace(/<br\s*\/?>/gi, '\n');
                    text = text.replace(/<\/?p[^>]*>/gi, '\n');
                    text = text.replace(/<\/?li[^>]*>/gi, '\n- ');
                    text = text.replace(/<\/?h[1-6][^>]*>/gi, '\n');
                    text = text.replace(/<\/?(ul|ol|div|span|blockquote|strong|b|em|i|u|s|mark|small|sub|sup|a|table|thead|tbody|tr|th|td)[^>]*>/gi, '');
                    // NUCLEAR FIX: strip ANY remaining HTML tag while keeping its inner text
                    text = text.replace(/<[^>]+>/g, '');
                    // Escape any leftover special characters so no stray HTML can ever re-render
                    text = text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    // Markdown links [text](url) -> clickable <a>
                    text = text.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-accent underline font-bold hover:text-primary transition-colors">$1</a>');
                    // Bold **text** -> <strong>
                    text = text.replace(/\*\*([^*]+?)\*\*/g, '<strong class="font-bold">$1</strong>');
                    // Italic *text* -> <em>
                    text = text.replace(/(?<!\*)\*([^*]+?)\*(?!\*)/g, '<em>$1</em>');
                    // Bare internal paths like /mass-schedule -> clickable link
                    text = text.replace(/(?<!["])(\/[a-z][a-z0-9\-/]+)(?![\w<])/g, '<a href="$1" class="text-accent underline font-bold hover:text-primary transition-colors">$1</a>');
                    // Bullet lines: "- text" or "* text" -> styled bullet points
                    text = text.replace(/^(\s*)([-*])\s+(.+)$/gm, '$1<span class="flex items-start gap-2 my-0.5"><span class="text-accent mt-0.5 shrink-0">\u2022</span><span>$3</span></span>');
                    // Double newlines -> paragraph break
                    text = text.replace(/\n\n/g, '</p><p class="mt-2">');
                    // Single newlines -> <br>
                    text = text.replace(/\n/g, '<br>');
                    // Wrap in paragraph
                    text = '<p>' + text + '</p>';
                    return text;
                },

                saveState() {
                    try {
                        const state = {
                            messages: this.messages,
                            liveAgentStatus: this.liveAgentStatus,
                            lastMessageId: this.lastMessageId,
                            _msgId: _msgId,
                            _resolved: this._resolved,
                            currentSuggestions: this.currentSuggestions
                        };
                        sessionStorage.setItem(this._sessionKey, JSON.stringify(state));
                    } catch (e) {}
                },

                loadState() {
                    try {
                        const raw = sessionStorage.getItem(this._sessionKey);
                        if (raw) {
                            const state = JSON.parse(raw);
                            this.messages = state.messages || [];
                            this.liveAgentStatus = state.liveAgentStatus || 'none';
                            this.lastMessageId = state.lastMessageId || 0;
                            _msgId = state._msgId || 0;
                            this.currentSuggestions = state.currentSuggestions || [];
                            this._resolved = state._resolved || false;
                        }
                    } catch (e) {}
                }
            }
        }
    </script>
</div>
