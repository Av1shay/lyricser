<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $rememberMe = $request->get('rememberMe');

        if (Auth::attempt($credentials, $rememberMe)) {
            $request->session()->regenerate();


            return response()->json(Auth::user());
        }

        return response()->json([
            'err' => 'The provided credentials do not match our records.',
        ], 400);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'User logged out successfully.']);
    }
}
