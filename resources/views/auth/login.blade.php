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
        html, body { height: 100%; font-family: 'Sora', system-ui, sans-serif; background: #03050d; color: #e2e8f0; overflow-x: hidden }

        /* ═══════════════════════════════════════════
           BACKGROUND SYSTEM
        ═══════════════════════════════════════════ */

        /* Base deep space */
        .bg-base {
            position: fixed; inset: 0; z-index: 0;
            background: radial-gradient(ellipse 140% 100% at 50% -10%, #0d0a2e 0%, #03050d 55%);
        }

        /* Animated aurora mesh */
        .aurora-mesh {
            position: fixed; inset: 0; z-index: 1;
            background:
                radial-gradient(ellipse 80% 60% at 15% 15%,  rgba(99,102,241,.28) 0%, transparent 60%),
                radial-gradient(ellipse 70% 55% at 85% 80%,  rgba(124,58,237,.24) 0%, transparent 60%),
                radial-gradient(ellipse 55% 45% at 80% 10%,  rgba(20,184,166,.14) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 20% 85%,  rgba(168,85,247,.16) 0%, transparent 55%),
                radial-gradient(ellipse 40% 35% at 50% 45%,  rgba(59,130,246,.08) 0%, transparent 50%);
            animation: meshPulse 10s ease-in-out infinite alternate;
        }
        @keyframes meshPulse {
            0%   { opacity: .8;  transform: scale(1)    rotate(0deg) }
            33%  { opacity: 1;   transform: scale(1.03) rotate(.3deg) }
            66%  { opacity: .85; transform: scale(.98)  rotate(-.2deg) }
            100% { opacity: 1;   transform: scale(1.02) rotate(.15deg) }
        }

        /* Moving spotlight sweep */
        .spotlight {
            position: fixed; inset: 0; z-index: 2; pointer-events: none;
            background: conic-gradient(from 0deg at 30% 30%, transparent 0deg, rgba(99,102,241,.06) 30deg, transparent 60deg);
            animation: spotSweep 16s linear infinite;
        }
        @keyframes spotSweep {
            from { transform: rotate(0deg) scale(1.4) }
            to   { transform: rotate(360deg) scale(1.4) }
        }

        /* Perspective grid floor */
        .grid-persp {
            position: fixed; bottom: 0; left: 0; right: 0; height: 55%; z-index: 1;
            background-image:
                linear-gradient(rgba(99,102,241,.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,.07) 1px, transparent 1px);
            background-size: 60px 60px;
            transform: perspective(600px) rotateX(60deg);
            transform-origin: bottom center;
            mask-image: linear-gradient(to top, rgba(0,0,0,.5) 0%, transparent 80%);
            -webkit-mask-image: linear-gradient(to top, rgba(0,0,0,.5) 0%, transparent 80%);
        }

        /* Star field */
        .stars {
            position: fixed; inset: 0; z-index: 1; pointer-events: none;
            background-image:
                radial-gradient(1px 1px at 15% 8%,  rgba(255,255,255,.7) 0%, transparent 100%),
                radial-gradient(1px 1px at 72% 12%, rgba(255,255,255,.5) 0%, transparent 100%),
                radial-gradient(1px 1px at 40% 5%,  rgba(255,255,255,.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 88% 22%, rgba(255,255,255,.4) 0%, transparent 100%),
                radial-gradient(1px 1px at 5%  35%, rgba(255,255,255,.5) 0%, transparent 100%),
                radial-gradient(1px 1px at 60% 3%,  rgba(255,255,255,.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 95% 45%, rgba(255,255,255,.3) 0%, transparent 100%),
                radial-gradient(1px 1px at 25% 60%, rgba(255,255,255,.4) 0%, transparent 100%),
                radial-gradient(1px 1px at 50% 18%, rgba(168,85,247,.8) 0%, transparent 100%),
                radial-gradient(1px 1px at 78% 55%, rgba(99,102,241,.8) 0%, transparent 100%),
                radial-gradient(2px 2px at 33% 25%, rgba(255,255,255,.3) 0%, transparent 100%),
                radial-gradient(2px 2px at 67% 70%, rgba(255,255,255,.2) 0%, transparent 100%);
            animation: twinkle 6s ease-in-out infinite alternate;
        }
        @keyframes twinkle {
            0%,100% { opacity: 1 }
            50%      { opacity: .6 }
        }

        /* Floating orbs */
        .orb { position: fixed; border-radius: 50%; filter: blur(100px); pointer-events: none; animation: orbDrift ease-in-out infinite alternate }
        .orb-a { width:600px; height:600px; top:-200px; left:-150px;  background:rgba(99,102,241,.16); animation-duration:14s }
        .orb-b { width:500px; height:500px; bottom:-150px; right:-120px; background:rgba(124,58,237,.15); animation-duration:18s }
        .orb-c { width:300px; height:300px; top:35%; right:5%;         background:rgba(20,184,166,.09); animation-duration:22s }
        .orb-d { width:200px; height:200px; top:8%;  left:55%;          background:rgba(168,85,247,.12); animation-duration:10s }
        @keyframes orbDrift {
            from { transform: translate(0, 0) scale(1) }
            to   { transform: translate(30px, 24px) scale(1.06) }
        }

        /* Noise overlay for premium texture */
        .noise {
            position: fixed; inset: 0; z-index: 3; pointer-events: none; opacity: .035;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-repeat: repeat;
            background-size: 200px 200px;
        }

        /* Top accent glow line */
        .accent-line {
            position: fixed; top: 0; left: 0; right: 0; height: 1px; z-index: 50;
            background: linear-gradient(90deg, transparent 0%, #6366f1 20%, #a855f7 50%, #14b8a6 80%, transparent 100%);
            box-shadow: 0 0 20px rgba(99,102,241,.6), 0 0 40px rgba(168,85,247,.3);
        }

        /* ═══════════════════════════════════════════
           CONTENT
        ═══════════════════════════════════════════ */
        .page {
            position: relative; z-index: 10;
            min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 40px 16px;
        }

        /* Logo */
        .logo-wrap { text-align: center; margin-bottom: 28px; animation: fadeUp .7s ease both }
        .logo-wrap img {
            height: 130px; width: auto; display: block; margin: 0 auto;
            mix-blend-mode: screen;
            filter:
                drop-shadow(0 0 40px rgba(99,102,241,1))
                drop-shadow(0 0 16px rgba(168,85,247,.8))
                drop-shadow(0 0 80px rgba(99,102,241,.35));
            transition: filter .3s;
        }
        .logo-wrap img:hover {
            filter:
                drop-shadow(0 0 50px rgba(99,102,241,1))
                drop-shadow(0 0 20px rgba(168,85,247,1))
                drop-shadow(0 0 100px rgba(99,102,241,.5));
        }
        .logo-sub {
            margin-top: 12px; font-size: .72rem; font-weight: 500;
            letter-spacing: .2em; text-transform: uppercase; color: #374151;
        }

        /* Card */
        .card {
            width: 100%; max-width: 452px;
            background: rgba(5,9,20,.72);
            border: 1px solid rgba(99,102,241,.2);
            border-radius: 28px;
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            box-shadow:
                0 0 0 1px rgba(255,255,255,.04) inset,
                0 1px 0 rgba(255,255,255,.08) inset,
                0 40px 80px rgba(0,0,0,.7),
                0 0 60px rgba(99,102,241,.06);
            animation: fadeUp .7s .1s ease both;
            overflow: hidden;
        }
        .card-body { padding: 38px 42px 30px }
        .card-foot { padding: 18px 42px 22px; border-top: 1px solid rgba(99,102,241,.1); text-align: center }

        /* Card icon */
        .c-icon {
            width: 44px; height: 44px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg,#6366f1,#7c3aed);
            box-shadow: 0 6px 24px rgba(99,102,241,.45); margin-bottom: 18px;
        }
        .c-icon svg { width: 22px; height: 22px; color: #fff }
        .c-title { font-size: 1.55rem; font-weight: 700; color: #f1f5f9; letter-spacing: -.03em }
        .c-sub   { font-size: .88rem; color: #64748b; margin-top: 5px }

        /* Fields */
        .lbl {
            display: block; font-size: .76rem; font-weight: 600; color: #475569;
            text-transform: uppercase; letter-spacing: .1em; margin-bottom: 9px;
        }
        .inp {
            width: 100%; background: rgba(2,4,12,.9);
            border: 1.5px solid rgba(30,42,66,.85);
            border-radius: 14px; padding: 15px 18px;
            font-size: 1rem; color: #e2e8f0;
            font-family: 'Sora', system-ui, sans-serif;
            outline: none; transition: all .2s;
        }
        .inp:focus {
            border-color: #6366f1; background: rgba(2,4,12,1);
            box-shadow: 0 0 0 4px rgba(99,102,241,.12), 0 0 20px rgba(99,102,241,.1);
        }
        .inp::placeholder { color: #1e2a3a }

        /* Alerts */
        .alert {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 14px 16px; border-radius: 14px; margin: 18px 0;
            font-size: .9rem; line-height: 1.45;
        }
        .alert-err { background: rgba(127,29,29,.2); border: 1px solid rgba(239,68,68,.3); color: #fca5a5 }
        .alert-ok  { background: rgba(6,78,59,.2);   border: 1px solid rgba(16,185,129,.3); color: #6ee7b7 }
        .alert svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px }

        /* Button */
        .btn-primary {
            width: 100%; padding: 16px 24px; border-radius: 14px;
            font-size: 1.02rem; font-weight: 700; color: #fff; border: none; cursor: pointer;
            font-family: 'Sora', system-ui, sans-serif; letter-spacing: .02em;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 45%, #7c3aed 100%);
            box-shadow: 0 6px 30px rgba(99,102,241,.5), 0 1px 0 rgba(255,255,255,.15) inset;
            transition: all .22s; margin-top: 6px; position: relative; overflow: hidden;
        }
        .btn-primary::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.1), transparent);
            opacity: 0; transition: opacity .2s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 40px rgba(99,102,241,.65) }
        .btn-primary:hover::after { opacity: 1 }
        .btn-primary:active { transform: translateY(0) }

        /* Footer link */
        .f-txt  { font-size: .9rem; color: #475569 }
        .f-link { color: #818cf8; font-weight: 600; text-decoration: none; transition: color .15s }
        .f-link:hover { color: #c4b5fd }

        /* Feature pills */
        .pills { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-top: 24px; animation: fadeUp .7s .35s ease both }
        .pill {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 16px; border-radius: 50px;
            background: rgba(5,9,20,.8); border: 1px solid rgba(30,42,66,.85);
            font-size: .78rem; font-weight: 500; color: #475569;
            backdrop-filter: blur(12px); transition: all .2s;
        }
        .pill:hover { border-color: rgba(99,102,241,.35); color: #94a3b8; transform: translateY(-1px) }
        .pill-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0 }

        /* Animations */
        @keyframes fadeUp { from { opacity:0; transform:translateY(24px) } to { opacity:1; transform:translateY(0) } }
        .fu1 { animation: fadeUp .6s .18s ease both }
        .fu2 { animation: fadeUp .6s .26s ease both }
        .fu3 { animation: fadeUp .6s .34s ease both }
    </style>
</head>
<body>

<div class="bg-base"></div>
<div class="aurora-mesh"></div>
<div class="spotlight"></div>
<div class="grid-persp"></div>
<div class="stars"></div>
<div class="orb orb-a"></div>
<div class="orb orb-b"></div>
<div class="orb orb-c"></div>
<div class="orb orb-d"></div>
<div class="noise"></div>
<div class="accent-line"></div>

<div class="page">

    <div class="logo-wrap">
        <img src="{{ asset('logo.png') }}" alt="ASOIINFO">
        <p class="logo-sub">Sistema Multiempresa &nbsp;·&nbsp; CRM &nbsp;·&nbsp; Facturación &nbsp;·&nbsp; WhatsApp</p>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="c-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h1 class="c-title">Iniciar sesión</h1>
            <p class="c-sub">Ingresa tus credenciales para continuar</p>

            @if($errors->any())
            <div class="alert alert-err">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $errors->first() }}
            </div>
            @endif

            @if(session('status'))
            <div class="alert alert-ok">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" style="margin-top:26px">
                @csrf
                <div style="margin-bottom:20px" class="fu1">
                    <label class="lbl">Correo electrónico</label>
                    <input class="inp" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="email" placeholder="usuario@empresa.com">
                </div>
                <div style="margin-bottom:6px" class="fu2">
                    <label class="lbl">Contraseña</label>
                    <input class="inp" type="password" name="password"
                           required autocomplete="current-password" placeholder="••••••••••">
                </div>
                <div class="fu3">
                    <button type="submit" class="btn-primary">Ingresar al sistema &nbsp;→</button>
                </div>
            </form>
        </div>

        <div class="card-foot">
            <p class="f-txt">¿No tienes cuenta?&nbsp;<a href="{{ route('register') }}" class="f-link">Crear cuenta →</a></p>
        </div>
    </div>

    <div class="pills">
        <span class="pill"><span class="pill-dot" style="background:#25d366"></span>Omnicanal WhatsApp</span>
        <span class="pill"><span class="pill-dot" style="background:#6366f1"></span>Facturación Automática</span>
        <span class="pill"><span class="pill-dot" style="background:#f59e0b"></span>Reportes en Tiempo Real</span>
        <span class="pill"><span class="pill-dot" style="background:#10b981"></span>CRM Multiempresa</span>
    </div>

    <div style="text-align:center;margin-top:14px;animation:fadeUp .7s .45s ease both">
        <p style="font-size:.73rem;color:#1e293b;margin-bottom:3px">
            <span style="color:#334155;font-weight:600">Admin:</span>
            <span style="color:#374151"> admin@asoiinfo.com &nbsp;/&nbsp; Admin2026!</span>
        </p>
        <p style="font-size:.73rem;color:#1e293b">
            <span style="color:#334155;font-weight:600">Asesor:</span>
            <span style="color:#374151"> asesor@asoiinfo.com &nbsp;/&nbsp; Agent2026!</span>
        </p>
    </div>

</div>
</body>
</html>
