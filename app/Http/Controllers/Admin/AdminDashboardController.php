<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Registration;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function adminDashboard(){
        $totalPatients = Patient::count();
        $totalSchedules = Schedule::count();
        $totalRegistrations = Registration::count();
        $totalMedicalRecords = MedicalRecord::count();

        // Statistik registrasi per bulan (12 bulan terakhir)
        $registrationsByMonth = Registration::selectRaw('MONTH(visit_datetime) as month, COUNT(*) as total')
            ->whereYear('visit_datetime', now()->year)
            ->groupByRaw('MONTH(visit_datetime)')
            ->pluck('total', 'month');

        return view('Admin.dashboard', compact(
            'totalPatients',
            'totalSchedules',
            'totalRegistrations',
            'totalMedicalRecords',
            'registrationsByMonth'
        ));
    }
}
