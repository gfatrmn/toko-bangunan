# Mengapa Orders (Pemesanan) dan Payments (Pembayaran) Dipisah?

## 📌 Ringkasan

Di aplikasi **BuildNest (Toko Bangunan)**, tabel `pemesanan` (orders) dan `pembayaran` (payments) dipisah menjadi dua tabel berbeda dengan relasi **1-to-many** (satu pesanan bisa memiliki banyak percobaan pembayaran). Dokumen ini menjelaskan alasan pemisahan tersebut, baik secara konseptual maupun implementasi database.

---

## 1. Beda Siklus Hidup (Lifecycle)

### Masalah jika digabung
Jika kolom pembayaran disimpan langsung di tabel `pemesanan`, maka setiap kali terjadi kegagalan pembayaran, harus dibuat **record pesanan baru**. Ini sangat tidak praktis.

### Realita di lapangan
Satu pesanan bisa punya banyak percobaan pembayaran:
- Customer gagal bayar pertama (kartu ditolak)
- Retry dengan metode berbeda
- Ganti metode pembayaran (dari transfer ke e-wallet)

### Implementasi di project
```php
// app/Models/Pemesanan.php
// Satu pesanan bisa punya BANYAK percobaan pembayaran
public function pembayaran()
{
    return $this->hasMany(Pembayaran::class, 'pemesanan_id');
}
```

```php
// app/Http/Controllers/PembayaranController.php
// Upload bukti bayar = create NEW record di tabel pembayaran
Pembayaran::create([
    'pemesanan_id'     => $pesanan->id,
    'jumlah'           => $pesanan->total,
    'metode'           => $request->metode_pembayaran,
    'status'           => 'pending',
    'bukti_pembayaran' => $filename,
]);
```

---

## 2. Relasi 1-ke-Banyak (One-to-Many)

### Skenario yang mungkin terjadi

| Skenario | Penjelasan |
|----------|-----------|
| **1 Order → Banyak Payment attempts** | Customer upload bukti transfer, ditolak admin, upload ulang lagi |
| **1 Order → Bayar partial/cicil (DP)** | Sistem bisa dikembangkan untuk menerima DP 50%, lalu pelunasan nanti |
| **1 Order → Refund sebagian** | Jika ada komplain, bisa refund sebagian jumlah, bukan seluruhnya |

### Struktur tabel yang mendukung

**Tabel `pemesanan`** — data logistik pesanan:
```sql
-- Kolom di tabel pemesanan:
id, user_id, nama, alamat, telepon, total, tanggal, status_pembayaran, created_at, updated_at
```

**Tabel `pembayaran`** — data finansial (bisa banyak per order):
```sql
-- Kolom di tabel pembayaran:
id, pemesanan_id (FK), jumlah, metode, status, bukti_pembayaran,
transaction_id, gateway_response, alasan_penolakan, dibayar_pada, created_at, updated_at
```

Setiap `pembayaran` memiliki `pemesanan_id` sebagai foreign key, sehingga satu order bisa punya N record pembayaran.

---

## 3. Pemisahan Tanggung Jawab (Separation of Concerns)

### Order (`pemesanan`) → urus **apa yang dipesan**
| Data | Contoh |
|------|--------|
| Produk apa saja | via `DetailPemesanan` (produk_id, jumlah, harga) |
| Qty berapa | `details.sum('jumlah')` |
| Alamat kirim | `alamat`, `nama`, `telepon` |
| Status pengiriman | (bisa ditambah nanti: `status_pengiriman`) |

```php
// app/Models/Pemesanan.php - fokus ke produk & pelanggan
protected $fillable = [
    'user_id', 'nama', 'alamat', 'telepon',
    'total', 'tanggal', 'status_pembayaran',
];
```

### Payment (`pembayaran`) → urus **bagaimana uang masuk**
| Data | Contoh |
|------|--------|
| Metode pembayaran | `transfer`, `e-wallet`, `kartu_kredit`, `COD` |
| Jumlah dibayar | `jumlah` (bisa berbeda tiap attempt) |
| Status verifikasi | `pending`, `sukses`, `gagal`, `refund` |
| Gateway response | `transaction_id`, `gateway_response` (raw JSON dari Midtrans/Xendit) |
| Waktu bayar | `dibayar_pada` |

```php
// app/Models/Pembayaran.php - fokus ke transaksi finansial
protected $fillable = [
    'pemesanan_id', 'jumlah', 'metode', 'status',
    'bukti_pembayaran', 'transaction_id',
    'gateway_response', 'alasan_penolakan', 'dibayar_pada',
];
```

### ❌ Dampak jika digabung
Tabel `pemesanan` akan campur aduk:
- Kolom `status_pembayaran` — sebenarnya milik payment
- Kolom `metode_pembayaran` — milik payment
- Kolom `alasan_penolakan` — milik payment
- Kolom `transaction_id`, `gateway_response` — akan ditambahkan ke tabel order → **berantakan**

