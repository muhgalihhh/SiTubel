<div>
    @if (session()->has('message'))
        <div class="p-2 bg-white">
            <div class="p-3 text-white bg-green-500">
                {{ session('message') }}
            </div>
            <p>
                Mohon Hubungi Admin Untuk Pembuatan Akun
            </p>
        </div>
    @else
        <form class="text-center" method="post" wire:submit="save">
            {{ $this->form }}
            <div class="flex justify-center gap-3 mx-auto mt-3">
                <button type="submit" class="px-10 py-2 font-bold text-white bg-green-500 rounded hover:bg-blue-700">
                    Simpan
                </button>
                <a href="/" class="px-10 py-2 font-bold text-white bg-yellow-500 rounded hover:bg-blue-700">
                    Kembali
                </a>
            </div>
        </form>
    @endif
</div>
