<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(){
        return view('Auth.login');
    }

    public function login(){
        
    }
    public function showRegister(){
        return view('Auth.register');
    }

    public function register(Request $request){
        $validate = $request->validate(
            [
                'name'=> 'required|string|max:100',
                'email'=> 'required|email|unique:users',
                'password'=> 'required|string|min:8|confirmed',
            ]
        );
        $user = User::create($validate);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
