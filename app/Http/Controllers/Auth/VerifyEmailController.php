<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            abort(403, 'Invalid verification link.');
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }
        Log::info('VerifyEmailController');
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(
                'http://localhost:8080'.RouteServiceProvider::HOME.'?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            'http://localhost:8080'.RouteServiceProvider::HOME.'?verified=1'
        );
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
            ['id' => $user->id, 'hash' => sha1($user->email)],
            true // absolute URL
        );

        return $url;
    }
}
