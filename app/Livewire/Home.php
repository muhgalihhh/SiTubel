<?php

namespace App\Livewire;

use Tabs\Tab;
use App\Models\User;
use App\Models\Pegawai;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\UnitKerja;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Home extends Component implements HasForms
{
    use InteractsWithForms;

    public $NIP = '';
    public $nama = '';
    public $no_telp = '';
    public $tempat_lahir = '';
    public $tanggal_lahir = '';
    public $pendidikan = '';
    public $tahun_lulus = '';
    public $pangkat_golongan = '';
    public $jabatan = '';
    public $unit_kerja = '';
    public $foto_pegawai;



    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make('Registrasi')->schema([
                TextInput::make('NIP')
                    ->label('NIP')
                    ->placeholder('Nomor Induk Pegawai')
                    ->numeric()
                    ->required(),

                TextInput::make('nama')
                    ->label('Nama (gelar)')
                    ->required(),

                TextInput::make('no_telp')
                    ->label('Nomor Telepon')
                    ->required(),

                TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->required(),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required(),

                Select::make('pendidikan')
                    ->label('Pendidikan')
                    ->options([
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                        'D1' => 'D1',
                        'D2' => 'D2',
                        'D3' => 'D3',
                        'S1' => 'S1',
                        'S2' => 'S2',
                        'S3' => 'S3',
                    ])
                    ->required(),

                TextInput::make('tahun_lulus')
                    ->label('Tahun Lulus')
                    ->numeric()
                    ->required(),

                TextInput::make('pangkat_golongan')
                    ->label('Pangkat Golongan')
                    ->required(),

                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required(),

                Select::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->options(static::getUnitKerjaOptions())
                    ->required(),


                TextInput::make('foto_pegawai')
                    ->type('file')
                    ->label('Foto Pegawai')
                    ->required(),
            ])->columns(2),
        ]);

    }

    public function render()
    {
        return view('livewire.home');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->foto_pegawai) {
            $uploadedFile = $this->foto_pegawai;
            $fileName = time() . '_' . $this->nama . 'foto_pegawai.' . $uploadedFile->getClientOriginalExtension();

            $path = $uploadedFile->storeAs('public/foto_pegawai', $fileName);

            $data['foto_pegawai'] = 'foto_pegawai/' . $fileName;
        }

        Pegawai::insert($data);

        Notification::make()
            ->title('Pegawai ' . $this->nama . ' Telah Mendaftar: Tolong Buatkan Akun untuk Pegawai ini')
            ->success()
            ->sendToDatabase(User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get());

        session()->flash('message', 'Data berhasil disimpan.');
    }
    protected static function getUnitKerjaOptions()
    {
        return UnitKerja::all()->pluck('name', 'name')->toArray();
    }

}
