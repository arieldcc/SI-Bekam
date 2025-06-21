@extends('layouts.main')

@section('title', 'Jadwal Saya')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-4">🗓️ Jadwal Praktik Saya</h4>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover" id="jadwalTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Hari / Tanggal</th>
                            <th>Jam Praktik</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
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
<!-- jQuery terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables dan Bootstrap -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
function updateStatus(id, newStatus) {
    fetch('{{ route("terapis.jadwal.updateStatus", ":id") }}'.replace(':id', id), {
        method: 'PATCH',
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

<!-- Script inisialisasi -->
<script>
    $(function () {
        $('#jadwalTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("terapis.jadwal.index") }}',
                error: function (xhr) {
                    console.error('❌ Gagal mengambil data:', xhr.responseText);
                    alert('Gagal mengambil data jadwal. Cek konsol.');
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'schedule_day', name: 'schedule_day' },
                { data: 'time_range', name: 'time_range' },
                { data: 'description', name: 'description' },
                { data: 'status', name: 'status', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection

