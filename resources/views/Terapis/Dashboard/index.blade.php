@extends('layouts.main')

@section('title', 'Dashboard Terapis')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-4">👋 Selamat Datang, {{ Auth::user()->name }}</h4>

        <div class="row">
            <!-- Kartu Jadwal Hari Ini -->
            <div class="col-md-4 mb-3">
                <div class="card border-start border-primary border-4 shadow">
                    <div class="card-body">
                        <h6 class="text-muted">Jadwal Hari Ini</h6>
                        @forelse ($schedulesToday as $schedule)
                            <p class="mb-1">
                                <strong>{{ \Carbon\Carbon::parse($schedule->start_datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_datetime)->format('H:i') }}</strong><br>
                                {{ $schedule->description ?? '-' }}
                            </p>
                        @empty
                            <p class="text-muted">Tidak ada jadwal hari ini.</p>
                        @endforelse
                        <a href="{{ route('terapis.jadwal.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat Semua Jadwal</a>
                    </div>
                </div>
            </div>

            <!-- Kartu Pasien Hari Ini -->
            <div class="col-md-4 mb-3">
                <div class="card border-start border-success border-4 shadow">
                    <div class="card-body">
                        <h6 class="text-muted">Pasien Hari Ini</h6>
                        <h3>{{ $registrationsToday }}</h3>
                        <a href="{{ route('terapis.pasien.index') }}" class="btn btn-sm btn-outline-success mt-2">Lihat Daftar Pasien</a>
                    </div>
                </div>
            </div>

            <!-- Rekam Medis Terbaru -->
            <div class="col-md-4 mb-3">
                <div class="card border-start border-danger border-4 shadow">
                    <div class="card-body">
                        <h6 class="text-muted">Rekam Medis Terakhir</h6>
                        @forelse ($recentMedicalRecords as $record)
                            <p class="mb-1">
                                <strong>{{ $record->registration->patient->full_name ?? '-' }}</strong><br>
                                {{ \Carbon\Carbon::parse($record->registration->visit_datetime)->format('d M Y') }}
                            </p>
                        @empty
                            <p class="text-muted">Belum ada data.</p>
                        @endforelse
                        <a href="{{ route('terapis.rekam-medis.index') }}" class="btn btn-sm btn-outline-danger mt-2">Lihat Semua Rekam Medis</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
