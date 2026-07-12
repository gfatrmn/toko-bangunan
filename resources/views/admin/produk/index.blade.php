@extends('admin.layout')

@section('title', 'Produk - BuildNest Admin')

@section('page_title', 'Produk')

@section('content')
<div class="card-dashboard mb-4">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-3">
            <span class="fw700 fs-6">Daftar Produk</span>
            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fs-7 fw500">{{ $produk->total() }} item</span>
        </div>
        <button type="button" class="btn btn-primary btn-sm px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#tambahProdukModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </button>
    </div>
    <div class="card-body p-0">
        {{-- Filter & Search --}}
        <div class="px-4 py-3 border-bottom" style="background:#fafbfc;">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-lg-4 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama produk..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <select name="kategori" class="form-select form-select-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-secondary px-3 rounded-pill"><i class="bi bi-funnel me-1"></i> Filter</button>
                </div>
                <div class="col-auto">
                    @if(request()->anyFilled(['search','kategori']))
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-sm btn-outline-danger px-3 rounded-pill"><i class="bi bi-x-circle me-1"></i> Reset</a>
                    @endif
                </div>
            </form>
        </div>

        @if($produk->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:44px;" class="ps-4">Gbr</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center" style="width:130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produk as $p)
                    <tr>
                        <td class="ps-4">
                            @if($p->gambar)
                                <div class="product-thumb">
                                    <img src="{{ asset('storage/' . $p->gambar) }}" alt="{{ $p->nama }}">
                                </div>
                            @else
                                <div class="product-thumb product-thumb-empty">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw600">{{ $p->nama }}</div>
                            @if($p->merek)
                                <small class="text-muted">{{ $p->merek }}</small>
                            @endif
                        </td>
                        <td><span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3 py-1 fw500">{{ $p->kategoriRelasi->nama ?? '—' }}</span></td>
                        <td class="text-end fw700" style="color:#0f172a;">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($p->stok <= 0)
                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-1 fw500">Habis</span>
                            @elseif($p->stok <= 5)
                                <span class="badge rounded-pill bg-warning bg-opacity-15 text-warning px-3 py-1 fw500">{{ $p->stok }} tersisa</span>
                            @else
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1 fw500">{{ $p->stok }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <button type="button" class="btn btn-sm btn-outline-warning btn-edit-produk rounded-circle"
                                    data-id="{{ $p->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editProdukModal"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk "{{ $p->nama }}"?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
            <small class="text-muted">Menampilkan {{ $produk->firstItem() }}–{{ $produk->lastItem() }} dari {{ $produk->total() }}</small>
            <div class="pagination-wrap">{{ $produk->links() }}</div>
        </div>
        @else
        <div class="text-center py-5">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h6 class="fw700 mt-3">Belum Ada Produk</h6>
                <p class="text-muted small mb-3">Mulai tambahkan produk pertama Anda.</p>
                <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#tambahProdukModal">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal Tambah Produk --}}
<div class="modal fade" id="tambahProdukModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 px-4 pt-4 pb-0">
                <h5 class="modal-title fw700"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 pt-3">
                    <div class="row g-3">
                        {{-- Kolom Kiri --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw600 small">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control form-control-sm" required placeholder="Masukkan nama produk">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw600 small">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select form-select-sm" required>
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw600 small">Merek</label>
                                <input type="text" name="merek" class="form-control form-control-sm" placeholder="contoh: Sika, Axton">
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw600 small">Harga <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light fw500">Rp</span>
                                        <input type="number" name="harga" class="form-control" required min="0" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw600 small">Stok <span class="text-danger">*</span></label>
                                    <input type="number" name="stok" class="form-control form-control-sm" required min="0" placeholder="0">
                                </div>
                            </div>
                            <div class="row g-2 mt-1">
                                <div class="col-6">
                                    <label class="form-label fw600 small">Satuan</label>
                                    <input type="text" name="satuan" class="form-control form-control-sm" placeholder="pcs, kg, m2">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw600 small">Min. Pembelian</label>
                                    <input type="number" name="min_pembelian" class="form-control form-control-sm" min="1" placeholder="1">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw600 small">Berat (gram)</label>
                                <input type="number" name="berat" class="form-control form-control-sm" min="0" placeholder="1000">
                            </div>
                        </div>
                        {{-- Kolom Kanan --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw600 small">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control form-control-sm" rows="4" placeholder="Deskripsi produk..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw600 small">Gambar</label>
                                <div class="upload-box" id="uploadBoxTambah">
                                    <input type="file" name="gambar" class="form-control" accept="image/*" id="fileInputTambah" style="display:none;">
                                    <div class="upload-placeholder text-center" id="uploadPlaceholderTambah">
                                        <i class="bi bi-cloud-arrow-up fs-2 text-primary"></i>
                                        <p class="small text-muted mb-0 mt-2">Klik untuk upload gambar</p>
                                        <p class="small text-muted">Format: JPG, PNG, WebP (max 2MB)</p>
                                    </div>
                                    <div class="upload-preview text-center" id="uploadPreviewTambah" style="display:none;">
                                        <img id="previewTambah" src="" alt="Preview" class="img-fluid rounded" style="max-height:160px;object-fit:contain;">
                                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2 rounded-pill" id="btnGantiTambah"><i class="bi bi-arrow-repeat me-1"></i> Ganti</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 rounded-pill"><i class="bi bi-check-lg me-1"></i> Tambah Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Produk --}}
<div class="modal fade" id="editProdukModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 px-4 pt-4 pb-0">
                <h5 class="modal-title fw700"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditProduk" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 pt-3">
                    <div class="row g-3">
                        {{-- Kolom Kiri --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw600 small">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control form-control-sm" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw600 small">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select form-select-sm" required>
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw600 small">Merek</label>
                                <input type="text" name="merek" class="form-control form-control-sm" placeholder="contoh: Sika, Axton">
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw600 small">Harga <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light fw500">Rp</span>
                                        <input type="number" name="harga" class="form-control" required min="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw600 small">Stok <span class="text-danger">*</span></label>
                                    <input type="number" name="stok" class="form-control form-control-sm" required min="0">
                                </div>
                            </div>
                            <div class="row g-2 mt-1">
                                <div class="col-6">
                                    <label class="form-label fw600 small">Satuan</label>
                                    <input type="text" name="satuan" class="form-control form-control-sm" placeholder="pcs, kg, m2">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw600 small">Min. Pembelian</label>
                                    <input type="number" name="min_pembelian" class="form-control form-control-sm" min="1" placeholder="1">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw600 small">Berat (gram)</label>
                                <input type="number" name="berat" class="form-control form-control-sm" min="0" placeholder="1000">
                            </div>
                        </div>
                        {{-- Kolom Kanan --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw600 small">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control form-control-sm" rows="4"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw600 small">Gambar</label>
                                <div class="upload-box" id="uploadBoxEdit">
                                    <input type="file" name="gambar" class="form-control" accept="image/*" id="fileInputEdit" style="display:none;">
                                    <div class="upload-placeholder text-center" id="uploadPlaceholderEdit" style="display:none;">
                                        <i class="bi bi-cloud-arrow-up fs-2 text-primary"></i>
                                        <p class="small text-muted mb-0 mt-2">Klik untuk upload gambar</p>
                                        <p class="small text-muted">Format: JPG, PNG, WebP (max 2MB)</p>
                                    </div>
                                    <div class="upload-preview text-center" id="uploadPreviewEdit">
                                        <img id="previewEdit" src="" alt="Preview" class="img-fluid rounded" style="max-height:160px;object-fit:contain;">
                                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2 rounded-pill" id="btnGantiEdit"><i class="bi bi-arrow-repeat me-1"></i> Ganti Gambar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 rounded-pill"><i class="bi bi-check-lg me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>

/* ─── Table enhancements ─── */
.table thead th {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
    padding: 12px 16px;
}
.table td {
    padding: 14px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}
.table-hover tbody tr:hover {
    background: #f8fafc;
}

/* ─── Product thumbnail ─── */
.product-thumb {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    overflow: hidden;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
}
.product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.product-thumb-empty {
    color: #94a3b8;
    font-size: 1.1rem;
}

/* ─── Empty state ─── */
.empty-state {
    padding: 48px 20px;
}
.empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #eef2ff;
    color: #6366f1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto;
}

/* ─── Upload box ─── */
.upload-box {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 20px;
    cursor: pointer;
    transition: all .2s;
    background: #fafbfc;
}
.upload-box:hover {
    border-color: #6366f1;
    background: #eef2ff;
}

/* ─── Badge improvements ─── */
.badge.bg-opacity-10 { background-opacity: 0.1 !important; }
.badge.bg-opacity-15 { background-opacity: 0.15 !important; }

/* ─── Pagination ─── */
.pagination-wrap {
    flex-shrink: 0;
    white-space: nowrap;
}
.pagination-wrap nav {
    display: contents !important;
}
.pagination-wrap .pagination {
    margin: 0 !important;
}
.pagination-wrap .page-link {
    padding: 0 .3rem !important;
    font-size: .6rem !important;
    line-height: 1.8 !important;
    border-radius: 3px !important;
    border: 1px solid #e2e8f0 !important;
    color: #475569 !important;
    background: #fff !important;
}
.pagination-wrap .page-item.active .page-link {
    background: #2563eb !important;
    border-color: #2563eb !important;
    color: #fff !important;
}
.pagination-wrap .page-item.disabled .page-link {
    color: #cbd5e1 !important;
    background: #f8fafc !important;
    border-color: #e2e8f0 !important;
}

/* ─── Form labels ─── */
.form-label { margin-bottom: 4px; }

/* ─── Modal adjustments ─── */
.modal-header .btn-close:focus { box-shadow: none; }

/* ─── Make sure modal footer buttons are visible ─── */
.modal-footer {
    flex-wrap: wrap;
    gap: 8px;
}

/* ─── Small helper ─── */
.fs-7 { font-size: .8rem; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Upload box interaction (Tambah) ──
    const uploadBoxTambah = document.getElementById('uploadBoxTambah');
    const fileInputTambah = document.getElementById('fileInputTambah');
    const placeholderTambah = document.getElementById('uploadPlaceholderTambah');
    const previewContainerTambah = document.getElementById('uploadPreviewTambah');
    const previewTambah = document.getElementById('previewTambah');
    const btnGantiTambah = document.getElementById('btnGantiTambah');

    if (uploadBoxTambah) {
        uploadBoxTambah.addEventListener('click', function(e) {
            if (e.target !== btnGantiTambah) fileInputTambah.click();
        });
        fileInputTambah.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    previewTambah.src = ev.target.result;
                    placeholderTambah.style.display = 'none';
                    previewContainerTambah.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
        if (btnGantiTambah) {
            btnGantiTambah.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInputTambah.value = '';
                fileInputTambah.click();
            });
        }
    }

    // ── Upload box interaction (Edit) ──
    const uploadBoxEdit = document.getElementById('uploadBoxEdit');
    const fileInputEdit = document.getElementById('fileInputEdit');
    const placeholderEdit = document.getElementById('uploadPlaceholderEdit');
    const previewContainerEdit = document.getElementById('uploadPreviewEdit');
    const previewEdit = document.getElementById('previewEdit');
    const btnGantiEdit = document.getElementById('btnGantiEdit');

    if (uploadBoxEdit) {
        uploadBoxEdit.addEventListener('click', function(e) {
            if (e.target !== btnGantiEdit) fileInputEdit.click();
        });
        fileInputEdit.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    previewEdit.src = ev.target.result;
                    placeholderEdit.style.display = 'none';
                    previewContainerEdit.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
        if (btnGantiEdit) {
            btnGantiEdit.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInputEdit.value = '';
                fileInputEdit.click();
            });
        }
    }

    // ── Edit produk: fetch & populate ──
    const form = document.getElementById('formEditProduk');

    document.querySelectorAll('.btn-edit-produk').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            form.action = `/admin/produk/${id}`;

            // Reset
            form.reset();
            previewEdit.src = '';
            previewContainerEdit.style.display = 'block';
            placeholderEdit.style.display = 'none';

            fetch(`/admin/produk/${id}/show`)
                .then(res => {
                    if (!res.ok) throw new Error('Network error');
                    return res.json();
                })
                .then(data => {
                    form.querySelector('[name="nama"]').value = data.nama || '';
                    form.querySelector('[name="kategori"]').value = data.kategori || '';
                    form.querySelector('[name="harga"]').value = data.harga || '';
                    form.querySelector('[name="stok"]').value = data.stok || '';
                    form.querySelector('[name="satuan"]').value = data.satuan || '';
                    form.querySelector('[name="min_pembelian"]').value = data.min_pembelian || '';
                    form.querySelector('[name="berat"]').value = data.berat || '';
                    form.querySelector('[name="merek"]').value = data.merek || '';
                    form.querySelector('[name="deskripsi"]').value = data.deskripsi || '';

                    if (data.gambar) {
                        previewEdit.src = '/storage/' + data.gambar;
                        previewContainerEdit.style.display = 'block';
                        placeholderEdit.style.display = 'none';
                    } else {
                        previewEdit.src = '';
                        previewContainerEdit.style.display = 'none';
                        placeholderEdit.style.display = 'block';
                    }
                })
                .catch(err => {
                    alert('Gagal memuat data produk.');
                    console.error(err);
                });
        });
    });

    // ── Reset modal tambah ketika ditutup ──
    const tambahModal = document.getElementById('tambahProdukModal');
    if (tambahModal) {
        tambahModal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) form.reset();
            previewTambah.src = '';
            placeholderTambah.style.display = 'block';
            previewContainerTambah.style.display = 'none';
            fileInputTambah.value = '';
        });
    }
});
</script>
@endpush
