<?php

use App\Http\Controllers\Api\v1\ResetPasswordController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/docs');
});

Route:: get('/setup',
    function () {
        $credentials = [
            'email' => 'admin@admin.com',
            'password' => 'password'
        ];

        if (!Auth::attempt($credentials)) {
            $user = new User();
            $user->name = 'Admin';
            $user->email = $credentials ['email'];
            $user->password = Hash::make($credentials['password']);
            $user->save();
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
            $updateToken = $user->createToken('admin-token', ['create', 'update']);
            $basicToken = $user->createToken('admin-token', ['create']);

            return [
                'admin' => $adminToken->plainTextToken,
                'update' => $updateToken->plainTextToken,
                'basic' => $basicToken->plainTextToken
            ];
        }
    });

require __DIR__.'/auth.php';

//
//Route::get('reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
//
Route::get('/login-after-reset', function () {
    return view('auth.reset-success');
})->name('login-after-reset');
