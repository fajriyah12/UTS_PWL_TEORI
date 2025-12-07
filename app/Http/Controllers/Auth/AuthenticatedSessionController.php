<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'Kredensial salah atau akun belum terdaftar.',
            ])->onlyInput('email');
        }

        // Kalau belum verifikasi email → Breeze akan mengarahkan ke verification notice
        $user = $request->user();

        // Jika ada intended redirect, gunakan itu
        if ($request->session()->has('url.intended')) {
            $request->session()->regenerate();
            return redirect()->intended();
        }

        // Redirect per role
        if ($user->role === 'admin') {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'organizer') {
            $request->session()->regenerate();
            return redirect()->route('organizer.dashboard');
        }

        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
