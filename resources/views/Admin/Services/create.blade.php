@extends('layouts.main')

@section('title', 'Tambah Layanan')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h5 class="mb-4">Tambah Layanan Baru</h5>

        {{-- Tampilkan error validasi jika ada --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Oops! Ada kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf

                    {{-- Nama Layanan --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Nama Layanan</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Ikon (opsional) --}}
                    <div class="mb-3">
                        <label for="icon" class="form-label">Ikon (opsional)</label>
                        <input type="text" name="icon" id="icon" value="{{ old('icon') }}"
                               class="form-control @error('icon') is-invalid @enderror" placeholder="Contoh: fas fa-heart">
                        @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Gunakan ikon FontAwesome. <a href="https://fontawesome.com/icons" target="_blank">Lihat daftar ikon</a>.</small>
                    </div>

                    {{-- Tampilkan preview ikon jika ada --}}
                    @if(old('icon'))
                        <div class="mb-3">
                            <label class="form-label d-block">Preview Ikon:</label>
                            <i class="{{ old('icon') }}" style="font-size: 24px;"></i> <code>{{ old('icon') }}</code>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan Layanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
