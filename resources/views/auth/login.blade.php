<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — ASOIINFO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html,body{height:100%;font-family:'Sora',system-ui,sans-serif;background:#020509;color:#e2e8f0;overflow-x:hidden}

        /* ════ 9-LAYER CINEMATIC BG ════ */
        .bg-cosmos{position:fixed;inset:0;z-index:0;background:radial-gradient(ellipse 180% 90% at 50% -5%,#0d0a2e 0%,#020509 62%)}
        .aurora{position:fixed;inset:0;z-index:1;
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%,rgba(99,102,241,.38) 0%,transparent 58%),
                radial-gradient(ellipse 70% 55% at 90% 78%,rgba(124,58,237,.32) 0%,transparent 58%),
                radial-gradient(ellipse 55% 45% at 82% 10%,rgba(20,184,166,.20) 0%,transparent 52%),
                radial-gradient(ellipse 55% 48% at 18% 88%,rgba(168,85,247,.25) 0%,transparent 55%),
                radial-gradient(ellipse 38% 32% at 52% 46%,rgba(59,130,246,.12) 0%,transparent 50%);
            animation:auroraPulse 11s ease-in-out infinite alternate}
        @keyframes auroraPulse{0%{opacity:.78;transform:scale(1) rotate(0deg)}33%{opacity:1;transform:scale(1.04) rotate(.4deg)}66%{opacity:.83;transform:scale(.97) rotate(-.3deg)}100%{opacity:1;transform:scale(1.03) rotate(.2deg)}}

        .conic-light{position:fixed;inset:0;z-index:2;pointer-events:none;
            background:conic-gradient(from 0deg at 25% 35%,transparent 0deg,rgba(99,102,241,.08) 25deg,rgba(168,85,247,.05) 50deg,transparent 78deg);
            animation:conicSpin 24s linear infinite}
        @keyframes conicSpin{to{transform:rotate(360deg) scale(1.55)}}

        .grid-floor{position:fixed;bottom:0;left:0;right:0;height:58%;z-index:1;
            background-image:linear-gradient(rgba(99,102,241,.11) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,.11) 1px,transparent 1px);
            background-size:54px 54px;
            transform:perspective(520px) rotateX(64deg);transform-origin:bottom center;
            mask-image:linear-gradient(to top,rgba(0,0,0,.55) 0%,transparent 72%);
            -webkit-mask-image:linear-gradient(to top,rgba(0,0,0,.55) 0%,transparent 72%)}

        .stars{position:fixed;inset:0;z-index:1;pointer-events:none;
            background-image:
                radial-gradient(1.5px 1.5px at 8% 7%,rgba(255,255,255,.9) 0%,transparent 100%),
                radial-gradient(1px 1px at 73% 12%,rgba(255,255,255,.65) 0%,transparent 100%),
                radial-gradient(1.5px 1.5px at 40% 5%,rgba(255,255,255,.8) 0%,transparent 100%),
                radial-gradient(1px 1px at 91% 26%,rgba(255,255,255,.5) 0%,transparent 100%),
                radial-gradient(1px 1px at 4% 40%,rgba(255,255,255,.6) 0%,transparent 100%),
                radial-gradient(1.5px 1.5px at 60% 3%,rgba(255,255,255,.55) 0%,transparent 100%),
                radial-gradient(1px 1px at 97% 50%,rgba(255,255,255,.4) 0%,transparent 100%),
                radial-gradient(2px 2px at 50% 17%,rgba(168,85,247,1) 0%,transparent 100%),
                radial-gradient(2px 2px at 81% 55%,rgba(99,102,241,1) 0%,transparent 100%),
                radial-gradient(2px 2px at 14% 24%,rgba(20,184,166,.85) 0%,transparent 100%);
            animation:twinkle 8s ease-in-out infinite alternate}
        @keyframes twinkle{0%,100%{opacity:1}50%{opacity:.45}}

        .orb{position:fixed;border-radius:50%;filter:blur(115px);pointer-events:none;animation:orbDrift ease-in-out infinite alternate}
        .orb-1{width:700px;height:700px;top:-240px;left:-180px;background:rgba(99,102,241,.18);animation-duration:16s}
        .orb-2{width:560px;height:560px;bottom:-170px;right:-150px;background:rgba(124,58,237,.16);animation-duration:20s}
        .orb-3{width:340px;height:340px;top:32%;right:3%;background:rgba(20,184,166,.11);animation-duration:25s}
        .orb-4{width:240px;height:240px;top:5%;left:52%;background:rgba(168,85,247,.14);animation-duration:12s}
        @keyframes orbDrift{from{transform:translate(0,0) scale(1)}to{transform:translate(35px,28px) scale(1.08)}}

        .noise{position:fixed;inset:0;z-index:3;pointer-events:none;opacity:.03;
            background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size:180px 180px}
        .accent-line{position:fixed;top:0;left:0;right:0;height:1.5px;z-index:50;
            background:linear-gradient(90deg,transparent 0%,#6366f1 18%,#a855f7 50%,#14b8a6 82%,transparent 100%);
            box-shadow:0 0 28px rgba(99,102,241,.75),0 0 56px rgba(168,85,247,.4)}

        /* ════ PAGE ════ */
        .page{position:relative;z-index:10;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 16px}

        /* Logo */
        .logo-wrap{text-align:center;margin-bottom:28px;animation:fadeUp .7s ease both}
        .logo-wrap img{
            height:130px;width:auto;display:block;margin:0 auto;mix-blend-mode:screen;
            filter:drop-shadow(0 0 44px rgba(99,102,241,1)) drop-shadow(0 0 18px rgba(168,85,247,.85)) drop-shadow(0 0 90px rgba(99,102,241,.38));
            transition:filter .3s}
        .logo-wrap img:hover{filter:drop-shadow(0 0 56px rgba(99,102,241,1)) drop-shadow(0 0 24px rgba(168,85,247,1)) drop-shadow(0 0 120px rgba(99,102,241,.55))}
        .logo-sub{margin-top:10px;font-size:.7rem;font-weight:500;letter-spacing:.22em;text-transform:uppercase;color:#2d3748}

        /* Card */
        .card{
            width:100%;max-width:460px;
            background:rgba(5,9,20,.76);
            border:1px solid rgba(99,102,241,.22);border-radius:28px;
            backdrop-filter:blur(42px);-webkit-backdrop-filter:blur(42px);
            box-shadow:0 0 0 1px rgba(255,255,255,.04) inset,0 1px 0 rgba(255,255,255,.08) inset,0 42px 88px rgba(0,0,0,.72),0 0 64px rgba(99,102,241,.07);
            animation:fadeUp .7s .1s ease both;overflow:hidden}
        .card-body{padding:36px 40px 26px}
        .card-foot{padding:18px 40px 22px;border-top:1px solid rgba(99,102,241,.1);text-align:center}

        /* Card icon */
        .c-icon{width:48px;height:48px;border-radius:16px;display:flex;align-items:center;justify-content:center;
            background:linear-gradient(135deg,#6366f1,#7c3aed);box-shadow:0 6px 26px rgba(99,102,241,.48);margin-bottom:16px}
        .c-icon svg{width:24px;height:24px;color:#fff}
        .c-title{font-size:1.65rem;font-weight:800;color:#f1f5f9;letter-spacing:-.04em;line-height:1.2}
        .c-sub{font-size:.875rem;color:#64748b;margin-top:5px;text-align:center;line-height:1.5}

        /* Fields */
        .field{margin-bottom:18px}
        .lbl{display:block;font-size:.76rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.1em;margin-bottom:9px}
        .inp{
            width:100%;background:rgba(2,4,14,.92);border:1.5px solid rgba(30,42,66,.9);
            border-radius:14px;padding:15px 19px;font-size:1rem;color:#e2e8f0;
            font-family:'Sora',system-ui,sans-serif;outline:none;transition:all .2s}
        .inp:focus{border-color:#6366f1;background:rgba(2,4,14,1);box-shadow:0 0 0 4px rgba(99,102,241,.13),0 0 22px rgba(99,102,241,.1)}
        .inp::placeholder{color:#1a2535}

        /* Alerts */
        .alert{display:flex;align-items:flex-start;gap:10px;padding:14px 16px;border-radius:14px;margin:16px 0;font-size:.9rem;line-height:1.45}
        .alert-err{background:rgba(127,29,29,.22);border:1px solid rgba(239,68,68,.3);color:#fca5a5}
        .alert-ok{background:rgba(6,78,59,.22);border:1px solid rgba(16,185,129,.3);color:#6ee7b7}
        .alert svg{width:18px;height:18px;flex-shrink:0;margin-top:1px}

        /* Checkbox row */
        .check-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
        .check-label{display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;color:#6b7280;user-select:none}
        .check-label input[type=checkbox]{
            width:16px;height:16px;border-radius:5px;cursor:pointer;accent-color:#6366f1;
            background:rgba(2,4,14,.9);border:1.5px solid rgba(30,42,66,.9)}
        .forgot-link{font-size:.82rem;color:#6366f1;text-decoration:none;font-weight:500;transition:color .15s}
        .forgot-link:hover{color:#a5b4fc}

        /* Button */
        .btn-login{
            width:100%;padding:16px 24px;border-radius:14px;
            font-size:1.04rem;font-weight:700;color:#fff;border:none;cursor:pointer;
            font-family:'Sora',system-ui,sans-serif;letter-spacing:.02em;
            background:linear-gradient(135deg,#6366f1 0%,#4f46e5 44%,#7c3aed 100%);
            box-shadow:0 6px 32px rgba(99,102,241,.52),0 1px 0 rgba(255,255,255,.15) inset;
            transition:all .22s;position:relative;overflow:hidden}
        .btn-login::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.1),transparent);opacity:0;transition:opacity .2s}
        .btn-login:hover{transform:translateY(-2px);box-shadow:0 14px 44px rgba(99,102,241,.68)}
        .btn-login:hover::after{opacity:1}
        .btn-login:active{transform:translateY(0)}

        /* Footer link */
        .f-txt{font-size:.875rem;color:#475569}
        .f-link{color:#818cf8;font-weight:600;text-decoration:none;transition:color .15s}
        .f-link:hover{color:#c4b5fd}

        /* Feature pills */
        .pills{display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin-top:22px;animation:fadeUp .7s .36s ease both}
        .pill{
            display:inline-flex;align-items:center;gap:8px;
            padding:9px 18px;border-radius:50px;
            background:rgba(5,9,20,.88);border:1px solid rgba(30,42,66,.9);
            font-size:.8rem;font-weight:500;color:#475569;
            backdrop-filter:blur(12px);transition:all .22s}
        .pill:hover{border-color:rgba(99,102,241,.4);color:#94a3b8;transform:translateY(-2px)}
        .pill-icon{width:22px;height:22px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}

        /* Copyright */
        .copyright{margin-top:18px;text-align:center;animation:fadeUp .7s .48s ease both}

        @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        .fu1{animation:fadeUp .6s .18s ease both}
        .fu2{animation:fadeUp .6s .26s ease both}
        .fu3{animation:fadeUp .6s .32s ease both}
        .fu4{animation:fadeUp .6s .38s ease both}
    </style>
</head>
<body>
<div class="bg-cosmos"></div>
<div class="aurora"></div>
<div class="conic-light"></div>
<div class="grid-floor"></div>
<div class="stars"></div>
<div class="orb orb-1"></div><div class="orb orb-2"></div>
<div class="orb orb-3"></div><div class="orb orb-4"></div>
<div class="noise"></div>
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
            <div style="text-align:center">
                <div class="c-icon" style="margin:0 auto 16px">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="c-title">Iniciar sesión</h1>
                <p class="c-sub">Accede a tu cuenta para continuar<br>gestionando tu empresa.</p>
            </div>

            @if($errors->any())
            <div class="alert alert-err">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $errors->first() }}
            </div>
            @endif
            @if(session('status'))
            <div class="alert alert-ok">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" style="margin-top:24px">
                @csrf
                <div class="field fu1">
                    <label class="lbl">Correo electrónico</label>
                    <input class="inp" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="email" placeholder="Correo electrónico">
                </div>
                <div class="field fu2">
                    <label class="lbl">Contraseña</label>
                    <input class="inp" type="password" name="password"
                           required autocomplete="current-password" placeholder="Contraseña">
                </div>
                <div class="check-row fu3">
                    <label class="check-label">
                        <input type="checkbox" name="remember">
                        Recordarme
                    </label>
                    <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="fu4">
                    <button type="submit" class="btn-login">Ingresar al sistema →</button>
                </div>
            </form>
        </div>
        <div class="card-foot">
            <p class="f-txt">¿No tienes cuenta?&nbsp;<a href="{{ route('register') }}" class="f-link">Crear cuenta →</a></p>
        </div>
    </div>

    {{-- Feature pills with icons --}}
    <div class="pills">
        <span class="pill">
            <span class="pill-icon" style="background:#25d36622">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </span>
            WhatsApp
        </span>
        <span class="pill">
            <span class="pill-icon" style="background:#6366f122">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#818cf8" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </span>
            Facturación
        </span>
        <span class="pill">
            <span class="pill-icon" style="background:#10b98122">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#34d399" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </span>
            Reportes
        </span>
        <span class="pill">
            <span class="pill-icon" style="background:#a855f722">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#d8b4fe" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </span>
            CRM
        </span>
    </div>

    {{-- Copyright --}}
    <div class="copyright">
        <p style="font-size:.72rem;color:#1e293b">
            © {{ date('Y') }} ASOIINFO. Todos los derechos reservados. &nbsp;·&nbsp;
            <span style="color:#1e293b">🔒 Seguridad y confianza</span>
        </p>
        <p style="font-size:.68rem;color:#111827;margin-top:4px">
            <span style="color:#1f2937">Admin:</span> admin@asoiinfo.com / Admin2026! &nbsp;·&nbsp;
            <span style="color:#1f2937">Asesor:</span> asesor@asoiinfo.com / Agent2026!
        </p>
    </div>

</div>
</body>
</html>
