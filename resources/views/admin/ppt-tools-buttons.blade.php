<button type="button" @click="fetchPreview()"
    class="inline-flex items-center gap-2 border border-border bg-white text-primary px-4 py-2 rounded-lg font-bold text-xs hover:bg-muted transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z" />
        <circle cx="12" cy="12" r="3" />
    </svg>
    Aperçu Word
</button>

<button type="button" @click="downloadWord()" :disabled="downloadingWord"
    class="inline-flex items-center gap-2 bg-accent text-accent-foreground px-4 py-2 rounded-lg font-bold text-xs shadow-sm hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-wait">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
        <polyline points="7 10 12 15 17 10" />
        <line x1="12" x2="12" y1="15" y2="3" />
    </svg>
    <span x-text="downloadingWord ? 'Préparation…' : 'Télécharger Word'"></span>
</button>