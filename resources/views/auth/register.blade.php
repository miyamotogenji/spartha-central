<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta — ASOIINFO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@200;300;400;500;600;700;800&family=Fira+Code:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0 }

        :root {
            --font: 'Sora', system-ui, sans-serif;
            --indigo: #6366f1;
            --violet: #7c3aed;
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

        /* ── Aurora ── */
        .aurora {
            position: fixed; inset: 0; z-index: 0; overflow: hidden;
        }
        .aurora::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 120% 80% at 100% 0%,   rgba(124,58,237,.2) 0%, transparent 55%),
                radial-gradient(ellipse 90%  70% at 0% 100%,   rgba(99,102,241,.18) 0%, transparent 55%),
                radial-gradient(ellipse 70%  60% at 50% 50%,   rgba(168,85,247,.07) 0%, transparent 60%),
                radial-gradient(ellipse 60%  50% at 0% 0%,     rgba(20,184,166,.08) 0%, transparent 50%);
            animation: auroraShift 14s ease-in-out infinite alternate;
        }
        @keyframes auroraShift {
            0%   { opacity: 1;   transform: scale(1)    rotate(0deg) }
            50%  { opacity: .85; transform: scale(1.04) rotate(-.5deg) }
            100% { opacity: 1;   transform: scale(1.02) rotate(.5deg) }
        }
        .aurora::after {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(99,102,241,.15) 1px, transparent 1px);
            background-size: 36px 36px;
            opacity: .5;
        }

        .orb { position: fixed; border-radius: 50%; filter: blur(90px); pointer-events: none; animation: orbFloat linear infinite }
        .orb-1 { width:500px; height:500px; top:-150px; right:-120px; background:rgba(124,58,237,.13); animation-duration:20s }
        .orb-2 { width:400px; height:400px; bottom:-100px; left:-80px;  background:rgba(99,102,241,.12); animation-duration:24s; animation-direction:reverse }
        .orb-3 { width:250px; height:250px; top:30%; left:10%;           background:rgba(20,184,166,.06); animation-duration:28s }
        @keyframes orbFloat {
            0%,100% { transform: translate(0,0) scale(1) }
            25%      { transform: translate(-18px,20px) scale(1.04) }
            50%      { transform: translate(14px,-16px) scale(.97) }
            75%      { transform: translate(-22px,-10px) scale(1.02) }
        }

        .accent-line {
            position: fixed; top: 0; left: 0; right: 0; height: 2px; z-index: 30;
            background: linear-gradient(90deg, transparent, #7c3aed 25%, #6366f1 60%, #14b8a6 85%, transparent);
        }

        /* ── Layout ── */
        .page {
            position: relative; z-index: 10;
            width: 100%; min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 40px 16px;
        }

        /* ── Logo ── */
        .logo-wrap {
            text-align: center; margin-bottom: 24px;
            animation: fadeUp .6s ease both;
        }
        .logo-wrap img {
            height: 120px; width: auto; display: block; margin: 0 auto;
            mix-blend-mode: screen;
            filter:
                drop-shadow(0 0 32px rgba(99,102,241,.9))
                drop-shadow(0 0 14px rgba(168,85,247,.6))
                drop-shadow(0 0 60px rgba(99,102,241,.3));
        }
        .logo-sub {
            margin-top: 10px; font-size: .72rem; font-weight: 500;
            letter-spacing: .18em; text-transform: uppercase; color: #475569;
        }

        /* ── Card ── */
        .card {
            width: 100%; max-width: 468px;
            background: rgba(7,12,24,.78);
            border: 1px solid rgba(99,102,241,.22);
            border-radius: 28px;
            backdrop-filter: blur(36px);
            -webkit-backdrop-filter: blur(36px);
            box-shadow: 0 40px 80px rgba(0,0,0,.65), 0 0 0 1px rgba(255,255,255,.04) inset, 0 1px 0 rgba(255,255,255,.07) inset;
            animation: fadeUp .6s .08s ease both;
            overflow: hidden;
        }
        .card-body { padding: 36px 40px 28px }
        .card-foot { padding: 20px 40px; border-top: 1px solid rgba(99,102,241,.12); text-align: center }

        /* ── Card header ── */
        .card-icon {
            width: 42px; height: 42px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #7c3aed, #6366f1);
            box-shadow: 0 6px 20px rgba(124,58,237,.4);
            margin-bottom: 16px;
        }
        .card-icon svg { width: 20px; height: 20px; color: #fff }
        .card-title { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; letter-spacing: -.03em }
        .card-sub   { font-size: .9rem; color: #64748b; margin-top: 4px }

        /* ── Inputs ── */
        .field { margin-bottom: 18px }
        .lbl {
            display: block; font-size: .78rem; font-weight: 600;
            color: #64748b; text-transform: uppercase;
            letter-spacing: .08em; margin-bottom: 8px;
        }
        .inp {
            width: 100%; background: rgba(2,5,12,.85);
            border: 1.5px solid rgba(30,42,66,.9);
            border-radius: 14px; padding: 14px 18px;
            font-size: 1rem; color: #e2e8f0;
            font-family: var(--font); outline: none; transition: all .2s;
        }
        .inp:focus {
            border-color: var(--indigo); background: rgba(2,5,12,1);
            box-shadow: 0 0 0 4px rgba(99,102,241,.12), 0 0 16px rgba(99,102,241,.1);
        }
        .inp::placeholder { color: #1e293b }
        .inp.err { border-color: rgba(239,68,68,.5) }

        /* Strength bar */
        .strength-bar { height: 3px; border-radius: 2px; transition: all .3s; margin-top: 7px; background: #0f1723 }
        .match-txt, .err-txt { font-size: .78rem; margin-top: 5px; display: flex; align-items: center; gap: 4px; min-height: 18px }
        .err-txt { color: #f87171 }

        /* ── Alerts ── */
        .alert-err {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 14px 16px; border-radius: 14px; margin-bottom: 20px;
            background: rgba(127,29,29,.2); border: 1px solid rgba(239,68,68,.3);
            color: #fca5a5; font-size: .9rem;
        }
        .alert-icon { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px }

        /* ── Button ── */
        .btn {
            width: 100%; padding: 15px 24px; border-radius: 14px;
            font-size: 1.02rem; font-weight: 700; color: #fff;
            border: none; cursor: pointer; font-family: var(--font);
            letter-spacing: .02em;
            background: linear-gradient(135deg, #7c3aed 0%, #6366f1 50%, #4f46e5 100%);
            box-shadow: 0 6px 28px rgba(124,58,237,.45), 0 1px 0 rgba(255,255,255,.15) inset;
            transition: all .22s; margin-top: 8px;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 12px 36px rgba(124,58,237,.6) }
        .btn:active { transform: translateY(0) }

        /* ── Footer ── */
        .foot-txt  { font-size: .9rem; color: #475569 }
        .foot-link { color: #a78bfa; font-weight: 600; text-decoration: none; transition: color .15s }
        .foot-link:hover { color: #c4b5fd }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px) }
            to   { opacity: 1; transform: translateY(0) }
        }
        .fu-1 { animation: fadeUp .5s .14s ease both }
        .fu-2 { animation: fadeUp .5s .20s ease both }
        .fu-3 { animation: fadeUp .5s .26s ease both }
        .fu-4 { animation: fadeUp .5s .32s ease both }
        .fu-5 { animation: fadeUp .5s .38s ease both }
    </style>
</head>
<body>

<div class="aurora"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>
<div class="accent-line"></div>

<div class="page">

    {{-- Logo --}}
    <div class="logo-wrap">
        <img src="{{ asset('logo.png') }}" alt="ASOIINFO">
        <p class="logo-sub">Sistema Multiempresa &nbsp;·&nbsp; CRM &nbsp;·&nbsp; Facturación</p>
    </div>

    {{-- Card --}}
    <div class="card">
        <div class="card-body">

            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="card-title">Crear cuenta</h1>
            <p class="card-sub">Regístrate para acceder a la plataforma</p>

            @if($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('password'))
            <div class="alert-err" style="margin-top:20px">
                <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" id="regForm" style="margin-top:24px">
                @csrf

                {{-- Name --}}
                <div class="field fu-1">
                    <label class="lbl">Nombre completo</label>
                    <input class="inp {{ $errors->has('name') ? 'err':'' }}" type="text" name="name"
                           value="{{ old('name') }}" required autocomplete="name" placeholder="Juan García">
                    @error('name')
                        <p class="err-txt">
                            <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="field fu-2">
                    <label class="lbl">Correo electrónico</label>
                    <input class="inp {{ $errors->has('email') ? 'err':'' }}" type="email" name="email"
                           value="{{ old('email') }}" required autocomplete="email" placeholder="usuario@empresa.com">
                    @error('email')
                        <p class="err-txt">
                            <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field fu-3">
                    <label class="lbl">Contraseña</label>
                    <input class="inp {{ $errors->has('password') ? 'err':'' }}" type="password" name="password" id="pw"
                           required autocomplete="new-password" placeholder="Mínimo 8 caracteres"
                           oninput="checkStrength(this.value)">
                    <div id="sBar" class="strength-bar" style="width:0%"></div>
                    <p id="sTxt" class="match-txt" style="color:#475569"></p>
                    @error('password')
                        <p class="err-txt">
                            <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Confirm --}}
                <div class="field fu-4">
                    <label class="lbl">Confirmar contraseña</label>
                    <input class="inp" type="password" name="password_confirmation" id="pwc"
                           required autocomplete="new-password" placeholder="Repite tu contraseña"
                           oninput="checkMatch()">
                    <p id="mTxt" class="match-txt"></p>
                </div>

                <div class="fu-5">
                    <button type="submit" class="btn">Crear cuenta &nbsp;→</button>
                </div>
            </form>

        </div>

        <div class="card-foot">
            <p class="foot-txt">
                ¿Ya tienes cuenta?&nbsp;
                <a href="{{ route('login') }}" class="foot-link">Iniciar sesión →</a>
            </p>
        </div>
    </div>

    {{-- Pills --}}
    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:8px;margin-top:22px;animation:fadeUp .6s .36s ease both">
        <span style="display:inline-flex;align-items:center;gap:7px;padding:7px 15px;border-radius:50px;background:rgba(7,12,24,.85);border:1px solid rgba(30,42,66,.9);font-size:.78rem;font-weight:500;color:#64748b">
            <span style="width:6px;height:6px;border-radius:50%;background:#25d366;flex-shrink:0"></span>Omnicanal WhatsApp
        </span>
        <span style="display:inline-flex;align-items:center;gap:7px;padding:7px 15px;border-radius:50px;background:rgba(7,12,24,.85);border:1px solid rgba(30,42,66,.9);font-size:.78rem;font-weight:500;color:#64748b">
            <span style="width:6px;height:6px;border-radius:50%;background:#6366f1;flex-shrink:0"></span>Facturación Automática
        </span>
        <span style="display:inline-flex;align-items:center;gap:7px;padding:7px 15px;border-radius:50px;background:rgba(7,12,24,.85);border:1px solid rgba(30,42,66,.9);font-size:.78rem;font-weight:500;color:#64748b">
            <span style="width:6px;height:6px;border-radius:50%;background:#10b981;flex-shrink:0"></span>CRM Multiempresa
        </span>
    </div>

</div>

<script>
function checkStrength(v) {
    const bar = document.getElementById('sBar'), txt = document.getElementById('sTxt');
    if (!v) { bar.style.width='0%'; txt.textContent=''; return }
    let s = 0;
    if (v.length >= 8)  s++;
    if (v.length >= 12) s++;
    if (/[A-Z]/.test(v)) s++;
    if (/[0-9]/.test(v)) s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    const lvl = [{w:'18%',c:'#ef4444',t:'Muy débil'},{w:'36%',c:'#f97316',t:'Débil'},{w:'55%',c:'#eab308',t:'Regular'},{w:'75%',c:'#84cc16',t:'Fuerte'},{w:'100%',c:'#22c55e',t:'Muy fuerte'}];
    const l = lvl[Math.min(s-1,4)]||lvl[0];
    bar.style.width=l.w; bar.style.background=l.c; txt.textContent=l.t; txt.style.color=l.c;
    checkMatch();
}
function checkMatch() {
    const pw=document.getElementById('pw').value, pwc=document.getElementById('pwc').value, t=document.getElementById('mTxt');
    if (!pwc) { t.textContent=''; return }
    if (pw===pwc) { t.textContent='✓ Las contraseñas coinciden'; t.style.color='#22c55e' }
    else          { t.textContent='✗ No coinciden'; t.style.color='#ef4444' }
}
</script>
</body>
</html>
