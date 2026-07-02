@extends('admin.layout')

@section('title', 'Detail Pesanan #' . $pesanan->id . ' - BuildNest')
@section('page_title', 'Detail Pesanan #' . $pesanan->id)

@section('content')
{{-- Tombol Kembali --}}
<div class="mb-4" data-aos="fade-up">
    <a href="{{ route('admin.konfirmasi') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row g-4">
    {{-- Kolom Kiri: Informasi Pesanan + Produk --}}
    <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
        {{-- Informasi Pesanan --}}
        <div class="card-dashboard">
            <div class="card-header">
                <span><i class="bi bi-receipt me-2" style="color:#2563eb;"></i> Informasi Pesanan</span>
                <span
                    class="badge-status {{ $pesanan->status_pembayaran == 'lunas' ? 'badge-lunas' : ($pesanan->status_pembayaran == 'pending' ? 'badge-pending' : 'badge-ditolak') }}">
                    @php
                        $icon = match (true) {
                            $pesanan->status_pembayaran == 'lunas' => 'bi-check-circle-fill',
                            $pesanan->status_pembayaran == 'pending' && $pesanan->bukti_pembayaran => 'bi-clock-fill',
                            $pesanan->status_pembayaran == 'pending' => 'bi-clock-fill',
                            $pesanan->status_pembayaran == 'ditolak' => 'bi-x-circle-fill',
                            default => 'bi-question-circle-fill',
                        };
                        $label = match (true) {
                            $pesanan->status_pembayaran == 'lunas' => 'LUNAS',
                            $pesanan->status_pembayaran == 'pending' && $pesanan->bukti_pembayaran => 'MENUNGGU KONFIRMASI',
                            $pesanan->status_pembayaran == 'pending' => 'BELUM BAYAR',
                            $pesanan->status_pembayaran == 'ditolak' => 'DITOLAK',
                            default => strtoupper($pesanan->status_pembayaran),
                        };
                    @endphp
                    <i class="{{ $icon }} me-1"></i> {{ $label }}
                </span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0" style="font-size:0.85rem;">
                            <tr>
                                <td class="text-muted" style="width:100px;padding:6px 0;">ID Pesanan</td>
                                <td class="fw-bold" style="padding:6px 0;">#{{ $pesanan->id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding:6px 0;">Tanggal</td>
                                <td style="padding:6px 0;">
                                    {{ \Carbon\Carbon::parse($pesanan->created_at)->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding:6px 0;">Status</td>
                                <td style="padding:6px 0;">
                                    <span
                                        class="badge-status {{ $pesanan->status_pembayaran == 'lunas' ? 'badge-lunas' : ($pesanan->status_pembayaran == 'pending' ? 'badge-pending' : 'badge-ditolak') }}"
                                        style="font-size:0.72rem;">
                                        {{ $label }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding:6px 0;">Metode</td>
                                <td style="padding:6px 0;">
                                    @if ($pesanan->metode_pembayaran == 'transfer')
                                        <i class="bi bi-bank me-1"></i> Transfer Bank
                                    @elseif($pesanan->metode_pembayaran == 'cod')
                                        <i class="bi bi-cash me-1"></i> COD
                                    @else
                                        {{ $pesanan->metode_pembayaran ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding:6px 0;">Total</td>
                                <td class="fw-bold" style="font-size:1.1rem;color:#2563eb;padding:6px 0;">
                                    Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0" style="font-size:0.85rem;">
                            <tr>
                                <td class="text-muted" style="width:80px;padding:6px 0;">Nama</td>
                                <td class="fw-bold" style="padding:6px 0;">{{ $pesanan->nama }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding:6px 0;">Telepon</td>
                                <td style="padding:6px 0;">
                                    <a href="tel:{{ $pesanan->telepon }}" class="text-decoration-none">
                                        <i class="bi bi-telephone me-1"></i> {{ $pesanan->telepon }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding:6px 0;">Email</td>
                                <td style="padding:6px 0;">{{ $pesanan->user->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding:6px 0;">Alamat</td>
                                <td style="padding:6px 0;">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $pesanan->alamat }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Alasan Penolakan (jika ditolak) --}}
                @if ($pesanan->status_pembayaran == 'ditolak' && $pesanan->alasan_penolakan)
                    <div class="mt-3 mb-0 py-2" style="font-size:0.85rem;background:#fef2f2;border-radius:8px;padding:8px 12px !important;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-x-octagon-fill mt-1" style="color:#dc2626;"></i>
                            <div>
                                <strong style="color:#991b1b;">Alasan Penolakan:</strong><br>
                                <span style="color:#7f1d1d;">{{ $pesanan->alasan_penolakan }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="card-dashboard mt-4">
            <div class="card-header">
                <span><i class="bi bi-cart me-2" style="color:#2563eb;"></i> Detail Produk</span>
                <span class="badge bg-primary rounded-pill">{{ $pesanan->details->sum('jumlah') }} item</span>
            </div>
            <div class="card-body p-0">
                @if ($pesanan->details && $pesanan->details->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" style="font-size:0.82rem;">
                            <thead
                                style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                <tr>
                                    <th
                                        style="padding:10px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;">
                                        Produk</th>
                                    <th
                                        style="padding:10px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;text-align:center;width:80px;">
                                        Jumlah</th>
                                    <th
                                        style="padding:10px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;text-align:right;width:120px;">
                                        Harga</th>
                                    <th
                                        style="padding:10px 14px;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;text-align:right;width:120px;">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pesanan->details as $detail)
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:10px 14px;">
                                            <div class="d-flex align-items-center gap-3">
                                                @if ($detail->produk && $detail->produk->gambar)
                                                    <img src="{{ asset('uploads/' . $detail->produk->gambar) }}"
                                                        alt="{{ $detail->produk->nama }}"
                                                        style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                                                @else
                                                    <div
                                                        style="width:44px;height:44px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;border:1px solid #e2e8f0;">
                                                        <i class="bi bi-box text-muted" style="font-size:1.1rem;"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold" style="color:#1e293b;">
                                                        {{ $detail->produk->nama ?? 'Produk Dihapus' }}</div>
                                                    @if ($detail->produk && $detail->produk->kategoriRelasi)
                                                         <small
                                                             class="text-muted">{{ $detail->produk->kategoriRelasi->nama }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center" style="padding:10px 14px;">
                                            <span class="fw-semibold">{{ $detail->jumlah }}</span>
                                        </td>
                                        <td class="text-end" style="padding:10px 14px;">
                                            <span style="color:#475569;">Rp
                                                {{ number_format($detail->harga, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-end fw-bold" style="padding:10px 14px;color:#1e293b;">
                                            Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot
                                style="background:#f8fafc;border-top:2px solid #e2e8f0;">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold"
                                        style="padding:10px 14px;font-size:0.85rem;">Total</td>
                                    <td class="text-end fw-bold"
                                        style="padding:10px 14px;font-size:1rem;color:#2563eb;">
                                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:8px;"></i>
                        <p class="mb-0" style="font-size:0.9rem;">Belum ada detail produk</p>
                        <p class="mb-0" style="font-size:0.78rem;color:#94a3b8;">
                            Data produk akan muncul setelah pelanggan melakukan checkout dengan benar.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Bukti + Aksi --}}
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
        {{-- Bukti Pembayaran --}}
        <div class="card-dashboard mb-4">
            <div class="card-header">
                <span><i class="bi bi-image me-2" style="color:#2563eb;"></i> Bukti Pembayaran</span>
            </div>
            <div class="card-body text-center" style="padding:16px;">
                @if ($pesanan->bukti_pembayaran)
                    @php
                        $ext = pathinfo($pesanan->bukti_pembayaran, PATHINFO_EXTENSION);
                        $buktiPath = 'uploads/' . $pesanan->bukti_pembayaran;
                    @endphp
                    @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <a href="{{ asset($buktiPath) }}" target="_blank">
                            <img src="{{ asset($buktiPath) }}" alt="Bukti Pembayaran"
                                class="img-fluid rounded border" style="max-height:220px;object-fit:contain;cursor:pointer;">
                        </a>
                        <div class="mt-2">
                            <a href="{{ asset($buktiPath) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary rounded-pill" style="font-size:0.78rem;">
                                <i class="bi bi-arrows-angle-expand me-1"></i> Lihat Full
                            </a>
                        </div>
                    @else
                        <div class="py-3">
                            <i class="bi bi-file-earmark" style="font-size:2.5rem;color:#94a3b8;"></i>
                            <p class="mt-2 mb-0" style="font-size:0.85rem;">File: {{ $pesanan->bukti_pembayaran }}</p>
                            <a href="{{ asset($buktiPath) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary rounded-pill mt-2" style="font-size:0.78rem;">
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>
                    @endif
                @else
                    <div class="py-4 text-muted">
                        <i class="bi bi-camera-off" style="font-size:2.5rem;display:block;margin-bottom:8px;"></i>
                        <p class="mb-0" style="font-size:0.85rem;">Tidak ada bukti pembayaran</p>
                        @if ($pesanan->metode_pembayaran == 'cod')
                            <small class="text-muted">(Pembayaran COD)</small>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Aksi Konfirmasi --}}
        @if ($pesanan->status_pembayaran == 'pending' && $pesanan->bukti_pembayaran)
            <div class="card-dashboard">
                <div class="card-header">
                    <span><i class="bi bi-shield-check me-2" style="color:#2563eb;"></i> Aksi</span>
                </div>
                <div class="card-body" style="padding:16px;">
                    <p class="text-muted mb-3" style="font-size:0.78rem;">
                        <i class="bi bi-info-circle me-1"></i>
                        Setelah menerima pembayaran, stok produk akan otomatis dikurangi.
                    </p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success rounded-pill fw-semibold"
                            style="font-size:0.78rem;padding:6px 16px;"
                            onclick="konfirmasiTerima({{ $pesanan->id }})">
                            <i class="bi bi-check-lg me-1"></i> Terima
                        </button>
                        <button type="button" class="btn btn-outline-danger rounded-pill fw-semibold"
                            style="font-size:0.78rem;padding:6px 16px;"
                            onclick="konfirmasiTolak({{ $pesanan->id }})">
                            <i class="bi bi-x-lg me-1"></i> Tolak
                        </button>
                    </div>
                </div>
            </div>
        @elseif($pesanan->status_pembayaran == 'lunas')
            <div class="card-dashboard">
                <div class="card-header">
                    <span><i class="bi bi-check-circle me-2" style="color:#22c55e;"></i> Status</span>
                </div>
                <div class="card-body text-center" style="padding:20px;">
                    <div
                        style="width:52px;height:52px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-check-circle-fill" style="font-size:1.5rem;color:#166534;"></i>
                    </div>
                    <h6 class="fw-bold" style="color:#166534;">Pembayaran Diterima</h6>
                    <p class="text-muted mb-0" style="font-size:0.78rem;">
                        Pesanan ini sudah dikonfirmasi dan stok produk telah dikurangi.
                    </p>
                </div>
            </div>
        @elseif($pesanan->status_pembayaran == 'ditolak')
            <div class="card-dashboard">
                <div class="card-header">
                    <span><i class="bi bi-x-circle me-2" style="color:#ef4444;"></i> Status</span>
                </div>
                <div class="card-body text-center" style="padding:20px;">
                    <div
                        style="width:52px;height:52px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-x-circle-fill" style="font-size:1.5rem;color:#991b1b;"></i>
                    </div>
                    <h6 class="fw-bold" style="color:#991b1b;">Pembayaran Ditolak</h6>
                    @if ($pesanan->alasan_penolakan)
                        <p class="mb-1 fw-semibold" style="font-size:0.78rem;">Alasan:</p>
                        <p class="text-muted mb-0" style="font-size:0.78rem;">{{ $pesanan->alasan_penolakan }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Modal Terima --}}
<div class="modal fade" id="modalTerima" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg text-center">
            <div class="modal-body py-4">
                <div
                    style="width:56px;height:56px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <i class="bi bi-check-circle-fill" style="font-size:1.6rem;color:#166534;"></i>
                </div>
                <h6 class="fw-bold mb-2">Konfirmasi Terima</h6>
                <p class="text-muted mb-3" style="font-size:0.85rem;">Yakin ingin menerima pembayaran ini?<br>Stok
                    produk akan dikurangi.</p>
                <form id="formTerima" method="POST" class="d-flex gap-2 justify-content-center">
                    @csrf
                    <button type="button" class="btn btn-light rounded-pill px-3" style="font-size:0.82rem;"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-3" style="font-size:0.82rem;">
                        <i class="bi bi-check-lg me-1"></i> Ya, Terima
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background:#fee2e2;padding:12px 16px;">
                <h6 class="modal-title fw-bold" style="color:#991b1b;">
                    <i class="bi bi-x-circle-fill me-2"></i> Konfirmasi Tolak
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTolak" method="POST">
                @csrf
                <div class="modal-body py-3" style="padding:12px 16px;">
                    <p class="mb-2" style="font-size:0.85rem;">Alasan menolak pembayaran ini:</p>
                    <textarea name="alasan_penolakan" class="form-control" rows="3" style="font-size:0.85rem;"
                        placeholder="Tuliskan alasan penolakan..." required></textarea>
                    <div class="form-text" style="font-size:0.75rem;">Alasan ini akan terlihat oleh pelanggan.</div>
                </div>
                <div class="modal-footer border-0" style="padding:8px 16px;">
                    <button type="button" class="btn btn-light rounded-pill px-3" style="font-size:0.82rem;"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-3" style="font-size:0.82rem;">
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
        const TERIMA_BASE = '{{ url('admin/konfirmasi/terima') }}';
        const TOLAK_BASE = '{{ url('admin/konfirmasi/tolak') }}';

        function konfirmasiTerima(id) {
            document.getElementById('formTerima').action = TERIMA_BASE + '/' + id;
            var modal = new bootstrap.Modal(document.getElementById('modalTerima'));
            modal.show();
        }

        function konfirmasiTolak(id) {
            document.getElementById('formTolak').action = TOLAK_BASE + '/' + id;
            var modal = new bootstrap.Modal(document.getElementById('modalTolak'));
            modal.show();
        }

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
