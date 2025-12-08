<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Rate;
use App\Models\Shipper;
use App\Models\Activity;

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

    public function dashboard()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                $lastRate = Rate::with('user')->latest()->first();
                $lastShipper = Shipper::with('user')->latest()->first();
                $lastActivity = Activity::with('user')->latest()->first();
            } elseif ($user->isMarketing()) {
                $lastRate = Rate::where('user_id', $user->id)->latest()->first();
                $lastShipper = Shipper::where('user_id', $user->id)->latest()->first();
                $lastActivity = Activity::where('user_id', $user->id)->latest()->first();
                if ($lastRate && $lastRate->created_at->diffInDays(now()) >= 1) {
                    $lastRate = null;
                }
                if ($lastShipper && $lastShipper->created_at->diffInDays(now()) >= 1) {
                    $lastShipper = null;
                }
                if ($lastActivity && $lastActivity->created_at->diffInDays(now()) >= 1) {
                    $lastActivity = null;
                }
            } else {
                $lastRate = null;
                $lastShipper = null;
                $lastActivity = null;
            }
            return view('pages.dashboard', compact( 'lastRate', 'lastShipper', 'lastActivity'));
        }
        return redirect('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
