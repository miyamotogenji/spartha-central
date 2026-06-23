<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — ASOIINFO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@200;300;400;500;600;700;800&family=Fira+Code:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0 }

        :root {
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'Fira Code', monospace;
            --indigo: #6366f1;
            --violet: #7c3aed;
            --purple: #a855f7;
            --teal: #14b8a6;
        }

        html, body { height: 100%; width: 100%; overflow-x: hidden }

        body {
            font-family: var(--font);
            background: #020509;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Aurora Background ── */
        .aurora {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .aurora::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 120% 80% at 0% 0%,   rgba(99,102,241,.22) 0%, transparent 55%),
                radial-gradient(ellipse 90%  70% at 100% 100%, rgba(124,58,237,.18) 0%, transparent 55%),
                radial-gradient(ellipse 70%  60% at 50% 50%,  rgba(168,85,247,.08) 0%, transparent 60%),
                radial-gradient(ellipse 80%  50% at 100% 0%,  rgba(20,184,166,.10) 0%, transparent 50%);
            animation: auroraShift 12s ease-in-out infinite alternate;
        }

        @keyframes auroraShift {
            0%   { opacity: 1;   transform: scale(1)    rotate(0deg) }
            50%  { opacity: .85; transform: scale(1.04) rotate(.5deg) }
            100% { opacity: 1;   transform: scale(1.02) rotate(-.5deg) }
        }

        /* Dot grid */
        .aurora::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(99,102,241,.18) 1px, transparent 1px);
            background-size: 36px 36px;
            opacity: .5;
        }

        /* Floating orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            animation: orbFloat linear infinite;
        }
        .orb-1 { width:560px; height:560px; top:-180px; left:-140px; background:rgba(99,102,241,.14); animation-duration:18s }
        .orb-2 { width:420px; height:420px; bottom:-120px; right:-100px; background:rgba(124,58,237,.13); animation-duration:22s; animation-direction:reverse }
        .orb-3 { width:300px; height:300px; top:40%; left:55%; background:rgba(20,184,166,.07); animation-duration:26s }
        .orb-4 { width:200px; height:200px; top:10%; right:20%; background:rgba(168,85,247,.1); animation-duration:14s; animation-direction:reverse }

        @keyframes orbFloat {
            0%,100% { transform: translate(0,0) scale(1) }
            25%      { transform: translate(18px,-22px) scale(1.04) }
            50%      { transform: translate(-12px,16px) scale(.97) }
            75%      { transform: translate(22px,10px) scale(1.02) }
        }

        /* Top accent line */
        .accent-line {
            position: fixed; top: 0; left: 0; right: 0; height: 2px; z-index: 30;
            background: linear-gradient(90deg, transparent, #6366f1 25%, #a855f7 60%, #14b8a6 85%, transparent);
        }

        /* ── Page layout ── */
        .page {
            position: relative;
            z-index: 10;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        /* ── Logo ── */
        .logo-wrap {
            text-align: center;
            margin-bottom: 28px;
            animation: fadeUp .6s ease both;
        }
        .logo-wrap img {
            height: 120px;
            width: auto;
            display: block;
            margin: 0 auto;
            mix-blend-mode: screen;
            filter:
                drop-shadow(0 0 32px rgba(99,102,241,.9))
                drop-shadow(0 0 14px rgba(168,85,247,.6))
                drop-shadow(0 0 60px rgba(99,102,241,.3));
        }
        .logo-sub {
            margin-top: 10px;
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #475569;
        }

        /* ── Card ── */
        .card {
            width: 100%;
            max-width: 448px;
            background: rgba(7,12,24,.78);
            border: 1px solid rgba(99,102,241,.22);
            border-radius: 28px;
            backdrop-filter: blur(36px);
            -webkit-backdrop-filter: blur(36px);
            box-shadow:
                0 40px 80px rgba(0,0,0,.65),
                0 0 0 1px rgba(255,255,255,.04) inset,
                0 1px 0 rgba(255,255,255,.07) inset;
            animation: fadeUp .6s .08s ease both;
            overflow: hidden;
        }

        .card-body { padding: 36px 40px 28px }
        .card-foot { padding: 20px 40px; border-top: 1px solid rgba(99,102,241,.12); text-align: center }

        /* ── Card header ── */
        .card-icon {
            width: 42px; height: 42px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #6366f1, #7c3aed);
            box-shadow: 0 6px 20px rgba(99,102,241,.4);
            margin-bottom: 16px;
        }
        .card-icon svg { width: 20px; height: 20px; color: #fff }
        .card-title { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; letter-spacing: -.03em; line-height: 1.2 }
        .card-sub   { font-size: .9rem; color: #64748b; margin-top: 4px }

        /* ── Form ── */
        .field { margin-bottom: 20px }
        .lbl {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 8px;
        }
        .inp {
            width: 100%;
            background: rgba(2,5,12,.85);
            border: 1.5px solid rgba(30,42,66,.9);
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 1rem;
            color: #e2e8f0;
            font-family: var(--font);
            outline: none;
            transition: all .2s;
        }
        .inp:focus {
            border-color: var(--indigo);
            background: rgba(2,5,12,1);
            box-shadow: 0 0 0 4px rgba(99,102,241,.12), 0 0 16px rgba(99,102,241,.1);
        }
        .inp::placeholder { color: #1e293b }

        /* ── Alert ── */
        .alert-err {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 14px 16px; border-radius: 14px; margin-bottom: 22px;
            background: rgba(127,29,29,.2); border: 1px solid rgba(239,68,68,.3);
            color: #fca5a5; font-size: .9rem; line-height: 1.4;
        }
        .alert-ok {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 14px 16px; border-radius: 14px; margin-bottom: 22px;
            background: rgba(6,78,59,.2); border: 1px solid rgba(16,185,129,.3);
            color: #6ee7b7; font-size: .9rem; line-height: 1.4;
        }
        .alert-icon { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px }

        /* ── Button ── */
        .btn {
            width: 100%;
            padding: 15px 24px;
            border-radius: 14px;
            font-size: 1.02rem;
            font-weight: 700;
            color: #fff;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            letter-spacing: .02em;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 40%, #7c3aed 100%);
            box-shadow: 0 6px 28px rgba(99,102,241,.45), 0 1px 0 rgba(255,255,255,.15) inset;
            transition: all .22s;
            margin-top: 8px;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 12px 36px rgba(99,102,241,.6) }
        .btn:active { transform: translateY(0); box-shadow: 0 4px 16px rgba(99,102,241,.35) }

        /* ── Footer link ── */
        .foot-txt { font-size: .9rem; color: #475569 }
        .foot-link { color: #818cf8; font-weight: 600; text-decoration: none; transition: color .15s }
        .foot-link:hover { color: #c4b5fd }

        /* ── Pills ── */
        .pills { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-top: 24px; animation: fadeUp .6s .3s ease both }
        .pill {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 15px; border-radius: 50px;
            background: rgba(7,12,24,.85);
            border: 1px solid rgba(30,42,66,.9);
            font-size: .78rem; font-weight: 500; color: #64748b;
            backdrop-filter: blur(8px);
            transition: border-color .2s, color .2s;
        }
        .pill:hover { border-color: rgba(99,102,241,.4); color: #94a3b8 }
        .pill-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0 }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px) }
            to   { opacity: 1; transform: translateY(0) }
        }
        .fu-1 { animation: fadeUp .6s .14s ease both }
        .fu-2 { animation: fadeUp .6s .22s ease both }
        .fu-3 { animation: fadeUp .6s .30s ease both }
        .fu-4 { animation: fadeUp .6s .38s ease both }
    </style>
</head>
<body>

<div class="aurora"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>
<div class="orb orb-4"></div>
<div class="accent-line"></div>

<div class="page">

    {{-- Logo --}}
    <div class="logo-wrap">
        <img src="{{ asset('logo.png') }}" alt="ASOIINFO">
        <p class="logo-sub">Sistema Multiempresa &nbsp;·&nbsp; CRM &nbsp;·&nbsp; Facturación &nbsp;·&nbsp; WhatsApp</p>
    </div>

    {{-- Card --}}
    <div class="card">
        <div class="card-body">

            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h1 class="card-title">Iniciar sesión</h1>
            <p class="card-sub">Ingresa tus credenciales para continuar</p>

            @if($errors->any())
            <div class="alert-err" style="margin-top:20px">
                <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            @if(session('status'))
            <div class="alert-ok" style="margin-top:20px">
                <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" style="margin-top:24px">
                @csrf

                <div class="field fu-1">
                    <label class="lbl">Correo electrónico</label>
                    <input class="inp" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="email" placeholder="usuario@empresa.com">
                </div>

                <div class="field fu-2">
                    <label class="lbl">Contraseña</label>
                    <input class="inp" type="password" name="password"
                           required autocomplete="current-password" placeholder="••••••••••">
                </div>

                <div class="fu-3">
                    <button type="submit" class="btn">Ingresar al sistema &nbsp;→</button>
                </div>
            </form>

        </div>

        <div class="card-foot">
            <p class="foot-txt">
                ¿No tienes cuenta?&nbsp;
                <a href="{{ route('register') }}" class="foot-link">Crear cuenta →</a>
            </p>
        </div>
    </div>

    {{-- Pills --}}
    <div class="pills">
        <span class="pill"><span class="pill-dot" style="background:#25d366"></span>Omnicanal WhatsApp</span>
        <span class="pill"><span class="pill-dot" style="background:#6366f1"></span>Facturación Automática</span>
        <span class="pill"><span class="pill-dot" style="background:#f59e0b"></span>Reportes en Tiempo Real</span>
        <span class="pill"><span class="pill-dot" style="background:#10b981"></span>CRM Multiempresa</span>
    </div>

    {{-- Demo credentials --}}
    <div style="text-align:center;margin-top:16px;animation:fadeUp .6s .4s ease both">
        <p style="font-size:.74rem;color:#1e293b;margin-bottom:3px">
            <span style="color:#334155;font-weight:600">Admin:</span>
            <span style="color:#374151"> admin@asoiinfo.com &nbsp;/&nbsp; Admin2026!</span>
        </p>
        <p style="font-size:.74rem;color:#1e293b">
            <span style="color:#334155;font-weight:600">Asesor:</span>
            <span style="color:#374151"> asesor@asoiinfo.com &nbsp;/&nbsp; Agent2026!</span>
        </p>
    </div>

</div>
</body>
</html>