Di sinilah **migrasi pemisahan** terjadi:
```php
// database/migrations/2026_07_02_060211_migrate_payment_data_to_pembayaran_table.php
// Hapus kolom payment dari tabel pemesanan
Schema::table('pemesanan', function (Blueprint $table) {
    $table->dropColumn([
        'bukti_pembayaran',
        'metode_pembayaran',
        'alasan_penolakan',
    ]);
});
```

---

## 4. Integrasi Payment Gateway (Persiapan, Belum Aktif)

### Status Saat Ini
Saat ini **pembayaran masih manual**: customer upload bukti transfer (gambar), lalu admin konfirmasi via menu Konfirmasi Pembayaran.

**Kolom yang sudah disiapkan untuk integrasi gateway nanti:**

```php
// app/Models/Pembayaran.php - kolom untuk integrasi gateway
$table->string('transaction_id', 100)->nullable()->comment('ID dari payment gateway');
$table->text('gateway_response')->nullable()->comment('Raw response dari payment gateway');
```

Saat ini kedua kolom tersebut masih **nullable dan tidak terpakai** karena belum ada integrasi dengan payment gateway manapun.

### Alasan Pemisahan Sudah Tepat Sejak Awal
Meskipun gateway belum aktif, struktur tabel `pembayaran` yang terpisah dari `pemesanan` sudah benar. Nanti ketika integrasi dilakukan:

**Problem yang akan dihadapi jika digabung:**
Payment gateway seperti **Midtrans**, **Xendit**, **Doku**, dll. mengirim callback/webhook berisi data transaksi yang sangat spesifik:

```json
{
  "transaction_id": "TRX-20260701-12345",
  "transaction_status": "settlement",
  "payment_type": "bank_transfer",
  "bank": "bca",
  "va_number": "1234567890",
  "signature_key": "abc123...",
  "gross_amount": "150000.00",
  "transaction_time": "2026-07-01 14:30:00"
}
```

Jika disatukan dengan tabel order:
```sql
-- Tabel pemesanan jadi "kotor" dengan kolom payment gateway:
ALTER TABLE pemesanan ADD COLUMN transaction_id VARCHAR(100) NULL;
ALTER TABLE pemesanan ADD COLUMN transaction_status VARCHAR(50) NULL;
ALTER TABLE pemesanan ADD COLUMN payment_type VARCHAR(50) NULL;
ALTER TABLE pemesanan ADD COLUMN bank VARCHAR(50) NULL;
ALTER TABLE pemesanan ADD COLUMN va_number VARCHAR(100) NULL;
ALTER TABLE pemesanan ADD COLUMN signature_key TEXT NULL;
ALTER TABLE pemesanan ADD COLUMN gateway_response TEXT NULL;
-- ... bayangkan 10+ kolom untuk tiap gateway ❌
```

### ✅ Dengan pemisahan (ketika gateway aktif nanti)
Semua data gateway disimpan di tabel `pembayaran`:
```php
// Webhook handler nanti tinggal:
Pembayaran::create([
    'pemesanan_id'     => $pemesananId,
    'jumlah'           => $grossAmount,
    'metode'           => $paymentType,
    'status'           => 'sukses',  // atau 'pending' untuk menunggu settlement
    'transaction_id'   => $transactionId,
    'gateway_response' => json_encode($fullWebhookPayload),
    'dibayar_pada'     => now(),
]);
```

Tabel `pemesanan` tetap bersih, hanya fokus ke data pesanan. Webhook tinggal create/update record di `pembayaran` tanpa menyentuh tabel order.

### Langkah yang Dibutuhkan untuk Aktifkan Gateway
1. Install SDK payment gateway (misal: `composer require midtrans/midtrans-php`)
2. Buat controller/webhook handler baru (misal: `PaymentWebhookController`)
3. Di webhook handler, cari/tentukan `pemesanan_id` lalu create/update record di `pembayaran`
4. Endpoint webhook didaftarkan di route tanpa CSRF (`excluded_middleware` di `bootstrap/app.php`)

---

## 5. Auditing & Keamanan

### Kenapa penting?
Data pembayaran membutuhkan **log terpisah yang lebih ketat** untuk tracing:
- Kapan pertama kali dibayar?
- Berapa kali retry?
- Gagal karena apa?
- Apakah ada indikasi fraud?

### Implementasi audit trail di project

```php
// app/Http/Controllers/AdminController.php
// Method terima — mencatat waktu sukses
public function terima($id)
{
    // ... validasi & kurangi stok ...
    
    $pesanan->update(['status_pembayaran' => 'lunas']);
    
    // Update payment record: catat waktu sukses
    Pembayaran::where('pemesanan_id', $pesanan->id)
              ->where('status', 'pending')
              ->update([
                  'status'       => 'sukses',
                  'dibayar_pada' => now(),  // timestamp audit
              ]);
}

// Method tolak — mencatat alasan penolakan
public function tolak(Request $request, $id)
{
    // ... validasi ...
    
    $pesanan->update(['status_pembayaran' => 'ditolak']);
    
    Pembayaran::where('pemesanan_id', $pesanan->id)
              ->where('status', 'pending')
              ->update([
                  'status'           => 'gagal',
                  'alasan_penolakan' => $request->alasan_penolakan,  // catat alasan
              ]);
}
```

