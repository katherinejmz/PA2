<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function status()
    {
        $user = Auth::user();
        return response()->json([
            'enabled' => !is_null($user->two_factor_secret),
        ]);
    }

    public function enable(Request $request)
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();
        $user->two_factor_secret = Crypt::encrypt($secret);
        $user->save();

        $QRImage = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return response()->json([
            'qr' => $QRImage,
            'secret' => $secret,
        ]);
    }

    public function disable(Request $request)
    {
        $user = Auth::user();
        $user->two_factor_secret = null;
        $user->save();

        return response()->json(['message' => '2FA désactivée.']);
    }
}
