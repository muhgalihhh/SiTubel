<?php

namespace App\Filament\Widgets;

use App\Models\Pegawai;
use App\Models\TugasBelajar;
use Illuminate\Support\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class PegawaiBaruWidget extends BaseWidget
{

    protected static ?int $sort = 2;
    protected function getStats(): array
    {
        $pegawaiBaruCount = Pegawai::whereDoesntHave('user')->count();
        $totalPegawaiCount = Pegawai::count();

        $tahapOpdData = $this->generateMonthlyDataForStage(['tahap_opd']);
        $tahapBkpsdmData = $this->generateMonthlyDataForStage(['tahap_bkpsdm']);
        $seleksiLulusData = $this->generateMonthlyDataForStage(['tahap_seleksi', 'tahap_lulus']);

        // Generate chart data for both Pegawai Baru and Total Pegawai
        $pegawaiBaruChartData = $this->generateMonthlyDataForPegawaiBaru();
        $totalPegawaiChartData = $this->generateMonthlyDataForTotalPegawai();
        $seleksiLulusChartData = $this->generateMonthlyDataForStage(['tahap_seleksi', 'tahap_lulus']);

        return [
            Stat::make('Total Pegawai', $totalPegawaiCount)
                ->description('Jumlah total pegawai yang terdaftar')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart($totalPegawaiChartData)
                ->color('success')
                ->url(route('filament.admin.resources.pegawais.index')),

            Stat::make('Pegawai Baru', $pegawaiBaruCount)
                ->description('Perlu dibuatkan akun')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart($pegawaiBaruChartData)
                ->color('warning')
                ->url(route('filament.admin.resources.users.index')),

            Stat::make('Izin Seleksi', array_sum($tahapOpdData) + array_sum($tahapBkpsdmData))
                ->description('Tahap OPD & BKPSDM')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([
                    'Tahap OPD' => $tahapOpdData,
                    'Tahap BKPSDM' => $tahapBkpsdmData,
                ])
                ->color('warning')
                ->url(route('filament.admin.resources.pengajuan-izin-seleksis.index')),

            Stat::make('Tugas Belajar', array_sum($seleksiLulusData))
                ->description('Tahap Seleksi & Lulus')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart($seleksiLulusChartData)
                ->color('primary')
                ->url(route('filament.admin.resources.daftar-pengajuan-tugas-belajars.index')),
        ];
    }

    protected function generateMonthlyDataForPegawaiBaru(): array
    {
        $data = [];

        // Loop through the last 5 months
        for ($i = 4; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');

            // Count Pegawai Baru for each month
            $count = Pegawai::whereDoesntHave('user')
                ->whereYear('created_at', Carbon::parse($month)->year)
                ->whereMonth('created_at', Carbon::parse($month)->month)
                ->count();

            $data[] = $count;
        }

        return $data;
    }

    protected function generateMonthlyDataForTotalPegawai(): array
    {
        $data = [];

        // Loop through the last 5 months
        for ($i = 4; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');

            // Count Total Pegawai for each month
            $count = Pegawai::whereYear('created_at', Carbon::parse($month)->year)
                ->whereMonth('created_at', Carbon::parse($month)->month)
                ->count();

            $data[] = $count;
        }

        return $data;
    }

    protected function generateMonthlyDataForStage(array $stages): array
    {
        $data = [];

        // Loop through the last 5 months
        for ($i = 4; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');

            // Count TugasBelajar for the specified stages and each month
            $count = TugasBelajar::whereIn('stage', $stages)
                ->whereYear('created_at', Carbon::parse($month)->year)
                ->whereMonth('created_at', Carbon::parse($month)->month)
                ->count();

            $data[] = $count;
        }

        return $data;
    }
}
