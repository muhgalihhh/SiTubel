<?php

namespace App\Filament\Resources\PengajuanIzinSeleksiResource\Pages;

use Filament\Actions;
use App\Models\TugasBelajar;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\PengajuanIzinSeleksiResource;

class ListPengajuanIzinSeleksis extends ListRecords
{
    protected static string $resource = PengajuanIzinSeleksiResource::class;

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