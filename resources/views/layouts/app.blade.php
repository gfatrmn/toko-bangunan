<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'BuildNest - Toko Bahan Bangunan')</title>
  <meta name="description" content="@yield('meta_description', 'Toko bahan bangunan online terpercaya. Produk lengkap, pengiriman cepat.')">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" rel="stylesheet">

  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; scroll-behavior: smooth; color: #1e293b; }
    .navbar { padding: 0.5rem 0; background-color: rgba(255, 255, 255, 0.85) !important; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0, 0, 0, 0.05); }
    .navbar-brand { color: #004aad !important; font-weight: 800 !important; letter-spacing: -0.5px; }
    .search-container { width: 100%; margin-top: 10px; }
    @media (min-width: 992px) {
      .search-container {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 35%;
        margin-top: 0;
        z-index: 10;
      }
    }
    .nav-custom-btn { font-weight: 600; color: #475569 !important; padding: 10px 20px !important; border-radius: 12px; transition: all 0.3s; margin: 0 3px; display: inline-flex; align-items: center; }
    .nav-custom-btn:hover { background-color: rgba(0, 74, 173, 0.06); color: #004aad !important; transform: translateY(-1px); }
    .nav-custom-btn.active { background: linear-gradient(135deg, #004aad, #0066f2); color: #fff !important; box-shadow: 0 4px 12px rgba(0, 74, 173, 0.25); }
    .user-profile-badge { background: #fff; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 25px; padding: 4px 16px; display: inline-flex; align-items: center; font-weight: 600; font-size: 0.85rem; cursor: pointer; }
    .user-profile-badge:hover { border-color: rgba(0, 74, 173, 0.3) !important; background: rgba(0, 74, 173, 0.03) !important; }
    .user-profile-badge.active { background: linear-gradient(135deg, #004aad, #0066f2); color: #fff !important; border-color: transparent !important; box-shadow: 0 4px 12px rgba(0, 74, 173, 0.25); }
    .user-profile-badge.active i { color: #fff !important; }
    .user-profile-badge::after { display: none !important; }
    .btn-logout { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff !important; border-radius: 20px; font-weight: 600; padding: 8px 25px; border: none; transition: all 0.3s; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2); }
    .btn-logout:hover { transform: translateY(-1px); box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3); }

    /* ─── Cart Count Badge ─── */
    .cart-count-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 18px;
      height: 18px;
      padding: 0 5px;
      border-radius: 9px;
      background: #ef4444;
      color: #fff;
      font-size: 0.65rem;
      font-weight: 700;
      line-height: 1;
      flex-shrink: 0;
    }
    .dropdown-item .cart-count-badge {
      margin-left: auto;
    }
    .nav-icon-link .cart-count-badge {
      margin-left: 0;
      position: absolute !important;
      top: -4px !important;
      right: -10px !important;
    }

    /* Badge notifikasi di pojok kanan atas profile badge */
    .badge-notif {
      position: absolute !important;
      top: -2px !important;
      right: -2px !important;
      min-width: 17px;
      height: 17px;
      padding: 0 4px;
      border-radius: 50%;
      background: #ef4444;
      color: #fff;
      font-size: 0.6rem;
      font-weight: 700;
      line-height: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 0 2px #fff;
      z-index: 5;
    }

    /* Dropdown User Menu Styling */
    .dropdown-item { transition: all 0.2s ease; display: flex; align-items: center; }
    .dropdown-item:hover { background-color: rgba(0, 74, 173, 0.06); }
    .dropdown-item.active { background: linear-gradient(135deg, #004aad, #0066f2) !important; color: #fff !important; }
    .dropdown-item.text-danger:hover { background-color: rgba(239, 68, 68, 0.08); }
    .dropdown-icon { display: inline-flex; align-items: center; justify-content: center; width: 20px; margin-right: 10px; font-size: 0.9rem; }

    /* Modern Global Scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* ─── Global Toast ─── */
    .global-toast {
      position: fixed;
      top: 80px;
      right: 20px;
      z-index: 99999;
      background: #fff;
      border-radius: 12px;
      padding: 0.75rem 1.25rem;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
      border: 1px solid rgba(0, 0, 0, 0.06);
      border-left: 4px solid #004aad;
      font-size: 0.9rem;
      font-weight: 500;
      color: #1e293b;
      max-width: 350px;
      display: none;
      align-items: center;
      gap: 8px;
      transition: opacity 0.3s ease, transform 0.3s ease;
    }

    @media (max-width: 991px) {
      .navbar-collapse { text-align: center; padding: 20px 0; }
      .navbar-nav { align-items: center !important; }
      .nav-item { width: 100%; display: flex; justify-content: center; margin-bottom: 10px; }
      .nav-custom-btn { width: 85%; justify-content: center; }
      .nav-item.dropdown { width: auto; }
      .dropdown-menu { text-align: left; width: 100%; }
      .dropdown-item { padding: 12px 20px !important; }
    }
  </style>

  @stack('styles')
</head>
<body>

  {{-- Global Toast Notification --}}
  <div id="global-toast" class="global-toast">
    <i class="bi bi-check-circle-fill text-primary"></i>
    <span id="global-toast-message">Berhasil</span>
  </div>

  @include('layouts.header')

  @yield('content')

  @include('layouts.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  <script>AOS.init({ duration: 1000, once: true });</script>

  <script>
  // ─── Global Cart Badge ───
  function updateCartBadge() {
    fetch('/keranjang/count', {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
      var count = data.total_items || 0;
      document.querySelectorAll('.cart-count-badge').forEach(function(el) {
        el.textContent = count;
        el.style.display = count > 0 ? 'inline-flex' : 'none';
      });
    })
    .catch(function() {});
  }

  // ─── Global Toast ───
  var globalToastTimeout;

  function showGlobalToast(message, iconClass) {
    var toast = document.getElementById('global-toast');
    var msgEl = document.getElementById('global-toast-message');
    if (!toast) return;
    msgEl.textContent = message;
    var icon = toast.querySelector('i');
    if (iconClass) {
      icon.className = iconClass;
    } else {
      icon.className = 'bi bi-check-circle-fill text-primary';
    }
    if (globalToastTimeout) clearTimeout(globalToastTimeout);
    toast.style.display = 'flex';
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(0)';
    globalToastTimeout = setTimeout(function() {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(50px)';
      setTimeout(function() { toast.style.display = 'none'; }, 300);
    }, 3000);
  }

  document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.cart-count-badge')) {
      updateCartBadge();
    }
  });
  </script>

  @stack('scripts')
</body>
</html>
