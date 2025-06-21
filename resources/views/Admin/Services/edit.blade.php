@extends('layouts.main')

@section('title', 'Edit Layanan')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h5 class="mb-4">Edit Layanan</h5>

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
                <form action="{{ route('admin.services.update', $service->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Layanan --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Nama Layanan</label>
                        <input type="text" name="title" id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $service->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $service->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Ikon --}}
                    <div class="mb-3">
                        <label for="icon" class="form-label">Ikon</label>
                        <input type="text" name="icon" id="icon"
                               class="form-control @error('icon') is-invalid @enderror"
                               value="{{ old('icon', $service->icon) }}" placeholder="Contoh: fas fa-stethoscope">
                        @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Gunakan ikon FontAwesome. <a href="https://fontawesome.com/icons" target="_blank">Lihat daftar ikon</a>.</small>
                    </div>

                    {{-- Preview ikon --}}
                    @if(old('icon', $service->icon))
                        <div class="mb-3">
                            <label class="form-label d-block">Preview Ikon:</label>
                            <i class="{{ old('icon', $service->icon) }}" style="font-size: 24px;"></i>
                            <code>{{ old('icon', $service->icon) }}</code>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
