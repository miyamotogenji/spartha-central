<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    private Google2FA $g2fa;

    public function __construct()
    {
        $this->g2fa = new Google2FA();
    }

    public function show()
    {
        $user   = auth()->user();
        $qrUrl  = null;
        $secret = null;

        if ($user->two_factor_secret && !$user->two_factor_confirmed) {
            $secret = $user->two_factor_secret;
            $qrUrl  = $this->g2fa->getQRCodeUrl(
                config('app.name', 'ASOIINFO'),
                $user->email,
                $secret
            );
        }

        return view('admin.two-factor.setup', compact('user', 'qrUrl', 'secret'));
    }

    public function enable(Request $request)
    {
        $user = auth()->user();

        if (!$user->two_factor_secret) {
            $secret = $this->g2fa->generateSecretKey();
            $user->update(['two_factor_secret' => $secret, 'two_factor_confirmed' => false]);
            $user->refresh();
        }

        $secret = $user->two_factor_secret;
        $qrUrl  = $this->g2fa->getQRCodeUrl(
            config('app.name', 'ASOIINFO'),
            $user->email,
            $secret
        );

        return view('admin.two-factor.setup', compact('user', 'qrUrl', 'secret'));
    }

    public function confirm(Request $request)
    {
        $request->validate(['code' => 'required|string|min:6|max:8']);
        $user = auth()->user();

        if (!$user->two_factor_secret) {
            return back()->withErrors(['code' => 'Primero activa el 2FA.']);
        }

        $valid = $this->g2fa->verifyKey($user->two_factor_secret, $request->code);
        if (!$valid) {
            return back()->withErrors(['code' => 'Código incorrecto. Verifica tu aplicación autenticadora.']);
        }

        $user->update(['two_factor_confirmed' => true]);
        return redirect()->route('admin.two-factor.show')->with('success', '2FA activado correctamente. Tu cuenta ahora está protegida.');
    }

    public function disable(Request $request)
    {
        $request->validate(['password' => 'required']);
        if (!\Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }
        auth()->user()->update(['two_factor_secret' => null, 'two_factor_confirmed' => false]);
        return redirect()->route('admin.two-factor.show')->with('success', '2FA desactivado.');
    }

    public function challenge()
    {
        return view('admin.two-factor.challenge');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $user = auth()->user();

        if (!$user->two_factor_secret) {
            return redirect()->route('admin.dashboard');
        }

        $valid = $this->g2fa->verifyKey($user->two_factor_secret, $request->code);
        if (!$valid) {
            return back()->withErrors(['code' => 'Código incorrecto.']);
        }

        session(['2fa_verified' => true]);
        return redirect()->intended(route('admin.dashboard'));
    }
}
