@extends('layouts.main')

@section('title', 'Daftar Terapi')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h5 class="mb-4">Formulir Pendaftaran Terapi</h5>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('pasien.registrasi.store') }}" method="POST">
                    @csrf

                    {{-- Nama Pasien --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Pasien</label>
                        <input type="text" class="form-control bg-light" value="{{ auth()->user()->full_name }}" readonly>
                    </div>

                    {{-- Terapis --}}
                    <div class="mb-3">
                        <label for="therapist_id" class="form-label">Terapis</label>
                        <select id="therapist_id" class="form-select">
                            <option value="">-- Pilih Terapis --</option>
                            @foreach($therapists as $therapist)
                                <option value="{{ $therapist->id }}">{{ $therapist->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-3">
                        <label for="selected_date" class="form-label">Tanggal Kunjungan</label>
                        <input type="text" id="selected_date" name="selected_date" class="form-control" placeholder="Pilih tanggal" readonly>
                    </div>

                    <input type="hidden" name="schedule_id" id="schedule_id">

                    <div class="mb-3">
                        <label class="form-label">Jam Praktik</label>
                        <div id="jam_praktik" class="form-control bg-light">-</div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pasien.registrasi.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- jQuery harus paling atas -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Flatpickr & Lokal Indonesia -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/id.js"></script>

<script>
    // Data jadwal dari controller
    let jadwalTerapis = @json($scheduleByTherapist);

    $(function () {
        // Inisialisasi Select2
        $('#therapist_id').select2({
            width: '100%',
            placeholder: 'Pilih Terapis'
        });

        // Inisialisasi Flatpickr
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
                    $('#jam_praktik').text(`Pukul ${jadwal[formattedDate].start} s/d ${jadwal[formattedDate].end} WITA`);
                    $('#schedule_id').val(jadwal[formattedDate].id);
                } else {
                    $('#jam_praktik').text('-');
                    $('#schedule_id').val('');
                }
            }
        });

        // Saat terapis dipilih, atur tanggal aktif
        $('#therapist_id').on('change', function () {
            const id = $(this).val();
            const jadwal = jadwalTerapis[id] || {};
            const enableDates = Object.keys(jadwal);

            flat.set('enable', enableDates);
            $('#jam_praktik').text('-');
            $('#schedule_id').val('');
        });
    });
</script>
@endsection
