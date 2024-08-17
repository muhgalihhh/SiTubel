# Sistem Pengelolaan Izin Belajar PNS Kota Banjar

Selamat datang di Sistem Pengelolaan Izin Belajar bagi Pegawai Negeri Sipil (PNS) Kota Banjar. Sistem ini dirancang untuk mempermudah proses pengajuan dan pengelolaan izin belajar secara online, menggantikan proses manual yang memakan waktu dan rentan terhadap kesalahan.

## Fitur Utama

- **Pengajuan Izin Belajar**: PNS dapat mengajukan izin belajar secara online.
- **Notifikasi Otomatis**: Sistem mengirimkan notifikasi otomatis kepada pengguna terkait setiap ada perubahan status.
- **Manajemen Berkas**: Upload dan kelola dokumen persyaratan dengan mudah.
- **Role-based Access**: Akses ke fitur sistem berdasarkan peran pengguna (Admin, OPD, PNS).

## Teknologi yang Digunakan

### Bahasa Pemrograman

- **PHP**: Digunakan untuk logika backend dan pengelolaan data.
- **JavaScript**: Digunakan untuk interaksi frontend dan AJAX.

### Framework

- **Laravel**: Framework PHP untuk pengembangan backend yang efisien.
- **Filament**: Untuk membangun interface admin yang dinamis dan responsif.

### Library

- **Bootstrap**: Library CSS untuk styling yang responsif.
- **jQuery**: Mempermudah manipulasi DOM dan AJAX.

## Instalasi

Pastikan Anda sudah menginstal Composer dan Node.js.

1. Clone repository ini:
    ```bash
    git clone https://github.com/username/repository.git
    cd repository
    ```

2. Install dependencies PHP dan JavaScript:
    ```bash
    composer install
    npm install
    ```

3. Copy file `.env.example` menjadi `.env` dan sesuaikan pengaturan database:
    ```bash
    cp .env.example .env
    ```

4. Generate key aplikasi Laravel:
    ```bash
    php artisan key:generate
    ```

5. Jalankan migrasi database:
    ```bash
    php artisan migrate
    ```

6. Jalankan server pengembangan:
    ```bash
    php artisan serve
    ```

## Penggunaan Aplikasi

### Pengajuan Izin Belajar

1. **Login**: Masuk ke aplikasi dengan akun PNS Anda.
2. **Formulir Pengajuan**: Navigasikan ke halaman pengajuan izin belajar dan isi formulir yang disediakan.
3. **Upload Berkas**: Unggah dokumen yang diperlukan, seperti surat usulan.
4. **Submit**: Klik tombol "Ajukan" untuk mengirim pengajuan izin belajar.

### Notifikasi

Setelah pengajuan dilakukan, Anda akan menerima notifikasi mengenai status pengajuan Anda.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Izin Belajar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Pengajuan Izin Belajar</h2>
        <form action="/submit-permission" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Pegawai</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="unit_kerja" class="form-label">Unit Kerja</label>
                <input type="text" class="form-control" id="unit_kerja" name="unit_kerja" required>
            </div>
            <div class="mb-3">
                <label for="surat_usulan" class="form-label">Surat Usulan</label>
                <input type="file" class="form-control" id="surat_usulan" name="surat_usulan" accept=".pdf,image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajukan Izin Belajar</button>
        </form>
    </div>
</body>
</html>
