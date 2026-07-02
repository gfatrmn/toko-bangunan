<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
  <div class="container">

    <a class="navbar-brand fw-bold fs-3 d-flex align-items-center" href="{{ url('/') }}">
      <i class="bi bi-buildings-fill me-2"></i>BuildNest
    </a>

    <div class="d-flex align-items-center order-lg-3">
      @if(session('role') !== 'admin')
        <a href="{{ route('keranjang.index') }}" class="nav-icon-link d-lg-none me-3 position-relative">
          <i class="bi bi-cart3 fs-4"></i>
          <span class="cart-count-badge" style="position:absolute;top:-4px;right:-10px;display:none;">0</span>
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
              <a class="nav-link user-profile-badge shadow-sm ms-lg-1 py-1 px-3 d-flex align-items-center {{ request()->is('dashboard*') ? 'active' : '' }}"
                 href="{{ route('admin.dashboard') }}"
                 style="border-radius: 25px; background: #fff; border: 1px solid rgba(0,0,0,0.08); color: #212529; font-size:0.85rem;">
                <i class="bi bi-grid-1x2-fill me-2 text-primary"></i>Dashboard
              </a>
            </li>
            <li class="nav-item ms-lg-1">
              <a class="nav-link user-profile-badge shadow-sm ms-lg-1 py-1 px-3 d-flex align-items-center {{ request()->is('admin/konfirmasi*') ? 'active' : '' }}"
                 href="{{ route('admin.konfirmasi') }}"
                 style="border-radius: 25px; background: #fff; border: 1px solid rgba(0,0,0,0.08); color: #212529; font-size:0.85rem;">
                <i class="bi bi-check-all me-2 text-primary"></i>Konfirmasi
              </a>
            </li>
          @else
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center user-profile-badge shadow-sm ms-lg-2 py-1 px-3 position-relative"
                 href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                 style="border-radius: 25px; background: #fff; border: 1px solid rgba(0,0,0,0.08);">
                <i class="bi bi-person-circle me-1 text-primary fs-6"></i>
                <span class="fw-semibold text-dark" style="font-size:0.85rem;">{{ session('nama') }}</span>
                <span class="cart-count-badge badge-notif" style="display:none;">0</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end border-0 rounded-bottom-2 py-1" style="width: 190px; font-size:0.8rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 0 0 10px 10px !important;">
                <li>
                  <a class="dropdown-item py-2 px-3 {{ request()->is('keranjang*') ? 'active' : '' }}"
                     href="{{ route('keranjang.index') }}">
                    <i class="bi bi-cart3 dropdown-icon"></i> Keranjang
                    <span class="cart-count-badge" style="display:none;">0</span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2 px-3 {{ request()->is('riwayat*') ? 'active' : '' }}"
                     href="{{ route('riwayat.index') }}">
                    <i class="bi bi-bag-check-fill dropdown-icon"></i> Pesanan
                  </a>
                </li>
                <li><hr class="dropdown-divider my-0"></li>
                <li>
                  <a class="dropdown-item py-2 px-3 text-danger"
                     href="{{ route('logout') }}">
                    <i class="bi bi-box-arrow-right dropdown-icon"></i> Logout
                  </a>
                  </li>
              </ul>
            </li>
          @endif

        @else
          <li class="nav-item d-flex align-items-center gap-2 ms-lg-2">
            <a href="{{ route('keranjang.index') }}" class="d-flex align-items-center text-decoration-none text-dark me-1">
              <i class="bi bi-cart3 fs-5"></i>
            </a>
            <a class="btn btn-primary fw-bold shadow-sm rounded-pill d-flex align-items-center py-1 px-3" style="font-size:0.85rem;" href="{{ route('login') }}">
              <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
            </a>
            <a class="btn btn-outline-primary fw-bold shadow-sm rounded-pill d-flex align-items-center py-1 px-3" style="font-size:0.85rem;" href="{{ route('register') }}">
              <i class="bi bi-person-plus me-1"></i>Daftar
            </a>
          </li>
        @endif

      </ul>
    </div>
  </div>
</nav>
