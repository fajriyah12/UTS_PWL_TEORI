<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settingAdmin', [
            'user' => auth()->user(),
        ]);
    }

    public function updatePersonalInfo(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.settingAdmin')->with('status', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.settingAdmin')->with('status', 'Password changed successfully');
    }

    public function deleteProfile(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();
        
        auth()->logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Profile deleted successfully');
    }
}
