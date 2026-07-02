@extends('admin.layout')

@section('title', 'Konfirmasi Pembayaran - BuildNest')
@section('page_title', 'Konfirmasi Pembayaran')

@section('content')
{{-- Alert sukses/error --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Statistik Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-4" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-card d-flex align-items-center" style="border-left: 4px solid #f59e0b;">
            <div class="stat-icon me-3" style="width:48px;height:48px;background:#fef3c7;color:#a16207;border-radius:12px;margin-bottom:0;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <h3 style="font-size:1.6rem;">{{ $pendingCount }}</h3>
                <p style="font-size:0.8rem;">Menunggu Konfirmasi</p>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card d-flex align-items-center" style="border-left: 4px solid #22c55e;">
            <div class="stat-icon me-3" style="width:48px;height:48px;background:#dcfce7;color:#15803d;border-radius:12px;margin-bottom:0;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <h3 style="font-size:1.6rem;">{{ $lunasCount }}</h3>
                <p style="font-size:0.8rem;">Lunas</p>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card d-flex align-items-center" style="border-left: 4px solid #ef4444;">
            <div class="stat-icon me-3" style="width:48px;height:48px;background:#fee2e2;color:#991b1b;border-radius:12px;margin-bottom:0;">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div>
                <h3 style="font-size:1.6rem;">{{ $ditolakCount }}</h3>
                <p style="font-size:0.8rem;">Ditolak</p>
            </div>
        </div>
    </div>
</div>

{{-- Filter & Search --}}
<div class="card-dashboard mb-4" data-aos="fade-up" data-aos-delay="100">
    <div class="card-body" style="padding:16px 24px;">
        <form method="GET" action="{{ route('admin.konfirmasi') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:0.8rem;">Filter Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold" style="font-size:0.8rem;">Cari Pesanan</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari ID, Nama, atau Telepon..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request('search') || request('status'))
                    <a href="{{ route('admin.konfirmasi') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                    @endif
                </div>
            </div>
            <div class="col-md-4 d-flex justify-content-end gap-2">
                <span class="text-muted align-self-center" style="font-size:0.85rem;">
                    <i class="bi bi-info-circle me-1"></i>
                    Urutan: Pending <i class="bi bi-arrow-right mx-1"></i> Lunas <i class="bi bi-arrow-right mx-1"></i> Ditolak
                </span>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Pesanan --}}
