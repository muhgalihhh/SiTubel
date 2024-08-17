<?php

namespace App\Filament\Pegawai\Resources\TugasBelajarResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use App\Filament\Pegawai\Resources\TugasBelajarResource;

class EditTugasBelajar extends EditRecord
{
    protected static string $resource = TugasBelajarResource::class;

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

        // Fetch users with role 'admin'
        $adminUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();


        $tugas_belajar = $this->record;
        $tugas_belajar->status = 'pending';
        $tugas_belajar->save();

        foreach ($adminUsers as $user) {
            Notification::make()
                ->warning()
                ->title("Tugas Belajar Telah Di Update Oleh Pegawai " . $name)
                ->body('Tugas Belajar diajukan kembali, mohon diproses kembali.')
                ->actions([
                    Action::make('Lihat Pengajuan Tugas Belajar')->url('/admin/daftar-pengajuan-tugas-belajars'),
                ])
                ->sendToDatabase($user); // Send notification to each user
        }

        return $this->getResource()::getUrl('index'); // Redirect to the resource index page
    }
}