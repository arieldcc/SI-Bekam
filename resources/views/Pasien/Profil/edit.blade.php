@extends('layouts.main')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h5 class="mb-4">Pengaturan Profil</h5>

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
                <form method="POST" action="{{ route('pasien.profil.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $patient->full_name) }}">
                    </div>

                    {{-- Gender --}}
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-select">
                            <option value="L" {{ $patient->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $patient->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $patient->date_of_birth) }}">
                    </div>

                    {{-- No Telepon --}}
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $patient->phone_number) }}">
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control">{{ old('address', $patient->address) }}</textarea>
                    </div>

                    {{-- Tinggi Badan --}}
                    <div class="mb-3">
                        <label class="form-label">Tinggi Badan (cm)</label>
                        <input type="number" name="height" class="form-control" value="{{ old('height', $patient->height) }}">
                    </div>

                    {{-- Golongan Darah --}}
                    <div class="mb-3">
                        <label class="form-label">Golongan Darah</label>
                        <select name="blood_type" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(['A', 'B', 'AB', 'O'] as $gol)
                                <option value="{{ $gol }}" {{ $patient->blood_type == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Riwayat Penyakit --}}
                    <div class="mb-3">
                        <label class="form-label">Riwayat Penyakit</label>
                        <textarea name="disease_history" class="form-control">{{ old('disease_history', $patient->disease_history) }}</textarea>
                    </div>

                    <hr>

                    <h6>Informasi Akun</h6>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                    </div>

                    {{-- Password Baru --}}
                    <div class="mb-3">
                        <label class="form-label">Kata Sandi Baru (opsional)</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah kata sandi.</small>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Kata Sandi</label>
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
