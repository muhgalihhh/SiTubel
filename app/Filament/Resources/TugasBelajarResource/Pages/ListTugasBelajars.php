<?php

namespace App\Filament\Resources\TugasBelajarResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\TugasBelajarResource;

class ListTugasBelajars extends ListRecords
{
    protected static string $resource = TugasBelajarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('Tugas Belajar Berlangsung')
                ->label('Tugas Belajar Berlangsung')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where(function ($query) {
                        $query->where('stage', 'tahap_seleksi')->whereIn('status', ['pending', 'approved'])
                            ->orWhere('stage', 'tahap_lulus')->whereIn('status', ['pending', 'rejected']);
                    });
                }),

            Tab::make('Tugas Belajar Selesai')
                ->label('Tugas Belajar Selesai')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where(function ($query) {
                        $query->where('stage', 'tahap_lulus')->whereIn('status', ['approved', 'passed'])
                            ->orWhere('stage', 'tahap_seleksi')->whereIn('status', ['rejected']);
                    });
                }),
        ];
    }

}
