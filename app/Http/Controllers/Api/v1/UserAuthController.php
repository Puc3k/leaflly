<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

/**
 * @group User Authentication
 * User authentication endpoints.
 */
class UserAuthController extends Controller
{
    /**
     * Register User
     *
     * Register a new user.
     *
     * @bodyParam name string required The user's name.
     * @bodyParam email string required The user's email address.
     * @bodyParam password string required The user's password.
     *
     * @response status=200 {"status": true, "message": "User Created Successfully", "token": "API TOKEN"}
     * @response status=401 {"status": false, "message": "Validation error", "errors": {}}
     * @response status=500 {"status": false, "message": "Internal Server Error"}
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login User
     *
     * Log in a user.
     *
     * @bodyParam email string required The user's email address.
     * @bodyParam password string required The user's password.
     *
     * @response status=200 {"status": true, "message": "User Logged In Successfully", "token": "API TOKEN"}
     * @response status=401 {"status": false, "message": "Email & Password does not match with our record."}
     * @response status=500 {"status": false, "message": "Internal Server Error"}
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loginUser(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ]);

        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user
     *
     * Logs out the specified user by invalidating their access token.
     *
     * @authenticated
     *
     * @bodyParam userId integer required The ID of the user to log out.
     *
     * @response status=200 {"message": "User successfully logged out"}
     * @response status=401 {"message": "Unauthenticated"}
     * @response status=404 {"message": "User not found"}
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $userId = $request->input('userId');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'User successfully logged out']);
    }

}
