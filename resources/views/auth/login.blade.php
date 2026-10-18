<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Login — Paroisse Saint-Joseph Artisan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preload" href="{{ asset('fonts/Canterbury.ttf') }}" as="font" type="font/ttf" crossorigin>
    <style>
        @font-face{font-family:'Canterbury';src:url('{{ asset('fonts/Canterbury.ttf') }}') format('truetype');font-weight:normal;font-style:normal;font-display:swap;}
        /* ── Login-specific design tokens ── */
        :root {
            --login-bg: #fbfbfd;
            --login-text: #1d1d1f;
            --login-primary: #7A2E3B;
            --login-primary-dark: #5E2430;
            --login-gradient-end: #3F1A22;
            --login-gold: #D9B26B;
            --login-gold-light: #E8CFA0;
            --login-surface: #ffffff;
            --login-border: rgba(125, 46, 59, 0.10);
            --login-border-focus: rgba(122, 46, 59, 0.35);
            --login-shadow-sm: 0 1px 3px rgba(63, 26, 34, 0.06);
            --login-shadow-md: 0 4px 16px rgba(63, 26, 34, 0.08);
            --login-shadow-lg: 0 12px 40px rgba(63, 26, 34, 0.12);
            --login-radius: 14px;
            --login-spring: cubic-bezier(.25, .1, .25, 1);
            --login-spring-bounce: cubic-bezier(.34, 1.56, .64, 1);
            --font-login: -apple-system, BlinkMacSystemFont, 'Inter', system-ui, sans-serif;
        }

        /* ── Reset for standalone page ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; text-rendering: optimizeLegibility; }
        body {
            font-family: var(--font-login);
            background: var(--login-bg);
            color: var(--login-text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Split layout ── */
        .login-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ── Left branded panel ── */
        .login-brand {
            position: relative;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 48%;
            padding: 3rem;
            overflow: hidden;
            text-align: center;
            color: #fff;
        }
        .login-brand-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
        .login-brand-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: saturate(.75) brightness(.85);
            transform: scale(1.04);
        }
        .login-brand-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(8,20,45,.68) 0%, rgba(10,25,55,.58) 40%, rgba(8,20,45,.8) 72%, rgba(247,249,255,1) 100%);
        }
        .login-brand-radial {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(ellipse 80% 60% at 50% 30%, rgba(26,64,128,.22) 0%, transparent 70%);
        }

        @media (min-width: 1024px) {
            .login-brand { display: flex; }
        }

        /* ── Rosary-bead arc motif ── */
        .rosary-arc {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 520px;
            height: 520px;
            pointer-events: none;
            z-index: 0;
        }
        .rosary-arc svg { width: 100%; height: 100%; }

        /* Brand content */
        .brand-content { position: relative; z-index: 1; }
        .brand-cross {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 1.5px solid rgba(217, 178, 107, 0.35);
            background: rgba(217, 178, 107, 0.08);
            backdrop-filter: blur(12px);
            margin-bottom: 1.75rem;
            overflow: hidden;
        }
        .brand-cross img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: 0.9;
        }
        .brand-title {
            font-family: 'Canterbury', serif;
            font-size: clamp(2rem, 4.5vw, 3.2rem);
            font-weight: 700;
            letter-spacing: -0.025em;
            line-height: 1.15;
            color: #fff;
            margin-bottom: 0.75rem;
        }
        .brand-subtitle {
            font-size: 0.8125rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(217, 178, 107, 0.8);
            margin-bottom: 2.5rem;
        }
        .brand-divider {
            width: 48px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--login-gold), transparent);
            margin: 0 auto 2rem;
        }
        .brand-quote {
            font-size: 0.9375rem;
            font-style: italic;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.65);
            max-width: 300px;
        }
        .brand-quote cite {
            display: block;
            margin-top: 0.75rem;
            font-style: normal;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(217, 178, 107, 0.55);
        }

        /* ── Right form panel ── */
        .login-form-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2.5rem 1.5rem;
            background: var(--login-surface);
            position: relative;
        }
        @media (min-width: 1024px) {
            .login-form-panel { width: 52%; padding: 3rem 4rem; }
        }

        /* Subtle decorative edge on the form panel (visible on desktop) */
        .login-form-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 1px;
            height: 100%;
            background: linear-gradient(180deg, transparent 10%, var(--login-border) 50%, transparent 90%);
            display: none;
        }
        @media (min-width: 1024px) {
            .login-form-panel::before { display: block; }
        }

        /* ── Form wrapper ── */
        .login-form-wrapper {
            width: 100%;
            max-width: 380px;
        }
        .form-header { margin-bottom: 2rem; }
        .form-header h2 {
            font-family: var(--font-login);
            font-size: 1.625rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--login-text);
            margin-bottom: 0.375rem;
        }
        .form-header p {
            font-size: 0.875rem;
            color: rgba(29, 29, 31, 0.5);
            line-height: 1.5;
        }

        /* ── Status message ── */
        .login-status {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--login-radius);
            background: rgba(34, 139, 34, 0.06);
            border: 1px solid rgba(34, 139, 34, 0.15);
            font-size: 0.8125rem;
            color: #1a6b1a;
            line-height: 1.45;
        }
        .login-status svg { flex-shrink: 0; }

        /* ── Global error banner ── */
        .login-error-banner {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--login-radius);
            background: rgba(220, 38, 38, 0.05);
            border: 1px solid rgba(220, 38, 38, 0.15);
            font-size: 0.8125rem;
            color: #b91c1c;
            line-height: 1.45;
            animation: shake 0.5s var(--login-spring);
        }
        .login-error-banner svg { flex-shrink: 0; }

        /* ── Form groups ── */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--login-text);
            margin-bottom: 0.375rem;
            letter-spacing: 0.01em;
        }
        .form-input-wrap {
            position: relative;
        }
        .form-input {
            display: block;
            width: 100%;
            height: 48px;
            padding: 0 1rem;
            font-family: var(--font-login);
            font-size: 0.9375rem;
            color: var(--login-text);
            background: var(--login-bg);
            border: 1px solid var(--login-border);
            border-radius: var(--login-radius);
            outline: none;
            transition: border-color 0.25s var(--login-spring), box-shadow 0.25s var(--login-spring), background 0.25s var(--login-spring);
            box-shadow: var(--login-shadow-sm);
        }
        .form-input::placeholder { color: rgba(29, 29, 31, 0.3); }
        .form-input:hover {
            border-color: rgba(125, 46, 59, 0.20);
        }
        .form-input:focus {
            border-color: var(--login-border-focus);
            box-shadow: var(--login-shadow-sm), 0 0 0 3px rgba(122, 46, 59, 0.08);
            background: #fff;
        }
        .form-input.has-error {
            border-color: rgba(220, 38, 38, 0.45);
            box-shadow: var(--login-shadow-sm), 0 0 0 3px rgba(220, 38, 38, 0.06);
            animation: shake 0.5s var(--login-spring);
        }

        /* Password input with toggle */
        .password-wrap .form-input { padding-right: 3rem; }
        .password-toggle {
            position: absolute;
            right: 4px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            color: rgba(29, 29, 31, 0.35);
            cursor: pointer;
            border-radius: 10px;
            transition: color 0.2s var(--login-spring), background 0.2s var(--login-spring);
        }
        .password-toggle:hover { color: var(--login-primary); background: rgba(122, 46, 59, 0.05); }
        .password-toggle:focus-visible {
            outline: 2px solid var(--login-primary);
            outline-offset: 2px;
        }
        .password-toggle svg { pointer-events: none; }

        /* ── Field error messages ── */
        .field-error {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            margin-top: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #b91c1c;
            animation: shake 0.5s var(--login-spring);
        }

        /* ── Submit button ── */
        .submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            height: 48px;
            margin-top: 0.5rem;
            padding: 0 1.5rem;
            font-family: var(--font-login);
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #fff;
            background: linear-gradient(135deg, var(--login-primary) 0%, var(--login-primary-dark) 100%);
            border: none;
            border-radius: var(--login-radius);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(122, 46, 59, 0.25), 0 1px 2px rgba(122, 46, 59, 0.15);
            transition: transform 0.25s var(--login-spring-bounce), box-shadow 0.25s var(--login-spring), opacity 0.25s var(--login-spring), filter 0.25s var(--login-spring);
            position: relative;
            overflow: hidden;
        }
        .submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 50%);
            pointer-events: none;
        }
        .submit-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(122, 46, 59, 0.3), 0 2px 6px rgba(122, 46, 59, 0.18);
        }
        .submit-btn:active:not(:disabled) {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(122, 46, 59, 0.2);
        }
        .submit-btn:focus-visible {
            outline: 2px solid var(--login-primary);
            outline-offset: 3px;
        }
        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Spinner */
        .btn-spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        .submit-btn.is-loading .btn-text { opacity: 0; }
        .submit-btn.is-loading .btn-spinner { display: block; position: absolute; }

        /* ── Footer link ── */
        .form-footer {
            margin-top: 2rem;
            text-align: center;
        }
        .form-footer a {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: rgba(29, 29, 31, 0.45);
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 10px;
            transition: color 0.2s var(--login-spring), background 0.2s var(--login-spring);
        }
        .form-footer a:hover { color: var(--login-primary); background: rgba(122, 46, 59, 0.04); }
        .form-footer a:focus-visible {
            outline: 2px solid var(--login-primary);
            outline-offset: 2px;
        }

        /* ── Mobile header (visible only on mobile) ── */
        .mobile-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 2rem;
            padding-top: 1rem;
        }
        @media (min-width: 1024px) {
            .mobile-brand { display: none; }
        }
        .mobile-brand-cross {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 1.5px solid rgba(125, 46, 59, 0.15);
            background: rgba(122, 46, 59, 0.04);
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .mobile-brand-cross img {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }
        .mobile-brand-title {
            font-family: 'Canterbury', serif;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: var(--login-text);
        }
        .mobile-brand-sub {
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(29, 29, 31, 0.4);
            margin-top: 0.25rem;
        }

        /* ── Keyframes ── */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            15% { transform: translateX(-6px); }
            30% { transform: translateX(5px); }
            45% { transform: translateX(-4px); }
            60% { transform: translateX(3px); }
            75% { transform: translateX(-2px); }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Entrance animation ── */
        .login-form-wrapper {
            animation: fadeSlideUp 0.6s var(--login-spring) both;
            animation-delay: 0.1s;
        }
        .brand-content {
            animation: fadeSlideUp 0.7s var(--login-spring) both;
            animation-delay: 0.2s;
        }

        /* ── Reduced motion ── */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            .submit-btn:hover:not(:disabled) { transform: none; }
        }

        /* ── Focus-visible global ── */
        :focus-visible {
            outline: 2px solid var(--login-primary);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="login-shell">

        {{-- ═══════════════════════════════════════════════════════
             LEFT PANEL — Branded (desktop only)
             ═══════════════════════════════════════════════════════ --}}
        <aside class="login-brand" aria-hidden="true">
            {{-- Background image matching hero section --}}
            <div class="login-brand-bg">
                <img src="{{ asset('images/parish-logo.png') }}" alt="" aria-hidden="true" fetchpriority="high" decoding="async">
                <div class="login-brand-overlay"></div>
                <div class="login-brand-radial"></div>
            </div>

            {{-- Rosary-bead arc motif --}}
            <div class="rosary-arc">
                <svg viewBox="0 0 520 520" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    {{-- Outer arc with rosary beads --}}
                    <circle cx="260" cy="260" r="230" stroke="rgba(217,178,107,0.08)" stroke-width="1" fill="none"/>
                    <circle cx="260" cy="260" r="200" stroke="rgba(217,178,107,0.06)" stroke-width="0.5" fill="none"/>
                    {{-- Rosary beads (small circles along the arc) --}}
                    <circle cx="260" cy="30" r="4" fill="rgba(217,178,107,0.12)"/>
                    <circle cx="338" cy="36" r="3.5" fill="rgba(217,178,107,0.10)"/>
                    <circle cx="410" cy="62" r="4" fill="rgba(217,178,107,0.12)"/>
                    <circle cx="468" cy="108" r="3" fill="rgba(217,178,107,0.08)"/>
                    <circle cx="504" cy="170" r="4" fill="rgba(217,178,107,0.12)"/>
                    <circle cx="516" cy="240" r="3.5" fill="rgba(217,178,107,0.10)"/>
                    <circle cx="504" cy="310" r="4" fill="rgba(217,178,107,0.12)"/>
                    <circle cx="468" cy="372" r="3" fill="rgba(217,178,107,0.08)"/>
                    <circle cx="410" cy="418" r="4" fill="rgba(217,178,107,0.12)"/>
                    <circle cx="338" cy="444" r="3.5" fill="rgba(217,178,107,0.10)"/>
                    <circle cx="260" cy="450" r="4" fill="rgba(217,178,107,0.12)"/>
                    <circle cx="182" cy="444" r="3.5" fill="rgba(217,178,107,0.10)"/>
                    <circle cx="110" cy="418" r="4" fill="rgba(217,178,107,0.12)"/>
                    <circle cx="52" cy="372" r="3" fill="rgba(217,178,107,0.08)"/>
                    <circle cx="16" cy="310" r="4" fill="rgba(217,178,107,0.12)"/>
                    <circle cx="4" cy="240" r="3.5" fill="rgba(217,178,107,0.10)"/>
                    <circle cx="16" cy="170" r="4" fill="rgba(217,178,107,0.12)"/>
                    <circle cx="52" cy="108" r="3" fill="rgba(217,178,107,0.08)"/>
                    <circle cx="110" cy="62" r="4" fill="rgba(217,178,107,0.12)"/>
                    <circle cx="182" cy="36" r="3.5" fill="rgba(217,178,107,0.10)"/>
                    {{-- Cross bead at top --}}
                    <circle cx="260" cy="24" r="6" fill="rgba(217,178,107,0.18)" stroke="rgba(217,178,107,0.25)" stroke-width="0.75"/>
                    <line x1="260" y1="19" x2="260" y2="29" stroke="rgba(217,178,107,0.35)" stroke-width="1"/>
                    <line x1="255" y1="24" x2="265" y2="24" stroke="rgba(217,178,107,0.35)" stroke-width="1"/>
                </svg>
            </div>

            <div class="brand-content">
                <div class="brand-cross"><img src="{{ asset('images/parish-logo.png') }}" alt="Paroisse Saint-Joseph Artisan Logo"></div>
                <h1 class="brand-title">Paroisse Saint-Joseph Artisan</h1>
                <p class="brand-subtitle">Staff Administration</p>
                <div class="brand-divider"></div>
                <p class="brand-quote">
                    "Le Saint Rosaire est le trésor d'innombrables grâces."
                    <cite>— St. Louis de Montfort</cite>
                </p>
            </div>
        </aside>

        {{-- ═══════════════════════════════════════════════════════
             RIGHT PANEL — Login Form
             ═══════════════════════════════════════════════════════ --}}
        <main class="login-form-panel" x-data="loginForm()" role="main">
            <div class="login-form-wrapper">

                {{-- Mobile brand header --}}
                <div class="mobile-brand">
                    <div class="mobile-brand-cross"><img src="{{ asset('images/parish-logo.png') }}" alt="Paroisse Saint-Joseph Artisan Logo"></div>
                    <p class="mobile-brand-title">Paroisse Saint-Joseph Artisan</p>
                    <p class="mobile-brand-sub">Staff Login</p>
                </div>

                {{-- Desktop form header --}}
                <div class="form-header">
                    <h2>Welcome back</h2>
                    <p>Sign in to the parish admin panel.</p>
                </div>

                {{-- Session status --}}
                @if (session('status'))
                    <div class="login-status" role="status" aria-live="polite">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="login-error-banner" role="alert" aria-live="assertive">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" novalidate @submit="submitting = true">
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label class="form-label" for="email">Email address</label>
                        <div class="form-input-wrap">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="admin@storosario.ph"
                                class="form-input @error('email') has-error @enderror"
                                aria-describedby="@error('email') email-error @enderror"
                                aria-invalid="@error('email') true @enderror"
                            />
                        </div>
                        @error('email')
                            <p class="field-error" id="email-error" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="form-input-wrap password-wrap">
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                class="form-input @error('password') has-error @enderror"
                                aria-describedby="@error('password') password-error @enderror"
                                aria-invalid="@error('password') true @enderror"
                            />
                            <button
                                type="button"
                                class="password-toggle"
                                @click="showPassword = !showPassword"
                                :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                :aria-pressed="showPassword.toString()"
                                tabindex="0"
                            >
                                {{-- Eye open (password hidden) --}}
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                {{-- Eye closed (password visible) --}}
                                <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="field-error" id="password-error" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="submit-btn"
                        :class="{ 'is-loading': submitting }"
                        :disabled="submitting"
                    >
                        <span class="btn-text">Sign In</span>
                        <span class="btn-spinner" aria-hidden="true"></span>
                    </button>
                </form>

                {{-- Footer --}}
                <div class="form-footer">
                    <a href="{{ route('home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        Back to public site
                    </a>
                </div>

            </div>
        </main>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         Alpine.js — Login Form Component
         ═══════════════════════════════════════════════════════ --}}
    <script>
        function loginForm() {
            return {
                showPassword: false,
                submitting: false,
            }
        }
    </script>

    <script>
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
    </script>
</body>
</html>
