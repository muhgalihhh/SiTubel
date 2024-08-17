<?php

namespace App\Filament\Pegawai\Resources\IzinSeleksiResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\TugasBelajar;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use App\Filament\Pegawai\Resources\IzinSeleksiResource;

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
        $pegawai = Auth::user()->pegawai;
        $name = $pegawai->nama;
        $unit_kerja = $pegawai->unit_kerja;

        // Fetch users with role 'opd' and the same unit_kerja
        $opd = User::whereHas('roles', function ($query) {
            $query->where('name', 'opd');
        })->whereHas('pegawai', function ($query) use ($unit_kerja) {
            $query->where('unit_kerja', $unit_kerja);
        })->get();
        $adminUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        $users = $opd->merge($adminUsers);

        $tugas_belajar = $this->record;
        $tugas_belajar->status = 'pending';
        $tugas_belajar->save();
        foreach ($users as $user) {
            Notification::make()
                ->warning()
                ->title("Izin Seleksi Diajukan Kembali Oleh " . $name)
                ->body('Izin Seleksi diajukan Kembali, mohon diproses.')
                ->actions([
                    Action::make('Lihat Izin Seleksi')->url('/opd/izin-seleksis'),
                ])
                ->sendToDatabase($user); // Send notification to each user
        }

        return $this->getResource()::getUrl('index'); // Redirect to the resource index page
    }
}
