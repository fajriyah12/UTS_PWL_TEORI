<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $organizer = auth()->user()->organizer;
        return view('organizer.profile.edit', compact('organizer'));
    }

    public function update(Request $request)
    {
        $organizer = auth()->user()->organizer;

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'bio' => 'nullable|string',
            'bank_name' => 'nullable|string|max:50',
            'bank_account' => 'nullable|string|max:50',
            'logo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $data = $request->only([
            'name', 'contact_email', 'contact_phone', 
            'address', 'bio', 'bank_name', 'bank_account'
        ]);
        
        // Sync redundant fields
        $data['company_name'] = $request->name;
        $data['phone'] = $request->contact_phone;

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($organizer->logo_path) {
                \Storage::disk('public')->delete($organizer->logo_path);
            }
            
            $path = $request->file('logo')->store('organizers/logos', 'public');
            $data['logo_path'] = $path;
        }

        $organizer->update($data);

        return redirect()->route('organizer.profile.edit')
            ->with('success', 'Profil organizer berhasil diperbarui!');
    }
}
