<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StaffSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.staff-login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = (bool) $request->boolean('remember');

        // Use 'staff' guard
        if (! Auth::guard('staff')->attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'Kredensial salah atau akun tidak ditemukan.',
            ])->onlyInput('email');
        }

        $user = Auth::guard('staff')->user();

        // STRICT CHECK: Only 'admin' or 'organizer' allowed
        if (! in_array($user->role, ['admin', 'organizer'])) {
            Auth::guard('staff')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akses ditolak. Halaman ini khusus untuk Staf (Admin/Organizer).',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'organizer') {
            return redirect()->route('organizer.dashboard');
        }

        return redirect('/');
    }
    
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('staff')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
