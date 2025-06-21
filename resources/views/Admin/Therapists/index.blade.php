@extends('layouts.main')

@section('title', 'Daftar Terapis')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>Daftar Terapis</h5>
            <a href="{{ route('admin.therapists.create') }}" class="btn btn-primary">+ Tambah Terapis</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover" id="therapists-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Spesialisasi</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Email Akun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
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
    const table = $('#therapists-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.therapists.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'full_name', name: 'full_name' },
            { data: 'specialty', name: 'specialty' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'address', name: 'address' },
            { data: 'user_email', name: 'user.email', orderable: false, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at', visible: false },
        ],
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[7, 'desc']]
    });

    window.deleteTherapist = function (id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak bisa dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('admin.therapists.destroy', ':id') }}".replace(':id', id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Terjadi kesalahan saat menghapus.');
                    return response.json();
                })
                .then(data => {
                    Swal.fire('Berhasil!', data.message || 'Terapis berhasil dihapus.', 'success');
                    table.ajax.reload();
                })
                .catch(error => {
                    Swal.fire('Gagal', error.message || 'Gagal menghapus data.', 'error');
                });
            }
        });
    }
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
