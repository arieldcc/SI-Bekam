@extends('layouts.main')

@section('title', 'Tambah Kontak')

@section('css')
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h5 class="mb-4">Tambah Informasi Kontak</h5>

        {{-- Alert error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Oops! Ada kesalahan saat mengisi form:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.contacts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" placeholder="Contoh: 082112345678">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="contoh@email.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Fisik</label>
                        <textarea name="address" id="address" rows="3"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Jl. Nama Jalan No. XX, Kota/Kab.">{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="whatsapp_link" class="form-label">Link WhatsApp</label>
                        <input type="text" name="whatsapp_link" id="whatsapp_link"
                               class="form-control @error('whatsapp_link') is-invalid @enderror"
                               value="{{ old('whatsapp_link') }}" placeholder="https://wa.me/62xxxxxxxxx">
                        @error('whatsapp_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="map_embed" class="form-label">Embed Google Maps</label>
                        <textarea name="map_embed" id="map_embed" rows="4"
                                  class="form-control @error('map_embed') is-invalid @enderror"
                                  placeholder="<iframe src='...'></iframe>">{{ old('map_embed') }}</textarea>
                        @error('map_embed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan Kontak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
