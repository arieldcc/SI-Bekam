<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MedicalRecordController extends Controller
{
     public function index(Request $request)
    {
        if ($request->ajax()) {
            $therapistId = Auth::user()->related_id;

            $query = MedicalRecord::with(['registration.patient'])
                ->where('therapist_id', $therapistId);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('patient_name', fn($row) => $row->registration->patient->full_name ?? '-')
                ->addColumn('visit_date', function ($row) {
                    \Carbon\Carbon::setLocale('id');
                    if ($row->registration && $row->registration->visit_datetime) {
                        return \Carbon\Carbon::parse($row->registration->visit_datetime)->translatedFormat('l, d F Y');
                    }
                    return '-';
                })
                ->addColumn('complaints', fn($row) => $row->complaints ?? '-')
                ->addColumn('therapy_area', fn($row) => $row->therapy_area ?? '-')
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('terapis.rekam-medis.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Terapis.RekamMedis.index');
    }

    public function create()
    {
        $therapistId = Auth::user()->related_id;

        $registrations = Registration::with('patient')
            ->whereHas('schedule', fn($q) => $q->where('therapist_id', $therapistId))
            ->whereDoesntHave('medicalRecord')
            ->where('status', 'terdaftar') // Tambahan: hanya yang masih aktif
            ->get();

        return view('Terapis.RekamMedis.create', compact('registrations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'registration_id'   => 'required|exists:registrations,id',
            'complaints'        => 'nullable|string',
            'therapy_area'      => 'nullable|string',
            'weight'            => 'nullable|numeric',
            'blood_pressure'    => 'nullable|string',
            'pulse'             => 'nullable|integer',
            'result_notes'      => 'nullable|string',
            'actual_start_time' => 'nullable|date',
            'actual_end_time'   => 'nullable|date',
        ]);

        $registration = Registration::findOrFail($request->registration_id);

        MedicalRecord::create([
            'registration_id'   => $registration->id,
            'therapist_id'      => Auth::user()->related_id,
            'complaints'        => $request->complaints,
            'therapy_area'      => $request->therapy_area,
            'weight'            => $request->weight,
            'blood_pressure'    => $request->blood_pressure,
            'pulse'             => $request->pulse,
            'result_notes'      => $request->result_notes,
            'actual_start_time' => $request->actual_start_time,
            'actual_end_time'   => $request->actual_end_time,
        ]);

        return redirect()->route('terapis.rekam-medis.index')->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    public function edit(MedicalRecord $rekam_medi)
    {
        $this->authorizeAccess($rekam_medi);

        return view('Terapis.RekamMedis.edit', compact('rekam_medi'));
    }

    public function update(Request $request, MedicalRecord $rekam_medi)
    {
        $this->authorizeAccess($rekam_medi);

        $request->validate([
            'complaints'        => 'nullable|string',
            'therapy_area'      => 'nullable|string',
            'weight'            => 'nullable|numeric',
            'blood_pressure'    => 'nullable|string',
            'pulse'             => 'nullable|integer',
            'result_notes'      => 'nullable|string',
            'actual_start_time' => 'nullable|date',
            'actual_end_time'   => 'nullable|date',
        ]);

        $rekam_medi->update([
            'complaints'        => $request->complaints,
            'therapy_area'      => $request->therapy_area,
            'weight'            => $request->weight,
            'blood_pressure'    => $request->blood_pressure,
            'pulse'             => $request->pulse,
            'result_notes'      => $request->result_notes,
            'actual_start_time' => $request->actual_start_time,
            'actual_end_time'   => $request->actual_end_time,
        ]);

        return redirect()->route('terapis.rekam-medis.index')->with('success', 'Rekam medis berhasil diperbarui.');
    }

    protected function authorizeAccess(MedicalRecord $record)
    {
        abort_unless($record->therapist_id === Auth::user()->related_id, 403);
    }

    public function exportPdf()
    {
        $therapistId = Auth::user()->related_id;

        $records = MedicalRecord::with(['registration.patient'])
            ->where('therapist_id', $therapistId)
            ->latest()
            ->get();

        $pdf = FacadePdf::loadView('Terapis.RekamMedis.pdf', compact('records'));

        return $pdf->download('rekam_medis_terapis_' . now()->format('Ymd_His') . '.pdf');
    }
}
