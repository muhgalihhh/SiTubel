<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Tugas Belajar Kota Banjar</title>
    @vite('resources/css/app.css')
    <style>
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 1s ease-out, transform 1s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .fixed-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="relative w-full h-full bg-center bg-cover"
        style="background-image: url('{{ asset('assets/bg-alun-alun.jpg') }}')">
        <div class="absolute inset-0 h-full opacity-50 bg-gradient-to-r from-blue-500 to-green-500"></div>

        <div class="relative flex items-center justify-center min-h-screen z-21">
            <img src="{{ asset('assets/Ellipse.png') }}" class="absolute z-20 w-6 animate-ping left-24 top-56" />
            <img src="{{ asset('assets/Ellipse.png') }}" class="absolute z-20 w-6 animate-ping left-24 top-56" />
            <img src="{{ asset('assets/Ellipse.png') }}" class="absolute z-20 w-6 animate-ping right-96 top-36" />
            <img src="{{ asset('assets/Ellipse.png') }}" class="absolute z-20 w-6 animate-ping left-64 bottom-24" />
            <img src="{{ asset('assets/Ellipse.png') }}" class="absolute z-20 w-6 animate-ping right-40 top-64" />

            <div class="relative flex flex-col items-center text-center z-21">
                <img id="logo" src="{{ asset('Kota Banjar.png') }}" alt="Logo Kota Banjar"
                    class="w-40 h-auto mx-auto fade-in">
                <h1 id="title" class="mb-4 text-5xl font-semibold text-white fade-in">Sistem Tugas Belajar Kota
                    Banjar</h1>
                <p id="description" class="text-white fade-in">Sistem Informasi untuk Pengajuan Pendidikan Lanjutan
                    <br>bagi Pegawai ASN Kota
                    Banjar, Jawa Barat
                </p>
                <div class="flex gap-5">
                    <a href="{{ route('register') }}"
                        class="py-3 mt-5 text-sm font-semibold tracking-wide text-white duration-300 bg-teal-600 border-2 rounded shadow-sm px-7 hover:scale-110 hover:bg-teal-800 fade-in">
                        Daftar (Pegawai)
                    </a>
                    <a href="/pegawai/login"
                        class="box-content py-2 py-3 mt-5 text-sm font-semibold tracking-wide text-white duration-300 border-2 rounded shadow-sm px-7 hover:scale-110 hover:bg-teal-800 fade-in">
                        Login (Pegawai)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Fixed Buttons -->
    <div class="flex flex-col fixed-buttons ">
        <a href="/admin/login"
            class="py-2 text-sm font-semibold tracking-wide text-white underline duration-300 rounded shadow-sm hover:text-blue-500 px-7 hover:scale-110 fade-in">
            Login (BKPSDM)
        </a>
        <a href="/opd/login"
            class="py-2 text-sm font-semibold tracking-wide text-white underline duration-300 rounded shadow-sm hover:text-blue-500 px-7 hover:scale-110 fade-in">
            Login (OPD)
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((element, index) => {
                setTimeout(() => {
                    element.classList.add('visible');
                }, index * 300); // stagger the animation
            });
        });
    </script>
</body>

</html>