### Informasi yang bisa dilacak
| Info | Kolom di `pembayaran` | Fungsi |
|------|----------------------|--------|
| Kapan dibayar | `dibayar_pada` | Tahu kapan transaksi sukses |
| Berapa kali retry | Hitung jumlah record per `pemesanan_id` | Analisa pola gagal bayar |
| Gagal karena apa | `alasan_penolakan` | Investigasi dispute/komplain |
| Riwayat status | `status` + `created_at`/`updated_at` | Tracking perubahan status |
| Metode tiap attempt | `metode` | Lihat metode mana yg akhirnya dipakai |

### Contoh query audit
```sql
-- Melihat semua percobaan pembayaran untuk order #5
SELECT * FROM pembayaran WHERE pemesanan_id = 5 ORDER BY created_at;

-- Menghitung berapa kali retry
SELECT pemesanan_id, COUNT(*) as total_attempt
FROM pembayaran
WHERE pemesanan_id = 5
GROUP BY pemesanan_id;

-- Rata-rata waktu pembayaran sukses (selisih created_at order vs dibayar_pada)
SELECT AVG(TIMESTAMPDIFF(HOUR, p.created_at, b.dibayar_pada)) as avg_hours
FROM pemesanan p
JOIN pembayaran b ON b.pemesanan_id = p.id
WHERE b.status = 'sukses';
```

---

## 🏗️ Struktur Lengkap Relasi

```
┌──────────────────────┐
│      pemesanan       │  1
│  (data pesanan)      │───┐
│                      │   │
│ id (PK)              │   │
│ user_id (FK)         │   │
│ nama, alamat, telepon│   │
│ total                │   │
│ tanggal              │   │
│ status_pembayaran    │   │
└──────────────────────┘   │
                           │ hasMany
┌──────────────────────┐   │
│      pembayaran      │◄──┘
│  (data pembayaran)   │  N
│                      │
│ id (PK)              │
│ pemesanan_id (FK) ───┘
│ jumlah               │
│ metode               │
│ status               │
│ bukti_pembayaran     │
│ transaction_id       │
│ gateway_response     │
│ alasan_penolakan     │
│ dibayar_pada         │
│ created_at           │
│ updated_at           │
└──────────────────────┘
```

**Relasi di Eloquent (Laravel):**
```php
// app/Models/Pemesanan.php
public function pembayaran() {
    return $this->hasMany(Pembayaran::class, 'pemesanan_id');
}
public function pembayaranTerakhir() {
    return $this->hasOne(Pembayaran::class, 'pemesanan_id')->latestOfMany();
}
public function pembayaranSukses() {
    return $this->hasOne(Pembayaran::class, 'pemesanan_id')->where('status', 'sukses');
}

// app/Models/Pembayaran.php
public function pemesanan() {
    return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
}
```

---

## ✅ Keuntungan yang Didapat

| Aspek | Sebelum (satu tabel) | Sesudah (pisah) |
|-------|---------------------|-----------------|
| **Fleksibilitas** | 1 order = 1 bayar, gagal bikin baru | 1 order = banyak attempt, retry tanpa bikin order baru |
| **Partial payment** | Tidak bisa | Bisa dikembangkan (DP + pelunasan) |
| **Refund** | Refund berarti update status order | Refund = create record baru di pembayaran dengan status 'refund' |
| **Data gateway** | Tabel order kotor dengan kolom payment | Tabel pembayaran menampung semua data gateway |
| **Audit trail** | Sulit melacak riwayat pembayaran | Setiap attempt tercatat dengan timestamp & alasan |
| **Query** | Query jadi ribet campur data logistik & finansial | Query terpisah, lebih sederhana & cepat |
| **Maintenance** | Sulit di-maintain karena campur aduk | Masing-masing tabel punya tanggung jawab jelas |

---

## 💡 Catatan Penting

> **`status_pembayaran` di tabel `pemesanan` adalah *derived/aggregate status* — ringkasan dari status pembayaran terbaru. Detail lengkapnya tetap ada di tabel `pembayaran`.**

Nilai `status_pembayaran` di `pemesanan`:
- `pending` → belum ada pembayaran sukses (masih menunggu / ada attempt pending)
- `lunas` → sudah ada pembayaran dengan status `sukses`
- `ditolak` → semua attempt ditolak admin

Ini adalah **denormalisasi yang disengaja** untuk mempercepat query yang sering dilakukan (misal: daftar pesanan yang perlu dikirim).
