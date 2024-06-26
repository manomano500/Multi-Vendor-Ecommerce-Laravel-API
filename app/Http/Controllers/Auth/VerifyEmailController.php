<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request, $id, $token)
    {
        $user = User::findOrFail($id);

        if (! $user) {
            abort(404, 'User not found');
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->email))) {
            abort(403, 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return view('redirect.verifed');

        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return view('redirect.verifed');
    }


    public function generateVerificationUrl($userId): string
    {
        $user = User::find($userId);

        if (!$user) {
            abort(404, 'User not found');
        }

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'token' => $user->token,
                'hash' => sha1($user->email)
            ],
            true // absolute URL
        );

        return $url;

    }
}
