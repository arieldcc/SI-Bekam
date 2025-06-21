<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Therapist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Schedule::with('therapist')->latest();

            return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('therapist_name', fn($row) => $row->therapist->full_name ?? '-')
                    ->addColumn('schedule_day', function ($row) {
                            $start = \Carbon\Carbon::parse($row->start_datetime)->locale('id');
                            $end = \Carbon\Carbon::parse($row->end_datetime)->locale('id');

                            $startDay = $start->translatedFormat('l, d F Y'); // l = hari, d = tanggal, F = bulan, Y = tahun
                            $endDay = $end->translatedFormat('l, d F Y');

                            return "$startDay s/d $endDay";
                        })
                    ->addColumn('time_range', function ($row) {
                        $startTime = \Carbon\Carbon::parse($row->start_datetime)->format('H:i');
                        $endTime = \Carbon\Carbon::parse($row->end_datetime)->format('H:i');
                        return "$startTime s/d $endTime WITA";
                    })
                    ->addColumn('description', fn($row) => $row->description ?? '-')
                    ->addColumn('status', function ($row) {
                        $options = ['tersedia', 'penuh', 'libur'];
                        $dropdown = '<select class="form-select form-select-sm" onchange="updateStatus(' . $row->id . ', this.value)">';
                        foreach ($options as $option) {
                            $selected = $row->status === $option ? 'selected' : '';
                            $dropdown .= "<option value=\"$option\" $selected>" . ucfirst($option) . "</option>";
                        }
                        $dropdown .= '</select>';
                        return $dropdown;
                    })
                    ->addColumn('action', function ($row) {
                        return '
                            <a href="' . route('admin.schedules.edit', $row->id) . '" class="btn btn-warning btn-sm me-1">Edit</a>
                            <button onclick="deleteSchedule(' . $row->id . ')" class="btn btn-danger btn-sm">Hapus</button>
                        ';
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);

        }

        return view('Admin.Schedules.index');
    }

    public function create()
    {
        $therapists = Therapist::all();
        return view('Admin.Schedules.create', compact('therapists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'therapist_id'   => 'required|exists:therapists,id',
            'start_datetime' => 'required|date|before:end_datetime',
            'end_datetime'   => 'required|date|after:start_datetime',
            'description'    => 'nullable|string',
            'status'         => 'required|in:tersedia,penuh,libur',
        ]);

        $start = Carbon::parse($request->start_datetime);
        $end   = Carbon::parse($request->end_datetime);

        // Cek apakah sudah ada jadwal lain milik terapis di rentang waktu yang bertabrakan
        $conflict = Schedule::where('therapist_id', $request->therapist_id)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_datetime', '<', $end)
                    ->where('end_datetime', '>', $start);
                });
            })->exists();

        if ($conflict) {
            return back()
                ->withErrors(['start_datetime' => 'Terapis sudah memiliki jadwal yang tumpang tindih pada rentang waktu ini.'])
                ->withInput();
        }

        Schedule::create([
            'therapist_id'   => $request->therapist_id,
            'start_datetime' => $start,
            'end_datetime'   => $end,
            'description'    => $request->description,
            'status'         => $request->status,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil disimpan.');
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $therapists = Therapist::orderBy('full_name')->get();

        return view('Admin.Schedules.edit', compact('schedule', 'therapists'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'therapist_id'   => 'required|exists:therapists,id',
            'start_datetime' => 'required|date|before:end_datetime',
            'end_datetime'   => 'required|date|after:start_datetime',
            'description'    => 'nullable|string',
            'status'         => 'required|in:tersedia,penuh,libur',
        ]);

        $start = \Carbon\Carbon::parse($request->start_datetime);
        $end   = \Carbon\Carbon::parse($request->end_datetime);

        // Cek konflik jadwal (exclude diri sendiri)
        $conflict = \App\Models\Schedule::where('therapist_id', $request->therapist_id)
            ->where('id', '!=', $id) // exclude current schedule
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_datetime', [$start, $end])
                    ->orWhereBetween('end_datetime', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_datetime', '<=', $start)
                            ->where('end_datetime', '>=', $end);
                    });
            })->exists();

        if ($conflict) {
            return back()
                ->withErrors(['start_datetime' => 'Terapis sudah memiliki jadwal pada rentang waktu tersebut.'])
                ->withInput();
        }

        // Update data
        $schedule = \App\Models\Schedule::findOrFail($id);
        $schedule->update([
            'therapist_id'   => $request->therapist_id,
            'start_datetime' => $start,
            'end_datetime'   => $end,
            'description'    => $request->description,
            'status'         => $request->status,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:tersedia,penuh,libur',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->status = $request->status;
        $schedule->save();

        return response()->json(['message' => 'Status berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Jadwal tidak ditemukan.'], 404);
        }

        $schedule->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus.'], 200);
    }

}
