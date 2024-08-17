<?php

namespace App\Filament\OPD\Widgets;

use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OPDStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Retrieve the currently logged-in user's pegawai record
        $user = Auth::user();
        $pegawai = $user->pegawai;

        // Initialize variables
        $jumlahPegawaiUnitKerja = 0;
        $jumlahIzinBelajar = 0;
        $jumlahIzinSeleksiSelesai = 0;

        // Check if pegawai record exists
        if ($pegawai) {
            // Get the unit_kerja of the logged-in user's pegawai record
            $unitKerja = $pegawai->unit_kerja;

            // Count the number of pegawai with the same unit_kerja
            $jumlahPegawaiUnitKerja = \App\Models\Pegawai::where('unit_kerja', $unitKerja)->count();

            // Count the number of izin belajar in tahap_opd or tahap_bkpsdm stages for the same unit_kerja
            $jumlahIzinBelajar = \App\Models\TugasBelajar::whereHas('pegawai', function ($query) use ($unitKerja) {
                $query->where('unit_kerja', $unitKerja);
            })
                ->whereIn('stage', ['tahap_opd', 'tahap_bkpsdm'])
                ->count();

            // Count the number of izin seleksi that have completed tahap_seleksi and tahap_opd stages
            $jumlahIzinSeleksiSelesai = \App\Models\TugasBelajar::whereHas('pegawai', function ($query) use ($unitKerja) {
                $query->where('unit_kerja', $unitKerja);
            })
                ->whereIn('stage', ['tahap_seleksi', 'tahap_lulus'])
                ->count();
        }

        return [
            Stat::make("Jumlah Pegawai", $jumlahPegawaiUnitKerja)
                ->description("Jumlah pegawai OPD yang terdaftar")
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([0, 1, 3])
                ->color('success'),

            Stat::make("Jumlah Izin Belajar", $jumlahIzinBelajar)
                ->description("Jumlah izin belajar dalam tahap OPD atau BKPSDM")
                ->descriptionIcon('heroicon-o-document-duplicate', IconPosition::Before)
                ->chart([0, 1, 2])
                ->color('primary'),

            Stat::make("Jumlah Izin Seleksi Selesai", $jumlahIzinSeleksiSelesai)
                ->description("Jumlah izin seleksi yang sudah selesai, Tahap Seleksi dan Tubel ")
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::Before)
                ->chart([1, 2, 3])
                ->color('warning'),
        ];
    }
}