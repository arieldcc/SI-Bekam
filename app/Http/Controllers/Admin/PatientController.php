<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Patient::with('user')->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_email', function ($row) {
                    return $row->user->email ?? 'Belum terhubung';
                })
                ->addColumn('status_toggle', function ($row) {
                    $label = $row->user && $row->user->is_active ? 'Aktif' : 'Nonaktif';
                    $btnClass = $row->user && $row->user->is_active ? 'btn-success' : 'btn-secondary';
                    $toggle = $row->user ? 'toggleStatus(' . $row->id . ', ' . ($row->user->is_active ? 'true' : 'false') . ')' : '';
                    return '<button onclick="' . $toggle . '" class="btn btn-sm ' . $btnClass . '">' . $label . '</button>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('admin.patients.edit', $row->id) . '" class="btn btn-sm btn-warning me-1">Edit</a>
                        <button class="btn btn-sm btn-danger" onclick="deletePatient(' . $row->id . ')">Hapus</button>
                    ';
                })
                ->rawColumns(['action', 'status_toggle']) // <-- hanya satu kali
                ->make(true);
        }

        return view('Admin.Patients.index'); // pastikan huruf kecil konsisten
    }


    public function create()
    {
        return view('Admin.Patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'        => 'required|string|max:255',
            'gender'           => 'required|in:L,P',
            'birth_date'       => 'required|date',
            'phone_number'     => 'required|string|max:20',
            'address'          => 'required|string|max:255',
            'height'           => 'nullable|numeric|min:30|max:300', // asumsi tinggi badan cm
            'blood_type'       => 'nullable|in:A,B,AB,O',
            'disease_history'  => 'nullable|string',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6',
        ]);

        // Simpan data pasien
        $patient = Patient::create([
            'full_name'       => $request->full_name,
            'gender'          => $request->gender,
            'date_of_birth'   => $request->birth_date,
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

        return redirect()->route('admin.patients.index')->with('success', 'Pasien berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $patient = Patient::with('user')->findOrFail($id);
        return view('Admin.Patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::with('user')->findOrFail($id);

        $request->validate([
            'full_name'        => 'required|string|max:255',
            'gender'           => 'required|in:L,P',
            'birth_date'       => 'nullable|date',
            'phone_number'     => 'required|string|max:20',
            'address'          => 'nullable|string',
            'height'           => 'nullable|numeric|min:0',
            'blood_type'       => 'nullable|in:A,B,AB,O',
            'disease_history'  => 'nullable|string',
            'email'            => 'required|email|unique:users,email,' . ($patient->user->id ?? 'NULL'),
            'password'         => 'nullable|min:6'
        ]);

        // Update data pasien
        $patient->update([
            'full_name'        => $request->full_name,
            'gender'           => $request->gender,
            'date_of_birth'    => $request->birth_date,
            'phone_number'     => $request->phone_number,
            'address'          => $request->address,
            'height'           => $request->height,
            'blood_type'       => $request->blood_type,
            'disease_history'  => $request->disease_history,
        ]);

        // Update akun user jika ada
        if ($patient->user) {
            $patient->user->update([
                'full_name'  => $request->full_name,
                'name'       => $request->full_name,
                'email'      => $request->email,
                'username'   => $request->email,
                'password'   => $request->filled('password')
                    ? Hash::make($request->password)
                    : $patient->user->password,
            ]);
        }

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $patient = Patient::with('user')->findOrFail($id);

        if ($patient->user) {
            $patient->user->delete();
        }

        $patient->delete();

        return response()->json(['status' => 'success', 'message' => 'Pasien berhasil dihapus.']);
    }

    public function toggleStatus($id)
    {
        Log::info("Toggle status dipanggil untuk user id: $id");

        $patient = Patient::with('user')->findOrFail($id);

        if ($patient->user) {
            $patient->user->is_active = !$patient->user->is_active;
            $patient->user->save();
            return response()->json(['message' => 'Status berhasil diperbarui.']);
        }

        return response()->json(['message' => 'User tidak ditemukan.'], 404);
    }

}
