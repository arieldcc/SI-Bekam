<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactController extends Controller
{
    /**
     * Tampilkan halaman index dengan DataTables server-side.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Contact::latest();

            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $checked = $row->is_active ? 'checked' : '';
                return '
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input toggle-active"
                               data-id="' . $row->id . '" ' . $checked . '>
                    </div>
                ';
            })
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('admin.contacts.edit', $row->id) . '" class="btn btn-warning btn-sm me-1">Edit</a>
                    <button class="btn btn-danger btn-sm btn-delete" data-id="' . $row->id . '">Hapus</button>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
        }

        return view('Admin.contacts.index');
    }

    public function toggle($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->is_active = !$contact->is_active;
        $contact->save();

        return response()->json([
            'message' => 'Status kontak berhasil diperbarui.',
            'status' => $contact->is_active ? 'Aktif' : 'Nonaktif',
        ]);
    }

    /**
     * Tampilkan form tambah kontak.
     */
    public function create()
    {
        return view('Admin.contacts.create');
    }

    /**
     * Simpan data kontak baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
            'address'       => 'nullable|string|max:255',
            'whatsapp_link' => 'nullable|url|max:255',
            'map_embed'     => 'nullable|string',
        ]);

        Contact::create($validated);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Informasi kontak berhasil disimpan.');
    }

    /**
     * Tampilkan form edit kontak.
     */
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        return view('Admin.contacts.edit', compact('contact'));
    }

    /**
     * Update data kontak.
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $request->validate([
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string|max:255',
            'whatsapp_link'  => 'nullable|url|max:255',
            'map_embed'      => 'nullable|string',
        ]);

        $contact->update([
            'phone'          => $request->phone,
            'email'          => $request->email,
            'address'        => $request->address,
            'whatsapp_link'  => $request->whatsapp_link,
            'map_embed'      => $request->map_embed,
        ]);

        return redirect()->route('admin.contacts.index')->with('success', 'Kontak berhasil diperbarui.');
    }

    /**
     * Hapus kontak.
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json(['message' => 'Kontak berhasil dihapus.']);
    }
}
