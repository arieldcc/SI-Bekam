<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Registration;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Therapist;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Registration::with('patient')->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('patient_name', fn($row) => $row->patient->full_name ?? '-')
                ->addColumn('registration_date', function ($row) {
                    \Carbon\Carbon::setLocale('id'); // Pastikan locale 'id' aktif
                    return \Carbon\Carbon::parse($row->registration_date)->translatedFormat('l, d F Y H:i');
                })
                ->addColumn('schedule_info', function ($row) {
                    if (!$row->schedule) return '-';

                    $therapist = $row->schedule->therapist->full_name ?? '-';

                    // Set locale ke bahasa Indonesia
                    \Carbon\Carbon::setLocale('id');

                    // Ambil tanggal dari visit_datetime
                    $tanggalKunjungan = $row->visit_datetime
                        ? \Carbon\Carbon::parse($row->visit_datetime)->translatedFormat('l, d F Y')
                        : '-';

                    $jam = $row->schedule->start_time && $row->schedule->end_time
                        ? substr($row->schedule->start_time, 0, 5) . ' - ' . substr($row->schedule->end_time, 0, 5)
                        : '-';

                    return "{$therapist}<br><small>{$tanggalKunjungan}, {$jam} WITA</small>";
                })
                ->addColumn('status', function ($row) {
                    $options = ['terdaftar', 'selesai', 'batal'];
                    $dropdown = '<select class="form-select form-select-sm" onchange="updateStatus(' . $row->id . ', this.value)">';
                    foreach ($options as $option) {
                        $selected = $row->status === $option ? 'selected' : '';
                        $dropdown .= "<option value=\"$option\" $selected>" . ucfirst($option) . "</option>";
                    }
                    $dropdown .= '</select>';
                    return $dropdown;
                })
                ->addColumn('notes', fn($row) => $row->notes ?? '-')
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('admin.registrations.edit', $row->id) . '" class="btn btn-warning btn-sm me-1">Edit</a>
                        <button onclick="deleteRegistration(' . $row->id . ')" class="btn btn-danger btn-sm">Hapus</button>
                    ';
                })
                ->rawColumns(['schedule_info', 'status', 'action']) // <== tambahkan 'status' di sini
                ->make(true);
        }

        return view('Admin.Registrations.index');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:terdaftar,selesai,batal'
        ]);

        $registration = Registration::findOrFail($id);
        $registration->status = $request->status;
        $registration->save();

        return response()->json(['message' => 'Status berhasil diperbarui.']);
    }

    public function create()
    {
        $patients = Patient::orderBy('full_name')->get();

        $schedules = Schedule::with('therapist')->get();

        $scheduleByTherapist = [];

        foreach ($schedules as $schedule) {
            if (!$schedule->start_datetime || !$schedule->end_datetime) continue;

            $start = \Carbon\Carbon::parse($schedule->start_datetime);
            $end = \Carbon\Carbon::parse($schedule->end_datetime);

            while ($start->lte($end)) {
                // Cek agar hanya tampilkan tanggal >= hari ini
                if ($start->gte(now()->startOfDay())) {
                    $dateKey = $start->format('Y-m-d');

                    if (!isset($scheduleByTherapist[$schedule->therapist_id])) {
                        $scheduleByTherapist[$schedule->therapist_id] = [];
                    }

                    // Simpan juga jam praktik untuk tanggal tsb
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

        return view('Admin.Registrations.create', compact(
            'patients',
            'therapists',
            'scheduleByTherapist'
        ));
    }

    public function getTherapistSchedules($id)
    {
        $schedules = Schedule::where('therapist_id', $id)
            ->where('status', 'tersedia')
            ->whereNotNull('start_datetime')
            ->whereNotNull('end_datetime')
            ->get(['id', 'start_datetime', 'end_datetime']);

        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'schedule_id' => 'required',
            'selected_date' => 'required|date',
            'status' => 'required|in:terdaftar,selesai,batal',
            'notes' => 'nullable|string',
        ]);

        $selectedDate = \Carbon\Carbon::parse($request->selected_date)->startOfDay(); // visit_datetime
        $today = now(); // registration_date

        // Cek duplikat pendaftaran untuk tanggal & jadwal yang sama
        $alreadyRegistered = Registration::where('patient_id', $request->patient_id)
            ->where('schedule_id', $request->schedule_id)
            ->whereDate('visit_datetime', $selectedDate)
            ->where('status', 'terdaftar')
            ->exists();

        if ($alreadyRegistered) {
            return back()
                ->withErrors(['patient_id' => 'Pasien sudah terdaftar di jadwal ini pada tanggal tersebut.'])
                ->withInput();
        }

        // Hitung nomor antrian untuk tanggal yang dipilih
        $existingCount = Registration::where('schedule_id', $request->schedule_id)
            ->whereDate('visit_datetime', $selectedDate)
            ->count();

        $queueNumber = $existingCount + 1;

        Registration::create([
            'patient_id'        => $request->patient_id,
            'schedule_id'       => $request->schedule_id,
            'registration_date' => $today,
            'visit_datetime'    => $selectedDate,
            'status'            => $request->status,
            'notes'             => $request->notes,
            'queue_number'      => $queueNumber,
        ]);

        Appointment::create([
            'patient_id'   => $request->patient_id,
            'schedule_id'  => $request->schedule_id,
            'status'       => 'terdaftar', // atau ambil dari $request->status
            'notes'        => $request->notes,
        ]);


        return redirect()->route('admin.registrations.index')->with('success', 'Pendaftaran berhasil disimpan.');
    }

    public function edit($id)
    {
        $registration = Registration::findOrFail($id);
        $patients = Patient::orderBy('full_name')->get();

        // Ambil semua jadwal dengan relasi terapis
        $schedules = Schedule::with('therapist')->get();

        // Bangun struktur jadwal berdasarkan therapist
        $scheduleByTherapist = [];

        foreach ($schedules as $schedule) {
            if (!$schedule->start_datetime || !$schedule->end_datetime) continue;

            $start = \Carbon\Carbon::parse($schedule->start_datetime);
            $end = \Carbon\Carbon::parse($schedule->end_datetime);

            while ($start->lte($end)) {
                if ($start->gte(now()->startOfDay())) {
                    $dateKey = $start->format('Y-m-d');

                    if (!isset($scheduleByTherapist[$schedule->therapist_id])) {
                        $scheduleByTherapist[$schedule->therapist_id] = [];
                    }

                    // Isi jam dan id schedule untuk tanggal tsb
                    $scheduleByTherapist[$schedule->therapist_id][$dateKey] = [
                        'start' => substr($schedule->start_datetime, 11, 15),
                        'end'   => substr($schedule->end_datetime, 11, 15),
                        'id'    => $schedule->id,
                    ];
                }

                $start->addDay();
            }
        }

        return view('Admin.Registrations.edit', compact(
            'registration',
            'patients',
            'schedules',
            'scheduleByTherapist'
        ));
    }

    public function update(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);

        $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'schedule_id'    => 'required|exists:schedules,id',
            'selected_date'  => 'required|date', // ← kunjungan pasien
            'status'         => 'required|in:terdaftar,selesai,batal',
            'notes'          => 'nullable|string',
        ]);

        // Gunakan selected_date sebagai tanggal kunjungan (visit_datetime)
        $visitDate = \Carbon\Carbon::parse($request->selected_date)->startOfDay();

        // Cek duplikat untuk kombinasi patient + schedule + visit_date (selain data ini sendiri)
        $duplicate = Registration::where('patient_id', $request->patient_id)
            ->where('schedule_id', $request->schedule_id)
            ->whereDate('visit_datetime', $visitDate)
            ->where('id', '!=', $registration->id)
            ->where('status', 'terdaftar')
            ->exists();

        if ($duplicate) {
            return back()
                ->withErrors(['patient_id' => 'Pasien sudah terdaftar pada jadwal dan tanggal tersebut.'])
                ->withInput();
        }

        // Hitung nomor antrian jika status masih aktif
        $queueNumber = null;
        if ($request->status === 'terdaftar') {
            $existingCount = Registration::where('schedule_id', $request->schedule_id)
                ->whereDate('visit_datetime', $visitDate)
                ->where('id', '!=', $registration->id)
                ->count();
            $queueNumber = $existingCount + 1;
        }

        // Update data
        $registration->update([
            'patient_id'        => $request->patient_id,
            'schedule_id'       => $request->schedule_id,
            'visit_datetime'    => $visitDate,
            'registration_date' => now(), // atau tetap $registration->registration_date jika tidak ingin diubah
            'status'            => $request->status,
            'notes'             => $request->notes,
            'queue_number'      => $queueNumber,
        ]);

        // Sync ke appointments
        if ($request->status === 'terdaftar') {
            Appointment::updateOrCreate(
                [
                    'patient_id'  => $request->patient_id,
                    'schedule_id' => $request->schedule_id,
                ],
                [
                    'status' => 'terdaftar',
                    'notes'  => $request->notes,
                ]
            );
        } else {
            // Jika status bukan "terdaftar", hapus appointment jika ada
            Appointment::where('patient_id', $request->patient_id)
                ->where('schedule_id', $request->schedule_id)
                ->delete();
        }

        return redirect()->route('admin.registrations.index')->with('success', 'Pendaftaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->delete();

        return response()->json(['message' => 'Data pendaftaran berhasil dihapus.']);
    }
}
