<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Praktek Bekam Sehat')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    {{-- Custom --}}
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fefefe;
        }
        header {
            background-color: #007b5e;
            color: #fff;
            padding: 1rem 0;
        }
        footer {
            background-color: #f8f9fa;
            padding: 2rem 0;
            margin-top: 4rem;
        }
        .nav-link {
            color: #fff !important;
            font-weight: 500;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
    </style>

    @yield('css')
</head>
<body>

{{-- Header --}}
<header>
    <div class="container d-flex justify-content-between align-items-center">
        <h3 class="mb-0">🏥 Bekam Sehat</h3>
        <nav>
            <a href="." class="nav-link d-inline-block me-3">Beranda</a>
            <a href="{{ route('jadwal') }}" class="nav-link d-inline-block me-3">Jadwal</a>
            {{-- <a href="#" class="nav-link d-inline-block me-3">Kontak</a> --}}
            @auth
                @php
                    $role = Auth::user()->role;
                @endphp

                @if ($role === 'pasien')
                    <a href="{{ route('pasien.dashboard') }}" class="btn btn-light btn-sm">Dashboard</a>
                @elseif ($role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">Dashboard</a>
                @elseif ($role === 'terapis')
                    <a href="{{ route('terapis.dashboard') }}" class="btn btn-light btn-sm">Dashboard</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-sm">Login</a>
                <a href="{{ route('landing.registration.create') }}" class="btn btn-light btn-sm">Daftar Mandiri</a>
            @endauth

        </nav>
    </div>
</header>

{{-- Konten --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
<footer>
    <div class="container text-center">
        <p class="mb-1">© {{ date('Y') }} Klinik Bekam Sehat. All rights reserved.</p>
        <small>FIKOM</small>
    </div>
</footer>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('js')
</body>
</html>
