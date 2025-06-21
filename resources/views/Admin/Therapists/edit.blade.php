@extends('layouts.main')

@section('title', 'Edit Terapis')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h4 class="mb-4">Edit Data Terapis</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.therapists.update', $therapist->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $therapist->full_name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="specialty" class="form-label">Spesialisasi</label>
                        <input type="text" name="specialty" class="form-control" value="{{ old('specialty', $therapist->specialty) }}">
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">No. Telepon</label>
                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $therapist->phone_number) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $therapist->address) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Akun</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $therapist->user->email ?? '') }}" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.therapists.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif
@endsection
