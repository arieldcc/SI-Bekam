<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Registration;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TerapisDashboardController extends Controller
{
    public function terapisDashboard(){
        $therapistId = Auth::user()->related_id;

        $today = Carbon::today();

        $schedulesToday = Schedule::where('therapist_id', $therapistId)
            ->whereDate('start_datetime', $today)
            ->get();

        $registrationsToday = Registration::whereHas('schedule', fn($q) => $q->where('therapist_id', $therapistId))
            ->whereDate('visit_datetime', $today)
            ->count();

        $recentMedicalRecords = MedicalRecord::with('registration.patient')
            ->where('therapist_id', $therapistId)
            ->latest()
            ->take(5)
            ->get();

        return view('Terapis.Dashboard.index', compact(
            'schedulesToday',
            'registrationsToday',
            'recentMedicalRecords'
        ));
    }
}
