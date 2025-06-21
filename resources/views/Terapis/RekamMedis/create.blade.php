@extends('layouts.main')

@section('title', 'Tambah Rekam Medis')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-3">🩺 Tambah Rekam Medis</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('terapis.rekam-medis.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="registration_id" class="form-label">Pasien & Jadwal</label>
                        <select name="registration_id" id="registration_id" class="form-select select2 @error('registration_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Pasien --</option>
                            @foreach ($registrations as $reg)
                                <option value="{{ $reg->id }}" {{ old('registration_id') == $reg->id ? 'selected' : '' }}>
                                    {{ $reg->patient->full_name }} - {{ \Carbon\Carbon::parse($reg->visit_datetime)->translatedFormat('l, d F Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('registration_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="complaints" class="form-label">Keluhan</label>
                        <textarea name="complaints" class="form-control @error('complaints') is-invalid @enderror">{{ old('complaints') }}</textarea>
                        @error('complaints') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="therapy_area" class="form-label">Area Terapi</label>
                        <input type="text" name="therapy_area" class="form-control @error('therapy_area') is-invalid @enderror" value="{{ old('therapy_area') }}">
                        @error('therapy_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="weight" class="form-label">Berat Badan (kg)</label>
                            <input type="number" name="weight" step="0.1" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight') }}">
                            @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="blood_pressure" class="form-label">Tekanan Darah</label>
                            <input type="text" name="blood_pressure" class="form-control @error('blood_pressure') is-invalid @enderror" value="{{ old('blood_pressure') }}">
                            @error('blood_pressure') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="pulse" class="form-label">Nadi (/menit)</label>
                            <input type="number" name="pulse" class="form-control @error('pulse') is-invalid @enderror" value="{{ old('pulse') }}">
                            @error('pulse') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="result_notes" class="form-label">Catatan Hasil</label>
                        <textarea name="result_notes" class="form-control @error('result_notes') is-invalid @enderror">{{ old('result_notes') }}</textarea>
                        @error('result_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="actual_start_time" class="form-label">Waktu Mulai</label>
                            <input type="datetime-local" name="actual_start_time" class="form-control @error('actual_start_time') is-invalid @enderror" value="{{ old('actual_start_time') }}">
                            @error('actual_start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="actual_end_time" class="form-label">Waktu Selesai</label>
                            <input type="datetime-local" name="actual_end_time" class="form-control @error('actual_end_time') is-invalid @enderror" value="{{ old('actual_end_time') }}">
                            @error('actual_end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('terapis.rekam-medis.index') }}" class="btn btn-secondary">← Kembali</a>
                        <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    {{-- jQuery duluan --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- Inisialisasi --}}
<script>
    $(document).ready(function () {
        $('#registration_id').select2({
            placeholder: "Pilih pasien...",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection
