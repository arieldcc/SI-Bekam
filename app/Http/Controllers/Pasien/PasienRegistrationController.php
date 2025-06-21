<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Therapist;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PasienRegistrationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $registrations = Registration::with('schedule.therapist')
                ->where('patient_id', Auth::user()->related_id)
                ->latest();

            return datatables()->of($registrations)
                ->addIndexColumn()
                ->addColumn('registration_date', function ($row) {
                    \Carbon\Carbon::setLocale('id'); // <-- Pastikan ini ditambahkan
                    return \Carbon\Carbon::parse($row->registration_date)->translatedFormat('l, d F Y H:i');
                })
                ->addColumn('schedule_info', function ($row) {
                    $jadwal = $row->schedule;
                    if (!$jadwal) return '-';

                    \Carbon\Carbon::setLocale('id'); // <-- Tambahkan di sini juga

                    $therapist = $jadwal->therapist->full_name ?? '-';
                    $tanggal = $row->visit_datetime
                        ? \Carbon\Carbon::parse($row->visit_datetime)->translatedFormat('l, d F Y')
                        : '-';
                    $jam = $jadwal->start_time && $jadwal->end_time
                        ? substr($jadwal->start_time, 0, 5) . ' - ' . substr($jadwal->end_time, 0, 5)
                        : '-';

                    return "{$therapist}<br><small>{$tanggal}, {$jam} WITA</small>";
                })
                ->addColumn('status', function ($row) {
                    return ucfirst($row->status ?? '-');
                })
                ->addColumn('notes', fn($row) => $row->notes ?? '-')
                ->rawColumns(['schedule_info']) // agar HTML <br> tetap diparsing
                ->make(true);
        }

        return view('Pasien.Registrations.index');
    }

    public function create()
    {
        $user = Auth::user();
        $patient = $user->related_id;

        $schedules = Schedule::with('therapist')->get();
        $scheduleByTherapist = [];

        foreach ($schedules as $schedule) {
            if (!$schedule->start_datetime || !$schedule->end_datetime) continue;

            $start = Carbon::parse($schedule->start_datetime);
            $end = Carbon::parse($schedule->end_datetime);

            while ($start->lte($end)) {
                if ($start->gte(now()->startOfDay())) {
                    $dateKey = $start->format('Y-m-d');

                    if (!isset($scheduleByTherapist[$schedule->therapist_id])) {
                        $scheduleByTherapist[$schedule->therapist_id] = [];
                    }

                    $scheduleByTherapist[$schedule->therapist_id][$dateKey] = [
                        'start' => substr($schedule->start_datetime, 11, 15),
                        'end'   => substr($schedule->end_datetime, 11, 15),
                        'id'    => $schedule->id,
                    ];
                }
                $start->addDay();
            }
        }

        $therapistIds = Schedule::select('therapist_id')->distinct()->pluck('therapist_id');
        $therapists = Therapist::whereIn('id', $therapistIds)->orderBy('full_name')->get();

        return view('Pasien.Registrations.create', compact('therapists', 'scheduleByTherapist', 'patient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id'    => 'required|exists:schedules,id',
            'selected_date'  => 'required|date',
            'notes'          => 'nullable|string',
        ]);

        $visitDate = Carbon::parse($request->selected_date)->startOfDay();
        $today = now();

        $patientId = Auth::user()->related_id;

        $already = Registration::where('patient_id', $patientId)
            ->where('schedule_id', $request->schedule_id)
            ->whereDate('visit_datetime', $visitDate)
            ->where('status', 'terdaftar')
            ->exists();

        if ($already) {
            return back()->withErrors(['selected_date' => 'Anda sudah mendaftar pada tanggal dan jadwal ini.'])->withInput();
        }

        $queueNumber = Registration::where('schedule_id', $request->schedule_id)
            ->whereDate('visit_datetime', $visitDate)
            ->count() + 1;

        Registration::create([
            'patient_id'        => $patientId,
            'schedule_id'       => $request->schedule_id,
            'registration_date' => $today,
            'visit_datetime'    => $visitDate,
            'status'            => 'terdaftar',
            'notes'             => $request->notes,
            'queue_number'      => $queueNumber,
        ]);

        return redirect()->route('pasien.registrasi.index')->with('success', 'Pendaftaran berhasil dikirim.');
    }
}
