@extends('layouts.main')

@section('title', 'Kontak')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>Informasi Kontak</h5>
    <a href="{{ route('admin.contacts.create') }}" class="btn btn-primary">+ Tambah Kontak</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered" id="contacts-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>WhatsApp</th>
                    <th>Map</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    const table = $('#contacts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.contacts.index") }}',
        order: [[0, 'desc']],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'address', name: 'address' },
            {
                data: 'whatsapp_link',
                name: 'whatsapp_link',
                render: function(data) {
                    return data ? `<a href="${data}" target="_blank">Lihat</a>` : '-';
                }
            },
            {
                data: 'map_embed',
                name: 'map_embed',
                render: function(data) {
                    if (!data) return '-';
                    const encoded = btoa(data); // Encode HTML iframe ke base64
                    return `<a href="#" class="btn-preview-map" data-embed="${encoded}">Preview</a>`;
                }
            },
            { data: 'status', name: 'in_active', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        pageLength: 10
    });

    // Toggle aktif/nonaktif
    $(document).on('change', '.toggle-active', function () {
        const id = $(this).data('id');

        fetch('{{ route("admin.contacts.toggle", ":id") }}'.replace(':id', id), {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(async res => {
            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(errorText);
            }

            const data = await res.json();
            Swal.fire({
                icon: 'success',
                title: 'Status Diperbarui',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });

            table.ajax.reload(null, false);
        })
        .catch(err => {
            console.error('❌', err);
            Swal.fire('Gagal!', 'Terjadi kesalahan saat mengubah status.', 'error');
        });
    });

    // Tombol hapus
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data kontak akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('admin.contacts.destroy', ':id') }}".replace(':id', id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(async res => {
                    if (!res.ok) {
                        const errText = await res.text();
                        throw new Error(errText);
                    }

                    const data = await res.json();
                    Swal.fire('Berhasil!', data.message || 'Data berhasil dihapus.', 'success');
                    table.ajax.reload(null, false);
                })
                .catch(() => {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                });
            }
        });
    });

    // ✅ Tombol preview peta (tanpa onclick langsung, aman untuk iframe)
    $(document).on('click', '.btn-preview-map', function (e) {
        e.preventDefault();
        const encoded = $(this).data('embed');

        try {
            const decoded = atob(encoded); // decode base64 ke iframe HTML

            Swal.fire({
                title: 'Preview Map',
                html: '<div id="map-container"></div>',
                width: 800,
                didOpen: () => {
                    const container = document.getElementById('map-container');
                    container.innerHTML = decoded; // sisipkan iframe ke modal
                },
                confirmButtonText: 'Tutup'
            });

        } catch (err) {
            console.error('❌ Error saat decoding embed:', err);
            Swal.fire('Gagal', 'Embed map tidak valid.', 'error');
        }
    });
});
</script>

@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif
@endsection
