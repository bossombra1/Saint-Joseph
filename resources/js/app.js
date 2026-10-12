import './bootstrap';
import Alpine from 'alpinejs';
import chatList from './components/chat-list';
import userManage from './components/user-manage';
import intentionList from './components/intention-list';
import { settingsForm, contactNumbers, timelineManager, settingsSection, formerPriestsManager } from './components/settings-form';

Alpine.data('chatList', chatList);
Alpine.data('userManage', userManage);
Alpine.data('intentionList', intentionList);
Alpine.data('settingsForm', settingsForm);
Alpine.data('settingsSection', settingsSection);
Alpine.data('contactNumbers', contactNumbers);
Alpine.data('timelineManager', timelineManager);
Alpine.data('formerPriestsManager', formerPriestsManager);

Alpine.store('toast', {
    show: false,
    message: '',
    type: 'success',
    _timeout: null,
    _lastMsg: '',
    _lastTime: 0,
    trigger(msg, type = 'success') {
        const now = Date.now();
        if (msg === this._lastMsg && now - this._lastTime < 3000) return;
        this._lastMsg = msg;
        this._lastTime = now;
        this.message = msg;
        this.type = type;
        this.show = true;
        if (this._timeout) clearTimeout(this._timeout);
        this._timeout = setTimeout(() => { this.show = false; }, 5000);
    }
});

Alpine.store('confirm', {
    show: false,
    title: '',
    message: '',
    confirmText: 'Confirm',
    type: 'danger',
    _onConfirm: null,
    open(opts) {
        this.title = opts.title || 'Confirm Action';
        this.message = opts.message || 'Are you sure?';
        this.confirmText = opts.confirmText || 'Confirm';
        this.type = opts.type || 'danger';
        this._onConfirm = opts.onConfirm || null;
        this.show = true;
    },
    execute() {
        this.show = false;
        if (typeof this._onConfirm === 'function') this._onConfirm();
        this._onConfirm = null;
    },
    cancel() {
        this.show = false;
        this._onConfirm = null;
    }
});

Alpine.store('ui', {
    sidebarOpen: localStorage.getItem('admin_sidebar') !== 'false',
    isMobile: typeof window !== 'undefined' ? window.innerWidth < 768 : false,
    loading: false,
    notifCounts: { intentions: 0, inquiries: 0, chats: 0 },
    _lastMsgId: 0,
    _chatNotified: false,
    _pollTimer: null,
    _notifUrl: null,
    _initialized: false,
    init() {
        if (this._initialized) return;
        this._initialized = true;
        if (this.isMobile) this.sidebarOpen = false;
        if (typeof window !== 'undefined') {
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 768;
                if (this.isMobile) this.sidebarOpen = false;
            });
        }
        Alpine.effect(() => {
            localStorage.setItem('admin_sidebar', this.sidebarOpen);
        });
        if (this._notifUrl) this._startNotifPolling();
    },
    _startNotifPolling() {
        if (this._pollTimer) return;
        const poll = async () => {
            try {
                const r = await fetch(this._notifUrl);
                const d = await r.json();
                if (this._chatNotified && d.last_user_message_id > this._lastMsgId) {
                    Alpine.store('toast').trigger('New message from a parishioner', 'success');
                    if (typeof Notification !== 'undefined' && Notification.permission === 'granted') {
                        new Notification('Paroisse Saint-Joseph Artisan', { body: 'New message from a parishioner' });
                    }
                }
                this._chatNotified = true;
                if (d.last_user_message_id > this._lastMsgId) this._lastMsgId = d.last_user_message_id;
                this.notifCounts = d;
            } catch (e) {}
        };
        poll();
        this._pollTimer = setInterval(poll, 30000);
    }
});

window.Alpine = Alpine;
Alpine.start();

import './locale-ui';

if (document.querySelector('[data-bulletin-reader]')) {
    import('./bulletin-reader');
}
