@extends('layouts.main')

@section('title', 'Daftar Pasien')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>Daftar Pasien</h5>
            <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">+ Tambah Pasien</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover" id="patients-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Email Akun</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>Status</th>
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
    const table = $('#patients-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.patients.index") }}',
        order: [[1, 'desc']],
        columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'full_name', name: 'full_name' },
                { data: 'user_email', name: 'user.email' },
                { data: 'gender', name: 'gender' },
                { data: 'date_of_birth', name: 'date_of_birth' },
                { data: 'phone_number', name: 'phone_number' },
                { data: 'address', name: 'address' },
                { data: 'status_toggle', name: 'status_toggle', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100]
    });

    window.deletePatient = function (id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data pasien yang dihapus tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = '{{ route("admin.patients.destroy", ":id") }}'.replace(':id', id);

                fetch(url, {
                    method: 'POST', // Spoofing DELETE
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' }) // Method spoofing
                })
                .then(response => {
                    if (!response.ok) throw new Error('Terjadi kesalahan saat menghapus.');
                    return response.json();
                })
                .then(data => {
                    Swal.fire('Berhasil!', data.message || 'Pasien berhasil dihapus.', 'success');
                    $('#patients-table').DataTable().ajax.reload(null, false);
                })
                .catch(error => {
                    Swal.fire('Gagal', error.message || 'Gagal menghapus data.', 'error');
                });
            }
        });
    }

    window.toggleStatus = function (id, isActive) {
        const action = isActive ? 'nonaktifkan' : 'aktifkan';
        const confirmText = isActive ? 'Ya, nonaktifkan!' : 'Ya, aktifkan!';

        Swal.fire({
            title: `Yakin ingin ${action} akun ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = '{{ route("admin.patients.toggle-status", ":id") }}'.replace(':id', id);

                fetch(url, {
                    method: 'POST', // pakai spoofing karena beberapa server tolak PUT langsung
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'PUT' }) // spoofing
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengubah status.');
                    return response.json();
                })
                .then(data => {
                    Swal.fire('Berhasil!', data.message || 'Status berhasil diperbarui.', 'success');
                    $('#patients-table').DataTable().ajax.reload(null, false);
                })
                .catch(error => {
                    Swal.fire('Gagal', error.message || 'Terjadi kesalahan.', 'error');
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
