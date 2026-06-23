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
        html, body { height: 100%; font-family: 'Sora', system-ui, sans-serif; background: #03050d; color: #e2e8f0; overflow-x: hidden }

        /* ── Background ── */
        .bg-base { position:fixed; inset:0; z-index:0; background:radial-gradient(ellipse 140% 100% at 50% -10%, #0d0a2e 0%, #03050d 55%) }

        .aurora-mesh {
            position:fixed; inset:0; z-index:1;
            background:
                radial-gradient(ellipse 80% 60% at 85% 15%,  rgba(124,58,237,.28) 0%, transparent 60%),
                radial-gradient(ellipse 70% 55% at 15% 80%,  rgba(99,102,241,.24) 0%, transparent 60%),
                radial-gradient(ellipse 55% 45% at 20% 10%,  rgba(20,184,166,.12) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 80% 85%,  rgba(168,85,247,.16) 0%, transparent 55%);
            animation: meshPulse 12s ease-in-out infinite alternate;
        }
        @keyframes meshPulse {
            0%  { opacity:.8;  transform:scale(1)    rotate(0deg) }
            50% { opacity:1;   transform:scale(1.04) rotate(-.3deg) }
            100%{ opacity:.85; transform:scale(1.02) rotate(.2deg) }
        }

        .spotlight {
            position:fixed; inset:0; z-index:2; pointer-events:none;
            background:conic-gradient(from 0deg at 70% 70%, transparent 0deg, rgba(124,58,237,.06) 30deg, transparent 60deg);
            animation:spotSweep 20s linear infinite;
        }
        @keyframes spotSweep { from{transform:rotate(0deg) scale(1.4)} to{transform:rotate(360deg) scale(1.4)} }

        .grid-persp {
            position:fixed; bottom:0; left:0; right:0; height:55%; z-index:1;
            background-image:linear-gradient(rgba(124,58,237,.07) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,.07) 1px,transparent 1px);
            background-size:60px 60px;
            transform:perspective(600px) rotateX(60deg);
            transform-origin:bottom center;
            mask-image:linear-gradient(to top,rgba(0,0,0,.5) 0%,transparent 80%);
            -webkit-mask-image:linear-gradient(to top,rgba(0,0,0,.5) 0%,transparent 80%);
        }

        .stars {
            position:fixed; inset:0; z-index:1; pointer-events:none;
            background-image:
                radial-gradient(1px 1px at 8%  12%, rgba(255,255,255,.7) 0%,transparent 100%),
                radial-gradient(1px 1px at 65% 8%,  rgba(255,255,255,.5) 0%,transparent 100%),
                radial-gradient(1px 1px at 35% 4%,  rgba(255,255,255,.6) 0%,transparent 100%),
                radial-gradient(1px 1px at 92% 20%, rgba(255,255,255,.4) 0%,transparent 100%),
                radial-gradient(1px 1px at 18% 40%, rgba(255,255,255,.5) 0%,transparent 100%),
                radial-gradient(1px 1px at 55% 2%,  rgba(255,255,255,.6) 0%,transparent 100%),
                radial-gradient(1px 1px at 82% 60%, rgba(255,255,255,.3) 0%,transparent 100%),
                radial-gradient(1px 1px at 45% 22%, rgba(168,85,247,.9) 0%,transparent 100%),
                radial-gradient(1px 1px at 73% 48%, rgba(99,102,241,.9) 0%,transparent 100%),
                radial-gradient(2px 2px at 28% 30%, rgba(255,255,255,.3) 0%,transparent 100%);
            animation:twinkle 7s ease-in-out infinite alternate;
        }
        @keyframes twinkle { 0%,100%{opacity:1} 50%{opacity:.55} }

        .orb { position:fixed; border-radius:50%; filter:blur(100px); pointer-events:none; animation:orbDrift ease-in-out infinite alternate }
        .orb-a { width:550px;height:550px;top:-180px;right:-140px;  background:rgba(124,58,237,.16); animation-duration:16s }
        .orb-b { width:480px;height:480px;bottom:-140px;left:-100px; background:rgba(99,102,241,.15); animation-duration:20s }
        .orb-c { width:280px;height:280px;top:30%;left:5%;            background:rgba(20,184,166,.08); animation-duration:24s }
        @keyframes orbDrift { from{transform:translate(0,0) scale(1)} to{transform:translate(28px,22px) scale(1.05)} }

        .noise { position:fixed;inset:0;z-index:3;pointer-events:none;opacity:.032;
            background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size:200px 200px }

        .accent-line { position:fixed;top:0;left:0;right:0;height:1px;z-index:50;
            background:linear-gradient(90deg,transparent 0%,#7c3aed 20%,#6366f1 50%,#14b8a6 80%,transparent 100%);
            box-shadow:0 0 20px rgba(124,58,237,.6),0 0 40px rgba(99,102,241,.3) }

        /* ── Content ── */
        .page { position:relative;z-index:10;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 16px }

        .logo-wrap { text-align:center;margin-bottom:24px;animation:fadeUp .7s ease both }
        .logo-wrap img {
            height:130px;width:auto;display:block;margin:0 auto;mix-blend-mode:screen;
            filter:drop-shadow(0 0 40px rgba(99,102,241,1)) drop-shadow(0 0 16px rgba(168,85,247,.8)) drop-shadow(0 0 80px rgba(99,102,241,.35));
            transition:filter .3s;
        }
        .logo-wrap img:hover { filter:drop-shadow(0 0 50px rgba(99,102,241,1)) drop-shadow(0 0 20px rgba(168,85,247,1)) drop-shadow(0 0 100px rgba(99,102,241,.5)) }
        .logo-sub { margin-top:12px;font-size:.72rem;font-weight:500;letter-spacing:.2em;text-transform:uppercase;color:#374151 }

        .card {
            width:100%;max-width:468px;
            background:rgba(5,9,20,.72);
            border:1px solid rgba(124,58,237,.2);
            border-radius:28px;
            backdrop-filter:blur(40px);
            -webkit-backdrop-filter:blur(40px);
            box-shadow:0 0 0 1px rgba(255,255,255,.04) inset,0 1px 0 rgba(255,255,255,.08) inset,0 40px 80px rgba(0,0,0,.7),0 0 60px rgba(124,58,237,.06);
            animation:fadeUp .7s .1s ease both;
            overflow:hidden;
        }
        .card-body { padding:38px 42px 30px }
        .card-foot { padding:18px 42px 22px;border-top:1px solid rgba(124,58,237,.1);text-align:center }

        .c-icon { width:44px;height:44px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#7c3aed,#6366f1);box-shadow:0 6px 24px rgba(124,58,237,.45);margin-bottom:18px }
        .c-icon svg { width:22px;height:22px;color:#fff }
        .c-title { font-size:1.55rem;font-weight:700;color:#f1f5f9;letter-spacing:-.03em }
        .c-sub   { font-size:.88rem;color:#64748b;margin-top:5px }

        .field { margin-bottom:18px }
        .lbl { display:block;font-size:.76rem;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.1em;margin-bottom:9px }
        .inp {
            width:100%;background:rgba(2,4,12,.9);
            border:1.5px solid rgba(30,42,66,.85);
            border-radius:14px;padding:15px 18px;
            font-size:1rem;color:#e2e8f0;
            font-family:'Sora',system-ui,sans-serif;
            outline:none;transition:all .2s;
        }
        .inp:focus { border-color:#7c3aed;background:rgba(2,4,12,1);box-shadow:0 0 0 4px rgba(124,58,237,.12),0 0 20px rgba(124,58,237,.1) }
        .inp::placeholder { color:#1e2a3a }
        .inp.err { border-color:rgba(239,68,68,.5) }

        .strength-bar { height:3px;border-radius:2px;transition:all .3s;margin-top:7px;background:#0f1723 }
        .hint-txt { font-size:.78rem;margin-top:5px;min-height:18px }
        .err-txt  { font-size:.78rem;margin-top:5px;color:#f87171;display:flex;align-items:center;gap:4px }

        .alert-err { display:flex;align-items:flex-start;gap:10px;padding:14px 16px;border-radius:14px;margin:18px 0;background:rgba(127,29,29,.2);border:1px solid rgba(239,68,68,.3);color:#fca5a5;font-size:.9rem }
        .alert-err svg { width:18px;height:18px;flex-shrink:0;margin-top:1px }

        .btn-primary {
            width:100%;padding:16px 24px;border-radius:14px;
            font-size:1.02rem;font-weight:700;color:#fff;border:none;cursor:pointer;
            font-family:'Sora',system-ui,sans-serif;letter-spacing:.02em;
            background:linear-gradient(135deg,#7c3aed 0%,#6366f1 50%,#4f46e5 100%);
            box-shadow:0 6px 30px rgba(124,58,237,.5),0 1px 0 rgba(255,255,255,.15) inset;
            transition:all .22s;margin-top:6px;position:relative;overflow:hidden;
        }
        .btn-primary:hover { transform:translateY(-2px);box-shadow:0 14px 40px rgba(124,58,237,.65) }
        .btn-primary:active { transform:translateY(0) }

        .f-txt  { font-size:.9rem;color:#475569 }
        .f-link { color:#a78bfa;font-weight:600;text-decoration:none;transition:color .15s }
        .f-link:hover { color:#c4b5fd }

        @keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        .fu1{animation:fadeUp .6s .16s ease both}
        .fu2{animation:fadeUp .6s .22s ease both}
        .fu3{animation:fadeUp .6s .28s ease both}
        .fu4{animation:fadeUp .6s .34s ease both}
        .fu5{animation:fadeUp .6s .40s ease both}
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
<div class="noise"></div>
<div class="accent-line"></div>

<div class="page">

    <div class="logo-wrap">
        <img src="{{ asset('logo.png') }}" alt="ASOIINFO">
        <p class="logo-sub">Sistema Multiempresa &nbsp;·&nbsp; CRM &nbsp;·&nbsp; Facturación</p>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="c-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="c-title">Crear cuenta</h1>
            <p class="c-sub">Regístrate para acceder a la plataforma</p>

            @if($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('password'))
            <div class="alert-err">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" style="margin-top:26px">
                @csrf

                <div class="field fu1">
                    <label class="lbl">Nombre completo</label>
                    <input class="inp {{ $errors->has('name') ? 'err':'' }}" type="text" name="name"
                           value="{{ old('name') }}" required autocomplete="name" placeholder="Juan García">
                    @error('name')<p class="err-txt"><svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                <div class="field fu2">
                    <label class="lbl">Correo electrónico</label>
                    <input class="inp {{ $errors->has('email') ? 'err':'' }}" type="email" name="email"
                           value="{{ old('email') }}" required autocomplete="email" placeholder="usuario@empresa.com">
                    @error('email')<p class="err-txt"><svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                <div class="field fu3">
                    <label class="lbl">Contraseña</label>
                    <input class="inp {{ $errors->has('password') ? 'err':'' }}" type="password" name="password" id="pw"
                           required autocomplete="new-password" placeholder="Mínimo 8 caracteres"
                           oninput="checkStrength(this.value)">
                    <div id="sBar" class="strength-bar" style="width:0%"></div>
                    <p id="sTxt" class="hint-txt" style="color:#475569"></p>
                    @error('password')<p class="err-txt"><svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                <div class="field fu4">
                    <label class="lbl">Confirmar contraseña</label>
                    <input class="inp" type="password" name="password_confirmation" id="pwc"
                           required autocomplete="new-password" placeholder="Repite tu contraseña"
                           oninput="checkMatch()">
                    <p id="mTxt" class="hint-txt"></p>
                </div>

                <div class="fu5">
                    <button type="submit" class="btn-primary">Crear cuenta &nbsp;→</button>
                </div>
            </form>
        </div>

        <div class="card-foot">
            <p class="f-txt">¿Ya tienes cuenta?&nbsp;<a href="{{ route('login') }}" class="f-link">Iniciar sesión →</a></p>
        </div>
    </div>

    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:8px;margin-top:22px;animation:fadeUp .7s .42s ease both">
        <span style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:50px;background:rgba(5,9,20,.8);border:1px solid rgba(30,42,66,.85);font-size:.78rem;font-weight:500;color:#475569">
            <span style="width:6px;height:6px;border-radius:50%;background:#25d366"></span>Omnicanal WhatsApp
        </span>
        <span style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:50px;background:rgba(5,9,20,.8);border:1px solid rgba(30,42,66,.85);font-size:.78rem;font-weight:500;color:#475569">
            <span style="width:6px;height:6px;border-radius:50%;background:#6366f1"></span>Facturación Automática
        </span>
        <span style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:50px;background:rgba(5,9,20,.8);border:1px solid rgba(30,42,66,.85);font-size:.78rem;font-weight:500;color:#475569">
            <span style="width:6px;height:6px;border-radius:50%;background:#10b981"></span>CRM Multiempresa
        </span>
    </div>

</div>

<script>
function checkStrength(v){
    const b=document.getElementById('sBar'),t=document.getElementById('sTxt');
    if(!v){b.style.width='0%';t.textContent='';return}
    let s=0;
    if(v.length>=8)s++;if(v.length>=12)s++;
    if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;
    const l=[{w:'18%',c:'#ef4444',t:'Muy débil'},{w:'36%',c:'#f97316',t:'Débil'},{w:'55%',c:'#eab308',t:'Regular'},{w:'75%',c:'#84cc16',t:'Fuerte'},{w:'100%',c:'#22c55e',t:'Muy fuerte'}];
    const lv=l[Math.min(s-1,4)]||l[0];
    b.style.width=lv.w;b.style.background=lv.c;t.textContent=lv.t;t.style.color=lv.c;
    checkMatch();
}
function checkMatch(){
    const pw=document.getElementById('pw').value,pwc=document.getElementById('pwc').value,t=document.getElementById('mTxt');
    if(!pwc){t.textContent='';return}
    if(pw===pwc){t.textContent='✓ Las contraseñas coinciden';t.style.color='#22c55e'}
    else{t.textContent='✗ No coinciden';t.style.color='#ef4444'}
}
</script>
</body>
</html>
