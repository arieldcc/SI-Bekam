@extends('layouts.main')

@section('title', 'Daftar Pasien')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-4">🧑‍⚕️ Daftar Pasien Saya</h4>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover" id="pasienTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Tgl Kunjungan</th>
                            <th>Jam</th>
                            <th>Antrian</th>
                            <th>Status</th>
                            <th>Catatan</th>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function updateField(id, field, value) {
        fetch('{{ route("terapis.pasien.update", ":id") }}'.replace(':id', id), {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ [field]: value })
        })
        .then(res => res.json())
        .then(data => {
            console.log('✅ Update berhasil:', data);
            Swal.fire('Berhasil', data.message || 'Data berhasil diperbarui', 'success');
        })
        .catch(err => {
            console.error('❌ Gagal update:', err);
            Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui data', 'error');
        });
    }

    $(function () {
        $('#pasienTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("terapis.pasien.index") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'patient_name', name: 'patient.name' },
                { data: 'visit_date', name: 'visit_datetime' },
                { data: 'time', name: 'schedule.start_datetime' },
                { data: 'queue', name: 'queue_number' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'notes', name: 'notes', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endsection
