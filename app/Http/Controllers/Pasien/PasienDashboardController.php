<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasienDashboardController extends Controller
{
    public function pasienDashboard(){
        $patientId = Auth::user()->related_id;

        // Jadwal terapi mendatang
        $upcoming = Registration::with('schedule.therapist')
            ->where('patient_id', $patientId)
            ->where('status', 'terdaftar')
            ->whereDate('visit_datetime', '>=', now())
            ->orderBy('visit_datetime')
            ->first();

        // Riwayat terapi terakhir
        $lastCompleted = Registration::with('schedule.therapist')
            ->where('patient_id', $patientId)
            ->where('status', 'selesai')
            ->orderByDesc('visit_datetime')
            ->first();

        // Statistik singkat
        $total = Registration::where('patient_id', $patientId)->count();
        $completed = Registration::where('patient_id', $patientId)->where('status', 'selesai')->count();
        $canceled = Registration::where('patient_id', $patientId)->where('status', 'batal')->count();

        return view('Pasien.Dashboard.dashboard', compact(
            'upcoming', 'lastCompleted', 'total', 'completed', 'canceled'
        ));
    }
}
