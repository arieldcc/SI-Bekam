@extends('layouts.main')

@section('title', 'Status Antrian')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <h5 class="mb-4">📋 Status Antrian Anda</h5>

        @forelse ($scheduleStatus as $item)
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-2">{{ $item['therapist'] }}</h6>
                    <p class="mb-1">🗓️ {{ $item['tanggal'] }} ({{ $item['jam'] }})</p>
                    <p class="mb-1">🔢 Nomor Antrian Anda: <strong>#{{ $item['urutan_saya'] }}</strong></p>
                    <p class="mb-0">📊 Total Antrian: {{ $item['total'] }} | Terdaftar: {{ $item['terdaftar'] }}, Selesai: {{ $item['selesai'] }}, Batal: {{ $item['batal'] }}</p>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-clock-history display-4 d-block mb-3"></i>
                <p>Belum ada antrian aktif yang Anda ikuti.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
