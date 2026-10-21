<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — Paroisse Saint-Joseph Artisan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }
        .admin-sidebar {
            background: linear-gradient(180deg, #0A1929 0%, #0D2A52 40%, #0A1929 100%);
        }
        .admin-sidebar-glow {
            position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
            width: 200px; height: 200px; border-radius: 50%;
            background: radial-gradient(circle, rgba(245,197,24,.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .admin-header-glass {
            background: rgba(255,255,255,.82);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
        }
        .admin-nav-item {
            position: relative;
            transition: all .2s cubic-bezier(.4,0,.2,1);
        }
        .admin-nav-item::before {
            content: '';
            position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 0; border-radius: 0 3px 3px 0;
            background: linear-gradient(180deg, #F5C518, #E0A800);
            transition: height .25s cubic-bezier(.4,0,.2,1);
        }
        .admin-nav-item.active::before {
            height: 24px;
        }
        .admin-nav-item.active {
            background: rgba(245,197,24,.08);
        }
        .admin-avatar-ring {
            background: linear-gradient(135deg, #F5C518, #E0A800);
            padding: 2px;
        }
        .stat-shine {
            position: absolute; top: -50%; right: -30%; width: 80px; height: 80px;
            background: radial-gradient(circle, rgba(255,255,255,.08) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
    </style>
</head>

<body class="bg-[#F5F7FA] font-sans antialiased text-foreground">
    <div class="fixed top-4 right-4 z-[10000] flex items-center gap-1 rounded-full border border-black/10 bg-white/90 px-1 py-1 text-[10px] font-bold uppercase tracking-wider text-[#0D2A52] shadow-lg backdrop-blur-md">
        <a href="{{ route('locale.switch', 'fr') }}" class="rounded-full px-2.5 py-1 {{ app()->getLocale() === 'fr' ? 'bg-[#0D2A52] text-white' : 'text-[#0D2A52]/70 hover:bg-black/5' }}">FR</a>
        <a href="{{ route('locale.switch', 'en') }}" class="rounded-full px-2.5 py-1 {{ app()->getLocale() === 'en' ? 'bg-[#0D2A52] text-white' : 'text-[#0D2A52]/70 hover:bg-black/5' }}">EN</a>
    </div>

    {{-- Session flash → Alpine store --}}
    <div x-data x-init="
        @if(session('success')) $store.toast.trigger('{{ session('success') }}', 'success'); @endif
        @if(session('error')) $store.toast.trigger('{{ session('error') }}', 'error'); @endif
        $store.ui._notifUrl = '{{ route('admin.notifications.count') }}';
    "></div>

    <div class="min-h-screen flex">
        {{-- Mobile overlay --}}
        <div
            x-data
            x-show="$store.ui.sidebarOpen && $store.ui.isMobile"
            @click="$store.ui.sidebarOpen = false"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"
            x-transition.opacity
            x-cloak
        ></div>

        {{-- Sidebar --}}
        <aside
            x-data
            :class="$store.ui.isMobile && $store.ui.sidebarOpen ? 'w-[260px]' : (!$store.ui.isMobile ? ($store.ui.sidebarOpen ? 'w-[260px]' : 'w-[72px]') : 'w-0 overflow-hidden')"
            class="admin-sidebar text-primary-foreground transition-all duration-300 ease-in-out flex flex-col fixed inset-y-0 z-50 shadow-2xl shadow-black/20"
            :style="$store.ui.isMobile && !$store.ui.sidebarOpen ? 'width:0;overflow:hidden' : ''"
        >
            {{-- Logo --}}
            <div class="h-[72px] flex items-center px-5 border-b border-white/[.06] shrink-0 relative">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl overflow-hidden shrink-0 shadow-lg shadow-accent/20 flex items-center justify-center bg-white">
                        <span class="w-8 h-8" style="background:#0D2A52;-webkit-mask:url('/images/parish-logo.png') center/contain no-repeat;mask:url('/images/parish-logo.png') center/contain no-repeat;"></span>
                    </div>
                    <div x-show="$store.ui.sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
                        <p class="font-heading font-bold text-[15px] tracking-tight leading-tight text-white/95">Saint-Joseph Artisan</p>
                        <p class="text-[9px] font-bold uppercase tracking-[.2em] text-accent/70">Admin Panel</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 py-5 px-3 space-y-0.5 overflow-y-auto">
                @php $role = Auth::user()->role ?? 'super_admin'; @endphp

                <x-admin-nav-link href="{{ route('admin.dashboard') }}" icon="layout-dashboard" label="Dashboard"
                    :active="request()->is('internal/dashboard')" />

                @if($role === 'super_admin' || $role === 'staff')
                    <x-admin-nav-link href="{{ route('admin.intentions') }}" icon="heart" label="Mass Intentions"
                        :active="request()->is('internal/intentions*')" />
                    {{-- DONS DÉSACTIVÉS TEMPORAIREMENT — code conservé en commentaire.
                    <x-admin-nav-link href="{{ route('admin.donations') }}" icon="banknote" label="Donations"
                        :active="request()->is('internal/donations*')" />
                    --}}
                @endif

                @if($role === 'super_admin' || $role === 'soccom' || $role === 'staff')
                    <x-admin-nav-link href="{{ route('admin.inquiries.index') }}" icon="message-square-quote" label="Inquiries"
                        :active="request()->is('internal/inquiries*')" />
                @endif

                @if($role === 'super_admin' || $role === 'soccom')
                    <div class="pt-4 pb-2 px-3" x-show="$store.ui.sidebarOpen">
                        <p class="text-[9px] font-black uppercase tracking-[.25em] text-white/20">Content</p>
                    </div>
                    <div class="pt-4 pb-2 px-3" x-show="!$store.ui.sidebarOpen">
                        <div class="h-px bg-white/10 mx-2"></div>
                    </div>

                    <x-admin-nav-link href="{{ route('admin.schedules.index') }}" icon="calendar" label="Schedules"
                        :active="request()->is('internal/schedules*')" />
                    <x-admin-nav-link href="{{ route('admin.announcements.index') }}" icon="megaphone" label="Announcements"
                        :active="request()->is('internal/announcements*')" />
                    <x-admin-nav-link href="{{ route('admin.events.index') }}" icon="sparkles" label="Events"
                        :active="request()->is('internal/events*')" />
                    <x-admin-nav-link href="{{ route('admin.bulletins.index') }}" icon="book-open" label="Bulletins"
                        :active="request()->is('internal/bulletins*')" />
                    <x-admin-nav-link href="{{ route('admin.gallery.index') }}" icon="image" label="Gallery"
                        :active="request()->is('internal/gallery*')" />
                    <x-admin-nav-link href="{{ route('admin.highlights.index') }}" icon="clapperboard" label="Video Highlights"
                        :active="request()->is('internal/highlights*')" />

                    <div class="relative">
                        <x-admin-nav-link href="{{ route('admin.chats.index') }}" icon="messages-square" label="Live Chat"
                            :active="request()->is('internal/chats*')" />
                        <template x-if="$store.ui.notifCounts.chats > 0">
                            <span class="absolute top-2 right-3 h-5 min-w-5 px-1 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center shadow-lg shadow-red-500/30 animate-pulse" x-text="$store.ui.notifCounts.chats"></span>
                        </template>
                    </div>
                @endif

                @if($role === 'super_admin')
                    <div class="pt-4 pb-2 px-3" x-show="$store.ui.sidebarOpen">
                        <p class="text-[9px] font-black uppercase tracking-[.25em] text-white/20">System</p>
                    </div>
                    <div class="pt-4 pb-2 px-3" x-show="!$store.ui.sidebarOpen">
                        <div class="h-px bg-white/10 mx-2"></div>
                    </div>

                    <x-admin-nav-link href="{{ route('admin.users') }}" icon="users" label="Users"
                        :active="request()->is('internal/users*')" />
                    <x-admin-nav-link href="{{ route('admin.logs') }}" icon="scroll-text" label="Logs"
                        :active="request()->is('internal/logs*')" />
                    <x-admin-nav-link href="{{ route('admin.settings') }}" icon="settings" label="Settings"
                        :active="request()->is('internal/settings*')" />
                @endif
            </nav>

            {{-- Sidebar Glow --}}
            <div class="admin-sidebar-glow"></div>

            {{-- Logout --}}
            <div class="p-3 border-t border-white/[.06] shrink-0">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="button" @click="$store.confirm.open({
                        title: 'Confirm Logout',
                        message: 'Are you sure you want to end your session?',
                        onConfirm: () => document.getElementById('logout-form').submit(),
                        confirmText: 'Sign Out',
                        type: 'primary'
                    })"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/[.06] text-white/50 hover:text-white/80 text-sm font-medium transition-all duration-200 group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="shrink-0 group-hover:translate-x-0.5 transition-transform">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" x2="9" y1="12" y2="12" />
                        </svg>
                        <span x-show="$store.ui.sidebarOpen" class="font-bold text-xs">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main
            x-data
            :class="($store.ui.isMobile ? 'ml-0' : ($store.ui.sidebarOpen ? 'ml-[260px]' : 'ml-[72px]'))"
            class="flex-1 flex flex-col transition-all duration-300 ease-in-out min-h-screen"
        >
            {{-- Top Header --}}
            <header class="admin-header-glass h-[72px] border-b border-black/[.04] flex items-center justify-between px-6 lg:px-10 sticky top-0 z-40">
                <button @click="$store.ui.sidebarOpen = !$store.ui.sidebarOpen"
                    class="p-2 -ml-2 rounded-xl hover:bg-black/[.04] text-muted-foreground transition-all duration-200 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" x2="20" y1="12" y2="12" />
                        <line x1="4" x2="20" y1="6" y2="6" />
                        <line x1="4" x2="20" y1="18" y2="18" />
                    </svg>
                </button>

                <div class="flex items-center gap-5">
                    {{-- Notification Bell --}}
                    <div x-data="{
                        open: false,
                        get counts() { return $store.ui.notifCounts },
                        get total() { return this.counts.intentions + this.counts.inquiries + this.counts.chats }
                    }" class="relative">
                        <button @click="open = !open" class="p-2.5 rounded-xl hover:bg-black/[.04] text-muted-foreground transition-all relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                            <template x-if="total > 0">
                                <span class="absolute -top-0.5 -right-0.5 h-[18px] min-w-[18px] px-1 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center border-2 border-white shadow-lg shadow-red-500/30 animate-pulse" x-text="total"></span>
                            </template>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-3 w-80 bg-white border border-black/[.06] shadow-2xl shadow-black/10 rounded-2xl overflow-hidden z-50"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                            <div class="p-5 border-b border-black/[.04] bg-gradient-to-r from-[#F5F7FA] to-white">
                                <h4 class="text-[10px] font-black uppercase tracking-[.2em] text-primary/60">Notifications</h4>
                            </div>
                            <div class="p-2 max-h-[320px] overflow-y-auto">
                                <template x-if="total === 0">
                                    <div class="p-8 text-center">
                                        <div class="w-12 h-12 rounded-full bg-[#F5F7FA] flex items-center justify-center mx-auto mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted-foreground/40"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                        </div>
                                        <p class="text-xs text-muted-foreground/60 font-medium">All caught up</p>
                                    </div>
                                </template>
                                <template x-if="counts.intentions > 0">
                                    <a href="{{ route('admin.intentions', ['status' => 'pending']) }}" class="flex items-center gap-3.5 p-3.5 rounded-xl hover:bg-[#F5F7FA] transition-colors group">
                                        <div class="h-10 w-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-primary"><span x-text="counts.intentions"></span> New Intentions</p>
                                            <p class="text-[10px] text-muted-foreground/60 font-medium">Pending review</p>
                                        </div>
                                    </a>
                                </template>
                                <template x-if="counts.inquiries > 0">
                                    <a href="{{ route('admin.inquiries.index') }}" class="flex items-center gap-3.5 p-3.5 rounded-xl hover:bg-[#F5F7FA] transition-colors group">
                                        <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-primary"><span x-text="counts.inquiries"></span> New Inquiries</p>
                                            <p class="text-[10px] text-muted-foreground/60 font-medium">Recent submissions</p>
                                        </div>
                                    </a>
                                </template>
                                <template x-if="counts.chats > 0">
                                    <a href="{{ route('admin.chats.index') }}" class="flex items-center gap-3.5 p-3.5 rounded-xl hover:bg-[#F5F7FA] transition-colors group">
                                        <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6 6-6"/><path d="m5 12 6 6 6-6"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-primary"><span x-text="counts.chats"></span> Chats Waiting</p>
                                            <p class="text-[10px] text-muted-foreground/60 font-medium">Awaiting reply</p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                            <div class="p-3 border-t border-black/[.04] bg-[#FAFBFC]">
                                <a href="{{ route('admin.dashboard') }}" class="block w-full text-center py-2 text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 hover:text-primary transition-colors">View All Activities</a>
                            </div>
                        </div>
                    </div>

                    {{-- User Profile --}}
                    <div class="flex items-center gap-3.5 border-l border-black/[.06] pl-5">
                        <div class="text-right hidden sm:block">
                            <p class="text-[13px] font-bold text-primary leading-tight">{{ Auth::user()->name ?? 'Admin User' }}</p>
                            <p class="text-[10px] text-muted-foreground/60 font-medium">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                        <div class="admin-avatar-ring rounded-full shrink-0">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center text-accent-foreground font-black text-xs">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="p-4 sm:p-6 lg:p-10 flex-1 page-animate">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Global Toast + Confirm --}}
    <x-admin-toast />
    <x-admin-confirm />

    @stack('scripts')
</body>
</html>
