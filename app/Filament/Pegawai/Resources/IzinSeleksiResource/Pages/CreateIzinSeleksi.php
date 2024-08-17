<?php
namespace App\Filament\Pegawai\Resources\IzinSeleksiResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Pegawai\Resources\IzinSeleksiResource;
use App\Models\User; // Import User model

class CreateIzinSeleksi extends CreateRecord
{
    protected static string $resource = IzinSeleksiResource::class;

    protected function getRedirectUrl(): string
    {
        $pegawai = Auth::user()->pegawai;
        $name = $pegawai->nama;
        $unit_kerja = $pegawai->unit_kerja;

        // Fetch users with role 'opd' and the same unit_kerja
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'opd');
        })->whereHas('pegawai', function ($query) use ($unit_kerja) {
            $query->where('unit_kerja', $unit_kerja);
        })->get();

        foreach ($users as $user) {
            Notification::make()
                ->warning()
                ->title("Izin Seleksi Diajukan Oleh " . $name)
                ->body('Izin seleksi diajukan, mohon diproses.')
                ->actions([
                    Action::make('Lihat Izin Seleksi')->url('/opd/izin-seleksis'),
                ])
                ->sendToDatabase($user); // Send notification to each user
        }

        return $this->getResource()::getUrl('index'); // Redirect to the resource index page
    }
}
