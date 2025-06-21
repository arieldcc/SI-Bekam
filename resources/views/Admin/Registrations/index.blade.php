@extends('layouts.main')

@section('title', 'Pendaftaran Pasien')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>Pendaftaran Pasien</h5>
    <a href="{{ route('admin.registrations.create') }}" class="btn btn-primary">+ Tambah Pendaftaran</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered" id="registrations-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pasien</th>
                    <th>No Antrian</th>
                    <th>Tanggal Daftar</th>
                    <th>Jadwal</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
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
    $('#registrations-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.registrations.index") }}',
        order: [[2, 'desc']],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'patient_name', name: 'patient.full_name' },
            { data: 'queue_number', name: 'queue_number' },
            { data: 'registration_date', name: 'registration_date' },
            { data: 'schedule_info', name: 'schedule_info', orderable: false, searchable: false },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'notes', name: 'notes' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100]
    });
});

function deleteRegistration(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Pendaftaran akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('admin.registrations.destroy', ':id') }}".replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire('Berhasil!', data.message || 'Data berhasil dihapus.', 'success');
                $('#registrations-table').DataTable().ajax.reload();
            })
            .catch(() => Swal.fire('Gagal!', 'Gagal menghapus data.', 'error'));
        }
    });
}

function updateStatus(id, newStatus) {
    fetch('{{ route("admin.registrations.update-status", ":id") }}'.replace(':id', id), {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire('Berhasil!', data.message || 'Status berhasil diperbarui.', 'success');
        $('#registrations-table').DataTable().ajax.reload(null, false);
    })
    .catch(() => {
        Swal.fire('Gagal!', 'Terjadi kesalahan saat memperbarui status.', 'error');
    });
}

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
