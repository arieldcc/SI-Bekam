<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $therapistId = Auth::user()->related_id;

                $query = Schedule::where('therapist_id', $therapistId)->latest();

                $count = $query->count();

                Log::info('👨‍⚕️ Jadwal terapis diakses', [
                    'user_id' => Auth::id(),
                    'therapist_id' => $therapistId,
                    'total_jadwal' => $count,
                ]);

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('schedule_day', function ($row) {
                            $start = \Carbon\Carbon::parse($row->start_datetime)->locale('id');
                            $end = \Carbon\Carbon::parse($row->end_datetime)->locale('id');

                            $startDay = $start->translatedFormat('l, d F Y'); // l = hari, d = tanggal, F = bulan, Y = tahun
                            $endDay = $end->translatedFormat('l, d F Y');

                            return "$startDay s/d $endDay";
                        })
                    ->addColumn('time_range', function ($row) {
                        return Carbon::parse($row->start_datetime)->format('H:i') .
                            ' s/d ' .
                            Carbon::parse($row->end_datetime)->format('H:i') . ' WITA';
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
                    ->rawColumns(['status'])
                    ->make(true);
            } catch (\Exception $e) {
                Log::error('❌ Gagal memuat jadwal terapis', [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json(['error' => 'Terjadi kesalahan saat mengambil data jadwal.'], 500);
            }
        }

        return view('Terapis.Jadwal.index');
    }

    public function updateStatus(Request $request, Schedule $schedule)
    {
        $request->validate([
            'status' => 'required|in:tersedia,penuh,libur'
        ]);

        // Pastikan hanya bisa update jadwal milik sendiri
        if ($schedule->therapist_id !== Auth::user()->related_id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $schedule->update(['status' => $request->status]);

        Log::info('📅 Status jadwal diperbarui oleh terapis', [
            'schedule_id' => $schedule->id,
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Status berhasil diperbarui.']);
    }

}
