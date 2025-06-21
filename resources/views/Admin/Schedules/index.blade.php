@extends('layouts.main')

@section('title', 'Jadwal Terapis')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Jadwal Terapis</h5>
    <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">+ Tambah Jadwal</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered" id="schedule-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Terapis</th>
                    <th>Hari / Tanggal</th>
                    <th>Jam</th>
                    <th>Keterangan</th>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function () {
        $('#schedule-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.schedules.index") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'therapist_name', name: 'therapist.full_name' },
                { data: 'schedule_day', name: 'schedule_day' },
                { data: 'time_range', name: 'time_range' },
                { data: 'description', name: 'description' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });

    function deleteSchedule(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Jadwal ini akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('admin.schedules.destroy', ':id') }}".replace(':id', id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Berhasil!', data.message || 'Jadwal berhasil dihapus.', 'success');
                    $('#schedule-table').DataTable().ajax.reload();
                })
                .catch(() => Swal.fire('Gagal!', 'Gagal menghapus jadwal.', 'error'));
            }
        });
    }
</script>

<script>
function updateStatus(id, newStatus) {
    fetch('{{ route("admin.schedules.update-status", ":id") }}'.replace(':id', id), {
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
        $('#schedule-table').DataTable().ajax.reload(null, false);
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
