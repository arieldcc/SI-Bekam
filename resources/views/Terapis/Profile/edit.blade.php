@extends('layouts.main')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h5 class="mb-4">Pengaturan Profil Terapis</h5>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('terapis.profil.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $therapist->full_name) }}">
                    </div>

                    {{-- Spesialisasi --}}
                    <div class="mb-3">
                        <label class="form-label">Spesialisasi</label>
                        <input type="text" name="specialty" class="form-control" value="{{ old('specialty', $therapist->specialty) }}">
                    </div>

                    {{-- Telepon --}}
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $therapist->phone_number) }}">
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control">{{ old('address', $therapist->address) }}</textarea>
                    </div>

                    <hr>

                    <h6>Informasi Akun</h6>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label">Kata Sandi Baru</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Sandi</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
