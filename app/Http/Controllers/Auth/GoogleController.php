<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if user exists by google_id
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                // If user exists, login
                Auth::login($user);
                return redirect()->intended('/');
            } else {
                // Check if user exists by email (to merge)
                $user = User::where('email', $googleUser->email)->first();
                
                if ($user) {
                    // Update google_id and login
                    $user->update([
                        'google_id' => $googleUser->id,
                        'email_verified_at' => now(), // Assume verified by Google
                    ]);
                    Auth::login($user);
                    return redirect()->intended('/');
                } else {
                    // Create new user
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'role' => 'user', // Default role
                        'password' => null, // No password for social login
                        'email_verified_at' => now(),
                    ]);
                    
                    Auth::login($newUser);
                    return redirect()->intended('/');
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }
    }
}
