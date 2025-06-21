<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        if(!empty(Auth::check())){
            // return redirect('dashboard/repodashboard');
            // dd('login');
        }
        return view('Auth.login');
    }

    public function auth_login(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            $role = Auth::user()->role;
            return match ($role) {
                'admin'   => redirect()->route('admin.dashboard'),
                'terapis' => redirect()->route('terapis.dashboard'),
                default   => redirect()->route('pasien.dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
