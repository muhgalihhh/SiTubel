<?php

namespace App\Filament\OPD\Resources\IzinSeleksiResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\OPD\Resources\IzinSeleksiResource;

class ListIzinSeleksis extends ListRecords
{
    protected static string $resource = IzinSeleksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('Izin Seleksi Berlangsung')
                ->label('Izin Seleksi Berlangsung')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->whereIn('stage', ['tahap_opd', 'tahap_bkpsdm']);
                }),

            Tab::make('Izin Seleksi Selesai')
                ->label('Izin Seleksi Selesai')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->whereIn('stage', ['tahap_seleksi', 'tahap_lulus']);
                }),
        ];
    }
}