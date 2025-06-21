@extends('layouts.main')

@section('title', 'Tambah Terapis')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h4 class="mb-4">Form Tambah Terapis</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.therapists.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="specialty" class="form-label">Spesialisasi (opsional)</label>
                        <input type="text" name="specialty" class="form-control" value="{{ old('specialty') }}">
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">No. Telepon</label>
                        <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                    </div>

                    <hr>

                    <h6 class="mt-4">Akun Login Terapis</h6>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email/Login</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.therapists.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Terapis</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
