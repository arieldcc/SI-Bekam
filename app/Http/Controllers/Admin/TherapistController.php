<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class TherapistController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $query = Therapist::with('user')->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_email', function ($row) {
                    return $row->user->email ?? 'Belum terhubung';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('admin.therapists.edit', $row->id) . '" class="btn btn-sm btn-warning me-1">Edit</a>
                        <button class="btn btn-sm btn-danger" onclick="deleteTherapist(' . $row->id . ')">Hapus</button>
                    ';
                })
                ->rawColumns(['action']) // biar tombol tidak di-escape
                ->make(true);
        }

        return view('Admin.Therapists.index');
    }

    public function create()
    {
        return view('Admin.Therapists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $therapist = Therapist::create($request->only(['full_name', 'specialty', 'phone_number', 'address']));

        User::create([
            'username' => $request->email,
            'full_name' => $therapist->full_name,
            'name' => $therapist->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'terapis',
            'related_id' => $therapist->id
        ]);

        return redirect()->route('admin.therapists.index')->with('success', 'Terapis berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $therapist = Therapist::with('user')->findOrFail($id);
        return view('Admin.Therapists.edit', compact('therapist'));
    }

    public function update(Request $request, $id)
    {
        $therapist = Therapist::findOrFail($id);

        $request->validate([
            'full_name' => 'required',
            'phone_number' => 'required',
        ]);

        $therapist->update($request->only(['full_name', 'specialty', 'phone_number', 'address']));

        if ($therapist->user) {
            $therapist->user->update([
                'full_name' => $therapist->full_name,
                'name' => $therapist->full_name,
                'email' => $request->email,
            ]);
        }

        return redirect()->route('admin.therapists.index')->with('success', 'Terapis berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $therapist = Therapist::with('user')->findOrFail($id);
        if ($therapist->user) {
            $therapist->user->delete();
        }
        $therapist->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Terapis berhasil dihapus.'
        ]);
    }
}
