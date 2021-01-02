<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'unique:users|required|email',
            'password' => 'required|min:6'
        ]);

        $request->merge([
            'password' => Hash::make($request->get('password')),
        ]);

        $user = User::create($request->only('name', 'email', 'password'));

        Auth::login($user);

        return $user;
    }
}
