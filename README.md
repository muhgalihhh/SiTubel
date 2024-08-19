<!-- Banner -->
![Sistem Pengajuan Tugas Belajar Mandiri](https://github.com/muhgalihhh/SiTubel/blob/Muhamad-galih/banner.jpg)

# **Sistem Pengajuan Tugas Belajar Mandiri PNS Kota Banjar**

Selamat datang di **Sistem Pengajuan Tugas Belajar Mandiri** bagi **Pegawai Negeri Sipil (PNS) Kota Banjar**. Sistem ini dirancang untuk mempermudah proses pengajuan dan pengelolaan tugas belajar secara online, menggantikan proses manual yang memakan waktu dan rentan terhadap kesalahan.

## 🚀 **Fitur Utama**

- **Pengajuan Izin Seleksi**: Sebelum mengajukan Tugas Belajar, PNS harus melakukan pengajuan Izin Seleksi terlebih dahulu 
- **Pengajuan Tugas Belajar**: PNS dapat mengajukan tugas belajar secara online melalui antarmuka yang sederhana.
- **Manajemen Data Konfigurasi**:
  - **Ubah Profil**: Pengguna dapat memperbarui informasi profil mereka secara mudah.
  - **Manajemen Pegawai**: Admin dapat menambah, mengedit, dan menghapus data pegawai.
  - **Manajemen Unit Kerja**: Admin dapat mengelola data unit kerja yang ada di organisasi.
- **Notifikasi Otomatis**: Sistem secara otomatis mengirimkan notifikasi kepada pengguna setiap kali ada perubahan status pengajuan.
- **Manajemen Berkas**: Memudahkan pengguna dalam mengupload dan mengelola dokumen persyaratan.
- **Role-based Access**: Akses sistem terstruktur berdasarkan peran pengguna (**Admin**, **OPD**, **PNS**) untuk meningkatkan keamanan dan efisiensi.
- **Generate-Surat** : Memudahkan pengguna dalam membuat surat kebutuhan untuk melakukan pengajuan

## 👥 **Role Pengguna**

Sistem ini memiliki tiga peran utama yang masing-masing memiliki hak akses dan tanggung jawab berbeda:

- **Admin**: Memiliki akses penuh ke semua fitur sistem, termasuk pengelolaan data pegawai dan unit kerja, serta dapat mengelola pengajuan tugas belajar dan notifikasi. Admin bertanggung jawab untuk konfigurasi dan manajemen sistem secara keseluruhan.
  
- **Pegawai**: Pengguna dengan peran ini dapat mengajukan tugas belajar, mengelola profil pribadi mereka, dan melihat status pengajuan mereka. Pegawai tidak memiliki akses untuk mengelola data pegawai atau unit kerja.
  
- **OPD (Organisasi Perangkat Daerah)**: Pengguna dengan peran ini memiliki akses untuk melihat dan mengelola pengajuan tugas belajar dalam unit kerja mereka. OPD dapat memberikan persetujuan atau penolakan terhadap pengajuan tugas belajar yang masuk.

## 🛠️ **Teknologi yang Digunakan**

### **Bahasa Pemrograman**

<p align="left">
    <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
    <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript" />
</p>

- **PHP**: Bahasa pemrograman server-side yang digunakan untuk membangun aplikasi web dinamis. PHP menangani logika backend, komunikasi database, dan pemrosesan server.
- **JavaScript**: Bahasa pemrograman yang digunakan di sisi klien untuk menambahkan interaktivitas dan dinamisme pada antarmuka pengguna. JavaScript berperan dalam pengelolaan tampilan dan interaksi pengguna.

### **Framework**

<p align="left">
    <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/Filament-3182CE?style=for-the-badge&logo=filament&logoColor=white" alt="Filament" />
</p>

- **Laravel**: Framework PHP yang mempermudah pengembangan aplikasi web dengan menyediakan struktur yang bersih dan beragam fitur canggih seperti routing, middleware, dan ORM (Eloquent).
- **Filament**: Paket Laravel untuk membangun antarmuka admin yang efisien dan mudah digunakan. Filament membantu dalam pembuatan dasbor, formulir, dan pengelolaan data dengan cepat.

### **Library**

<p align="left">
    <img src="https://img.shields.io/badge/Tailwind%20CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white" alt="jQuery" />
</p>

- **Tailwind CSS**: Framework CSS utilitas-first yang memungkinkan pengembangan antarmuka pengguna dengan desain responsif dan modern menggunakan kelas-kelas yang sudah ditentukan.
- **jQuery**: Library JavaScript yang menyederhanakan manipulasi DOM, pengelolaan event, dan komunikasi AJAX. Meskipun kurang umum pada proyek baru, jQuery masih digunakan untuk mempercepat pengembangan web.

### **Database**

<p align="left">
    <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
</p>

- **MySQL**: Sistem manajemen basis data relasional open-source yang digunakan untuk menyimpan dan mengelola data aplikasi. MySQL sangat efisien dalam menangani data yang besar dan mendukung berbagai fitur seperti transaksi dan pemulihan data.

## 📦 **Instalasi**

Pastikan Anda sudah menginstal [Composer](https://getcomposer.org/), [Node.js](https://nodejs.org/), dan [MySQL](https://www.mysql.com/).

1. **Clone repository ini**:
    ```bash
    git clone https://github.com/muhgalihhh/SiTubel.git
    cd SiTubel
    ```

2. **Install dependencies PHP dan JavaScript**:
    ```bash
    composer install
    npm install
    ```

3. **Setup database MySQL**:
    - Buat database baru di MySQL:
        ```sql
        CREATE DATABASE your_dbname;
        ```

4. **Copy file `.env.example` menjadi `.env` dan sesuaikan pengaturan database**:
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

5. **Generate key aplikasi Laravel**:
    ```bash
    php artisan key:generate
    ```

6. **Jalankan migrasi database**:
    ```bash
    php artisan migrate
    ```

7. **Jalankan server pengembangan**:
    ```bash
    php artisan serve
    ```

8. **Jalankan proses build JavaScript**:
    ```bash
    npm run dev
    ```

## 💡 **Contributing**

Kontribusi selalu diterima! Silakan fork repository ini dan buat pull request dengan perubahan yang Anda ajukan.

## 📄 **Lisensi**

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
