<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $therapist = $user->related;

        return view('Terapis.Profile.edit', compact('user', 'therapist'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $therapist = $user->related;

        $request->validate([
            'full_name'       => 'required|string|max:255',
            'specialty'       => 'required|string|max:255',
            'phone_number'    => 'required|string|max:20',
            'address'         => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'password'        => 'nullable|string|min:6|confirmed',
        ]);

        $therapist->update([
            'full_name'    => $request->full_name,
            'specialty'    => $request->specialty,
            'phone_number' => $request->phone_number,
            'address'      => $request->address,
        ]);

        $user->update([
            'email'      => $request->email,
            'full_name'  => $request->full_name,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
