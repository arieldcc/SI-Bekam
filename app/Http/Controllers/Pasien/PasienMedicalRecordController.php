<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PasienMedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $patientId = Auth::user()->related_id;

            $query = MedicalRecord::with(['registration.schedule'])
                ->whereHas('registration', function ($q) use ($patientId) {
                    $q->where('patient_id', $patientId);
                })
                ->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('visit_date', function ($row) {
                    \Carbon\Carbon::setLocale('id');
                    return $row->registration && $row->registration->visit_datetime
                        ? \Carbon\Carbon::parse($row->registration->visit_datetime)->translatedFormat('l, d F Y')
                        : '-';
                })
                ->addColumn('complaints', fn($row) => $row->complaints ?? '-')
                ->addColumn('therapy_area', fn($row) => $row->therapy_area ?? '-')
                ->addColumn('notes', fn($row) => $row->result_notes ?? '-')
                ->addColumn('action', function ($row) {
                    $url = route('pasien.rekam-medis.show', $row->id);
                    return '<a href="'.$url.'" class="btn btn-sm btn-info">Detail</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Pasien.RekamMedis.index');
    }

    public function show($id)
    {
        $record = MedicalRecord::with(['registration.schedule', 'registration.patient'])
            ->where('id', $id)
            ->whereHas('registration', function ($q) {
                $q->where('patient_id', Auth::user()->related_id);
            })
            ->firstOrFail();

        return view('Pasien.RekamMedis.show', compact('record'));
    }

}
