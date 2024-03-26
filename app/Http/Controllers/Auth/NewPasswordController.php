<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

/**
 * @group User Authentication
 * User authentication endpoints.
 */
class NewPasswordController extends Controller
{
    /**
     * Reset Password
     *
     * Reset the user's password.
     *
     * @bodyParam token string required The token received in the password reset email.
     * @bodyParam email string required The user's email address.
     * @bodyParam password string required The new password.
     * @bodyParam password_confirmation string required The new password confirmation.
     *
     * @response status=200 {"message": "Hasło zostało zresetowane."}
     * @response status=400 {"message": "Nie udało się zresetować hasła."}
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login-after-reset')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
//    public function store(Request $request)
//    {
//        $request->validate([
//            'token' => 'required',
//            'email' => 'required|email',
//            'password' => ['required', 'confirmed', RulesPassword::defaults()]
//        ]);
//
//        $status = Password::reset(
//            $request->only('email', 'password', 'password_confirmation', 'token'),
//            function ($user) use ($request) {
//                $user->forceFill([
//                    'password' => bcrypt($request->password),
//                    'remember_token' => Str::random(60)
//                ])->save();
//
//                $user->tokens()->delete();
//
//                event(new PasswordReset($user));
//            }
//        );
//        return $status == Password::PASSWORD_RESET
//            ? redirect()->route('login-after-reset')->with('status', __($status))
//            : back()->withInput($request->only('email'))
//                ->withErrors(['email' => __($status)]);
//    }
}
