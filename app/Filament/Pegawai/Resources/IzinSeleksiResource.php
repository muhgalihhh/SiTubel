<?php

namespace App\Filament\Pegawai\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Pegawai;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\IzinSeleksi;
use App\Models\TugasBelajar;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\View;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Tabs;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Pegawai\Resources\IzinSeleksiResource\Pages;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Pegawai\Resources\IzinSeleksiResource\RelationManagers;

class IzinSeleksiResource extends Resource
{
    protected static ?string $model = TugasBelajar::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Tugas Belajar';
    protected static ?string $label = 'Izin Seleksi';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                View::make('components.announcement')
                    ->columnSpan('full'),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Tabs::make('Form Tabs')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Kampus Tujuan')
                                    ->schema([
                                        Select::make('pegawai_id')
                                            ->label('Pegawai')
                                            ->options(function () {
                                                $user = Auth::user();
                                                $pegawai = $user->pegawai;
                                                return [$pegawai->id => $pegawai->nama];
                                            })
                                            ->default(Auth::user()->pegawai->id)
                                            ->searchable()
                                            ->required(),
                                        Forms\Components\TextInput::make('universitas')
                                            ->required(),
                                        Forms\Components\TextInput::make('fakultas')
                                            ->required(),
                                        Forms\Components\TextInput::make('prodi')
                                            ->required(),
                                        Forms\Components\TextInput::make('jenjang_tujuan')
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Lampiran Persyaratan Pengajuan')
                                    ->schema([
                                        Forms\Components\Grid::make(2) // Set the grid to have 2 columns
                                            ->schema([
                                                Section::make('Data Kepegawaian')
                                                    ->columns(2)
                                                    ->schema([
                                                        FileUpload::make('keputusan_cpns_sk_pns_pangkat')
                                                            ->directory('lampiran_persyaratan_pengajuan_tugas_belajar')
                                                            ->required()
                                                            ->panelLayout('compact')
                                                            ->acceptedFileTypes([
                                                                'application/pdf',
                                                                'image/*',
                                                                'application/zip',                // .zip
                                                                'application/x-rar-compressed',   // .rar
                                                                'application/x-7z-compressed',    // .7z
                                                                'application/x-tar',              // .tar
                                                                'application/gzip',               // .gz
                                                            ])
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                    ->prepend('keputusan_cpns_sk_pns_pangkat-'),
                                                            ),
                                                        FileUpload::make('skp_dua_tahun')
                                                            ->directory('lampiran_persyaratan_pengajuan_tugas_belajar')
                                                            ->required()
                                                            ->panelLayout('compact')
                                                            ->acceptedFileTypes([
                                                                'application/pdf',
                                                                'image/*',
                                                                'application/zip',                // .zip
                                                                'application/x-rar-compressed',   // .rar
                                                                'application/x-7z-compressed',    // .7z
                                                                'application/x-tar',              // .tar
                                                                'application/gzip',               // .gz
                                                            ])
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                    ->prepend('skp_dua_tahun-'),
                                                            ),
                                                        FileUpload::make('foto_kopi_ijazah_terakhir')
                                                            ->directory('lampiran_persyaratan_pengajuan_tugas_belajar')
                                                            ->required()
                                                            ->panelLayout('compact')
                                                            ->acceptedFileTypes([
                                                                'application/pdf',
                                                                'image/*',
                                                                'application/zip',                // .zip
                                                                'application/x-rar-compressed',   // .rar
                                                                'application/x-7z-compressed',    // .7z
                                                                'application/x-tar',              // .tar
                                                                'application/gzip',               // .gz
                                                            ])
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                    ->prepend('foto_kopi_ijazah_terakhir-'),
                                                            ),
                                                        FileUpload::make('foto_kopi_transkrip_terakhir')
                                                            ->directory('lampiran_persyaratan_pengajuan_tugas_belajar')
                                                            ->required()
                                                            ->panelLayout('compact')
                                                            ->acceptedFileTypes([
                                                                'application/pdf',
                                                                'image/*',
                                                                'application/zip',                // .zip
                                                                'application/x-rar-compressed',   // .rar
                                                                'application/x-7z-compressed',    // .7z
                                                                'application/x-tar',              // .tar
                                                                'application/gzip',               // .gz
                                                            ])
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                    ->prepend('foto_kopi_transkrip_terakhir-'),
                                                            ),
                                                        FileUpload::make('keputusan_jabatan')
                                                            ->directory('lampiran_persyaratan_pengajuan_tugas_belajar')
                                                            ->required()
                                                            ->panelLayout('compact')
                                                            ->acceptedFileTypes([
                                                                'application/pdf',
                                                                'image/*',
                                                                'application/zip',                // .zip
                                                                'application/x-rar-compressed',   // .rar
                                                                'application/x-7z-compressed',    // .7z
                                                                'application/x-tar',              // .tar
                                                                'application/gzip',               // .gz
                                                            ])
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                    ->prepend('keputusan_jabatan-'),
                                                            ),
                                                        FileUpload::make('surat_keterangan_akreditasi')
                                                            ->directory('lampiran_persyaratan_pengajuan_tugas_belajar')
                                                            ->required()
                                                            ->panelLayout('compact')
                                                            ->acceptedFileTypes([
                                                                'application/pdf',
                                                                'image/*',
                                                                'application/zip',                // .zip
                                                                'application/x-rar-compressed',   // .rar
                                                                'application/x-7z-compressed',    // .7z
                                                                'application/x-tar',              // .tar
                                                                'application/gzip',               // .gz
                                                            ])
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                    ->prepend('surat_keterangan_akreditasi-'),
                                                            ),
                                                        FileUpload::make('brosur_pamflet')
                                                            ->directory('lampiran_persyaratan_pengajuan_tugas_belajar')
                                                            ->required()
                                                            ->panelLayout('compact')
                                                            ->acceptedFileTypes([
                                                                'application/pdf',
                                                                'image/*',
                                                                'application/zip',                // .zip
                                                                'application/x-rar-compressed',   // .rar
                                                                'application/x-7z-compressed',    // .7z
                                                                'application/x-tar',              // .tar
                                                                'application/gzip',               // .gz
                                                            ])
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                    ->prepend('brosur_pamflet-'),
                                                            ),
                                                        FileUpload::make('surat_keterangan_konversi')
                                                            ->directory('lampiran_persyaratan_pengajuan_tugas_belajar')
                                                            ->required()
                                                            ->panelLayout('compact')
                                                            ->acceptedFileTypes([
                                                                'application/pdf',
                                                                'image/*',
                                                                'application/zip',                // .zip
                                                                'application/x-rar-compressed',   // .rar
                                                                'application/x-7z-compressed',    // .7z
                                                                'application/x-tar',              // .tar
                                                                'application/gzip',               // .gz
                                                            ])
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                    ->prepend('surat_keterangan_konversi-'),
                                                            ),
                                                        FileUpload::make('sertifikat_akreditasi')
                                                            ->directory('lampiran_persyaratan_pengajuan_tugas_belajar')
                                                            ->required()
                                                            ->panelLayout('compact')
                                                            ->acceptedFileTypes([
                                                                'application/pdf',
                                                                'image/*',
                                                                'application/zip',                // .zip
                                                                'application/x-rar-compressed',   // .rar
                                                                'application/x-7z-compressed',    // .7z
                                                                'application/x-tar',              // .tar
                                                                'application/gzip',               // .gz
                                                            ])
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                    ->prepend('sertifikat_akreditasi-'),
                                                            ),
                                                        FileUpload::make('dokumen_pengembangan_kompetensi')
                                                            ->directory('lampiran_persyaratan_pengajuan_tugas_belajar')
                                                            ->label('Dokumen Lainnya')
                                                            ->panelLayout('compact')
                                                            ->acceptedFileTypes([
                                                                'application/pdf',
                                                                'image/*',
                                                                'application/zip',                // .zip
                                                                'application/x-rar-compressed',   // .rar
                                                                'application/x-7z-compressed',    // .7z
                                                                'application/x-tar',              // .tar
                                                                'application/gzip',               // .gz
                                                            ])
                                                            ->getUploadedFileNameForStorageUsing(
                                                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                                    ->prepend('dokumen_pengembangan_kompetensi-'),
                                                            ),
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                    ])->columnSpan('full'), // Set the column span to full width
            ]);
    }



    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('pegawai.nama')
                    ->label('Pegawai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('universitas')
                    ->label('Universitas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('fakultas')
                    ->label('Fakultas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('prodi')
                    ->label('Prodi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenjang_tujuan')
                    ->label('Jenjang Tujuan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->badge(function ($record) {
                        if ($record->status === 'pending') {
                            return 'warning';
                        } elseif ($record->status === 'approved') {
                            return 'success';
                        } else {
                            return 'danger';
                        }
                    })
                    ->color(
                        fn($record) => match ($record->status) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'passed' => 'success',
                            default => 'primary',
                        }
                    )
                    ->formatStateUsing(
                        fn($record) => match ($record->status) {
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'passed' => 'Selesai',
                            default => 'Pending',
                        }
                    )
                    ->sortable(),
                TextColumn::make('stage')
                    ->label('Tahap')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(
                        fn($record) => match ($record->stage) {
                            'tahap_opd' => 'warning',
                            'tahap_bkpsdm' => 'warning',
                            'tahap_seleksi' => 'warning',
                            'tahap_lulus' => 'success',
                            default => 'primary',
                        }
                    )
                    ->formatStateUsing(
                        fn($record) => match ($record->stage) {
                            'tahap_opd' => 'Tahap OPD',
                            'tahap_bkpsdm' => 'Tahap BKPSDM',
                            'tahap_seleksi' => 'Tahap Seleksi Masuk PT',
                            'tahap_lulus' => 'Tahap Lulus',
                            default => 'Tahap OPD',
                        }
                    ),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Upload Surat Permohonan')->form([
                    FileUpload::make('surat_usulan')
                        ->label('Surat Permohonan Mengikuti Seleksi(image/.pdf')
                        ->required()
                        ->acceptedFileTypes([
                            'application/pdf',
                            'image/*',
                            'application/zip',                // .zip
                            'application/x-rar-compressed',   // .rar
                            'application/x-7z-compressed',    // .7z
                            'application/x-tar',              // .tar
                            'application/gzip',               // .gz
                        ])
                        ->panelLayout('compact')
                        ->directory('lampiran_persyaratan_pengajuan_tugas_belajar'),
                ])->action(function (TugasBelajar $record, array $data) {
                    foreach ($data as $key => $value) {
                        if ($value instanceof \Illuminate\Http\UploadedFile) {
                            $filename = $value->store('lampiran_persyaratan_pengajuan_tugas_belajar');
                            $record->{$key} = $filename;
                        } elseif (is_string($value)) {
                            $record->{$key} = $value;
                        }
                    }

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
                            ->title("Surat Permohonan Izin Seleksi Diajukan Oleh " . $name)
                            ->body('Surat Permohonan Izin Seleksi diajukan, mohon diproses.')
                            ->sendToDatabase($user); // Send notification to each user
                    }

                    Notification::make()
                        ->title('Berhasil Diupload: Tunggu Verifikasi OPD dan BKPSDM')
                        ->success()
                        ->send();

                    $record->save();
                })->visible(fn(TugasBelajar $record): bool => ($record->status === 'pending' || $record->status === 'rejected') && $record->stage === 'tahap_opd'),

                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->visible(fn(TugasBelajar $record): bool => ($record->status === 'pending' || $record->status === 'rejected') && ($record->stage === 'tahap_opd' || $record->stage === 'tahap_bkpsdm')),
                    Action::make('Cetak Surat Permohonan')
                        ->label('Cetak Surat Permohonan')
                        ->action(
                            function (TugasBelajar $record) {
                                Notification::make()
                                    ->title('Surat Didownload: Mohon Upload Surat Yang Telah Didownload dengan yang sudah di ttd basah')
                                    ->success()
                                    ->send();
                                return redirect()->route('download.surat', ['recordId' => $record->id]);
                            }
                        )->icon('heroicon-o-document-duplicate'),
                ])->button()->icon('heroicon-o-document-duplicate')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIzinSeleksis::route('/'),
            'create' => Pages\CreateIzinSeleksi::route('/create'),
            'edit' => Pages\EditIzinSeleksi::route('/{record}/edit'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Card::make()
                    ->schema([
                        Tabs::make('')
                            ->schema([
                                Tab::make('Identitas Pegawai')
                                    ->schema([
                                        ImageEntry::make('pegawai.foto_pegawai')
                                            ->label('Foto Pegawai')
                                            ->circular(),
                                        TextEntry::make('pegawai.nama')
                                            ->label('Nama Pegawai'),
                                        TextEntry::make('pegawai.NIP')
                                            ->label('NIP'),
                                        TextEntry::make('pegawai.jabatan')
                                            ->label('Jabatan'),
                                        TextEntry::make('pegawai.pangkat_golongan')
                                            ->label('Pangkat'),
                                        TextEntry::make('pegawai.unit_kerja')
                                            ->label('Unit Kerja'),
                                        TextEntry::make('pegawai.no_telp')
                                            ->label('Telepon'),
                                    ])->columns(2),
                                Tab::make('Identitas Pengajuan Pendidikan')
                                    ->schema([
                                        TextEntry::make('universitas')
                                            ->label('Universitas'),
                                        TextEntry::make('fakultas')
                                            ->label('Fakultas'),
                                        TextEntry::make('prodi')
                                            ->label('Program Studi'),
                                        TextEntry::make('jenjang_tujuan')
                                            ->label('Jenjang Tujuan'),
                                        TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->color(fn($record) => match ($record->status) {
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'passed' => 'success',
                                                default => 'secondary',
                                            })
                                            ->formatStateUsing(function ($record) {
                                                return match ($record->status) {
                                                    'pending' => 'Menunggu Persetujuan',
                                                    'approved' => 'Disetujui',
                                                    'rejected' => 'Ditolak',
                                                    'passed' => 'Selesai',
                                                    default => 'Tidak Diketahui',
                                                };
                                            }),

                                        TextEntry::make('stage')
                                            ->label('Tahap')
                                            ->badge()
                                            ->color(fn($record) => match ($record->stage) {
                                                'tahap_opd' => 'warning',
                                                'tahap_bkpsdm' => 'warning',
                                                'tahap_seleksi' => 'warning',
                                                'tahap_lulus' => 'success',
                                                default => 'secondary',
                                            })
                                            ->formatStateUsing(function ($record) {
                                                return match ($record->stage) {
                                                    'tahap_opd' => 'Menunggu OPD',
                                                    'tahap_bkpsdm' => 'Menunggu BKPSDM',
                                                    'tahap_seleksi' => 'Tahap Seleksi Masuk PT',
                                                    'tahap_lulus' => 'Tahap Lulus',
                                                    default => 'Tahap Tidak Diketahui',
                                                };
                                            }),

                                    ])->columns(2),
                            ]),
                    ]),


                Grid::make(2)
                    ->schema([
                        // Kolom kiri
                        Card::make()
                            ->schema([
                                TextEntry::make('catatan')
                                    ->label('Catatan')
                                    ->badge()
                                    ->color(function ($record) {
                                        if ($record->status === 'pending') {
                                            return 'danger';
                                        } elseif ($record->status === 'approved') {
                                            return 'success';
                                        } else {
                                            return 'danger';
                                        }
                                    }),
                            ]),
                        Card::make('Surat Permohonan dan Rekomendasi')
                            ->schema([
                                TextEntry::make('surat_usulan')
                                    ->label('1. Surat Permohonan Pemohon (Oleh Pemohon)')
                                    ->formatStateUsing(fn($record) => $record->surat_usulan ? 'Sudah Diunggah' : 'Belum Diunggah')
                                    ->color(fn($record) => $record->surat_usulan ? 'success' : 'danger')
                                    ->badge()
                                    ->suffix(function ($record) {
                                        if ($record->surat_usulan) {
                                            $fileName = basename($record->surat_usulan);
                                            return
                                                ActionGroup::make([
                                                    Action::make('download')
                                                        ->color('primary')
                                                        ->icon('heroicon-o-arrow-down-tray')
                                                        ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                                    Action::make('preview')
                                                        ->color('secondary')
                                                        ->icon('heroicon-o-eye')
                                                        ->url(route('preview.lampiran', ['file' => $fileName]))
                                                        ->openUrlInNewTab(),
                                                ])->button();
                                        }
                                        return null;
                                    })->default('Belum Diunggah'),

                                TextEntry::make('rekomendasi_kepala')
                                    ->label('2. Rekomendasi Kepala OPD (Oleh KaSubbag)')
                                    ->formatStateUsing(fn($record) => $record->rekomendasi_kepala ? 'Sudah Diunggah' : 'Belum Diunggah')
                                    ->color(fn($record) => $record->rekomendasi_kepala ? 'success' : 'danger')
                                    ->badge()
                                    ->suffix(function ($record) {
                                        if ($record->rekomendasi_kepala) {
                                            $fileName = basename($record->rekomendasi_kepala);
                                            return ActionGroup::make([
                                                Action::make('download')
                                                    ->color('primary')
                                                    ->icon('heroicon-o-arrow-down-tray')
                                                    ->url(route('download.fileSurat', ['file' => $fileName])),
                                                Action::make('preview')
                                                    ->color('secondary')
                                                    ->icon('heroicon-o-eye')
                                                    ->url(route('preview.lampiranSurat', ['file' => $fileName]))
                                                    ->openUrlInNewTab(),
                                            ])->button();
                                        }
                                        return null;
                                    })->default('Belum Diunggah'),
                                TextEntry::make('foto_kopi_rekomendasi_sekda')
                                    ->label('3. Foto Kopi Rekomendasi Sekda (BKPSDM)')
                                    ->badge()
                                    ->formatStateUsing(fn($record) => $record->foto_kopi_rekomendasi_sekda ? 'Sudah Diunggah' : 'Belum Diunggah')
                                    ->color(fn($record) => $record->foto_kopi_rekomendasi_sekda ? 'success' : 'danger')
                                    ->suffix(function ($record) {
                                        if ($record->foto_kopi_rekomendasi_sekda) {
                                            $fileName = basename($record->foto_kopi_rekomendasi_sekda);
                                            return
                                                ActionGroup::make([
                                                    Action::make('download')
                                                        ->color('primary')
                                                        ->icon('heroicon-o-arrow-down-tray')
                                                        ->url(route('download.fileSurat', ['file' => $fileName])),
                                                    Action::make('preview')
                                                        ->color('secondary')
                                                        ->icon('heroicon-o-eye')
                                                        ->url(route('preview.lampiranSurat', ['file' => $fileName]))
                                                        ->openUrlInNewTab(),
                                                ])->button();
                                        }
                                        return null;
                                    })->default('Belum Diunggah'),
                            ])->columns(3),
                    ]),

                Card::make('Data Kebutuhan Izin Seleksi')
                    ->label('Data Kebutuhan Izin Seleksi')
                    ->schema([
                        // Data Kebutuhan Izin Seleksi
                        TextEntry::make('keputusan_cpns_sk_pns_pangkat')
                            ->label('1. Keputusan CPNS SK PNS Pangkat')
                            ->formatStateUsing(fn($record) => $record->keputusan_cpns_sk_pns_pangkat != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                            ->badge()
                            ->color(fn($record) => $record->keputusan_cpns_sk_pns_pangkat != null ? 'success' : 'danger')
                            ->suffix(function ($record) {
                                if ($record->keputusan_cpns_sk_pns_pangkat) {
                                    $fileName = basename($record->keputusan_cpns_sk_pns_pangkat);
                                    return
                                        ActionGroup::make([
                                            Action::make('download')
                                                ->color('primary')
                                                ->icon('heroicon-o-arrow-down-tray')
                                                ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                            Action::make('preview')
                                                ->color('secondary')
                                                ->icon('heroicon-o-eye')
                                                ->url(route('preview.lampiran', ['file' => $fileName]))
                                                ->openUrlInNewTab(),
                                        ])->button();
                                }
                                return null;
                            })->default('Belum Diunggah'),

                        TextEntry::make('skp_dua_tahun')
                            ->label('2. SKP Dua Tahun')
                            ->formatStateUsing(fn($record) => $record->skp_dua_tahun != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                            ->badge()
                            ->color(fn($record) => $record->skp_dua_tahun != null ? 'success' : 'danger')
                            ->suffix(function ($record) {
                                if ($record->skp_dua_tahun) {
                                    $fileName = basename($record->skp_dua_tahun);
                                    return
                                        ActionGroup::make([
                                            Action::make('download')
                                                ->color('primary')
                                                ->icon('heroicon-o-arrow-down-tray')
                                                ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                            Action::make('preview')
                                                ->color('secondary')
                                                ->icon('heroicon-o-eye')
                                                ->url(route('preview.lampiran', ['file' => $fileName]))
                                                ->openUrlInNewTab(),
                                        ])->button();
                                }
                                return null;
                            })->default('Belum Diunggah'),

                        TextEntry::make('foto_kopi_ijazah_terakhir')
                            ->label('3. Foto Kopi Ijazah Terakhir')
                            ->formatStateUsing(fn($record) => $record->foto_kopi_ijazah_terakhir != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                            ->badge()
                            ->color(fn($record) => $record->foto_kopi_ijazah_terakhir != null ? 'success' : 'danger')
                            ->suffix(function ($record) {
                                if ($record->foto_kopi_ijazah_terakhir) {
                                    $fileName = basename($record->foto_kopi_ijazah_terakhir);
                                    return
                                        ActionGroup::make([
                                            Action::make('download')
                                                ->color('primary')
                                                ->icon('heroicon-o-arrow-down-tray')
                                                ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                            Action::make('preview')
                                                ->color('secondary')
                                                ->icon('heroicon-o-eye')
                                                ->url(route('preview.lampiran', ['file' => $fileName]))
                                                ->openUrlInNewTab(),
                                        ])->button();
                                }
                                return null;
                            })->default('Belum Diunggah'),

                        TextEntry::make('foto_kopi_transkrip_terakhir')
                            ->label('4. Foto Kopi Transkrip Terakhir')
                            ->formatStateUsing(fn($record) => $record->foto_kopi_transkrip_terakhir != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                            ->badge()
                            ->color(fn($record) => $record->foto_kopi_transkrip_terakhir != null ? 'success' : 'danger')
                            ->suffix(function ($record) {
                                if ($record->foto_kopi_transkrip_terakhir) {
                                    $fileName = basename($record->foto_kopi_transkrip_terakhir);
                                    return
                                        ActionGroup::make([
                                            Action::make('download')
                                                ->color('primary')
                                                ->icon('heroicon-o-arrow-down-tray')
                                                ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                            Action::make('preview')
                                                ->color('secondary')
                                                ->icon('heroicon-o-eye')
                                                ->url(route('preview.lampiran', ['file' => $fileName]))
                                                ->openUrlInNewTab(),
                                        ])->button();
                                }
                                return null;
                            })->default('Belum Diunggah'),

                        TextEntry::make('keputusan_jabatan')
                            ->label('5. Keputusan Jabatan')
                            ->formatStateUsing(fn($record) => $record->keputusan_jabatan != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                            ->badge()
                            ->color(fn($record) => $record->keputusan_jabatan != null ? 'success' : 'danger')
                            ->suffix(function ($record) {
                                if ($record->keputusan_jabatan) {
                                    $fileName = basename($record->keputusan_jabatan);
                                    return
                                        ActionGroup::make([
                                            Action::make('download')
                                                ->color('primary')
                                                ->icon('heroicon-o-arrow-down-tray')
                                                ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                            Action::make('preview')
                                                ->color('secondary')
                                                ->icon('heroicon-o-eye')
                                                ->url(route('preview.lampiran', ['file' => $fileName]))
                                                ->openUrlInNewTab(),
                                        ])->button();
                                }
                                return null;
                            })->default('Belum Diunggah'),

                        TextEntry::make('surat_keterangan_akreditasi')
                            ->label('6. Surat Keterangan Akreditasi')
                            ->formatStateUsing(fn($record) => $record->surat_keterangan_akreditasi != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                            ->badge()
                            ->color(fn($record) => $record->surat_keterangan_akreditasi != null ? 'success' : 'danger')
                            ->suffix(function ($record) {
                                if ($record->surat_keterangan_akreditasi) {
                                    $fileName = basename($record->surat_keterangan_akreditasi);
                                    return
                                        ActionGroup::make([
                                            Action::make('download')
                                                ->color('primary')
                                                ->icon('heroicon-o-arrow-down-tray')
                                                ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                            Action::make('preview')
                                                ->color('secondary')
                                                ->icon('heroicon-o-eye')
                                                ->url(route('preview.lampiran', ['file' => $fileName]))
                                                ->openUrlInNewTab(),
                                        ])->button();
                                }
                                return null;
                            })->default('Belum Diunggah'),

                        TextEntry::make('brosur_pamflet')
                            ->label('7. Brosur Pamflet')
                            ->formatStateUsing(fn($record) => $record->brosur_pamflet != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                            ->badge()
                            ->color(fn($record) => $record->brosur_pamflet != null ? 'success' : 'danger')
                            ->suffix(function ($record) {
                                if ($record->brosur_pamflet) {
                                    $fileName = basename($record->brosur_pamflet);
                                    return
                                        ActionGroup::make([
                                            Action::make('download')
                                                ->color('primary')
                                                ->icon('heroicon-o-arrow-down-tray')
                                                ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                            Action::make('preview')
                                                ->color('secondary')
                                                ->icon('heroicon-o-eye')
                                                ->url(route('preview.lampiran', ['file' => $fileName]))
                                                ->openUrlInNewTab(),
                                        ])->button();
                                }
                                return null;
                            })->default('Belum Diunggah'),

                        TextEntry::make('surat_keterangan_konversi')
                            ->label('8. Surat Keterangan Konversi')
                            ->formatStateUsing(fn($record) => $record->surat_keterangan_konversi != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                            ->badge()
                            ->color(fn($record) => $record->surat_keterangan_konversi != null ? 'success' : 'danger')
                            ->suffix(function ($record) {
                                if ($record->surat_keterangan_konversi) {
                                    $fileName = basename($record->surat_keterangan_konversi);
                                    return
                                        ActionGroup::make([
                                            Action::make('download')
                                                ->color('primary')
                                                ->icon('heroicon-o-arrow-down-tray')
                                                ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                            Action::make('preview')
                                                ->color('secondary')
                                                ->icon('heroicon-o-eye')
                                                ->url(route('preview.lampiran', ['file' => $fileName]))
                                                ->openUrlInNewTab(),
                                        ])->button();
                                }
                                return null;
                            })->default('Belum Diunggah'),

                        TextEntry::make('sertifikat_akreditasi')
                            ->label('9. Sertifikat Akreditasi')
                            ->formatStateUsing(fn($record) => $record->sertifikat_akreditasi != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                            ->badge()
                            ->color(fn($record) => $record->sertifikat_akreditasi != null ? 'success' : 'danger')
                            ->suffix(function ($record) {
                                if ($record->sertifikat_akreditasi) {
                                    $fileName = basename($record->sertifikat_akreditasi);
                                    return
                                        ActionGroup::make([
                                            Action::make('download')
                                                ->color('primary')
                                                ->icon('heroicon-o-arrow-down-tray')
                                                ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                            Action::make('preview')
                                                ->color('secondary')
                                                ->icon('heroicon-o-eye')
                                                ->url(route('preview.lampiran', ['file' => $fileName]))
                                                ->openUrlInNewTab(),
                                        ])->button();
                                }
                                return null;
                            })->default('Belum Diunggah'),
                        TextEntry::make('dokumen_pengembangan_kompetensi')
                            ->label('10. Dokumen Lainnya (Opsional)')
                            ->formatStateUsing(fn($record) => $record->dokumen_pengembangan_kompetensi != null ? 'Sudah Diunggah' : 'Belum Ada')
                            ->badge()
                            ->color(fn($record) => $record->dokumen_pengembangan_kompetensi != null ? 'success' : 'danger')
                            ->suffix(function ($record) {
                                if ($record->dokumen_pengembangan_kompetensi) {
                                    $fileName = basename($record->dokumen_pengembangan_kompetensi);
                                    return
                                        ActionGroup::make([
                                            Action::make('download')
                                                ->color('primary')
                                                ->icon('heroicon-o-arrow-down-tray')
                                                ->url(route('download.lampiranpersyaratan', ['file' => $fileName])),
                                            Action::make('preview')
                                                ->color('secondary')
                                                ->icon('heroicon-o-eye')
                                                ->url(route('preview.lampiran', ['file' => $fileName]))
                                                ->openUrlInNewTab(),
                                        ])->button();
                                }
                                return null;
                            })->default('Belum Ada'),

                    ])->columns(3),
            ]);

    }

    public static function getEloquentQuery(): Builder
    {
        $currentPegawaiId = Auth::user()->pegawai_id;

        return parent::getEloquentQuery()
            ->where('pegawai_id', $currentPegawaiId);
    }

}
