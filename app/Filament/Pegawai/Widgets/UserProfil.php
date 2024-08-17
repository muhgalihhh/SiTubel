<?php

namespace App\Filament\Pegawai\Widgets;

use Filament\Widgets\Widget;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class UserProfil extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pegawai.widgets.user-profil';

    // Define the properties to hold form data
    public $name;
    public $email;
    public $password;
    public $foto_pegawai;
    public $NIP;
    public $nama;
    public $no_telp;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $pendidikan;
    public $tahun_lulus;
    public $pangkat_golongan;
    public $jabatan;
    public $unit_kerja;

    public function mount(): void
    {
        $user = Auth::user();
        $pegawai = $user->pegawai; // Assuming `pegawai` is the relationship method on `User`

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'foto_pegawai' => $pegawai->foto_pegawai ?? null,
            'NIP' => $pegawai->NIP ?? '',
            'nama' => $pegawai->nama ?? '',
            'no_telp' => $pegawai->no_telp ?? '',
            'tempat_lahir' => $pegawai->tempat_lahir ?? '',
            'tanggal_lahir' => $pegawai->tanggal_lahir ?? '',
            'pendidikan' => $pegawai->pendidikan ?? '',
            'tahun_lulus' => $pegawai->tahun_lulus ?? '',
            'pangkat_golongan' => $pegawai->pangkat_golongan ?? '',
            'jabatan' => $pegawai->jabatan ?? '',
            'unit_kerja' => $pegawai->unit_kerja ?? '',
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            // Card for Data Akun
            Card::make()
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required(),
                    TextInput::make('password')
                        ->label('Password')
                        ->password(),
                ])
                ->columns(2)
                ->label('Data Akun'),

            // Card for Data Profil
            Card::make()
                ->schema([
                    FileUpload::make('foto_pegawai')
                        ->label('Foto Pegawai')
                        ->image()
                        ->directory('foto_pegawai')
                        ->maxSize(1024), // in KB
                    TextInput::make('NIP')
                        ->label('NIP')
                        ->required(),
                    TextInput::make('nama')
                        ->label('Nama')
                        ->required(),
                    TextInput::make('no_telp')
                        ->label('No. Telepon')
                        ->tel(),
                    TextInput::make('tempat_lahir')
                        ->label('Tempat Lahir')
                        ->required(),
                    TextInput::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->type('date')
                        ->required(),
                    TextInput::make('pendidikan')
                        ->label('Pendidikan')
                        ->required()
                        ->disabled(),
                    TextInput::make('tahun_lulus')
                        ->label('Tahun Lulus')
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue(date('Y'))
                        ->disabled(),
                    TextInput::make('pangkat_golongan')
                        ->label('Pangkat Golongan')
                        ->required()
                        ->disabled(),
                    TextInput::make('jabatan')
                        ->label('Jabatan')
                        ->required()
                        ->disabled(),
                    TextInput::make('unit_kerja')
                        ->label('Unit Kerja')
                        ->required()
                        ->disabled(),
                ])
                ->columns(2)
                ->label('Data Profil'),
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        // Fetch the logged-in user's pegawai model
        $user = Auth::user();
        $pegawai = $user->pegawai; // Assuming `pegawai` is the relationship method on `User`

        // Validate and update user and pegawai profiles
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => !empty($data['password']) ? bcrypt($data['password']) : $user->password,
        ]);

        $pegawai->update(array_filter([
            'foto_pegawai' => $data['foto_pegawai'] ?? null,
            'NIP' => $data['NIP'] ?? '',
            'nama' => $data['nama'] ?? '',
            'no_telp' => $data['no_telp'] ?? '',
            'tempat_lahir' => $data['tempat_lahir'] ?? '',
            'tanggal_lahir' => $data['tanggal_lahir'] ?? '',
            'pendidikan' => $data['pendidikan'] ?? '',
            'tahun_lulus' => $data['tahun_lulus'] ?? '',
            'pangkat_golongan' => $data['pangkat_golongan'] ?? '',
            'jabatan' => $data['jabatan'] ?? '',
            'unit_kerja' => $data['unit_kerja'] ?? '',
        ]));

        // Send notification
        Notification::make()
            ->title('Profile Updated')
            ->success()
            ->send();
    }
}
