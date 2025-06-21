<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class serviceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $services = Service::query()->latest();

            return DataTables::of($services)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $checked = $row->is_active ? 'checked' : '';
                    return '
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-active" data-id="' . $row->id . '" ' . $checked . '>
                        </div>
                    ';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('admin.services.edit', $row->id) . '" class="btn btn-sm btn-warning me-1">Edit</a>
                        <button onclick="deleteService(' . $row->id . ')" class="btn btn-sm btn-danger">Hapus</button>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('Admin.Services.index');
    }

    public function toggle($id)
    {
        $service = Service::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();

        return response()->json([
            'message' => 'Status layanan berhasil diperbarui.',
            'status' => $service->is_active ? 'Aktif' : 'Nonaktif',
        ]);
    }

    /**
     * Form tambah layanan
     */
    public function create()
    {
        return view('Admin.Services.create');
    }

    /**
     * Simpan layanan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:100',
        ]);

        Service::create($request->only('title', 'description', 'icon'));

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Form edit layanan
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('Admin.Services.edit', compact('service'));
    }

    /**
     * Simpan perubahan layanan
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:100',
        ]);

        $service->update($request->only('title', 'description', 'icon'));

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Hapus layanan
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['message' => 'Layanan berhasil dihapus.']);
    }
}
