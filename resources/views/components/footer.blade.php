<footer class="border-t bg-primary text-primary-foreground">
    <div class="container py-12 mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl text-accent">✝</span>
                    <span class="font-heading text-xl font-bold">Paroisse Saint-Joseph Artisan</span>
                </div>
                <p class="text-primary-foreground/70 text-base leading-relaxed">
                    Serving the faithful community with love and devotion. Join us in prayer and worship.
                </p>
            </div>
            <div>
                <h4 class="font-heading font-semibold mb-4 text-accent">Quick Links</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                    <a href="{{ route('mass-schedule') }}" class="text-sm text-primary-foreground/70 hover:text-primary-foreground transition-colors">Mass Schedule</a>
                    <a href="{{ route('submit-intention') }}" class="text-sm text-primary-foreground/70 hover:text-primary-foreground transition-colors">Submit Intention</a>
                    <a href="{{ route('inquiry') }}" class="text-sm text-primary-foreground/70 hover:text-primary-foreground transition-colors">Sacramental Inquiry</a>
                    <a href="{{ route('events') }}" class="text-sm text-primary-foreground/70 hover:text-primary-foreground transition-colors">Parish Events</a>
                    <a href="{{ route('gallery') }}" class="text-sm text-primary-foreground/70 hover:text-primary-foreground transition-colors">Photo Gallery</a>

                    <a href="{{ route('track') }}" class="text-sm text-primary-foreground/70 hover:text-primary-foreground transition-colors">Track Submission</a>
                    <a href="{{ route('about') }}" class="text-sm text-primary-foreground/70 hover:text-primary-foreground transition-colors">About Us</a>
                    <a href="{{ route('donate') }}" class="text-sm text-primary-foreground/70 hover:text-primary-foreground transition-colors">Donate</a>
                    <a href="{{ route('privacy-policy') }}" class="text-sm text-primary-foreground/70 hover:text-primary-foreground transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="text-sm text-primary-foreground/70 hover:text-primary-foreground transition-colors">Terms &amp; Conditions</a>
                </div>
            </div>
            <div>
                <h4 class="font-heading font-semibold mb-4 text-accent">Contact</h4>
                <p class="text-base text-primary-foreground/70 leading-relaxed">
                    <a href="https://www.facebook.com/saintjosephartisanrivierabonoumin/?locale=fr_FR">Paroisse Saint-Joseph Artisan Riviera Bonoumin</a><br />
                    @php
                        $contactRaw = $global_settings['parish_contact'] ?? '';
                        $contactNumbers = is_string($contactRaw) && $contactRaw !== ''
                            ? (json_decode($contactRaw, true) ?: [$contactRaw])
                            : (is_array($contactRaw) ? $contactRaw : ['2722496069']);
                    @endphp
                    {{ implode(' | ', $contactNumbers) }}<br />
                    Sunday Mass: 6:30 AM, 8:30 AM, 10:00 AM, 3:00 PM, 4:30 PM, 6:00 PM
                </p>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-primary-foreground/10 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left text-xs text-primary-foreground/50">
            <p>© {{ date('Y') }} Paroisse Saint-Joseph Artisan. Developed for the Greater Glory of God.</p>
            <p class="uppercase tracking-widest font-black opacity-30 italic">Riviera Bonoumin Cocody, Abidjan, Côte d'Ivoire</p>
        </div>
    </div>
</footer>