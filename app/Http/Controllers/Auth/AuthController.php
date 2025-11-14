<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Rate;
use App\Models\Shipper;

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
            return redirect()->intended('/')
                ->withSuccess('Login successful!');
        }
        return redirect()->back()
            ->withError('Invalid credentials!')
            ->withInput();
    }

    public function dashboard()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                $lastRate = Rate::with('user')->latest()->first();
                $lastShipper = Shipper::with('user')->latest()->first();
            } elseif ($user->isMarketing()) {
                $lastRate = Rate::where('user_id', $user->id)->latest()->first();
                $lastShipper = Shipper::where('user_id', $user->id)->latest()->first();
            } else {
                $lastRate = null;
                $lastShipper = null;
            }
            return view('pages.dashboard', compact('lastRate', 'lastShipper'));
        }
        return redirect('login')->withError('You must login first!');
    }

    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::logout();
        return redirect('login')->withSuccess('Logout successful!');
    }
}
