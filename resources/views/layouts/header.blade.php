<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
  <div class="container">

    <a class="navbar-brand fw-bold fs-3 d-flex align-items-center" href="{{ url('/') }}">
      <i class="bi bi-buildings-fill me-2"></i>BuildNest
    </a>

    <div class="d-flex align-items-center order-lg-3">
      @if(session('role') !== 'admin')
        <a href="{{ route('keranjang.index') }}" class="nav-icon-link d-lg-none me-3">
          <i class="bi bi-cart3 fs-4"></i>
        </a>
      @endif
      <button class="navbar-toggler border-0 shadow-none" type="button"
              data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="navbarNav">

      {{-- Search Bar --}}
      @php
        $hide_search = in_array(request()->route()->getName() ?? '', [
          'produk.show', 'pemesanan.create', 'pembayaran.index',
          'riwayat.index', 'keranjang.index'
        ]) || session('role') === 'admin';
      @endphp

      @unless($hide_search)
        <div class="search-container mx-lg-auto px-3 px-lg-0">
          <form action="{{ url('/#semua_produk') }}" method="GET" class="w-100">
            <div class="input-group">
              <input type="search" class="form-control border-primary border-end-0 shadow-none"
                     name="search" style="border-radius: 10px 0 0 10px;"
                     placeholder="Cari kebutuhan proyek..."
                     value="{{ request('search') }}">
              <button class="btn btn-primary px-3" type="submit" style="border-radius: 0 10px 10px 0;">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </form>
        </div>
      @endunless

      <ul class="navbar-nav ms-auto align-items-center">

        @if(session('user_id'))
          @if(session('role') === 'admin')
            <li class="nav-item">
              <a class="nav-link nav-custom-btn {{ request()->is('dashboard*') ? 'active' : '' }}"
                 href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid-1x2-fill me-2"></i>Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-custom-btn {{ request()->is('admin/konfirmasi*') ? 'active' : '' }}"
                 href="{{ route('admin.konfirmasi') }}">
                <i class="bi bi-check-all me-2"></i>Konfirmasi
              </a>
            </li>
          @else
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center user-profile-badge shadow-sm ms-lg-2 py-1 px-3"
                 href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                 style="border-radius: 25px; background: #fff; border: 1px solid rgba(0,0,0,0.08);">
                <i class="bi bi-person-circle me-1 text-primary fs-6"></i>
                <span class="fw-semibold text-dark" style="font-size:0.85rem;">{{ session('nama') }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end border-0 rounded-bottom-2 py-1 mt-0 pt-0" style="min-width: 170px; font-size:0.8rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 0 0 10px 10px !important;">
                <li>
                  <a class="dropdown-item py-1 px-3 {{ request()->is('keranjang*') ? 'active' : '' }}"
                     href="{{ route('keranjang.index') }}" style="font-size:0.8rem;">
                    <i class="bi bi-cart3 me-2"></i> Keranjang
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-1 px-3 {{ request()->is('riwayat*') ? 'active' : '' }}"
                     href="{{ route('riwayat.index') }}" style="font-size:0.8rem;">
                    <i class="bi bi-bag-check-fill me-2"></i> Pesanan
                  </a>
                </li>
                <li><hr class="dropdown-divider my-0"></li>
                <li>
                  <a class="dropdown-item py-1 px-3 text-danger"
                     href="{{ route('logout') }}" style="font-size:0.8rem;">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                  </a>
                </li>
              </ul>
            </li>
          @endif

        @else
          <li class="nav-item d-none d-lg-block me-3">
            <a href="{{ route('keranjang.index') }}" class="nav-icon-link px-3 border-end">
              <i class="bi bi-cart3 fs-4"></i>
            </a>
          </li>
          <li class="nav-item w-100 mt-2 mt-lg-0">
            <div class="d-flex auth-buttons">
              <a class="btn btn-primary fw-bold shadow-sm me-2" style="border-radius:10px"
                 href="{{ route('login') }}">Masuk</a>
              <a class="btn btn-outline-primary fw-bold shadow-sm" style="border-radius:10px"
                 href="{{ route('register') }}">Daftar</a>
            </div>
          </li>
        @endif

      </ul>
    </div>
  </div>
</nav>
