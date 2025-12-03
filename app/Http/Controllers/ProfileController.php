<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'  => ['required','string','max:100'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'phone' => ['nullable','string','max:32'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'institution' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);

        // jika email berubah, hapus verifikasi
        if ($data['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && \Illuminate\Support\Facades\Storage::exists('public/profile/' . $user->photo)) {
                \Illuminate\Support\Facades\Storage::delete('public/profile/' . $user->photo);
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/profile', $filename);
            $data['photo'] = $filename;
        }

        $user->fill($data)->save();

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required','current_password'],
        ]);

        $user = $request->user();
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function show(Request $request)
   {
    return view('profile.show', [
        'user' => $request->user(),
    ]);
   }

}
