<?php

namespace App\Filament\Resources\PengajuanIzinSeleksiResource\Pages;

use App\Filament\Resources\PengajuanIzinSeleksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanIzinSeleksi extends EditRecord
{
    protected static string $resource = PengajuanIzinSeleksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
