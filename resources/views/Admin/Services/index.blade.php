@extends('layouts.main')

@section('title', 'Kelola Layanan')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>Daftar Layanan</h5>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">+ Tambah Layanan</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered" id="services-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th>Ikon</th>
                    <th>Status</th>
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
    $('#services-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.services.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'description', name: 'description' },
            { data: 'icon', name: 'icon', render: function(data) {
                return data ? `<i class="${data}"></i> <code>${data}</code>` : '-';
            }},
            { data: 'status', name: 'is_active', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    // Handler untuk toggle status layanan
    $(document).on('change', '.toggle-active', function () {
        const id = $(this).data('id');

        fetch('{{ route("admin.services.toggle", ":id") }}'.replace(':id', id), {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(async res => {
            if (!res.ok) throw new Error(await res.text());
            const data = await res.json();
            Swal.fire({
                icon: 'success',
                title: 'Status Diperbarui',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
            $('#services-table').DataTable().ajax.reload(null, false);
        })
        .catch(() => {
            Swal.fire('Gagal!', 'Terjadi kesalahan saat mengubah status.', 'error');
        });
    });

});

function deleteService(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Layanan ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('admin.services.destroy', ':id') }}".replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire('Berhasil!', data.message, 'success');
                $('#services-table').DataTable().ajax.reload();
            })
            .catch(() => Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error'));
        }
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
