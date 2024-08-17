<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pegawai;
use Filament\Forms\Form;
use App\Models\UnitKerja;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PegawaiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\PegawaiResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Konfigurasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form inputan
                FileUpload::make('foto_pegawai')
                    ->directory('foto_pegawai')
                    ->preserveFilenames()
                    ->acceptedFileTypes(['image/*'])
                    ->image()
                    ->imageEditor()
                    ->imageEditorMode(1)
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, callable $get) {
                        // Mengakses nilai dari state form menggunakan $get
                        $employeeName = $get('nama');
                        $sanitizedEmployeeName = preg_replace('/[^A-Za-z0-9\-]/', '_', $employeeName);

                        // Mendapatkan ekstensi file asli
                        $extension = $file->getClientOriginalExtension();

                        // Menggabungkan untuk membuat nama file baru
                        return $sanitizedEmployeeName . '.' . $extension;
                    }),
                TextInput::make('NIP')
                    ->label('Nomor Induk Pegawai')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('nama')
                    ->label('Nama')
                    ->required(),

                TextInput::make('no_telp')
                    ->label('Nomor Telepon (WhatsApp)'),

                TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir'),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir'),

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
                    ]),
                TextInput::make('tahun_lulus')
                    ->label('Tahun Lulus')
                    ->numeric(),

                TextInput::make('pangkat_golongan')
                    ->label('Pangkat/Golongan'),

                TextInput::make('jabatan')
                    ->label('Jabatan'),

                Select::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->options(UnitKerja::all()->pluck('name', 'name'))
                    ->searchable()
                    ->required(),



            ])->columns();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('foto_pegawai'),
                TextColumn::make('NIP')
                    ->label('Nomor Induk Pegawai')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('no_telp')
                    ->label('Nomor Telepon')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('pendidikan')
                    ->label('Pendidikan')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('tahun_lulus')
                    ->label('Tahun Lulus')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('pangkat_golongan')
                    ->label('Pangkat/Golongan')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('user.id')
                    ->label('Status Akun')
                    ->formatStateUsing(function ($state, $record) {
                        // Determine if the pegawai has an associated user
                        return $record->user ? 'Akun Ada' : 'Akun Tidak Ada';
                    })
                    ->colors([
                        'success' => 'Akun Ada',
                        'danger' => 'Akun Tidak Ada',
                    ])
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->default('Akun Tidak Ada'),
            ])
            ->filters([


            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->button()->label('aksi')->icon('heroicon-o-document-duplicate'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePegawais::route('/'),
        ];
    }
}
