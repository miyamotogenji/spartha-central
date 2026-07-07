<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\WhatsappNumber;
use App\Services\MetaWhatsAppService;
use Illuminate\Http\Request;

class WhatsAppIntegrationController extends Controller
{
    public function index()
    {
        $numbers = WhatsappNumber::orderByDesc('connected_at')->get();

        return view('admin.integrations.index', [
            'numbers'  => $numbers,
            'meta'     => [
                'app_id'    => config('services.meta.app_id'),
                'config_id' => config('services.meta.embedded_signup_config_id'),
                'graph'     => config('services.meta.graph_version', 'v21.0'),
            ],
            'redirectUri' => route('admin.integrations.index'),
        ]);
    }

    public function connectCoexistence(Request $request, MetaWhatsAppService $meta)
    {
        $request->validate([
            'code'             => 'nullable|string',
            'access_token'     => 'nullable|string',
            'waba_id'          => 'nullable|string',
            'phone_number_id'  => 'nullable|string',
            'phone_number'     => 'nullable|string',
            'display_phone_number' => 'nullable|string',
            'verified_name'    => 'nullable|string',
            'purpose'          => 'nullable|in:support,sales,billing,general',
        ]);

        if (! $request->filled('code') && ! $request->filled('access_token')) {
            return response()->json([
                'success' => false,
                'message' => 'Se requiere el código OAuth o los datos de sesión de Meta.',
            ], 422);
        }

        try {
            $number = $meta->registerFromSession(
                session: $request->only([
                    'access_token', 'waba_id', 'phone_number_id',
                    'phone_number', 'display_phone_number', 'verified_name', 'purpose',
                ]),
                oauthCode: $request->input('code'),
                redirectUri: $request->input('redirect_uri', route('admin.integrations.index')),
            );

            if ($number->waba_id && $number->access_token) {
                $meta->subscribeAppToWaba($number->waba_id, $number->access_token);
            }

            AuditLog::log(
                'whatsapp_connect',
                WhatsappNumber::class,
                $number->id,
                [],
                ['phone' => $number->phone_number, 'coexistence' => true]
            );

            return response()->json([
                'success' => true,
                'message' => "Número {$number->phone_number} conectado correctamente con coexistencia.",
                'number'  => [
                    'id'              => $number->id,
                    'phone_number'    => $number->phone_number,
                    'phone_number_id' => $number->phone_number_id,
                    'waba_id'         => $number->waba_id,
                    'status'          => $number->connection_status,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function disconnect(WhatsappNumber $number)
    {
        $number->update([
            'is_active'         => false,
            'connection_status' => 'disconnected',
            'access_token'      => null,
        ]);

        AuditLog::log(
            'whatsapp_disconnect',
            WhatsappNumber::class,
            $number->id,
            ['phone' => $number->phone_number],
            []
        );

        return back()->with('success', 'Número desconectado correctamente.');
    }
}
