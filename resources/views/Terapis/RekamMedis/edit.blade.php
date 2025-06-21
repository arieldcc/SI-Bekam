@extends('layouts.main')

@section('title', 'Edit Rekam Medis')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-3">✏️ Edit Rekam Medis</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('terapis.rekam-medis.update', $rekam_medi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Pasien & Jadwal</label>
                        <input type="text" class="form-control" value="{{ $rekam_medi->registration->patient->full_name }} - {{ \Carbon\Carbon::parse($rekam_medi->registration->visit_datetime)->translatedFormat('l, d F Y') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="complaints" class="form-label">Keluhan</label>
                        <textarea name="complaints" class="form-control @error('complaints') is-invalid @enderror">{{ old('complaints', $rekam_medi->complaints) }}</textarea>
                        @error('complaints') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="therapy_area" class="form-label">Area Terapi</label>
                        <input type="text" name="therapy_area" class="form-control @error('therapy_area') is-invalid @enderror" value="{{ old('therapy_area', $rekam_medi->therapy_area) }}">
                        @error('therapy_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="weight" class="form-label">Berat Badan (kg)</label>
                            <input type="number" name="weight" step="0.1" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight', $rekam_medi->weight) }}">
                            @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="blood_pressure" class="form-label">Tekanan Darah</label>
                            <input type="text" name="blood_pressure" class="form-control @error('blood_pressure') is-invalid @enderror" value="{{ old('blood_pressure', $rekam_medi->blood_pressure) }}">
                            @error('blood_pressure') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="pulse" class="form-label">Nadi (/menit)</label>
                            <input type="number" name="pulse" class="form-control @error('pulse') is-invalid @enderror" value="{{ old('pulse', $rekam_medi->pulse) }}">
                            @error('pulse') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="result_notes" class="form-label">Catatan Hasil</label>
                        <textarea name="result_notes" class="form-control @error('result_notes') is-invalid @enderror">{{ old('result_notes', $rekam_medi->result_notes) }}</textarea>
                        @error('result_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="actual_start_time" class="form-label">Waktu Mulai</label>
                            <input type="datetime-local" name="actual_start_time" class="form-control @error('actual_start_time') is-invalid @enderror"
                                   value="{{ old('actual_start_time', $rekam_medi->actual_start_time ? \Carbon\Carbon::parse($rekam_medi->actual_start_time)->format('Y-m-d\TH:i') : '') }}">
                            @error('actual_start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="actual_end_time" class="form-label">Waktu Selesai</label>
                            <input type="datetime-local" name="actual_end_time" class="form-control @error('actual_end_time') is-invalid @enderror"
                                   value="{{ old('actual_end_time', $rekam_medi->actual_end_time ? \Carbon\Carbon::parse($rekam_medi->actual_end_time)->format('Y-m-d\TH:i') : '') }}">
                            @error('actual_end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('terapis.rekam-medis.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
