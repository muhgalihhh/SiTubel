<?php
namespace App\Filament\Resources\IzinSeleksiResource\Pages;

use Filament\Actions;
use App\Models\TugasBelajar;
use Filament\Tables\Filters\Filter;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\IzinSeleksiResource;
use Illuminate\Database\Eloquent\Builder;

class ListIzinSeleksis extends ListRecords
{
    protected static string $resource = IzinSeleksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
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
