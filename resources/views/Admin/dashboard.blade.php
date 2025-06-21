@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title">Total Pasien</h5>
                        <h2>{{ $totalPatients }}</h2>
                    </div>
                    <i class="bi bi-people-fill fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title">Jadwal Praktik</h5>
                        <h2>{{ $totalSchedules }}</h2>
                    </div>
                    <i class="bi bi-calendar2-check-fill fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title">Pendaftaran</h5>
                        <h2>{{ $totalRegistrations }}</h2>
                    </div>
                    <i class="bi bi-journal-check fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title">Rekam Medis</h5>
                        <h2>{{ $totalMedicalRecords }}</h2>
                    </div>
                    <i class="bi bi-clipboard2-pulse fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart --}}
<div class="card">
    <div class="card-body">
        <h5 class="card-title">📈 Statistik Pendaftaran Pasien per Bulan ({{ now()->year }})</h5>
        <canvas id="registrationsChart" height="100"></canvas>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('registrationsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ],
            datasets: [{
                label: 'Jumlah Pendaftaran',
                data: [
                    @for($i = 1; $i <= 12; $i++)
                        {{ $registrationsByMonth[$i] ?? 0 }},
                    @endfor
                ],
                backgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision:0,
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
