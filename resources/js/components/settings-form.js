export const settingsForm = () => ({
    qrPreview: null,
    priestPreview: null,
    assistantPriestPreview: null,
    activeSection: '',
    _previewUrls: {},
    _observer: null,

    init() {
        const el = document.getElementById('settings-previews-data');
        if (el) {
            try {
                const d = JSON.parse(el.textContent);
                this.qrPreview = d.qrUrl || null;
                this.priestPreview = d.priestUrl || null;
                this.assistantPriestPreview = d.assistantPriestUrl || null;
            } catch {}
        }
    },

    initSettingsNav() {
        const sectionIds = ['section-parish', 'section-donations', 'section-leadership', 'section-former-priests', 'section-about-video', 'section-timeline', 'section-gallery', 'section-email'];
        const sections = sectionIds.map(id => document.getElementById(id)).filter(Boolean);
        if (!sections.length) return;

        this._observer = new IntersectionObserver((entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    this.activeSection = entry.target.id;
                }
            }
        }, { rootMargin: '-80px 0px -60% 0px', threshold: 0 });

        sections.forEach(s => this._observer.observe(s));
        if (sections.length) this.activeSection = sections[0].id;

        this.$el.addEventListener('submit', () => {
            if (this._observer) this._observer.disconnect();
        }, { once: true });
    },

    scrollToSection(id) {
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            this.activeSection = id;
        }
    },

    handleFileUpload(e, key, label) {
        const file = e.target.files[0];
        if (!file) return;
        if (file.size > 1.8 * 1024 * 1024) {
            this.$store.toast.trigger(`${label} is too large. Maximum size is 1.8MB.`, 'error');
            e.target.value = '';
            return;
        }
        if (this._previewUrls[key]) URL.revokeObjectURL(this._previewUrls[key]);
        this._previewUrls[key] = URL.createObjectURL(file);
        this[key] = this._previewUrls[key];
    },

    revokePreviews() {
        Object.values(this._previewUrls).forEach(url => URL.revokeObjectURL(url));
    }
});

export const settingsSection = () => ({
    isDirty: false,
    _snapshot: '',
    _submitting: false,

    init() {
        this.$nextTick(() => {
            this._snapshot = this._serialize();
        });
        this.$el.addEventListener('input', () => this._checkDirty(), true);
        this.$el.addEventListener('change', () => this._checkDirty(), true);
        this.$el.addEventListener('settings-changed', () => {
            this.$nextTick(() => this._checkDirty());
        });
        this.$el.addEventListener('submit', (e) => this._handleSubmit(e));
    },

    _serialize() {
        const fd = new FormData(this.$el);
        const pairs = [];
        for (const [k, v] of fd.entries()) {
            if (v instanceof File) {
                pairs.push([k, v.name + ':' + v.size + ':' + v.lastModified]);
            } else {
                pairs.push([k, String(v)]);
            }
        }
        pairs.sort((a, b) => a[0].localeCompare(b[0]));
        return JSON.stringify(pairs);
    },

    _checkDirty() {
        this.isDirty = this._serialize() !== this._snapshot;
    },

    notifyChanged() {
        this._checkDirty();
    },

    async _handleSubmit(e) {
        e.preventDefault();
        if (this._submitting) return;
        this._submitting = true;

        const fd = new FormData(this.$el);
        try {
            const res = await fetch(this.$el.action, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            });

            if (res.ok) {
                this.$store.toast.trigger('Settings saved successfully!', 'success');
                this._snapshot = this._serialize();
                this.isDirty = false;
                window.setTimeout(() => window.location.reload(), 500);
            } else if (res.status === 422) {
                const data = await res.json();
                const msg = data.message || Object.values(data.errors || {}).flat().join('\n');
                this.$store.toast.trigger(msg || 'Validation failed.', 'error');
            } else {
                this.$store.toast.trigger('Failed to save settings.', 'error');
            }
        } catch {
            this.$store.toast.trigger('Network error. Please try again.', 'error');
        } finally {
            this._submitting = false;
        }
    }
});

