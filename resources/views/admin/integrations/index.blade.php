@extends('layouts.admin')
@section('title', 'Integraciones WhatsApp')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Integraciones</h1>
            <p class="text-sm text-slate-500 mt-1">Conecte números de WhatsApp Business con coexistencia (App + Cloud API).</p>
        </div>
        <button type="button" onclick="openConnectModal()" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
            <span>📱</span> Conectar número con coexistencia
        </button>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-lg font-semibold text-slate-800">Números conectados</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Número</th>
                        <th class="px-6 py-3">Phone ID</th>
                        <th class="px-6 py-3">Coexistencia</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Conectado</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($numbers as $n)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $n->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $n->phone_number }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $n->phone_number_id }}</td>
                        <td class="px-6 py-4">
                            @if($n->coexistence_enabled)
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Sí</span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">No</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'connected' => 'bg-emerald-100 text-emerald-800',
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'error' => 'bg-red-100 text-red-800',
                                    'disconnected' => 'bg-slate-100 text-slate-600',
                                ];
                            @endphp
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$n->connection_status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($n->connection_status) }}
                            </span>
                            @if($n->last_error)
                                <p class="mt-1 text-xs text-red-600 max-w-xs truncate" title="{{ $n->last_error }}">{{ $n->last_error }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $n->connected_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($n->connection_status === 'connected')
                            <form method="POST" action="{{ route('admin.integrations.disconnect', $n) }}" class="inline" onsubmit="return confirm('¿Desconectar este número?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:text-red-800">Desconectar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            No hay números conectados. Use el botón superior para conectar con coexistencia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-800 mb-2">Requisitos de coexistencia</h3>
        <ul class="text-sm text-slate-600 space-y-1 list-disc list-inside">
            <li>WhatsApp Business App versión <strong>2.24.17+</strong> abierta en el teléfono durante el proceso.</li>
            <li>Configuration ID configurado en Meta Embedded Signup y en el servidor (<code class="text-xs bg-slate-100 px-1 rounded">META_EMBEDDED_SIGNUP_CONFIG_ID</code>).</li>
            <li>El número permanece en el teléfono; el CRM recibe mensajes nuevos y sincroniza contactos.</li>
            <li>Importación manual de historial: <code class="text-xs bg-slate-100 px-1 rounded">php artisan whatsapp:import-history</code></li>
        </ul>
    </div>
</div>

{{-- Modal: Conectar número existente --}}
<div id="connectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl" role="dialog" aria-labelledby="connectModalTitle">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 id="connectModalTitle" class="text-lg font-bold text-slate-800">Conectar número existente</h2>
            <p class="mt-1 text-sm text-slate-500">
                Conecte su cuenta de WhatsApp Business manteniendo el número en la App móvil (coexistencia entre App y Cloud API).
            </p>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div id="connectStatus" class="hidden rounded-lg px-4 py-3 text-sm"></div>

            @if(empty($meta['app_id']) || empty($meta['config_id']))
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Configure <code>META_APP_ID</code> y <code>META_EMBEDDED_SIGNUP_CONFIG_ID</code> en el servidor antes de conectar.
                </div>
            @else
                <button type="button" id="btnConnectCoexistence" onclick="launchEmbeddedSignup()"
                    class="w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                    Conectar número con coexistencia
                </button>
            @endif

            <p class="text-xs text-slate-400 text-center">
                Inicie sesión en Meta, elija conectar una cuenta existente y confirme en el teléfono con código o QR.
            </p>
        </div>
        <div class="border-t border-slate-100 px-6 py-4 flex justify-end">
            <button type="button" onclick="closeConnectModal()" class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Cerrar</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(!empty($meta['app_id']) && !empty($meta['config_id']))
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js"></script>
<script>
(function() {
    const META_APP_ID = @json($meta['app_id']);
    const META_CONFIG_ID = @json($meta['config_id']);
    const GRAPH_VERSION = @json($meta['graph']);
    const REDIRECT_URI = @json($redirectUri);
    const CONNECT_URL = @json(route('admin.integrations.connect'));
    const CSRF_TOKEN = @json(csrf_token());

    let sessionData = {};
    let sdkReady = false;

    window.fbAsyncInit = function() {
        FB.init({
            appId: META_APP_ID,
            cookie: true,
            xfbml: true,
            version: GRAPH_VERSION
        });
        sdkReady = true;
    };

    window.openConnectModal = function() {
        document.getElementById('connectModal').classList.remove('hidden');
        document.getElementById('connectModal').classList.add('flex');
        hideStatus();
    };

    window.closeConnectModal = function() {
        document.getElementById('connectModal').classList.add('hidden');
        document.getElementById('connectModal').classList.remove('flex');
        sessionData = {};
        hideStatus();
    };

    function showStatus(message, type) {
        const el = document.getElementById('connectStatus');
        el.classList.remove('hidden', 'bg-red-50', 'text-red-800', 'border-red-200',
            'bg-green-50', 'text-green-800', 'border-green-200',
            'bg-blue-50', 'text-blue-800', 'border-blue-200');
        if (type === 'error') {
            el.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
        } else if (type === 'success') {
            el.classList.add('bg-green-50', 'text-green-800', 'border', 'border-green-200');
        } else {
            el.classList.add('bg-blue-50', 'text-blue-800', 'border', 'border-blue-200');
        }
        el.textContent = message;
    }

    function hideStatus() {
        const el = document.getElementById('connectStatus');
        el.classList.add('hidden');
        el.textContent = '';
    }

    function setLoading(loading) {
        const btn = document.getElementById('btnConnectCoexistence');
        if (btn) {
            btn.disabled = loading;
            btn.textContent = loading ? 'Conectando…' : 'Conectar número con coexistencia';
        }
    }

    /**
     * Envía al backend código OAuth y/o datos de sesión.
     * Acepta respuesta solo con código OAuth (fix solicitado por el cliente).
     */
    async function completeConnection(payload) {
        setLoading(true);
        showStatus('Procesando conexión con Meta…', 'info');

        try {
            const response = await fetch(CONNECT_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    ...payload,
                    redirect_uri: REDIRECT_URI
                })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Error al conectar el número.');
            }

            showStatus(data.message, 'success');
            setTimeout(() => window.location.reload(), 1500);
        } catch (err) {
            showStatus(err.message || 'Error inesperado. Intente nuevamente.', 'error');
            setLoading(false);
        }
    }

    /**
     * Meta puede devolver:
     * 1) Solo código OAuth en FB.login (authResponse.code)
     * 2) Datos de sesión vía postMessage (WA_EMBEDDED_SIGNUP)
     * 3) Ambos — en cualquier orden
     * No rechazar la sesión si solo llega el código.
     */
    function tryFinalizeConnection() {
        const code = sessionData.code;
        const hasSessionFields = sessionData.phone_number_id || sessionData.waba_id || sessionData.access_token;

        if (code) {
            completeConnection({
                code: code,
                waba_id: sessionData.waba_id || null,
                phone_number_id: sessionData.phone_number_id || null,
                phone_number: sessionData.phone_number || sessionData.display_phone_number || null,
                display_phone_number: sessionData.display_phone_number || null,
                verified_name: sessionData.verified_name || null,
                access_token: sessionData.access_token || null
            });
            return;
        }

        if (hasSessionFields && sessionData.access_token) {
            completeConnection({
                access_token: sessionData.access_token,
                waba_id: sessionData.waba_id || null,
                phone_number_id: sessionData.phone_number_id || null,
                phone_number: sessionData.phone_number || sessionData.display_phone_number || null,
                display_phone_number: sessionData.display_phone_number || null,
                verified_name: sessionData.verified_name || null
            });
            return;
        }

        if (hasSessionFields) {
            showStatus('Esperando código OAuth de Meta…', 'info');
            return;
        }

        showStatus('Meta no devolvió los datos de la sesión. Intente nuevamente.', 'error');
    }

    window.addEventListener('message', function(event) {
        if (event.origin !== 'https://www.facebook.com' && event.origin !== 'https://web.facebook.com') {
            return;
        }

        let data;
        try {
            data = typeof event.data === 'string' ? JSON.parse(event.data) : event.data;
        } catch (e) {
            return;
        }

        if (!data || data.type !== 'WA_EMBEDDED_SIGNUP') {
            return;
        }

        const payload = data.data || data;
        sessionData = Object.assign(sessionData, {
            phone_number_id: payload.phone_number_id || sessionData.phone_number_id,
            waba_id: payload.waba_id || sessionData.waba_id,
            phone_number: payload.phone_number || sessionData.phone_number,
            display_phone_number: payload.display_phone_number || sessionData.display_phone_number,
            verified_name: payload.verified_name || sessionData.verified_name,
            access_token: payload.access_token || sessionData.access_token
        });

        tryFinalizeConnection();
    });

    window.launchEmbeddedSignup = function() {
        if (!sdkReady || typeof FB === 'undefined') {
            showStatus('Cargando SDK de Meta… espere un momento e intente de nuevo.', 'info');
            return;
        }

        hideStatus();
        sessionData = {};

        FB.login(function(response) {
            if (response.authResponse) {
                if (response.authResponse.code) {
                    sessionData.code = response.authResponse.code;
                    tryFinalizeConnection();
                    return;
                }
                if (response.authResponse.accessToken) {
                    sessionData.access_token = response.authResponse.accessToken;
                    tryFinalizeConnection();
                    return;
                }
            }

            if (response.status === 'not_authorized' || !response.authResponse) {
                showStatus('Inicio de sesión cancelado o no autorizado.', 'error');
                return;
            }

            setTimeout(function() {
                if (!sessionData.code && !sessionData.phone_number_id && !sessionData.waba_id) {
                    showStatus('Meta no devolvió los datos de la sesión. Intente nuevamente.', 'error');
                }
            }, 8000);
        }, {
            config_id: META_CONFIG_ID,
            response_type: 'code',
            override_default_response_type: true,
            extras: {
                setup: {},
                featureType: 'whatsapp_business_app_onboarding',
                sessionInfoVersion: '3'
            }
        });
    };
})();
</script>
@endif
@endpush
