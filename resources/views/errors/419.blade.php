@php
    $home = route('home');
    $email = config('services.parish.office_email', 'paroisse.stjoseph.bonoumin@gmail.com');
@endphp

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
                        <circle cx="12" cy="12" r="9"/>
                        <polyline points="12 7 12 12 17 14.5"/>
                    </svg>
                </div>
            </div>

            {{-- Error code --}}
            <p class="font-cinzel font-bold mb-2"
               style="font-size: clamp(5rem, 15vw, 8rem); color: rgba(13,42,82,0.08);
                       line-height: 1; letter-spacing: 0.1em; margin-top: -1rem;">
                419
            </p>

            {{-- Eyebrow --}}
            <div style="display:flex; align-items:center; gap:12px; justify-content:center; margin-bottom:12px; margin-top:-1rem;">
                <span style="height:1px; width:40px; background:linear-gradient(90deg,transparent,rgba(245,197,24,0.5)); display:block;"></span>
                <span style="font-size:10px; font-weight:700; letter-spacing:0.32em;
                             text-transform:uppercase; color:var(--gold, #F5C518);">Session Expired</span>
                <span style="height:1px; width:40px; background:linear-gradient(90deg,rgba(245,197,24,0.5),transparent); display:block;"></span>
            </div>

            <h1 class="font-heading font-bold italic mb-4"
                style="font-size: clamp(2rem, 5vw, 3rem); color: var(--blue-deep, #0D2A52); line-height: 1.1;">
                Page Expired
            </h1>

            <p style="color: rgba(13,42,82,0.48); font-size: 14px; line-height: 1.8; margin-bottom: 2.5rem;">
                Your session has expired, likely because you left a form open for too long.
                Please try again — your generosity is still welcome.
            </p>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="javascript:history.back()"
                   style="display:inline-flex; align-items:center; gap:8px;
                          background: linear-gradient(135deg, #FFD740 0%, #F5C918 55%, #E0A800 100%);
                          color: #0D2A52; font-weight: 700; font-size: 11px;
                          letter-spacing: 0.2em; text-transform: uppercase;
                          padding: 0 32px; height: 52px; border-radius: 999px;
                          box-shadow: 0 4px 20px rgba(245,197,24,0.40);
                          text-decoration: none; transition: all 0.25s ease;"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 32px rgba(245,197,24,0.55)';"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(245,197,24,0.40)';">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 19 8 12 15 5"/>
                    </svg>
                    Try Again
                </a>

                <a href="{{ $home }}"
                   style="display:inline-flex; align-items:center; gap:8px;
                          height: 52px; padding: 0 28px; border-radius: 999px;
                          border: 1.5px solid rgba(13,42,82,0.18);
                          color: rgba(13,42,82,0.55); font-weight: 700;
                          font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase;
                          text-decoration: none; transition: all 0.2s ease;"
                   onmouseover="this.style.background='rgba(13,42,82,0.05)'; this.style.borderColor='rgba(13,42,82,0.30)';"
                   onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(13,42,82,0.18)';">
                    Back to Home
                </a>
            </div>

        </div>
    </section>
</x-public-layout>
