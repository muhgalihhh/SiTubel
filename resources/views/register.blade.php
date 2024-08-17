<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Tugas Belajar Kota Banjar</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body>
    <div class="relative h-full p-4 bg-center bg-cover"
        style="background-image: url('{{ asset('assets/bg-alun-alun.jpg') }}')">
        <div class="absolute inset-0 h-full opacity-50 bg-gradient-to-r from-blue-500 to-green-500"></div>
        {{-- <nav class="relative z-10 flex items-center justify-between px-5 py-2 bg-white">
            <img src="{{ asset('Kota Banjar.png') }}" alt="Logo Kota Banjar" class="w-20 h-auto">
            <ul>
                <li class="inline-block px-5 font-semibold list-none"><a href="#"
                        class="text-black no-underline">Simpeg
                        Banjar</a>
                </li>
                <button
                    class="py-3 text-sm font-semibold tracking-wide duration-300 bg-teal-600 rounded-full px-7 hover:scale-110">
                    Login
                </button>
            </ul>
        </nav> --}}

        <div class="relative flex items-center justify-center w-full min-h-screen z-21">
            <img src="{{ asset('assets/Ellipse.png') }}" class="absolute z-20 w-6 animate-ping left-24 top-56" />
            <img src="{{ asset('assets/Ellipse.png') }}" class="absolute z-20 w-6 animate-ping left-24 top-56" />
            <img src="{{ asset('assets/Ellipse.png') }}" class="absolute z-20 w-6 animate-ping right-96 top-36" />
            <img src="{{ asset('assets/Ellipse.png') }}" class="absolute z-20 w-6 animate-ping left-64 bottom-24" />
            <img src="{{ asset('assets/Ellipse.png') }}" class="absolute z-20 w-6 animate-ping right-40 top-64" />
            <div class="w-4/5 p-3 rounded-lg md:w-3/4 sm:w-3/4 h-1/2 bg-slate-200 lg:w-1/2">
                @livewire('home')
            </div>
        </div>
    </div>
    @livewireScripts
</body>

</html>
