@extends('admin.layout')

@section('title', 'Dashboard Admin - BuildNest')
@section('page_title', 'Dashboard')

@section('content')
    {{-- Statistik Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0f2fe; color: #0369a1;">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <h3>{{ $totalProduk }}</h3>
                <p>Total Produk</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dcfce7; color: #15803d;">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
                <h3>{{ $totalPesanan }}</h3>
                <p>Total Pesanan</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #a16207;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3>{{ $totalUser }}</h3>
                <p>Total Pengguna</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0e7ff; color: #4338ca;">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <p>Total Pendapatan</p>
            </div>
        </div>
    </div>

    {{-- Chart & Stok Menipis --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
            <div class="card-dashboard">
                <div class="card-header">
                    <span><i class="bi bi-graph-up-arrow me-2" style="color:#2563eb;"></i> Penjualan 7 Hari Terakhir</span>
                </div>
                <div class="card-body" style="padding:20px;">
                    <canvas id="penjualanChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-dashboard">
                <div class="card-header">
                    <span><i class="bi bi-exclamation-triangle-fill me-2" style="color:#f59e0b;"></i> Stok Menipis</span>
                    <span class="badge bg-warning text-dark">{{ $stokMenipis->count() }} item</span>
                </div>
                <div class="card-body">
                    @if($stokMenipis->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stokMenipis as $produk)
                                <tr>
                                    <td>{{ $produk->nama }}</td>
                                    <td>
                                        <span class="badge {{ $produk->stok <= 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                            {{ $produk->stok }} {{ $produk->satuan ?? 'pcs' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-check-circle-fill" style="font-size:2rem;color:#22c55e;"></i>
                            <p class="mt-2">Semua stok aman</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Pesanan Terbaru --}}
    <div class="row g-4">
        <div class="col-12" data-aos="fade-up" data-aos-delay="100">
            <div class="card-dashboard">
                <div class="card-header">
                    <span><i class="bi bi-clock-history me-2" style="color:#2563eb;"></i> Pesanan Terbaru</span>
                    <a href="#" class="btn btn-sm btn-outline-primary rounded-pill">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($pesananTerbaru->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Pelanggan</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesananTerbaru as $pesanan)
                                    <tr>
                                        <td><strong>#{{ $pesanan->id }}</strong></td>
                                        <td>{{ $pesanan->user->nama ?? '-' }}</td>
                                        <td>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($pesanan->status_pembayaran) {
                                                    'lunas' => 'badge-lunas',
                                                    'pending' => 'badge-pending',
                                                    'ditolak' => 'badge-ditolak',
                                                    default => 'bg-secondary'
                                                };
                                                $label = match($pesanan->status_pembayaran) {
                                                    'lunas' => 'Lunas',
                                                    'pending' => 'Pending',
                                                    'ditolak' => 'Ditolak',
                                                    default => $pesanan->status_pembayaran
                                                };
                                            @endphp
                                            <span class="badge-status {{ $badgeClass }}">{{ $label }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d M Y, H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox" style="font-size:2rem;"></i>
                            <p class="mt-2">Belum ada pesanan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('penjualanChart').getContext('2d');

        const labels = {!! json_encode(array_column($penjualanHarian, 'tanggal')) !!};
        const data   = {!! json_encode(array_column($penjualanHarian, 'total')) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    borderWidth: 3,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
