@extends('layouts.main')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h4>Halo, {{ Auth::user()->full_name }} 👋</h4>
        <p class="text-muted">Selamat datang di panel pasien. Lihat informasi terapi Anda di bawah ini.</p>
    </div>

    {{-- Statistik --}}
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <h6>Total Kunjungan</h6>
                <h3>{{ $total }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <h6>Terapi Selesai</h6>
                <h3>{{ $completed }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body">
                <h6>Dibatalkan</h6>
                <h3>{{ $canceled }}</h3>
            </div>
        </div>
    </div>

    {{-- Jadwal mendatang --}}
    <div class="col-md-6 mt-4">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="text-info">Jadwal Terapi Mendatang</h6>
                @if($upcoming)
                    <p>
                        <strong>{{ $upcoming->schedule->therapist->full_name ?? '-' }}</strong><br>
                        📆 {{ \Carbon\Carbon::parse($upcoming->visit_datetime)->translatedFormat('l, d F Y') }}<br>
                        ⏰ {{ \Carbon\Carbon::parse($upcoming->schedule->start_datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($upcoming->schedule->end_datetime)->format('H:i') }} WITA<br>
                        🎟️ Antrian: #{{ $upcoming->queue_number }}
                    </p>
                @else
                    <p class="text-muted">Belum ada jadwal terdekat.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Riwayat Terakhir --}}
    <div class="col-md-6 mt-4">
        <div class="card border-secondary">
            <div class="card-body">
                <h6 class="text-secondary">Riwayat Terapi Terakhir</h6>
                @if($lastCompleted)
                    <p>
                        <strong>{{ $lastCompleted->schedule->therapist->full_name ?? '-' }}</strong><br>
                        📆 {{ \Carbon\Carbon::parse($lastCompleted->visit_datetime)->translatedFormat('l, d F Y') }}<br>
                        ⏰ {{ \Carbon\Carbon::parse($lastCompleted->schedule->start_datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($lastCompleted->schedule->end_datetime)->format('H:i') }} WITA<br>
                        📝 Catatan: {{ $lastCompleted->notes ?? '-' }}
                    </p>
                @else
                    <p class="text-muted">Belum ada riwayat terapi selesai.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Shortcut --}}
    <div class="col-12 mt-4">
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('pasien.registrasi.create') }}" class="btn btn-primary">
                <i class="ti ti-calendar-plus"></i> Daftar Terapi Baru
            </a>
            <a href="{{ route('pasien.registrasi.index') }}" class="btn btn-outline-primary">
                <i class="ti ti-clipboard-list"></i> Riwayat Terapi
            </a>
            <a href="{{ route('pasien.antrian.index') }}" class="btn btn-outline-info">
                <i class="ti ti-clock-hour-4"></i> Cek Status Antrian
            </a>
            <a href="{{ route('pasien.profil.edit') }}" class="btn btn-outline-dark">
                <i class="ti ti-settings"></i> Pengaturan Profil
            </a>
        </div>
    </div>
</div>
@endsection
