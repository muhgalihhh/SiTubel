<?php

namespace App\Filament\Resources\IzinSeleksiResource\Pages;

use App\Filament\Resources\IzinSeleksiResource;
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

    protected function getRedirectUrl(): string
    {

        $tugas_belajar = $this->record;
        $tugas_belajar->status = 'pending';
        $tugas_belajar->save();
    }
}
