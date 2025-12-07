<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOrganizer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Cek jika user adalah organizer
        if (!$user->isOrganizer()) {
            // Jika bukan organizer, redirect ke home dengan error message
            return redirect('/')
                ->with('error', 'Anda tidak memiliki akses ke halaman organizer.');
        }

        // Organizer langsung dianggap verified (tidak perlu verifikasi admin)
        // Cek jika organizer profile ada
        $organizer = $user->organizer;
        if (!$organizer) {
            return redirect()->route('organizer.pending-verification')
                ->with('warning', 'Profil organizer Anda belum lengkap.');
        }

        return $next($request);
    }
}