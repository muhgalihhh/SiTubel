<?php

namespace App\Filament\Resources\UnitKerjaResource\Pages;

use Filament\Actions;
use App\Models\UnitKerja;
use App\Imports\ImportUnitKerja;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\UnitKerjaResource;

class ManageUnitKerjas extends ManageRecords
{
    protected static string $resource = UnitKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getHeader(): ?View
    {
        $data = Actions\CreateAction::make();
        return view('filament.custom.upload-file', compact('data'));
    }

    public $file = '';

    public function save()
    {
        // UnitKerja::create([]);

        if ($this->file != '') {
            Excel::import(new ImportUnitKerja, $this->file);
        }
    }
}
