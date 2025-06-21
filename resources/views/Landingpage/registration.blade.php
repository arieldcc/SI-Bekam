@extends('layouts.landingpage.app')

@section('title', 'Pendaftaran Pasien')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4">📋 Pendaftaran Pasien Mandiri</h2>

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('landing.registration.store') }}" method="POST" class="mx-auto" style="max-width: 600px;">
        @csrf

        <div class="mb-3">
            <label for="full_name" class="form-label">Nama Lengkap *</label>
            <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
            @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin *</label>
            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                <option value="">-- Pilih --</option>
                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Tanggal Lahir *</label>
            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}" required>
            @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Nomor Telepon / WA *</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" required>
            @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Alamat Lengkap *</label>
            <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="height" class="form-label">Tinggi Badan (cm)</label>
            <input type="number" name="height" id="height" class="form-control @error('height') is-invalid @enderror" value="{{ old('height') }}">
            @error('height') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="blood_type" class="form-label">Golongan Darah</label>
            <select name="blood_type" id="blood_type" class="form-select @error('blood_type') is-invalid @enderror">
                <option value="">-- Pilih --</option>
                <option value="A" {{ old('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                <option value="B" {{ old('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                <option value="AB" {{ old('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                <option value="O" {{ old('blood_type') == 'O' ? 'selected' : '' }}>O</option>
            </select>
            @error('blood_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label for="disease_history" class="form-label">Riwayat Penyakit (jika ada)</label>
            <textarea name="disease_history" id="disease_history" rows="2" class="form-control @error('disease_history') is-invalid @enderror">{{ old('disease_history') }}</textarea>
            @error('disease_history') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <hr class="my-4">
        <h5 class="text-center mb-3">🧾 Informasi Akun Login</h5>

        <div class="mb-3">
            <label for="email" class="form-label">Email Aktif *</label>
            <input type="email" name="email" id="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Password *</label>
            <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>


        <div class="d-grid">
            <button type="submit" class="btn btn-success btn-lg">Kirim Pendaftaran</button>
        </div>
    </form>
</div>
@endsection
