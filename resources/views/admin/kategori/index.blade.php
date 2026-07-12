@extends('admin.layout')

@section('title', 'Kategori - BuildNest Admin')

@section('page_title', 'Kategori')

@section('content')
<div class="row g-4">
    {{-- Form Tambah --}}
    <div class="col-md-5">
        <div class="card-dashboard">
            <div class="card-header">Tambah Kategori</div>
            <div class="card-body p-3">
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Nama kategori..." value="{{ old('nama') }}" required>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</button>
                    </div>
                    @error('nama')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </form>
            </div>
        </div>
    </div>

    {{-- Daftar Kategori --}}
    <div class="col-md-7">
        <div class="card-dashboard">
            <div class="card-header">Daftar Kategori</div>
            <div class="card-body">
                @if($kategori->count())
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th class="text-center">Jumlah Produk</th>
                                <th style="width:160px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategori as $kat)
                            <tr>
                                <td><strong>{{ $kat->nama }}</strong></td>
                                <td class="text-center">{{ $kat->produk_count }}</td>
                                <td>
                                    {{-- Edit inline --}}
                                    <button class="btn btn-sm btn-outline-warning" onclick="editKategori({{ $kat->id }}, '{{ $kat->nama }}')"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('admin.kategori.destroy', $kat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori {{ $kat->nama }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $kategori->links() }}</div>
                @else
                <div class="text-center py-4 text-muted">Belum ada kategori.</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Kategori --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editForm" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="nama" id="editNama" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editKategori(id, nama) {
    document.getElementById('editForm').action = '{{ url("admin/kategori") }}/' + id;
    document.getElementById('editNama').value = nama;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endpush
