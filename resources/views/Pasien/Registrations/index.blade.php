@extends('layouts.main')

@section('title', 'Riwayat Pendaftaran Saya')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>Riwayat Pendaftaran Terapi</h5>
    <a href="{{ route('pasien.registrasi.create') }}" class="btn btn-primary">+ Daftar Terapi</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered" id="riwayat-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Antrian</th>
                    <th>Tanggal Daftar</th>
                    <th>Jadwal</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    $('#riwayat-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("pasien.registrasi.index") }}',
        order: [[2, 'desc']],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'queue_number', name: 'queue_number' },
            { data: 'registration_date', name: 'registration_date' },
            { data: 'schedule_info', name: 'schedule_info', orderable: false, searchable: false },
            { data: 'status', name: 'status' },
            { data: 'notes', name: 'notes' },
        ],
        pageLength: 10
    });
});
</script>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif
@endsection
