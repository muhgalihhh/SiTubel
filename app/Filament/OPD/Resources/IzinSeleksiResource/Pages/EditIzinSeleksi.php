<?php

namespace App\Filament\OPD\Resources\IzinSeleksiResource\Pages;

use App\Filament\OPD\Resources\IzinSeleksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIzinSeleksi extends EditRecord
{
    protected static string $resource = IzinSeleksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
