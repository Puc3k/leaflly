<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Forgot Password
     *
     * Request a password reset link for a user with the given email.
     *
     * @bodyParam email required The email address of the user. Example: user@example.com
     *
     * @response 200 {
     *     "status": "passwords.sent"
     * }
     *
     * @response 400 {
     *     "message": "Nie udało się wysłać linku resetującego hasło."
     * }
     *
     * @param Request $request
     *
     * @return JsonResponse
     */

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => __($status)
            ]);
        }
        return response()->json(['message' => 'Nie udało się wysłać linku resetującego hasło.'], 400);
    }
}
