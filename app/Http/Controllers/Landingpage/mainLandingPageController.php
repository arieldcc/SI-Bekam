<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class mainLandingPageController extends Controller
{
    public function index(){
        $services = Service::where('is_active', true)->take(3)->get();
        $contact = Contact::where('is_active', true)->latest()->first();

        return view('Landingpage.index', compact('services', 'contact'));
    }

    public function jadwal(){
        App::setLocale('id'); // 👈 pastikan locale ID
        Carbon::setLocale('id');

        $jadwalHarian = [];

        $schedules = Schedule::with('therapist')
            ->where('status', 'tersedia') // kalau mau tampil semua status, hapus where ini
            ->orderBy('start_datetime')
            ->get();

        foreach ($schedules as $schedule) {
            $start = Carbon::parse($schedule->start_datetime);
            $end = Carbon::parse($schedule->end_datetime);

            while ($start->lte($end)) {
                if ($start->gte(now()->startOfDay())) {
                    $dateKey = $start->translatedFormat('l, d F Y'); // bahasa Indonesia

                    $jadwalHarian[$dateKey][] = [
                        'therapist' => $schedule->therapist->full_name ?? '-',
                        'start_time' => $start->format('H:i'),
                        'end_time'   => Carbon::parse($schedule->end_datetime)->format('H:i'),
                        'status'     => ucfirst($schedule->status),
                    ];
                }

                $start->addDay();
            }
        }

        return view('Landingpage.jadwal', compact('jadwalHarian'));
    }
}
