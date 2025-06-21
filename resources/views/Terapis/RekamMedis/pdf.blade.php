<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekam Medis</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; vertical-align: top; }
        th { background-color: #f0f0f0; }
        h3 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h3>Laporan Rekam Medis</h3>
    <p>Terapis: {{ Auth::user()->name }} <br> Dicetak: {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pasien</th>
                <th>Tgl Kunjungan</th>
                <th>Keluhan</th>
                <th>Area Terapi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $i => $rec)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $rec->registration->patient->full_name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($rec->registration->visit_datetime)->format('d M Y') }}</td>
                    <td>{{ $rec->complaints ?? '-' }}</td>
                    <td>{{ $rec->therapy_area ?? '-' }}</td>
                    <td>{{ $rec->result_notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
