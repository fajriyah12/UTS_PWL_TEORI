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

        $user = $request->user();

        // STRICT CHECK: Only 'user' role allowed here
        if ($user->role !== 'user') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun anda tidak terdaftar silahkan daftar terlebih dahulu.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->intended(route('home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
