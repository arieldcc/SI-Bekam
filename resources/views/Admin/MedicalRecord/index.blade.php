@extends('layouts.main')

@section('title', 'Rekam Medis')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-3">📋 Daftar Rekam Medis Pasien</h4>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="rekamTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pasien</th>
                            <th>Tanggal Kunjungan</th>
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
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function () {
        $('#rekamTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.rekam-medis.index") }}',
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'patient_name', name: 'registration.patient.full_name' },
                { data: 'visit_date', name: 'registration.visit_datetime' },
                { data: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
