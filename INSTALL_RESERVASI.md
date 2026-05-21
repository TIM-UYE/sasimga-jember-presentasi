# PANDUAN INSTALASI SISTEM RESERVASI CINEMA-STYLE

## PENTING: Langkah Instalasi

### Untuk Pengguna Laragon (Localhost)

1. **Ubah konfigurasi database di `.env`:**
   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=db_samsimga
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. **Jalankan SQL script manual:**
   - Buka phpMyAdmin (http://localhost/phpmyadmin)
   - Pilih database `db_samsimga`
   - Klik tab "SQL"
   - Copy-paste isi file `database/sql/reservasi_tables_setup.sql`
   - Klik "Go" atau "Jalankan"

3. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. **Test API endpoint:**
   Buka browser dan akses: `http://localhost/reservasi/tables?tanggal=2026-05-15&waktu=19:00`
   (Ganti localhost dengan domain Anda jika berbeda)

   Jika berhasil, Anda akan melihat JSON berisi data meja.

### Untuk Pengguna Docker

1. **Jalankan migration dan seeder:**
   ```bash
   php artisan migrate
   php artisan db:seed --class=MejaSeeder
   ```

2. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Test API endpoint:**
   ```bash
   curl "http://localhost/reservasi/tables?tanggal=2026-05-15&waktu=19:00"
   ```

## Troubleshooting

### Masalah: "Meja tidak muncul setelah pilih tanggal/waktu"

**Solusi:**
1. Pastikan database sudah diisi dengan data meja (lihat langkah instalasi di atas)
2. Buka Developer Console (F12) → Tab Console, cek apakah ada error JavaScript
3. Pastikan route `/reservasi/tables` terdaftar. Cek dengan:
   ```bash
   php artisan route:list | findstr tables
   ```
   (Windows) atau
   ```bash
   php artisan route:list | grep tables
   ```
   (Linux/Mac)

### Masalah: Error "No such host is known"

**Solusi:**
- Jika menggunakan Laragon: Ubah `DB_HOST` di `.env` menjadi `localhost`
- Jika menggunakan Docker: Biarkan `DB_HOST=mysql`

### Masalah: Error "Table 'meja' doesn't exist"

**Solusi:**
- Jalankan ulang SQL script `database/sql/reservasi_tables_setup.sql`
- Atau jalankan `php artisan migrate` jika menggunakan Docker

### Masalah: Error "Class 'MejaSeeder' not found"

**Solusi:**
```bash
composer dump-autoload
```

## Verifikasi Instalasi

Setelah instalasi, verifikasi dengan langkah berikut:

1. **Cek database:**
   ```sql
   SELECT * FROM meja LIMIT 5;
   ```
   Harus muncul 5 meja pertama.

2. **Test API:**
   ```
   GET /reservasi/tables?tanggal=2026-05-15&waktu=19:00
   ```
   Response harus berisi array tables.

3. **Test frontend:**
   - Buka halaman `/reservasi`
   - Pilih tanggal (besok atau lebih)
   - Pilih waktu (misal: 19:00)
   - Grid meja harus muncul dengan 12 meja

## Kontak Support

Jika masih mengalami masalah, silakan hubungi tim developer dengan menyertakan:
- Screenshot error di console browser (F12)
- Output dari `php artisan route:list | grep reservasi`
- Hasil query `SELECT COUNT(*) FROM meja;`
