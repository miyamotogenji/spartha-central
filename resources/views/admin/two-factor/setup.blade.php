@extends('layouts.admin')
@section('title','Autenticación de Dos Factores')
@section('content')
<div class="max-w-lg mx-auto space-y-5">
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-purple-400">Seguridad</p>
        <h1 class="text-2xl font-bold text-white">Autenticación de Dos Factores</h1>
        <p class="text-sm text-slate-400 mt-1">Protege tu cuenta con Google Authenticator o Authy.</p>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-900/40 border border-emerald-700 text-emerald-300 text-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="px-4 py-3 rounded-xl bg-red-900/40 border border-red-700 text-red-300 text-sm">
        {{ $errors->first() }}
    </div>
    @endif

    <div class="bg-slate-800/60 rounded-2xl border border-slate-700 p-6">

        @if($user->two_factor_confirmed)
        {{-- ── 2FA ACTIVE ─────────────────────────────────────────────────────── --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-emerald-900/50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <p class="text-emerald-400 font-semibold">2FA Activo</p>
                <p class="text-slate-400 text-sm">Tu cuenta está protegida con autenticación de dos factores.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.two-factor.disable') }}">
            @csrf @method('DELETE')
            <label class="block text-sm text-slate-300 mb-2">Confirma tu contraseña para desactivar</label>
            <div class="flex gap-3">
                <input type="password" name="password"
                       class="flex-1 px-4 py-2.5 bg-slate-900 border border-slate-600 text-white rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none"
                       placeholder="••••••••">
                <button class="px-5 py-2.5 bg-red-700 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition-colors">Desactivar</button>
            </div>
        </form>

        @elseif($user->two_factor_secret && $qrUrl)
        {{-- ── SCAN QR CODE ────────────────────────────────────────────────────── --}}
        <h3 class="text-white font-semibold mb-2">Paso 2: Escanea el código QR</h3>
        <p class="text-slate-400 text-sm mb-5">Abre <strong class="text-slate-200">Google Authenticator</strong> o <strong class="text-slate-200">Authy</strong> y escanea:</p>

        <div class="flex justify-center mb-4">
            <div class="bg-white p-3 rounded-2xl" id="qrcode-container"></div>
        </div>

        @if($secret)
        <p class="text-xs text-slate-500 text-center mb-4">
            O ingresa la clave manual: <code class="bg-slate-700 text-slate-200 px-2 py-0.5 rounded font-mono text-xs">{{ $secret }}</code>
        </p>
        @endif

        <form method="POST" action="{{ route('admin.two-factor.confirm') }}" class="mt-4">
            @csrf
            <label class="block text-sm text-slate-300 mb-2">Ingresa el código de 6 dígitos que aparece en tu app:</label>
            <div class="flex gap-3">
                <input type="text" name="code" maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                       class="w-36 text-center text-xl font-mono px-3 py-2.5 bg-slate-900 border border-slate-600 text-white rounded-xl focus:ring-2 focus:ring-purple-500 focus:outline-none tracking-widest"
                       placeholder="000000" autofocus autocomplete="one-time-code">
                <button class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-xl transition-colors">Confirmar y activar</button>
            </div>
        </form>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
        new QRCode(document.getElementById("qrcode-container"), {
            text: "{{ addslashes($qrUrl) }}",
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.M
        });
        </script>

        @else
        {{-- ── 2FA DISABLED ────────────────────────────────────────────────────── --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-slate-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <p class="text-slate-200 font-semibold">2FA Desactivado</p>
                <p class="text-slate-400 text-sm">Activa 2FA para añadir una capa extra de seguridad a tu cuenta.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.two-factor.enable') }}">
            @csrf
            <button class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-xl transition-colors">
                Activar autenticación de dos factores
            </button>
        </form>
        @endif

    </div>
</div>
@endsection
