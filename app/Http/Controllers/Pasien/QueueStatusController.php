<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueueStatusController extends Controller
{
    public function index()
    {
        $patientId = Auth::user()->related_id;

        // Set bahasa Indonesia untuk nama hari
        Carbon::setLocale('id');

        // Ambil semua registration aktif milik pasien
        $registrations = Registration::with('schedule.therapist')
            ->where('patient_id', $patientId)
            ->where('status', 'terdaftar')
            ->whereDate('visit_datetime', '>=', now()->toDateString())
            ->orderBy('visit_datetime')
            ->get();

        $scheduleStatus = [];

        foreach ($registrations as $reg) {
            $scheduleId = $reg->schedule_id;
            $visitDate = \Carbon\Carbon::parse($reg->visit_datetime)->format('Y-m-d');

            $all = Registration::where('schedule_id', $scheduleId)
                ->whereDate('visit_datetime', $visitDate)
                ->get();

            $scheduleStatus[] = [
                'therapist'   => $reg->schedule->therapist->full_name ?? '-',
                'tanggal'     => \Carbon\Carbon::parse($reg->visit_datetime)->translatedFormat('l, d F Y'),
                'jam'         => substr($reg->schedule->start_datetime, 11, 5) . ' - ' . substr($reg->schedule->end_datetime, 11, 5) . ' WITA',
                'total'       => $all->count(),
                'terdaftar'   => $all->where('status', 'terdaftar')->count(),
                'selesai'     => $all->where('status', 'selesai')->count(),
                'batal'       => $all->where('status', 'batal')->count(),
                'urutan_saya' => $reg->queue_number ?? '-',
            ];
        }

        return view('Pasien.QueueStatus.index', compact('scheduleStatus'));
    }
}
