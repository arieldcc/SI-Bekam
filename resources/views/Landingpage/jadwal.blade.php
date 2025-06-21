@extends('layouts.landingpage.app')

@section('title', 'Jadwal Harian Terapis')

@section('css')
<style>
    @media (max-width: 768px) {
        .col-md-6 {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-5">🗓️ Jadwal Harian Terapis</h2>

    @php
        $flat = [];

        foreach ($jadwalHarian as $tanggal => $items) {
            foreach ($items as $item) {
                $flat[] = [
                    'tanggal'    => $tanggal,
                    'therapist'  => $item['therapist'],
                    'start_time' => $item['start_time'],
                    'end_time'   => $item['end_time'],
                    'status'     => $item['status'],
                ];
            }
        }

        $chunks = array_chunk($flat, ceil(count($flat) / 2));
    @endphp

    @if (count($flat))
    <div class="row">
        @foreach ($chunks as $column)
        <div class="col-md-6">
            @foreach ($column as $item)
            <div class="border rounded shadow-sm p-3 mb-3 bg-white">
                <h6 class="text-primary mb-1">{{ $item['tanggal'] }}</h6>
                <p class="mb-1">
                    👤 <strong>{{ $item['therapist'] }}</strong><br>
                    ⏰ {{ $item['start_time'] }} - {{ $item['end_time'] }} WITA<br>
                    📌 <span class="badge bg-info text-dark">{{ ucfirst($item['status']) }}</span>
                </p>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center text-muted py-5">
        <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
        <p>Belum ada jadwal yang tersedia.</p>
    </div>
    @endif
</div>
@endsection
