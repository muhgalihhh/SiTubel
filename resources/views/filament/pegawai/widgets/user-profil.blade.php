<x-filament-widgets::widget>
    <x-filament::card>
        @php
            $user = Auth::user();
            $pegawai = $user->pegawai;
        @endphp

        <div class="flex justify-between p-10 mb-10">
            <div class="flex flex-col items-center">
                <div class="flex items-center justify-center mb-5 border-black rounded-md shadow-sm border-1">
                    @if ($pegawai && $pegawai->foto_pegawai)
                        <img src="{{ asset('storage/' . $pegawai->foto_pegawai) }}" alt="{{ $user->name }}"
                            class="object-cover mb-5 rounded-full" style="width: 100px; height: 100px;">
                    @else
                        <img src="https://picsum.photos/500" class="object-cover mb-5 rounded-full"
                            style="width: 100px; height: 100px;">
                    @endif
                </div>

                <h2 class="mt-4 text-xl font-semibold">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
                @if ($pegawai && $pegawai->bio)
                    <p class="mt-2 text-gray-600">{{ $pegawai->unit_kerja }}</p>
                @endif
            </div>

            <div class="flex flex-col">
                <h2 class="text-xl font-semibold">Detail Profil</h2>
                <div class="mt-4">
                    <p class="text-gray-600">Nama: {{ $user->name }}</p>
                    <p class="text-gray-600">Email: {{ $user->email }}</p>
                    @if ($pegawai)
                        <p class="text-gray-600">NIP: {{ $pegawai->nip }}</p>
                        <p class="text-gray-600">Unit Kerja: {{ $pegawai->unit_kerja }}</p>
                    @else
                        <p class="text-gray-600">NIP: -</p>
                        <p class="text-gray-600">Unit Kerja: -</p>
                    @endif
                </div>

                <!-- Button to open the modal -->
                <x-filament::button x-data @click="$dispatch('open-modal', { id: 'editProfileModal' })" class="mt-4">
                    Ubah Profil
                </x-filament::button>
            </div>
        </div>
    </x-filament::card>

    <!-- Modal definition -->
    <x-filament::modal id="editProfileModal" width="5xl">

        <!-- Modal header -->
        <x-slot name="header">
            <h2 class="text-lg font-semibold ">Ubah Profil</h2>
        </x-slot>
        <form wire:submit.prevent="save">
            {{ $this->form }}

            <div class="flex justify-end mt-4">
                <x-filament::button type="submit">
                    Simpan
                </x-filament::button>
            </div>
        </form>
    </x-filament::modal>
</x-filament-widgets::widget>
