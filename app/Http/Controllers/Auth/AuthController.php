<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function index(): View
    {
        return view('auth.login');
    }

    public function postLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);
        $loginField = $request->input('login');
        $password = $request->input('password');
        $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        if (Auth::attempt([$fieldType => $loginField, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        return back()->withErrors(['login'])->withInput();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
