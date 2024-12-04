<div class="max-w-lg p-5 mx-auto bg-gray-100 rounded-lg shadow-lg">
    @if (session()->has('message'))
        <div class="p-4 mb-5 text-center bg-white rounded-lg shadow-md">
            <div class="p-4 mb-3 text-white bg-green-500 rounded">
                <p class="text-lg font-semibold">{{ session('message') }}</p>
            </div>
            <p class="text-sm text-gray-700">
                Mohon Hubungi Admin Untuk Pembuatan Akun:
                <br />
                <span class="font-bold">No BKPSDM:</span> (0265) 743072
                <br />
                <span class="font-bold">Email:</span> bkd.kotabanjar@gmail.com
            </p>
            <a href="/"
                class="px-8 py-2 text-sm font-bold text-white bg-yellow-500 rounded-lg shadow hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-300">
                Kembali
            </a>
        </div>
    @else
        <form class="text-center" method="post" wire:submit="save">
            <div class="mb-4">
                {{ $this->form }}
            </div>
            <div class="flex justify-center gap-4 mt-5">
                <button type="submit"
                    class="px-8 py-2 text-sm font-bold text-white bg-green-500 rounded-lg shadow hover:bg-green-600 focus:ring-2 focus:ring-green-300">
                    Simpan
                </button>
                <a href="/"
                    class="px-8 py-2 text-sm font-bold text-white bg-yellow-500 rounded-lg shadow hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-300">
                    Kembali
                </a>
            </div>
        </form>
    @endif
</div>
