@extends('layouts.landingpage.app')

@section('title', 'Beranda Klinik Bekam')

@section('content')
    <div class="container py-5">

    {{-- Hero --}}
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Selamat Datang di Klinik Bekam Sehat</h1>
        <p class="lead">Kami menyediakan layanan terapi bekam dan kesehatan Islami profesional.</p>
        @auth
                @php
                    $role = Auth::user()->role;
                @endphp

                @if ($role === 'pasien')
                    <a href="{{ route('pasien.dashboard') }}" class="btn btn-primary btn-lg mt-3">Dashboard</a>
                @elseif ($role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg mt-3">Dashboard</a>
                @elseif ($role === 'terapis')
                    <a href="{{ route('terapis.dashboard') }}" class="btn btn-primary btn-lg mt-3">Dashboard</a>
                @endif
            @else
                <a href="{{ route('landing.registration.create') }}" class="btn btn-primary btn-lg mt-3">Daftar Sekarang</a>
            @endauth
    </div>

    {{-- Info Layanan --}}
    <div class="row text-center mb-5">
        @forelse ($services as $service)
            <div class="col-md-4 mb-4">
                @if ($service->icon)
                    <div class="mb-2">
                        <i class="{{ $service->icon }} fs-1 text-primary"></i>
                    </div>
                @endif
                <h4>{{ $service->title }}</h4>
                <p>{{ $service->description }}</p>
            </div>
        @empty
            <p class="text-muted">Belum ada layanan yang tersedia.</p>
        @endforelse
    </div>

    {{-- Alamat --}}
    @if($contact)
    <div class="text-center mt-5">
        <h5>Alamat</h5>
        <p>{{ $contact->address ?? '-' }}</p>
        <p>Telp: {{ $contact->phone ?? '-' }}</p>

        @if ($contact->whatsapp_link)
            <p>
                <a href="{{ $contact->whatsapp_link }}" target="_blank" class="btn btn-success btn-sm">
                    Hubungi via WhatsApp
                </a>
            </p>
        @endif

        @if ($contact->map_embed)
            <div class="ratio ratio-16x9">
                {!! $contact->map_embed !!}
            </div>
        @else
            <p class="text-muted">Peta lokasi belum tersedia.</p>
        @endif
    </div>
    @endif


    </div>
</div>
@endsection
