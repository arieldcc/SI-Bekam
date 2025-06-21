@extends('layouts.main')

@section('title', 'Tambah Pendaftaran Pasien')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h5 class="mb-4">Form Pendaftaran Pasien</h5>

        {{-- Tampilkan semua error validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Oops! Ada kesalahan saat mengisi form:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.registrations.store') }}" method="POST">
                    @csrf

                    {{-- Pilih Pasien --}}
                    <div class="mb-3">
                        <label for="patient_id" class="form-label">Pasien</label>
                        <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Pilih Terapis --}}
                    <div class="mb-3">
                        <label for="therapist_id" class="form-label">Terapis</label>
                        <select id="therapist_id" class="form-select">
                            <option value="">-- Pilih Terapis --</option>
                            @foreach($therapists as $therapist)
                                <option value="{{ $therapist->id }}">{{ $therapist->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pilih Tanggal Kunjungan --}}
                    <div class="mb-3">
                        <label for="selected_date" class="form-label">Pilih Tanggal Kunjungan</label>
                        <input type="text" id="selected_date" name="selected_date"
                            class="form-control @error('selected_date') is-invalid @enderror"
                            value="{{ old('selected_date') }}"
                            placeholder="Pilih tanggal dari jadwal terapis" readonly>
                        @error('selected_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <input type="hidden" name="schedule_id" id="schedule_id" value="{{ old('schedule_id') }}">

                    {{-- Jam Praktik --}}
                    <div class="mb-3">
                        <label class="form-label">Jam Praktik</label>
                        <div id="jam_praktik" class="form-control bg-light text-muted" readonly>-</div>
                    </div>

                    {{-- Status default --}}
                    <input type="hidden" name="status" value="terdaftar">
                    @error('status') <div class="text-danger small">{{ $message }}</div> @enderror

                    {{-- Catatan --}}
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan Tambahan</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
                        @error('notes') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/id.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let jadwalTerapis = @json($scheduleByTherapist); // Format: { therapist_id: { 'yyyy-mm-dd': {start, end, id} } }
    console.log("Jadwal Terapis:", jadwalTerapis);

    $(function () {
        $('#patient_id, #therapist_id').select2({ width: '100%' });

        const flat = flatpickr("#selected_date", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "l, d F Y",
            locale: "id",
            enable: [],
            onChange: function (selectedDates) {
                const therapistId = $('#therapist_id').val();
                const jadwal = jadwalTerapis[therapistId];
                const formattedDate = flat.formatDate(selectedDates[0], "Y-m-d");

                if (jadwal && jadwal[formattedDate]) {
                    const jam = jadwal[formattedDate];

                    $('#jam_praktik').text(`Pukul ${jam.start} s/d ${jam.end} WITA`);
                    $('#schedule_id').val(jam.id); //isi schedule_id

                    // 🔍 Debug log
                    console.log("Schedule ID:", jam.id);
                } else {
                    $('#jam_praktik').text('-');
                    $('#schedule_id').val('');

                    // 🔍 Debug log
                    console.warn("Jadwal tidak ditemukan untuk tanggal tersebut.");
                }
            }
        });

        $('#therapist_id').on('change', function () {
            const selected = $(this).val();

            if (jadwalTerapis[selected]) {
                const enabledDates = Object.keys(jadwalTerapis[selected]);
                flat.set('enable', enabledDates);

                console.log("Tanggal aktif:", enabledDates);

                // Jika sebelumnya user sudah memilih tanggal, cek ulang jam & schedule_id
                if (flat.selectedDates.length > 0) {
                    const selectedDate = flat.formatDate(flat.selectedDates[0], "Y-m-d");
                    const jam = jadwalTerapis[selected][selectedDate];

                    if (jam) {
                        $('#jam_praktik').text(`Pukul ${jam.start} s/d ${jam.end} WITA`);
                        $('#schedule_id').val(jam.id);

                        // 🔍 Debug log
                        console.log("Schedule ID dari terapis change:", jam.id);
                    } else {
                        $('#jam_praktik').text('-');
                        $('#schedule_id').val('');
                        console.warn("Tanggal dipilih tidak cocok dengan jadwal terapis ini.");
                    }
                } else {
                    $('#jam_praktik').text('-');
                    $('#schedule_id').val('');
                }
            } else {
                flat.clear();
                flat.set('enable', []);
                $('#jam_praktik').text('-');
                $('#schedule_id').val('');

                console.warn("Terapis tidak punya jadwal.");
            }
        });
    });
</script>

@endsection
