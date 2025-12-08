<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organizer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register'); // pakai Breeze view
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required','string','min:3','max:100'],
            'email'    => ['required','string','lowercase','email','max:255','unique:users,email'],
            'phone'    => ['nullable','string','max:32'],
            'password' => ['required','confirmed','min:8'],
            // Paksa role user saja saat register biasa
            // field role & org_name tidak lagi diterima dari form publik
        ]);

        $role = 'user';

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'role'     => $role,                   // default user / organizer opsional
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Breeze: akan mendorong ke email verification notice jika belum verified
        return redirect()->route('verification.notice');
    }
}