export const contactNumbers = () => ({
    numbers: [''],

    init() {
        const el = document.getElementById('contact-numbers-data');
        if (el) {
            try {
                const parsed = JSON.parse(el.textContent);
                this.numbers = Array.isArray(parsed) && parsed.length ? parsed : [''];
            } catch {}
        }
    },

    addNumber() {
        if (this.numbers.length < 10) {
            this.numbers.push('');
            this.$dispatch('settings-changed');
        }
    },

    removeNumber(index) {
        if (this.numbers.length > 1) {
            this.numbers.splice(index, 1);
            this.$dispatch('settings-changed');
        }
    }
});

export const timelineManager = () => ({
    entries: [],

    init() {
        const el = document.getElementById('timeline-entries-data');
        if (el) {
            try {
                const parsed = JSON.parse(el.textContent);
                this.entries = Array.isArray(parsed) && parsed.length ? parsed : [{ year: '', badge: '', title: '', short: '', full: '' }];
            } catch {}
        }
    },

    _notify() {
        this.$dispatch('settings-changed');
    },

    addEntry() {
        if (this.entries.length >= 30) {
            this.$store.toast.trigger('Maximum 30 timeline entries allowed.', 'error');
            return;
        }
        this.entries.push({ year: '', badge: '', title: '', short: '', full: '' });
        this._notify();
    },

    removeEntry(index) {
        if (this.entries.length <= 1) {
            this.$store.toast.trigger('At least one timeline entry is required.', 'error');
            return;
        }
        this.entries.splice(index, 1);
        this._notify();
    },

    moveUp(index) {
        if (index === 0) return;
        const item = this.entries.splice(index, 1)[0];
        this.entries.splice(index - 1, 0, item);
        this._notify();
    },

    moveDown(index) {
        if (index >= this.entries.length - 1) return;
        const item = this.entries.splice(index, 1)[0];
        this.entries.splice(index + 1, 0, item);
        this._notify();
    }
});

export const formerPriestsManager = () => ({
    entries: [],
    _previewUrls: {},

    init() {
        const el = document.getElementById('former-priests-data');
        if (el) {
            try {
                const parsed = JSON.parse(el.textContent);
                this.entries = Array.isArray(parsed) ? parsed.map(e => ({
                    name: e.name || '',
                    role: e.role || 'Parish Priest',
                    years: e.years || '',
                    quote: e.quote || '',
                    existing_image: e.image || '',
                    imagePreview: e.imageUrl || null,
                    contrib_short: e.contrib_short || '',
                    contrib_full: e.contrib_full || '',
                    contrib_confirmed: !!e.contrib_confirmed,
                    contrib_sources: e.contrib_sources || '',
                })) : [];
            } catch {}
        }
    },

    _notify() {
        this.$dispatch('settings-changed');
    },

    addEntry() {
        if (this.entries.length >= 20) {
            this.$store.toast.trigger('Maximum 20 former priests allowed.', 'error');
            return;
        }
        this.entries.push({ name: '', role: 'Parish Priest', years: '', quote: '', existing_image: '', imagePreview: null, contrib_short: '', contrib_full: '', contrib_confirmed: false, contrib_sources: '' });
        this._notify();
    },

    removeEntry(index) {
        this.entries.splice(index, 1);
        this._notify();
    },

    moveUp(index) {
        if (index === 0) return;
        const item = this.entries.splice(index, 1)[0];
        this.entries.splice(index - 1, 0, item);
        this._notify();
    },

    moveDown(index) {
        if (index >= this.entries.length - 1) return;
        const item = this.entries.splice(index, 1)[0];
        this.entries.splice(index + 1, 0, item);
        this._notify();
    },

    handleImageUpload(e, index) {
        const file = e.target.files[0];
        if (!file) return;
        if (file.size > 1.8 * 1024 * 1024) {
            this.$store.toast.trigger('Image is too large. Maximum size is 1.8MB.', 'error');
            e.target.value = '';
            return;
        }
        const key = `former-${index}`;
        if (this._previewUrls[key]) URL.revokeObjectURL(this._previewUrls[key]);
        this._previewUrls[key] = URL.createObjectURL(file);
        this.entries[index].imagePreview = this._previewUrls[key];
        this._notify();
        e.target.dispatchEvent(new Event('change', { bubbles: true }));
    },

    initials(name) {
        if (!name) return '?';
        const parts = name.trim().split(/\s+/).filter(p => !/^(rev|fr|father|msgr|monsignor)\.?$/i.test(p));
        if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
        return (parts[0]?.[0] || '?').toUpperCase();
    }
});
