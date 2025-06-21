<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AdminMedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = MedicalRecord::with('registration.patient');

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
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.rekam-medis.show', $row->id) . '" class="btn btn-sm btn-primary">Detail</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Admin.MedicalRecord.index');
    }

    public function show(MedicalRecord $rekam_medis)
    {
        $rekam_medis->load('registration.patient', 'therapist');
        return view('Admin.MedicalRecord.show', compact('rekam_medis'));
    }
}
