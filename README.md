# Sistem Absensi Siswa SMA Yadika

Aplikasi web untuk mengelola absensi siswa di SMA Yadika. Sistem ini dibangun menggunakan Laravel 12 dengan Tailwind CSS dan Alpine.js untuk antarmuka yang responsif dan modern.

## 📋 Fitur

-   ✅ Manajemen absensi siswa harian
-   👨‍🏫 Manajemen data guru dan mata pelajaran
-   📊 Laporan absensi dan rekap data
-   👥 Manajemen kelas dan jadwal pelajaran
-   📤 Export data ke Excel/PDF
-   📱 Interface responsif untuk mobile dan desktop

## 🛠️ Requirements

Pastikan sistem Anda memiliki requirements berikut:

-   **PHP**: >= 8.2
-   **Composer**: Latest version
-   **Node.js**: >= 18.x
-   **NPM**: >= 9.x
-   **Database**: SQLite (default) atau MySQL
-   **Web Server**: Apache/Nginx atau PHP built-in server

## 🚀 Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/RyuZenc/AbsensiSiswa_Yadika.git
cd AbsensiSiswa_Yadika
```

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Install Dependencies JavaScript

```bash
npm install
```

### 4. Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Konfigurasi Database

Edit file `.env` dan sesuaikan pengaturan database:

```env
# Untuk SQLite (Recommended)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Untuk MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=absensi_yadika
# DB_USERNAME=root
# DB_PASSWORD=

# Session Configuration
SESSION_DRIVER=file
```

### 6. Setup Database

```bash
# Buat file database SQLite (jika menggunakan SQLite)
touch database/database.sqlite

# Jalankan migrasi database
php artisan migrate

# Jalankan seeder untuk data awal
php artisan db:seed
```

### 7. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Jalankan Aplikasi

```bash
# Jalankan server development
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🎯 Cara Penggunaan

### Login ke Sistem

1. Buka browser dan akses `http://localhost:8000`
2. Login menggunakan akun default:
    - **Admin**: `admin@yadika.com` / `password`
    - **Guru**: `guru@yadika.com` / `password`

### Manajemen Data Master

#### 1. Manajemen Guru

-   Akses menu **Guru** di sidebar
-   Klik **Tambah Guru** untuk menambah data guru baru
-   Import data guru dalam jumlah banyak menggunakan fitur **Import Excel**

#### 2. Manajemen Siswa

-   Akses menu **Siswa** di sidebar
-   Klik **Tambah Siswa** untuk menambah data siswa baru
-   Siswa dapat dikelompokkan berdasarkan kelas

#### 3. Manajemen Kelas

-   Akses menu **Kelas** untuk membuat dan mengelola kelas
-   Setiap kelas dapat memiliki wali kelas

#### 4. Manajemen Mata Pelajaran

-   Akses menu **Mata Pelajaran** untuk mengelola mapel
-   Mapel dapat dihubungkan dengan guru pengampu

#### 5. Jadwal Pelajaran

-   Akses menu **Jadwal** untuk mengatur jadwal mengajar
-   Jadwal menghubungkan guru, kelas, dan mata pelajaran

### Absensi Harian

#### 1. Input Absensi

-   Guru dapat mengakses **Absensi Harian**
-   Pilih kelas dan mata pelajaran
-   Tandai kehadiran siswa: Hadir (H), Izin (I), Sakit (S), Alpha (A)

#### 2. Sesi Absensi

-   Sistem mendukung multiple sesi per hari
-   Setiap sesi dapat memiliki mata pelajaran yang berbeda

### Laporan dan Rekap

#### 1. Laporan Harian

-   Akses **Laporan** > **Harian** untuk melihat absensi per hari
-   Data dapat difilter berdasarkan tanggal dan kelas

#### 2. Rekap Bulanan

-   Akses **Laporan** > **Bulanan** untuk rekap per bulan
-   Menampilkan persentase kehadiran siswa

#### 3. Export Data

-   Semua laporan dapat diekspor ke format Excel atau PDF
-   Gunakan tombol **Export** pada setiap halaman laporan

## 🔧 Pengembangan

### Menjalankan dalam Mode Development

```bash
# Terminal 1: Jalankan Laravel server
php artisan serve

# Terminal 2: Watch untuk perubahan asset
npm run dev

# Terminal 3: Jalankan queue worker (opsional)
php artisan queue:work
```

### Struktur Project

```
app/
├── Http/Controllers/     # Controllers untuk handle request
├── Models/              # Model Eloquent
├── Exports/             # Classes untuk export Excel
├── Imports/             # Classes untuk import Excel
resources/
├── views/               # Blade templates
├── css/                 # Tailwind CSS
├── js/                  # Alpine.js dan JavaScript
database/
├── migrations/          # Database migrations
├── seeders/             # Database seeders
```

### Menambah Fitur Baru

1. **Buat Migration**: `php artisan make:migration nama_migration`
2. **Buat Model**: `php artisan make:model NamaModel`
3. **Buat Controller**: `php artisan make:controller NamaController`
4. **Buat View**: Tambahkan file blade di `resources/views/`

## 🚨 Troubleshooting

### Error "Class not found"

```bash
composer dump-autoload
```

### Error Permission

```bash
chmod -R 775 storage bootstrap/cache
```

### Error Database

```bash
php artisan migrate:fresh --seed
```

### Error Assets

```bash
npm run build
php artisan view:clear
```

## 📝 Backup dan Restore

### Backup Database

```bash
# SQLite
cp database/database.sqlite backup/database_backup_$(date +%Y%m%d).sqlite

# MySQL
mysqldump -u username -p database_name > backup/backup_$(date +%Y%m%d).sql
```

### Backup Files

```bash
tar -czf backup/files_backup_$(date +%Y%m%d).tar.gz storage/app/public
```

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch fitur: `git checkout -b fitur-baru`
3. Commit perubahan: `git commit -am 'Tambah fitur baru'`
4. Push ke branch: `git push origin fitur-baru`
5. Buat Pull Request

## 📄 License

Project ini menggunakan [MIT License](LICENSE).

## 📞 Support

Untuk bantuan teknis atau pertanyaan:

-   Email: bs.dhimas@gmail.com
-   GitHub Issues: [Buat Issue Baru](https://github.com/RyuZenc/AbsensiSiswa_Yadika/issues)

---

**Built with ❤️ for SMA Yadika**
