@extends('layouts.main')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-3">📄 Detail Rekam Medis</h4>

        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Nama Pasien</dt>
                    <dd class="col-sm-8">{{ $record->registration->patient->full_name ?? '-' }}</dd>

                    <dt class="col-sm-4">Tanggal Kunjungan</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($record->registration->visit_datetime)->translatedFormat('l, d F Y') }}</dd>

                    <dt class="col-sm-4">Keluhan</dt>
                    <dd class="col-sm-8">{{ $record->complaints ?? '-' }}</dd>

                    <dt class="col-sm-4">Area Terapi</dt>
                    <dd class="col-sm-8">{{ $record->therapy_area ?? '-' }}</dd>

                    <dt class="col-sm-4">Berat Badan</dt>
                    <dd class="col-sm-8">{{ $record->weight ? $record->weight . ' kg' : '-' }}</dd>

                    <dt class="col-sm-4">Tekanan Darah</dt>
                    <dd class="col-sm-8">{{ $record->blood_pressure ?? '-' }}</dd>

                    <dt class="col-sm-4">Nadi</dt>
                    <dd class="col-sm-8">{{ $record->pulse ? $record->pulse . ' /menit' : '-' }}</dd>

                    <dt class="col-sm-4">Catatan Hasil</dt>
                    <dd class="col-sm-8">{{ $record->result_notes ?? '-' }}</dd>

                    <dt class="col-sm-4">Waktu Mulai</dt>
                    <dd class="col-sm-8">{{ $record->actual_start_time ? \Carbon\Carbon::parse($record->actual_start_time)->format('H:i d-m-Y') : '-' }}</dd>

                    <dt class="col-sm-4">Waktu Selesai</dt>
                    <dd class="col-sm-8">{{ $record->actual_end_time ? \Carbon\Carbon::parse($record->actual_end_time)->format('H:i d-m-Y') : '-' }}</dd>
                </dl>

                <a href="{{ route('pasien.rekam-medis.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
