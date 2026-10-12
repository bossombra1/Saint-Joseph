<x-public-layout>
    <section class="min-h-[70vh] flex items-center justify-center py-24 px-6"
             style="background: var(--cream, #F7F9FF);">
        <div class="max-w-lg mx-auto text-center">

            {{-- Icon --}}
            <div class="flex justify-center mb-8">
                <div class="w-24 h-24 rounded-[2rem] flex items-center justify-center"
                     style="background: rgba(13,42,82,0.06); border: 1.5px solid rgba(13,42,82,0.12);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24"
                         fill="none" stroke="#0D2A52" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                        <path d="M12 9v4"/>
                        <path d="M12 17h.01"/>
                    </svg>
                </div>
            </div>

            {{-- Error code --}}
            <p class="font-cinzel font-bold mb-2"
               style="font-size: clamp(5rem, 15vw, 8rem); color: rgba(13,42,82,0.08);
                      line-height: 1; letter-spacing: 0.1em; margin-top: -1rem;">
                500
            </p>

            {{-- Eyebrow --}}
            <div style="display:flex; align-items:center; gap:12px; justify-content:center; margin-bottom:12px; margin-top:-1rem;">
                <span style="height:1px; width:40px; background:linear-gradient(90deg,transparent,rgba(245,197,24,0.5)); display:block;"></span>
                <span style="font-size:10px; font-weight:700; letter-spacing:0.32em;
                             text-transform:uppercase; color:var(--gold, #F5C518);">System Error</span>
                <span style="height:1px; width:40px; background:linear-gradient(90deg,rgba(245,197,24,0.5),transparent); display:block;"></span>
            </div>

            <h1 class="font-heading font-bold italic mb-4"
                style="font-size: clamp(2rem, 5vw, 3rem); color: var(--blue-deep, #0D2A52); line-height: 1.1;">
                Something Went Wrong
            </h1>

            <p style="color: rgba(13,42,82,0.48); font-size: 14px; line-height: 1.8; margin-bottom: 2.5rem;">
                Something went wrong on our end. Please try again later or
                contact the parish office if the problem persists.
            </p>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('home') }}"
                   style="display:inline-flex; align-items:center; gap:8px;
                          background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
                          color: #0D2A52; font-weight: 700; font-size: 11px;
                          letter-spacing: 0.2em; text-transform: uppercase;
                          padding: 0 32px; height: 52px; border-radius: 999px;
                          box-shadow: 0 4px 20px rgba(245,197,24,0.40);
                          text-decoration: none; transition: all 0.25s ease;"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 32px rgba(245,197,24,0.55)';"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(245,197,24,0.40)';">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Back to Home
                </a>

                <a href="mailto:{{ config('services.parish.office_email', 'paroisse.stjoseph.bonoumin@gmail.com') }}"
                   style="display:inline-flex; align-items:center; gap:8px;
                          height: 52px; padding: 0 28px; border-radius: 999px;
                          border: 1.5px solid rgba(13,42,82,0.18);
                          color: rgba(13,42,82,0.55); font-weight: 700;
                          font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase;
                          text-decoration: none; transition: all 0.2s ease;"
                   onmouseover="this.style.background='rgba(13,42,82,0.05)'; this.style.borderColor='rgba(13,42,82,0.30)';"
                   onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(13,42,82,0.18)';">
                    Contact Parish
                </a>
            </div>

        </div>
    </section>
</x-public-layout>