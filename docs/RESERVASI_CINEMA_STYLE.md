# Reservasi Meja Cinema-Style

## Overview

Fitur reservasi meja dengan tampilan interaktif seperti memilih kursi di bioskop. Pengguna dapat melihat ketersediaan meja secara visual dan memilih meja yang diinginkan.

## Fitur Utama

1. **Visual Table Selection** - Tampilan grid meja seperti denah bioskop
2. **Real-time Availability** - Menampilkan meja yang tersedia vs sudah dipesan
3. **12-Hour Validation** - Reservasi harus dilakukan minimal 12 jam sebelum waktu reservasi
4. **12 Tables, 4 Seats Each** - Total 12 meja dengan kapasitas 4 orang per meja
5. **Multiple Table Selection** - Pengguna dapat memilih lebih dari 1 meja

## Instalasi

### Opsi 1: Menggunakan Migration (Docker)

Jika Anda menggunakan Docker, jalankan:

```bash
php artisan migrate
php artisan db:seed --class=MejaSeeder
```

### Opsi 2: Manual SQL

Jika Anda menggunakan Laragon atau MySQL lokal, jalankan file SQL:

```bash
# Di phpMyAdmin atau MySQL CLI, import file:
database/sql/reservasi_tables_setup.sql
```

Atau copy-paste isi file SQL tersebut ke query window.

## Konfigurasi Database

Pastikan `.env` Anda sudah dikonfigurasi dengan benar:

```env
DB_CONNECTION=mysql
DB_HOST=localhost  # Atau mysql jika menggunakan Docker
DB_PORT=3306
DB_DATABASE=db_samsimga
DB_USERNAME=root
DB_PASSWORD=
```

## Struktur Database

### Tabel `meja`
Menyimpan data meja/restoran:
- `id` - Primary key
- `nama_meja` - Nama meja (e.g., "Meja A1", "Meja B2")
- `kategori` - regular (semua meja adalah regular)
- `kapasitas` - 4 orang per meja
- `posisi_row` - Baris (A, B, C)
- `posisi_col` - Kolom (1, 2, 3, 4)
- `is_active` - Status aktif/meja

### Tabel `kursi_reservasi`
Menyimpan ketersediaan meja per sesi waktu:
- `id` - Primary key
- `meja_id` - Foreign key ke meja
- `tanggal` - Tanggal reservasi
- `waktu_sesi` - Waktu sesi reservasi
- `tersedia` - Boolean, true jika tersedia
- `reservasi_id` - Foreign key ke reservasi (nullable)

### Tabel `reservasis` (updated)
Ditambahkan kolom:
- `meja_ids` - JSON array berisi ID meja yang dipilih

## Cara Kerja

### Frontend (Pengguna)

1. Pengguna membuka halaman `/reservasi`
2. Mengisi form: Nama, No. WhatsApp, Tanggal, Waktu, Jumlah Orang
3. Setelah memilih tanggal & waktu, sistem akan memuat grid meja
4. Meja yang tersedia ditampilkan dengan warna abu-abu
5. Meja yang sudah dipesan ditampilkan dengan opacity 50% (tidak bisa diklik)
6. Pengguna klik meja yang diinginkan (berubah jadi orange)
7. Klik "Pesan Sekarang" untuk submit

### Backend (Validasi)

1. **Validasi 12 Jam**: Sistem mengecek apakah waktu reservasi minimal 12 jam dari sekarang
2. **Validasi Ketersediaan**: Mengecek ulang apakah meja belum dipesan
3. **Create Reservasi**: Menyimpan data reservasi + meja_ids
4. **Update Ketersediaan**: Menandai meja sebagai "booked" di `kursi_reservasi`
5. **Send Notification**: Mengirim notifikasi WhatsApp

### Admin Panel

Admin dapat melihat:
- Daftar reservasi dengan meja yang dipilih
- Status reservasi (Pending, Dikonfirmasi, Dibatalkan, Selesai)
- Mengubah status reservasi
- Ketika dibatalkan, meja otomatis dibebaskan

## API Endpoints

### Get Available Tables
```
GET /reservasi/tables?tanggal=2026-05-12&waktu=19:00
```

Response:
```json
{
  "tables": [
    {
      "id": 1,
      "nama_meja": "Meja A1",
      "kategori": "regular",
      "kapasitas": 4,
      "posisi_row": "A",
      "posisi_col": 1,
      "is_available": true
    },
    ...
  ]
}
```

### Store Reservation
```
POST /reservasi
Content-Type: application/json

{
  "nama": "John Doe",
  "nomor_wa": "081234567890",
  "tanggal_reservasi": "2026-05-12",
  "waktu_reservasi": "19:00",
  "jumlah_orang": 4,
  "meja_ids": [1, 2]
}
```

## Default Table Layout

Sistem datang dengan 12 meja default (semua regular, 4 kursi per meja):

| Row | Quantity | Capacity |
|-----|----------|----------|
| A   | 4        | 4 orang  |
| B   | 4        | 4 orang  |
| C   | 4        | 4 orang  |

Total: **12 meja × 4 kursi = 48 kursi**

## Customization

### Mengubah Layout Meja

Edit file `database/seeders/MejaSeeder.php`:

```php
$tables = [
    ['nama_meja' => 'Meja X1', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'X', 'posisi_col' => 1],
    // ... tambah/meja lain
];
```

Kemudian jalankan:
```bash
php artisan db:seed --class=MejaSeeder
```

### Mengubah Validasi 12 Jam

Edit file `app/Http/Controllers/ReservasiController.php`:

```php
// Ubah angka 12 menjadi jumlah jam yang diinginkan
$minReservationTime = $now->copy()->addHours(12);
```

### Mengubah Tampilan

Edit file `resources/views/frontend/sections/reservasi.blade.php`

## Troubleshooting

### Meja tidak muncul setelah pilih tanggal/waktu

1. Pastikan tabel `meja` sudah terisi data
2. Cek console browser untuk error JavaScript
3. Pastikan route `/reservasi/tables` bisa diakses

### Error "No such host is known"

Ubah `DB_HOST` di `.env`:
- Docker: `mysql`
- Laragon/Local: `localhost` atau `127.0.0.1`

### Error migration foreign key

Pastikan urutan migration benar atau jalankan:
```bash
php artisan migrate:fresh --seed
```

## Files Modified/Created

### New Files:
- `database/migrations/2026_05_11_000106_create_tables_and_seats_table.php`
- `database/migrations/2026_05_11_000441_add_meja_ids_to_reservasis_table.php`
- `app/Models/Meja.php`
- `app/Models/KursiReservasi.php`
- `database/seeders/MejaSeeder.php`
- `database/sql/reservasi_tables_setup.sql`

### Modified Files:
- `app/Models/Reservasi.php` - Added relationships & methods
- `app/Http/Controllers/ReservasiController.php` - Added validation & availability logic
- `resources/views/frontend/sections/reservasi.blade.php` - New cinema-style UI
- `resources/views/admin/reservasi/index.blade.php` - Added meja column
- `routes/web.php` - Added `/reservasi/tables` route
