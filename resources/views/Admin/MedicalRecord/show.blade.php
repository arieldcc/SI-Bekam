@extends('layouts.main')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-3">🩺 Detail Rekam Medis Pasien</h4>

        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nama Pasien</dt>
                    <dd class="col-sm-9">{{ $rekam_medis->registration->patient->full_name ?? '-' }}</dd>

                    <dt class="col-sm-3">Tgl Kunjungan</dt>
                    <dd class="col-sm-9">
                        {{ $rekam_medis->registration && $rekam_medis->registration->visit_datetime
                            ? \Carbon\Carbon::parse($rekam_medis->registration->visit_datetime)->translatedFormat('l, d F Y')
                            : '-' }}
                    </dd>

                    <dt class="col-sm-3">Terapis</dt>
                    <dd class="col-sm-9">{{ $rekam_medis->therapist->full_name ?? '-' }}</dd>

                    <dt class="col-sm-3">Keluhan</dt>
                    <dd class="col-sm-9">{{ $rekam_medis->complaints ?? '-' }}</dd>

                    <dt class="col-sm-3">Area Terapi</dt>
                    <dd class="col-sm-9">{{ $rekam_medis->therapy_area ?? '-' }}</dd>

                    <dt class="col-sm-3">Berat Badan</dt>
                    <dd class="col-sm-9">{{ $rekam_medis->weight ?? '-' }} kg</dd>

                    <dt class="col-sm-3">Tekanan Darah</dt>
                    <dd class="col-sm-9">{{ $rekam_medis->blood_pressure ?? '-' }}</dd>

                    <dt class="col-sm-3">Nadi</dt>
                    <dd class="col-sm-9">{{ $rekam_medis->pulse ?? '-' }} /menit</dd>

                    <dt class="col-sm-3">Catatan Hasil</dt>
                    <dd class="col-sm-9">{{ $rekam_medis->result_notes ?? '-' }}</dd>

                    <dt class="col-sm-3">Waktu Terapi</dt>
                    <dd class="col-sm-9">
                        {{ optional($rekam_medis->actual_start_time)->format('H:i') ?? '-' }} -
                        {{ optional($rekam_medis->actual_end_time)->format('H:i') ?? '-' }}
                    </dd>
                </dl>
                <a href="{{ route('admin.rekam-medis.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
