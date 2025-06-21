@extends('layouts.main')

@section('title', 'Tambah Jadwal Terapis')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h5 class="mb-4">Form Tambah Jadwal Terapis</h5>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.schedules.store') }}" method="POST">
                    @csrf

                    {{-- Terapis --}}
                    <div class="mb-3">
                        <label for="therapist_id" class="form-label">Terapis</label>
                        <select name="therapist_id" id="therapist_id" class="form-select @error('therapist_id') is-invalid @enderror">
                            <option value="">-- Pilih Terapis --</option>
                            @foreach($therapists as $therapist)
                                <option value="{{ $therapist->id }}" {{ old('therapist_id') == $therapist->id ? 'selected' : '' }}>
                                    {{ $therapist->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('therapist_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Waktu Mulai --}}
                    <input type="text" name="start_datetime" id="start_datetime"
                        class="form-control @error('start_datetime') is-invalid @enderror"
                        value="{{ old('start_datetime') }}">
                    @error('start_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror


                    {{-- Waktu Selesai --}}
                    <input type="text" name="end_datetime" id="end_datetime"
                        class="form-control @error('end_datetime') is-invalid @enderror"
                        value="{{ old('end_datetime') }}">
                    @error('end_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    {{-- Keterangan --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Keterangan Tambahan</label>
                        <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Jadwal</label>
                        <select name="status" id="status" class="form-select">
                            <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="penuh" {{ old('status') == 'penuh' ? 'selected' : '' }}>Penuh</option>
                            <option value="libur" {{ old('status') == 'libur' ? 'selected' : '' }}>Libur</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/id.js"></script>
<script>

    flatpickr("#start_datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "l, d F Y H:i",
        locale: "id",
    });

    flatpickr("#end_datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "l, d F Y H:i",
        locale: "id",
    });

    // pastikan ini berjalan setelah jQuery tersedia
    $(function () {
        $('input[name="type"]').on('change', function () {
            if ($(this).val() === 'weekly') {
                $('#weekday-group').removeClass('d-none');
                $('#date-group').addClass('d-none');
            } else {
                $('#weekday-group').addClass('d-none');
                $('#date-group').removeClass('d-none');
            }
        });
    });
</script>
@endsection