<div class="card-dashboard" data-aos="fade-up" data-aos-delay="150">
    <div class="card-header">
        <span><i class="bi bi-receipt me-2" style="color:#2563eb;"></i> Daftar Pesanan</span>
        <span class="badge bg-primary rounded-pill">{{ $pesanan->total() }} total</span>
    </div>
    <div class="card-body p-0">
        @if($pesanan->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="font-size:0.85rem;">
                    <thead style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                        <tr>
                            <th style="padding:12px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;">#ID</th>
                            <th style="padding:12px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;">Pelanggan</th>
                            <th style="padding:12px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;">Total</th>
                            <th style="padding:12px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;">Metode</th>
                            <th style="padding:12px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;">Status</th>
                            <th style="padding:12px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;">Tanggal</th>
                            <th style="padding:12px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan as $p)
                        <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            {{-- ID --}}
                            <td style="padding:12px 14px;">
                                <span class="fw-bold" style="color:#2563eb;">#{{ $p->id }}</span>
                            </td>
                            {{-- Pelanggan --}}
                            <td style="padding:12px 14px;">
                                <div class="fw-semibold" style="color:#1e293b;">{{ $p->nama }}</div>
                            </td>
                            {{-- Total --}}
                            <td style="padding:12px 14px;">
                                <span class="fw-bold" style="color:#1e293b;">Rp {{ number_format($p->total, 0, ',', '.') }}</span>
                            </td>
                            {{-- Metode --}}
                            <td style="padding:12px 14px;">
                                @php
                                    $metodeLabel = match($p->metode_pembayaran) {
                                        'transfer' => ['label' => 'Transfer', 'icon' => 'bi-bank', 'color' => '#475569', 'bg' => '#f1f5f9'],
                                        'cod' => ['label' => 'COD', 'icon' => 'bi-cash-stack', 'color' => '#92400e', 'bg' => '#fef3c7'],
                                        default => ['label' => $p->metode_pembayaran ?? '-', 'icon' => 'bi-credit-card', 'color' => '#475569', 'bg' => '#f1f5f9'],
                                    };
                                @endphp
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:4px;font-size:0.75rem;font-weight:500;background:{{ $metodeLabel['bg'] }};color:{{ $metodeLabel['color'] }};">
                                    <i class="{{ $metodeLabel['icon'] }}"></i>
                                    {{ $metodeLabel['label'] }}
                                </span>
                            </td>
                            {{-- Status --}}
                            <td style="padding:12px 14px;">
                                @php
                                    $statusStyle = match($p->status_pembayaran) {
                                        'lunas' => ['color' => '#166534', 'bg' => '#dcfce7', 'icon' => 'bi-check-circle-fill'],
                                        'pending' => ['color' => '#92400e', 'bg' => '#fef3c7', 'icon' => 'bi-clock-fill'],
                                        'ditolak' => ['color' => '#991b1b', 'bg' => '#fee2e2', 'icon' => 'bi-x-circle-fill'],
                                        default => ['color' => '#64748b', 'bg' => '#f1f5f9', 'icon' => 'bi-question-circle-fill'],
                                    };
                                    $statusLabel = match($p->status_pembayaran) {
                                        'lunas' => 'Lunas',
                                        'pending' => 'Menunggu',
                                        'ditolak' => 'Ditolak',
                                        default => $p->status_pembayaran,
                                    };
                                @endphp
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:4px;font-size:0.75rem;font-weight:600;background:{{ $statusStyle['bg'] }};color:{{ $statusStyle['color'] }};">
                                    <i class="{{ $statusStyle['icon'] }}"></i>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            {{-- Tanggal --}}
                            <td style="padding:12px 14px;white-space:nowrap;color:#64748b;font-size:0.8rem;">
                                {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}
                            </td>
                            {{-- Aksi kotak icon --}}
                            <td style="padding:12px 14px;text-align:center;">
                                <div class="d-flex gap-1 justify-content-center">
                                    @if($p->status_pembayaran == 'pending')
                                    <button type="button"
                                            onclick="konfirmasiTerima({{ $p->id }}, '{{ addslashes($p->nama) }}')"
                                            title="Terima Pembayaran"
                                            style="width:32px;height:32px;border:none;border-radius:6px;background:#059669;color:white;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.15s;"
                                            onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button type="button"
                                            onclick="konfirmasiTolak({{ $p->id }}, '{{ addslashes($p->nama) }}')"
                                            title="Tolak Pembayaran"
                                            style="width:32px;height:32px;border:none;border-radius:6px;background:#b91c1c;color:white;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.15s;"
                                            onmouseover="this.style.background='#991b1b'" onmouseout="this.style.background='#b91c1c'">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    @endif
                                    <a href="{{ route('admin.konfirmasi.detail', $p->id) }}"
                                       title="Lihat Detail"
                                       style="width:32px;height:32px;border:none;border-radius:6px;background:#2563eb;color:white;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;transition:all 0.15s;"
                                       onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Info & Pagination --}}
            <div style="padding:12px 18px;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;background:#f8fafc;">
                <small style="color:#64748b;font-size:0.78rem;">
                    Menampilkan {{ $pesanan->firstItem() }} - {{ $pesanan->lastItem() }} dari {{ $pesanan->total() }} data
                </small>
                <div>
                    {{ $pesanan->onEachSide(1)->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
                <p class="fw-semibold mb-1">Belum ada pesanan</p>
                <small>Tidak ada data pesanan ditemukan</small>
            </div>
        @endif
    </div>
</div>

{{-- Modal Terima --}}
<div class="modal fade" id="modalTerima" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background:#dcfce7;">
                <h5 class="modal-title fw-bold" style="color:#166534;">
                    <i class="bi bi-check-circle-fill me-2"></i> Konfirmasi Terima
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTerima" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <p class="mb-0">Yakin ingin menerima pembayaran dari <strong id="namaTerima"></strong>?</p>
                    <div class="alert alert-warning mt-3 mb-0 py-2">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Stok produk akan otomatis dikurangi setelah dikonfirmasi.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-check-lg me-1"></i> Ya, Terima
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background:#fee2e2;">
                <h5 class="modal-title fw-bold" style="color:#991b1b;">
                    <i class="bi bi-x-circle-fill me-2"></i> Konfirmasi Tolak
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTolak" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <p class="mb-2">Alasan menolak pembayaran dari <strong id="namaTolak"></strong>:</p>
                    <textarea name="alasan_penolakan" class="form-control" rows="3"
                              placeholder="Tuliskan alasan penolakan..." required></textarea>
                    <div class="invalid-feedback">Alasan penolakan harus diisi.</div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-x-lg me-1"></i> Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const TERIMA_BASE = '{{ url("admin/konfirmasi/terima") }}';
    const TOLAK_BASE  = '{{ url("admin/konfirmasi/tolak") }}';

    function konfirmasiTerima(id, nama) {
        document.getElementById('namaTerima').textContent = nama;
        document.getElementById('formTerima').action = TERIMA_BASE + '/' + id;
        var modal = new bootstrap.Modal(document.getElementById('modalTerima'));
        modal.show();
    }

    function konfirmasiTolak(id, nama) {
        document.getElementById('namaTolak').textContent = nama;
        document.getElementById('formTolak').action = TOLAK_BASE + '/' + id;
        var modal = new bootstrap.Modal(document.getElementById('modalTolak'));
        modal.show();
    }

    // Validasi form tolak
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('formTolak')?.addEventListener('submit', function(e) {
            var alasan = this.querySelector('textarea[name="alasan_penolakan"]');
            if (!alasan.value.trim()) {
                e.preventDefault();
                alasan.classList.add('is-invalid');
            } else {
                alasan.classList.remove('is-invalid');
            }
        });
    });
</script>
@endpush
