<!-- Banner -->
![Sistem Pengajuan Tugas Belajar Mandiri](https://github.com/muhgalihhh/SiTubel/blob/Muhamad-galih/banner.jpg)

# Sistem Pengajuan Tugas Belajar Mandiri PNS Kota Banjar

Selamat datang di Sistem Pengajuan Tugas Belajar Mandiri bagi Pegawai Negeri Sipil (PNS) Kota Banjar. Sistem ini dirancang untuk mempermudah proses pengajuan dan pengelolaan tugas belajar secara online, menggantikan proses manual yang memakan waktu dan rentan terhadap kesalahan.

## 🚀 Fitur Utama

- **Pengajuan Tugas Belajar**: PNS dapat mengajukan tugas belajar secara online.
- **Notifikasi Otomatis**: Sistem mengirimkan notifikasi otomatis kepada pengguna terkait setiap ada perubahan status.
- **Manajemen Berkas**: Upload dan kelola dokumen persyaratan dengan mudah.
- **Role-based Access**: Akses ke fitur sistem berdasarkan peran pengguna (Admin, OPD, PNS).

## 🛠️ Teknologi yang Digunakan

### Bahasa Pemrograman

<p align="left">
    <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
    <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript" />
</p>

### Framework

<p align="left">
    <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/Filament-3182CE?style=for-the-badge&logo=filament&logoColor=white" alt="Filament" />
</p>

### Library

<p align="left">
    <img src="https://img.shields.io/badge/Tailwind%20CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white" alt="jQuery" />
</p>

## 📦 Instalasi

Pastikan Anda sudah menginstal [Composer](https://getcomposer.org/) dan [Node.js](https://nodejs.org/).

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

    Sesuaikan `.env` dengan konfigurasi yang Anda butuhkan:
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_dbname
    DB_USERNAME=your_dbusername
    DB_PASSWORD=your_dbpassword
    ```

5. Generate key aplikasi Laravel:
    ```bash
    php artisan key:generate
    ```

6. Jalankan migrasi database:
    ```bash
    php artisan migrate
    ```

7. Jalankan server pengembangan:
    ```bash
    php artisan serve
    ```

      ```bash
    npm run dev
    ```

## 💡 Contributing

Kontribusi selalu diterima! Silakan fork repository ini dan buat pull request dengan perubahan yang Anda ajukan.

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
