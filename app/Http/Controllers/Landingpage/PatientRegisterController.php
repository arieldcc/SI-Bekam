<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientRegisterController extends Controller
{
    public function create()
    {
        return view('Landingpage.registration');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'        => 'required|string|max:100',
            'gender'           => 'required|in:L,P',
            'date_of_birth'    => 'required|date',
            'phone_number'     => 'required|string|max:20',
            'address'          => 'required|string|max:255',
            'height'           => 'nullable|numeric|min:0|max:300',
            'blood_type'       => 'nullable|in:A,B,AB,O',
            'disease_history'  => 'nullable|string|max:255',

            // akun login
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6',
        ]);

        // Simpan data pasien
        $patient = Patient::create([
            'full_name'       => $request->full_name,
            'gender'          => $request->gender,
            'date_of_birth'   => $request->date_of_birth,
            'phone_number'    => $request->phone_number,
            'address'         => $request->address,
            'height'          => $request->height,
            'blood_type'      => $request->blood_type,
            'disease_history' => $request->disease_history,
        ]);

        // Buat user login untuk pasien
        User::create([
            'username'    => $request->email,
            'full_name'   => $patient->full_name,
            'name'        => $patient->full_name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'pasien',
            'related_id'  => $patient->id,
            'is_active'   => true
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login menggunakan email dan password Anda.');
    }
}
