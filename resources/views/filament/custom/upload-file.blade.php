<div>
    <x-filament::breadcrumbs :breadcrumbs="[
        '/admin/unit-kerjas' => 'UnitKerja',
        '' => 'List',
    ]" />

    <div class="flex justify-between mt-1">
        <div class="text-3xl font-bold">Unit Kerja</div>
        <div>
            {{ $data }}
        </div>
    </div>

    <div>
        <form wire:submit="save" class="flex w-full max-w-sm mt-2">
            <div class="mb-4">
                <label for="fileInput" class="block mb-2 text-sm text-gray-700 font-boold">Pilih Berkas</label>
                <input
                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                    id="fileInput" type="file" wire:model="file" accept=".csv, .xlsx, .xls" />
            </div>

            <div class="flex items-center justify-between mt-3">
                <button
                    class="px-4 py-2 font-bold text-white bg-teal-500 rounded hover:bg-teal-700 focus:outline-none focus:shadow-outline"
                    type="submit">
                    Unggah </button>
            </div>

        </form>
    </div>
</div>
