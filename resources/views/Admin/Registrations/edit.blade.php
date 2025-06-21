@extends('layouts.main')

@section('title', 'Edit Pendaftaran Pasien')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h5 class="mb-4">Edit Pendaftaran Pasien</h5>

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
                <form action="{{ route('admin.registrations.update', $registration->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Pasien --}}
                    <div class="mb-3">
                        <label for="patient_id" class="form-label">Pasien</label>
                        <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror">
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $registration->patient_id == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Terapis --}}
                    <div class="mb-3">
                        <label for="therapist_id" class="form-label">Terapis</label>
                        <select id="therapist_id" class="form-select">
                            @foreach($schedules->groupBy('therapist_id') as $therapistId => $scheds)
                                <option value="{{ $therapistId }}" {{ $registration->schedule->therapist_id == $therapistId ? 'selected' : '' }}>
                                    {{ $scheds->first()->therapist->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Kunjungan --}}
                    <div class="mb-3">
                        <label for="selected_date" class="form-label">Tanggal Kunjungan</label>
                        <input type="text" id="selected_date" name="selected_date"
                               class="form-control @error('selected_date') is-invalid @enderror"
                               value="{{ old('selected_date', \Carbon\Carbon::parse($registration->visit_datetime)->format('Y-m-d')) }}"
                               readonly>
                        @error('selected_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Jam Praktik --}}
                    <div class="mb-3">
                        <label class="form-label">Jam Praktik</label>
                        <div id="jam_praktik" class="form-control bg-light text-muted" readonly>-</div>
                    </div>

                    {{-- Hidden schedule_id --}}
                    <input type="hidden" name="schedule_id" id="schedule_id" value="{{ old('schedule_id', $registration->schedule_id) }}">
                    @error('schedule_id') <div class="text-danger small">{{ $message }}</div> @enderror

                    {{-- Status --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            @foreach(['terdaftar', 'selesai', 'batal'] as $status)
                                <option value="{{ $status }}" {{ $registration->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes', $registration->notes) }}</textarea>
                        @error('notes') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
    let jadwalTerapis = @json($scheduleByTherapist);
    console.log("📦 Jadwal Terapis (Edit):", jadwalTerapis);

    $(function () {
        $('#patient_id, #therapist_id, #status').select2({ width: '100%' });

        const defaultDate = "{{ \Carbon\Carbon::parse($registration->visit_datetime)->format('Y-m-d') }}";
        const therapistIdInit = $('#therapist_id').val();

        const flat = flatpickr("#selected_date", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "l, d F Y",
            locale: "id",
            defaultDate: defaultDate,
            enable: [defaultDate], // ⬅️ pastikan tanggal default tetap aktif
            onChange: function (selectedDates) {
                const therapistId = $('#therapist_id').val();
                const jadwal = jadwalTerapis[therapistId];
                const selectedDate = selectedDates.length > 0
                    ? flat.formatDate(selectedDates[0], "Y-m-d")
                    : null;

                if (selectedDate && jadwal && jadwal[selectedDate]) {
                    const jam = jadwal[selectedDate];
                    $('#jam_praktik').text(`Pukul ${jam.start} s/d ${jam.end} WITA`);
                    $('#schedule_id').val(jam.id);
                    console.log("✅ Schedule ID onChange:", jam.id);
                } else {
                    $('#jam_praktik').text('-');
                    $('#schedule_id').val('');
                    console.warn("⚠️ Tidak ada jadwal ditemukan pada tanggal tersebut.");
                }
            }
        });

        $('#therapist_id').on('change', function () {
            const therapistId = $(this).val();
            const jadwal = jadwalTerapis[therapistId];

            if (jadwal) {
                const enabledDates = Object.keys(jadwal);

                // Gabungkan tanggal default agar tetap bisa dipilih meski tidak dalam jadwal
                if (!enabledDates.includes(defaultDate)) {
                    enabledDates.push(defaultDate);
                }

                flat.set('enable', enabledDates);
                console.log("📅 Tanggal tersedia:", enabledDates);

                const selectedDate = flat.selectedDates.length > 0
                    ? flat.formatDate(flat.selectedDates[0], "Y-m-d")
                    : null;

                if (selectedDate && jadwal[selectedDate]) {
                    const jam = jadwal[selectedDate];
                    $('#jam_praktik').text(`Pukul ${jam.start} s/d ${jam.end} WITA`);
                    $('#schedule_id').val(jam.id);
                    console.log("🔁 Schedule ID (therapist change):", jam.id);
                } else {
                    $('#jam_praktik').text('-');
                    $('#schedule_id').val('');
                }
            } else {
                flat.set('enable', [defaultDate]);
                $('#jam_praktik').text('-');
                $('#schedule_id').val('');
                console.warn("⚠️ Terapis tidak memiliki jadwal.");
            }
        });

        // Trigger update on page load
        $('#therapist_id').trigger('change');

        // Set initial jam_praktik & schedule_id
        const jadwalDefault = jadwalTerapis[therapistIdInit];
        const selectedDateDefault = defaultDate;

        if (jadwalDefault && selectedDateDefault && jadwalDefault[selectedDateDefault]) {
            const jam = jadwalDefault[selectedDateDefault];
            $('#jam_praktik').text(`Pukul ${jam.start} s/d ${jam.end} WITA`);
            $('#schedule_id').val(jam.id);
            console.log("🚀 Initial Load Schedule ID:", jam.id);
        } else {
            $('#jam_praktik').text('-');
            $('#schedule_id').val('');
        }
    });
</script>
@endsection
