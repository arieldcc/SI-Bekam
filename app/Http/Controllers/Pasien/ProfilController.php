<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $patient = $user->related; // relasi ke model Patient

        return view('Pasien.Profil.edit', compact('user', 'patient'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $patient = $user->related;

        $request->validate([
            'full_name'       => 'required|string|max:255',
            'gender'          => 'required|in:L,P',
            'date_of_birth'   => 'required|date',
            'phone_number'    => 'required|string|max:20',
            'address'         => 'required|string|max:255',
            'height'          => 'nullable|numeric|min:30|max:300',
            'blood_type'      => 'nullable|in:A,B,AB,O',
            'disease_history' => 'nullable|string',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'password'        => 'nullable|string|min:6|confirmed',
        ]);

        // Update patient
        $patient->update([
            'full_name'       => $request->full_name,
            'gender'          => $request->gender,
            'date_of_birth'   => $request->date_of_birth,
            'phone_number'    => $request->phone_number,
            'address'         => $request->address,
            'height'          => $request->height,
            'blood_type'      => $request->blood_type,
            'disease_history' => $request->disease_history,
        ]);

        // Update user login
        $user->update([
            'full_name' => $request->full_name,
            'email'     => $request->email,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
