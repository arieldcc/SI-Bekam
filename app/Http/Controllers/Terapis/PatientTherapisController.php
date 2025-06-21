<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PatientTherapisController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $therapistId = Auth::user()->related_id;

            $query = Registration::with(['patient', 'schedule'])
                ->whereHas('schedule', function ($q) use ($therapistId) {
                    $q->where('therapist_id', $therapistId);
                })->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('patient_name', fn($row) => $row->patient->full_name)
                ->addColumn('visit_date', fn($row) => \Carbon\Carbon::parse($row->visit_datetime)->format('d M Y'))
                ->addColumn('time', fn($row) => \Carbon\Carbon::parse($row->schedule->start_datetime)->format('H:i') . ' - ' . \Carbon\Carbon::parse($row->schedule->end_datetime)->format('H:i'))
                ->addColumn('queue', fn($row) => $row->queue_number)
                ->addColumn('status', function ($row) {
                    $statuses = ['terdaftar', 'selesai', 'batal'];
                    $select = "<select class='form-select form-select-sm' onchange='updateField($row->id, \"status\", this.value)'>";
                    foreach ($statuses as $status) {
                        $selected = $row->status === $status ? 'selected' : '';
                        $select .= "<option value='$status' $selected>" . ucfirst($status) . "</option>";
                    }
                    $select .= "</select>";
                    return $select;
                })
                ->addColumn('notes', function ($row) {
                    return "<input type='text' class='form-control form-control-sm' value='" . e($row->notes) . "'
                        onchange='updateField($row->id, \"notes\", this.value)' />";
                })
                ->rawColumns(['status', 'notes'])
                ->make(true);
        }

        return view('Terapis.Pasien.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:terdaftar,selesai,batal',
            'notes' => 'nullable|string|max:255',
        ]);

        $registration = Registration::with('schedule')->findOrFail($id);

        // Cek otorisasi berdasarkan terapis yang login
        if ($registration->schedule->therapist_id !== Auth::user()->related_id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $registration->update([
            'status' => $request->status ?? $registration->status,
            'notes'  => $request->notes ?? $registration->notes,
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }
}
