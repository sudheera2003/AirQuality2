<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin(){
        return view('Auth.login');
    }

    public function login(Request $request){
        $validate = $request->validate(
            [
                'email'=>'required|email',
                'password'=>'required|string',
            ]
        );

        if(Auth::attempt($validate)){
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        throw ValidationException::withMessages([
            'creadentials' => 'Sorry, incorrect credentials'
        ]);
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
        User::create($validate);

        return redirect()->route('dashboard')->with('success', 'Admin created successfully!');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
