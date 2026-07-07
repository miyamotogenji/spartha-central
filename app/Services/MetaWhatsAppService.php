<?php

namespace App\Services;

use App\Models\WhatsappNumber;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaWhatsAppService
{
    public function graphUrl(string $path = ''): string
    {
        $base = rtrim(config('services.meta.graph_url'), '/');
        $version = config('services.meta.graph_version', 'v21.0');

        return $path ? "{$base}/{$version}/{$path}" : "{$base}/{$version}";
    }

    public function exchangeCodeForToken(string $code, ?string $redirectUri = null): array
    {
        $params = [
            'client_id'     => config('services.meta.app_id'),
            'client_secret' => config('services.meta.app_secret'),
            'code'          => $code,
        ];

        if ($redirectUri) {
            $params['redirect_uri'] = $redirectUri;
        }

        $response = Http::get($this->graphUrl('oauth/access_token'), $params);

        if (! $response->successful()) {
            Log::error('Meta OAuth token exchange failed', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            throw new \RuntimeException(
                $response->json('error.message') ?? 'No se pudo intercambiar el código OAuth con Meta.'
            );
        }

        $data = $response->json();

        if (empty($data['access_token'])) {
            throw new \RuntimeException('Meta no devolvió access_token después del intercambio OAuth.');
        }

        return $data;
    }

    public function getSharedWabaAccounts(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->get($this->graphUrl('debug_token'), [
                'input_token' => $accessToken,
            ]);

        if (! $response->successful()) {
            return [];
        }

        $granular = $response->json('data.granular_scopes', []);

        foreach ($granular as $scope) {
            if (($scope['scope'] ?? '') === 'whatsapp_business_management') {
                return $scope['target_ids'] ?? [];
            }
        }

        return [];
    }

    public function getPhoneNumbers(string $wabaId, string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->get($this->graphUrl("{$wabaId}/phone_numbers"), [
                'fields' => 'id,display_phone_number,verified_name,quality_rating',
            ]);

        if (! $response->successful()) {
            Log::error('Meta phone numbers fetch failed', [
                'waba_id' => $wabaId,
                'body'    => $response->json(),
            ]);

            throw new \RuntimeException(
                $response->json('error.message') ?? 'No se pudieron obtener los números de WhatsApp.'
            );
        }

        return $response->json('data', []);
    }

    public function registerFromSession(array $session, ?string $oauthCode = null, ?string $redirectUri = null): WhatsappNumber
    {
        $accessToken = $session['access_token'] ?? null;

        if (! $accessToken && $oauthCode) {
            $tokenData   = $this->exchangeCodeForToken($oauthCode, $redirectUri);
            $accessToken = $tokenData['access_token'];
        }

        if (! $accessToken) {
            throw new \RuntimeException('No se recibió token de acceso ni código OAuth válido.');
        }

        $wabaId         = $session['waba_id'] ?? null;
        $phoneNumberId  = $session['phone_number_id'] ?? null;
        $displayPhone   = $session['phone_number'] ?? $session['display_phone_number'] ?? null;

        if (! $wabaId) {
            $wabaIds = $this->getSharedWabaAccounts($accessToken);
            $wabaId  = $wabaIds[0] ?? null;
        }

        if (! $phoneNumberId && $wabaId) {
            $phones = $this->getPhoneNumbers($wabaId, $accessToken);
            $first  = $phones[0] ?? null;
            if ($first) {
                $phoneNumberId = $first['id'];
                $displayPhone  = $displayPhone ?? ($first['display_phone_number'] ?? null);
            }
        }

        if (! $phoneNumberId) {
            throw new \RuntimeException('No se pudo determinar el phone_number_id. Verifique permisos en Meta Business.');
        }

        $displayPhone = $displayPhone ? preg_replace('/\D+/', '', $displayPhone) : 'pending';

        return WhatsappNumber::updateOrCreate(
            ['phone_number_id' => $phoneNumberId],
            [
                'name'                => $session['verified_name'] ?? $session['name'] ?? 'WhatsApp '.$displayPhone,
                'phone_number'        => $displayPhone,
                'waba_id'             => $wabaId,
                'access_token'        => $accessToken,
                'coexistence_enabled' => true,
                'connection_status'   => 'connected',
                'connected_at'        => now(),
                'last_error'          => null,
                'is_active'           => true,
                'purpose'             => $session['purpose'] ?? 'support',
            ]
        );
    }

    public function subscribeAppToWaba(string $wabaId, string $accessToken): bool
    {
        $response = Http::withToken($accessToken)
            ->post($this->graphUrl("{$wabaId}/subscribed_apps"));

        return $response->successful();
    }
}
