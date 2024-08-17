<x-filament-widgets::widget>
    <x-filament::section>
        @php
            $name = auth()->user()->name;
            $role = auth()->user()->role;
        @endphp
        <div x-data="{ open: false }">
            <h2 class="text-2xl font-semibold">Selamat Datang!

                @if ($role == 'admin')
                    Admin
                @elseif($role == 'pegawai')
                    Pegawai
                @elseif($role == 'opd')
                    Kasubag
                @endif

                {{ $name }}

            </h2>
            <p class="text-justify">
                Sistem Pengajuan Tugas Belajar Mandiri Kota Banjar adalah sebuah platform yang dirancang untuk membantu
                para Pegawai Negeri Sipil (PNS) di Kota Banjar dalam mengelola dan mengajukan permohonan tugas belajar.
                Sistem ini bertujuan untuk memfasilitasi proses pengajuan tugas belajar agar lebih efisien, transparan,
                dan terstruktur. Dengan memanfaatkan teknologi, sistem ini memberikan kemudahan bagi PNS dalam mengakses
                informasi terkait tugas belajar, mulai dari persyaratan, alur pengajuan, hingga proses verifikasi dan
                persetujuan.
                <span x-show="!open">...</span>
                <span x-show="open">
                    <br><br>
                    Sistem ini juga dilengkapi dengan berbagai fitur yang dirancang untuk mempermudah proses
                    administrasi, seperti notifikasi otomatis, pengelolaan dokumen digital, dan pelacakan status
                    pengajuan secara real-time. Selain itu, pengguna dapat dengan mudah mengakses sistem ini kapan saja
                    dan di mana saja melalui perangkat komputer maupun smartphone. Tujuan utama dari pengembangan sistem
                    ini adalah untuk mendukung peningkatan kualitas sumber daya manusia di lingkungan pemerintah Kota
                    Banjar, dengan memberikan kesempatan kepada PNS untuk terus mengembangkan kemampuan dan pengetahuan
                    mereka melalui pendidikan formal.
                    <br><br>
                    Dalam jangka panjang, diharapkan sistem ini tidak hanya mempermudah proses pengajuan tugas belajar,
                    tetapi juga meningkatkan akuntabilitas dan transparansi dalam pengelolaan tugas belajar di
                    lingkungan pemerintah. Semua data yang diinput oleh pengguna akan tersimpan secara aman dan dapat
                    diakses oleh pihak-pihak yang berwenang, sehingga meminimalisir kemungkinan terjadinya kesalahan
                    atau penyalahgunaan data. Dengan demikian, Sistem Pengajuan Tugas Belajar Mandiri Kota Banjar tidak
                    hanya menjadi alat bantu, tetapi juga menjadi bagian dari upaya untuk mendorong budaya kerja yang
                    lebih profesional dan berorientasi pada hasil.
                </span>
            </p>
            <button x-on:click="open = ! open" class="mt-4 text-blue-500 underline">
                <span x-show="!open">Read More</span>
                <span x-show="open">Read Less</span>
            </button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
