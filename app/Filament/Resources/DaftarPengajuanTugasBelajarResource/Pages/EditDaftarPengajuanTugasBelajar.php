<?php

namespace App\Filament\Resources\DaftarPengajuanTugasBelajarResource\Pages;

use App\Filament\Resources\DaftarPengajuanTugasBelajarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDaftarPengajuanTugasBelajar extends EditRecord
{
    protected static string $resource = DaftarPengajuanTugasBelajarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
