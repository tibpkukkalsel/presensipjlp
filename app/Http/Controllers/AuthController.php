<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        // Otomatis aktifkan "Remember Me" agar pada device & browser yang sama otomatis login
        $remember = true;
        $loginInput = $request->nik;
        $password = $request->password;

        if (Auth::guard('karyawan')->attempt(['nik' => $loginInput, 'password' => $password], $remember) ||
            Auth::guard('karyawan')->attempt(['username' => $loginInput, 'password' => $password], $remember)) {
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }
            return redirect('/dashboard');
        } else {
            return redirect('/')->with(['warning' => 'ID / Password Salah']);
        }
    }

    public function proseslogout()
    {
        if (Auth::guard('karyawan')->check()) {
            Auth::guard('karyawan')->logout();
            if (request()->hasSession()) {
                request()->session()->invalidate();
                request()->session()->regenerateToken();
            }
        }
        return redirect('/');
    }

    public function proseslogoutadmin()
    {
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
            return redirect('/panel-adm14');
        }
        return redirect('/panel-adm14');
    }

    public function prosesloginadmin(Request $request)
    {
        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/panel-adm14/dashboardadmin');
        } else {
            return redirect('/panel-adm14')->with(['warning' => 'Username atau Password Salah']);
        }
    }
}
