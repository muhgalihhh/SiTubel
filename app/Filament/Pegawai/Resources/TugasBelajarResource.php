<?php

namespace App\Filament\Pegawai\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Pegawai;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TugasBelajar;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\View;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\Tabs;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Pegawai\Resources\TugasBelajarResource\Pages;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Pegawai\Resources\TugasBelajarResource\RelationManagers;

class TugasBelajarResource extends Resource
{
    protected static ?string $model = TugasBelajar::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Tugas Belajar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    View::make('components.announcement')
                        ->columnSpan('full'),
                    FileUpload::make('surat_keterangan_lulus')
                        ->label('Surat Keterangan Lulus')
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
                        ->directory('pemberkasan_setelah_lulus'),

                    FileUpload::make('fotocopi_sk_cpns')
                        ->label('Fotocopi SK CPNS')
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
                        ->directory('pemberkasan_setelah_lulus'),

                    FileUpload::make('fotocopi_sk_pns')
                        ->label('Fotocopi SK PNS')
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
                        ->directory('pemberkasan_setelah_lulus'),

                    FileUpload::make('fotocopi_sk_pangkat')
                        ->label('Fotocopi SK Pangkat')
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
                        ->directory('pemberkasan_setelah_lulus'),

                    FileUpload::make('skp_satu_tahun')
                        ->label('SKP 1 Tahun Terakhir')
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
                        ->directory('pemberkasan_setelah_lulus'),

                    FileUpload::make('ijazah_terakhir')
                        ->label('Ijazah Terakhir')
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
                        ->directory('pemberkasan_setelah_lulus'),

                    FileUpload::make('bukti_pendaftaran')
                        ->label('Bukti Pendaftaran')
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
                        ->directory('pemberkasan_setelah_lulus'),

                    FileUpload::make('surat_pernyataan_biaya')
                        ->label('Surat Pernyataan Biaya')
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
                        ->directory('pemberkasan_setelah_lulus'),

                    FileUpload::make('pas_foto')
                        ->label('Pas Foto 3x4')
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
                        ->directory('pemberkasan_setelah_lulus'),

                    FileUpload::make('surat_pernyataan_disiplin')
                        ->label('Surat Pernyataan Disiplin')
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
                        ->directory('pemberkasan_setelah_lulus'),

                    FileUpload::make('surat_keterangan_kesehatan')
                        ->label('Surat Keterangan Kesehatan')
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
                        ->directory('pemberkasan_setelah_lulus'),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pegawai.nama')->label('Nama Pegawai'),
                TextColumn::make('universitas')->label('Universitas'),
                TextColumn::make('prodi')->label('Program Studi'),
                TextColumn::make('jenjang_tujuan')->label('Jenjang Tujuan'),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(
                        fn($record) => match ($record->status) {
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'passed' => 'Selesai',
                            default => 'Lulus Seleksi',
                        }
                    )
                    ->badge()
                    ->color(
                        fn($record) => match ($record->status) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'passed' => 'success',
                            default => 'success',
                        }
                    ),
                TextColumn::make('stage')
                    ->label('Tahap')
                    ->formatStateUsing(
                        fn($record) => match ($record->stage) {
                            'tahap_opd' => 'Tahap OPD',
                            'tahap_bkpsdm' => 'Tahap BKPSDM',
                            'tahap_seleksi' => 'Tahap Seleksi Masuk PT',
                            'tahap_lulus' => 'Tahap Lulus',
                            default => 'Tahap OPD',
                        }
                    )
                    ->badge()
                    ->color(
                        fn($record) => match ($record->stage) {
                            'tahap_opd' => 'warning',
                            'tahap_bkpsdm' => 'warning',
                            'tahap_seleksi' => 'warning',
                            'tahap_lulus' => 'success',
                            default => 'warning',
                        }
                    ),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Pemberkasan Setelah Kelulusan')
                    ->form([
                        Forms\Components\Grid::make(2)->schema([
                            View::make('components.announcement')
                                ->columnSpan('full'),
                            FileUpload::make('surat_keterangan_lulus')
                                ->label('Surat Keterangan Lulus')
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
                                ->directory('pemberkasan_setelah_lulus'),

                            FileUpload::make('fotocopi_sk_cpns')
                                ->label('Fotocopi SK CPNS')
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
                                ->directory('pemberkasan_setelah_lulus'),

                            FileUpload::make('fotocopi_sk_pns')
                                ->label('Fotocopi SK PNS')
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
                                ->directory('pemberkasan_setelah_lulus'),

                            FileUpload::make('fotocopi_sk_pangkat')
                                ->label('Fotocopi SK Pangkat')
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
                                ->directory('pemberkasan_setelah_lulus'),

                            FileUpload::make('skp_satu_tahun')
                                ->label('SKP 1 Tahun Terakhir')
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
                                ->directory('pemberkasan_setelah_lulus'),

                            FileUpload::make('ijazah_terakhir')
                                ->label('Ijazah Terakhir')
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
                                ->directory('pemberkasan_setelah_lulus'),

                            FileUpload::make('bukti_pendaftaran')
                                ->label('Bukti Pendaftaran')
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
                                ->directory('pemberkasan_setelah_lulus'),

                            FileUpload::make('surat_pernyataan_biaya')
                                ->label('Surat Pernyataan Biaya')
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
                                ->directory('pemberkasan_setelah_lulus'),

                            FileUpload::make('pas_foto')
                                ->label('Pas Foto 3x4')
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
                                ->directory('pemberkasan_setelah_lulus'),

                            FileUpload::make('surat_pernyataan_disiplin')
                                ->label('Surat Pernyataan Disiplin')
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
                                ->directory('pemberkasan_setelah_lulus'),

                            FileUpload::make('surat_keterangan_kesehatan')
                                ->label('Surat Keterangan Kesehatan')
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
                                ->directory('pemberkasan_setelah_lulus'),

                        ]),
                    ])
                    ->action(
                        function (TugasBelajar $record, array $data) {
                            foreach ($data as $key => $value) {
                                if ($value instanceof \Illuminate\Http\UploadedFile) {
                                    $filename = $value->store('pemberkasan_setelah_lulus');
                                    $record->{$key} = $filename;
                                } elseif (is_string($value)) {
                                    $record->{$key} = $value;
                                }
                            }

                            $record->save();
                            $user = User::whereHas('roles', function ($query) {
                                $query->where('name', 'admin');
                            })->first();

                            Notification::make()
                                ->title('Pemberkasan Setelah Lulus telah diunggah oleh ' . $record->pegawai->nama)
                                ->body('Pemberkasan Setelah Lulus telah diunggah oleh ' . $record->pegawai->nama . ' Mohon dilakukan pengecekan dan verifikasi')
                                ->success()
                                ->sendToDatabase($user);
                        }
                    )
                    ->button()
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('gray')
                    ->visible(fn(TugasBelajar $record): bool => $record->status === 'approved' && $record->stage === 'tahap_seleksi'),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->visible(fn(TugasBelajar $record): bool => ($record->status === 'pending' || $record->status === 'rejected') && ($record->stage === 'tahap_seleksi' || $record->stage === 'tahap_lulus')),
                    Action::make('Telah Lulus Seleksi PT')
                        ->label('Lulus Seleksi Masuk PT')
                        ->form([
                            Forms\Components\Select::make('confirmation')
                                ->label('Apakah Anda diterima di Perguruan Tinggi?')
                                ->options([
                                    'approved' => 'Diterima',
                                    'rejected' => 'Ditolak',
                                ])
                                ->required()
                        ])
                        ->action(function (array $data, TugasBelajar $record) {
                            if ($data['confirmation'] === 'approved') {
                                $record->status = 'approved';
                                $record->stage = 'tahap_seleksi';
                            } elseif ($data['confirmation'] === 'rejected') {
                                $record->status = 'rejected';
                                $record->stage = 'tahap_seleksi';
                            }
                            $record->save();
                        })
                        ->modalHeading('Konfirmasi Kelulusan PT')
                        ->modalButton('OK')
                        ->visible(fn(TugasBelajar $record): bool => $record->status === 'pending' && $record->stage === 'tahap_seleksi')
                        ->button()
                        ->color('info')
                        ->icon('heroicon-o-check-circle'),


                ])->button()->icon('heroicon-o-document-duplicate'),
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

                        Card::make('surat_perintah')
                            ->schema([
                                TextEntry::make('surat_perintah')
                                    ->label('4. Surat Perintah Tugas Belajar')
                                    ->formatStateUsing(fn($record) => $record->surat_perintah != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                    ->badge()
                                    ->color(fn($record) => $record->surat_perintah != null ? 'success' : 'danger')
                                    ->suffix(function ($record) {
                                        if ($record->surat_perintah) {
                                            $fileName = basename($record->surat_perintah);
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
                            ]),


                    ])->columns(3),
                Card::make('Lampiran')
                    ->schema([
                        Tabs::make('Lampiran')
                            ->schema([
                                Tab::make('Data Kebutuhan Izin Seleksi')
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
                                Tab::make('Pemberkasan Tugas Belajar')
                                    ->label('Pemberkasan Tugas Belajar')
                                    ->schema([
                                        TextEntry::make('surat_keterangan_lulus')
                                            ->label('1. Surat Keterangan Lulus')
                                            ->formatStateUsing(fn($record) => $record->surat_keterangan_lulus != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->surat_keterangan_lulus != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->surat_keterangan_lulus) {
                                                    $fileName = basename($record->surat_keterangan_lulus);
                                                    return
                                                        ActionGroup::make([
                                                            Action::make('download')
                                                                ->color('primary')
                                                                ->icon('heroicon-o-arrow-down-tray')
                                                                ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                            Action::make('preview')
                                                                ->color('secondary')
                                                                ->icon('heroicon-o-eye')
                                                                ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                                ->openUrlInNewTab(),
                                                        ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),

                                        TextEntry::make('fotocopi_sk_cpns')
                                            ->label('2. Fotocopi SK CPNS')
                                            ->formatStateUsing(fn($record) => $record->fotocopi_sk_cpns != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->fotocopi_sk_cpns != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->fotocopi_sk_cpns) {
                                                    $fileName = basename($record->fotocopi_sk_cpns);
                                                    return ActionGroup::make([
                                                        Action::make('download')
                                                            ->color('primary')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                        Action::make('preview')
                                                            ->color('secondary')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                            ->openUrlInNewTab(),
                                                    ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),

                                        TextEntry::make('fotocopi_sk_pns')
                                            ->label('3. Fotocopi SK PNS')
                                            ->formatStateUsing(fn($record) => $record->fotocopi_sk_pns != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->fotocopi_sk_pns != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->fotocopi_sk_pns) {
                                                    $fileName = basename($record->fotocopi_sk_pns);
                                                    return ActionGroup::make([
                                                        Action::make('download')
                                                            ->color('primary')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                        Action::make('preview')
                                                            ->color('secondary')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                            ->openUrlInNewTab(),
                                                    ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),

                                        TextEntry::make('fotocopi_sk_pangkat')
                                            ->label('4. Fotocopi SK Pangkat')
                                            ->formatStateUsing(fn($record) => $record->fotocopi_sk_pangkat != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->fotocopi_sk_pangkat != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->fotocopi_sk_pangkat) {
                                                    $fileName = basename($record->fotocopi_sk_pangkat);
                                                    return ActionGroup::make([
                                                        Action::make('download')
                                                            ->color('primary')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                        Action::make('preview')
                                                            ->color('secondary')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                            ->openUrlInNewTab(),
                                                    ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),

                                        TextEntry::make('skp_satu_tahun')
                                            ->label('5. SKP Satu Tahun')
                                            ->formatStateUsing(fn($record) => $record->skp_satu_tahun != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->skp_satu_tahun != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->skp_satu_tahun) {
                                                    $fileName = basename($record->skp_satu_tahun);
                                                    return ActionGroup::make([
                                                        Action::make('download')
                                                            ->color('primary')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                        Action::make('preview')
                                                            ->color('secondary')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                            ->openUrlInNewTab(),
                                                    ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),

                                        TextEntry::make('ijazah_terakhir')
                                            ->label('6. Ijazah Terakhir')
                                            ->formatStateUsing(fn($record) => $record->ijazah_terakhir != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->ijazah_terakhir != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->ijazah_terakhir) {
                                                    $fileName = basename($record->ijazah_terakhir);
                                                    return ActionGroup::make([
                                                        Action::make('download')
                                                            ->color('primary')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                        Action::make('preview')
                                                            ->color('secondary')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                            ->openUrlInNewTab(),
                                                    ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),

                                        TextEntry::make('bukti_pendaftaran')
                                            ->label('7. Bukti Pendaftaran')
                                            ->formatStateUsing(fn($record) => $record->bukti_pendaftaran != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->bukti_pendaftaran != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->bukti_pendaftaran) {
                                                    $fileName = basename($record->bukti_pendaftaran);
                                                    return ActionGroup::make([
                                                        Action::make('download')
                                                            ->color('primary')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                        Action::make('preview')
                                                            ->color('secondary')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                            ->openUrlInNewTab(),
                                                    ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),

                                        TextEntry::make('surat_pernyataan_biaya')
                                            ->label('8. Surat Pernyataan Biaya')
                                            ->formatStateUsing(fn($record) => $record->surat_pernyataan_biaya != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->surat_pernyataan_biaya != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->surat_pernyataan_biaya) {
                                                    $fileName = basename($record->surat_pernyataan_biaya);
                                                    return ActionGroup::make([
                                                        Action::make('download')
                                                            ->color('primary')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                        Action::make('preview')
                                                            ->color('secondary')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                            ->openUrlInNewTab(),
                                                    ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),

                                        TextEntry::make('pas_foto')
                                            ->label('9. Pas Foto')
                                            ->formatStateUsing(fn($record) => $record->pas_foto != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->pas_foto != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->pas_foto) {
                                                    $fileName = basename($record->pas_foto);
                                                    return ActionGroup::make([
                                                        Action::make('download')
                                                            ->color('primary')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                        Action::make('preview')
                                                            ->color('secondary')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                            ->openUrlInNewTab(),
                                                    ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),

                                        TextEntry::make('surat_pernyataan_disiplin')
                                            ->label('10. Surat Pernyataan Disiplin')
                                            ->formatStateUsing(fn($record) => $record->surat_pernyataan_disiplin != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->surat_pernyataan_disiplin != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->surat_pernyataan_disiplin) {
                                                    $fileName = basename($record->surat_pernyataan_disiplin);
                                                    return ActionGroup::make([
                                                        Action::make('download')
                                                            ->color('primary')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                        Action::make('preview')
                                                            ->color('secondary')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                            ->openUrlInNewTab(),
                                                    ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),

                                        TextEntry::make('surat_keterangan_kesehatan')
                                            ->label('11. Surat Keterangan Kesehatan')
                                            ->formatStateUsing(fn($record) => $record->surat_keterangan_kesehatan != null ? 'Sudah Diunggah' : 'Belum Diunggah')
                                            ->badge()
                                            ->color(fn($record) => $record->surat_keterangan_kesehatan != null ? 'success' : 'danger')
                                            ->suffix(function ($record) {
                                                if ($record->surat_keterangan_kesehatan) {
                                                    $fileName = basename($record->surat_keterangan_kesehatan);
                                                    return ActionGroup::make([
                                                        Action::make('download')
                                                            ->color('primary')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->url(route('download.pemberkasansetelahlulus', ['file' => $fileName])),
                                                        Action::make('preview')
                                                            ->color('secondary')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(route('preview.lampiranLulus', ['file' => $fileName]))
                                                            ->openUrlInNewTab(),
                                                    ])->button();
                                                }
                                                return null;
                                            })->default('Belum Diunggah'),
                                    ])->columns(3),

                            ])

                    ])

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTugasBelajars::route('/'),
            'create' => Pages\CreateTugasBelajar::route('/create'),
            'edit' => Pages\EditTugasBelajar::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $currentPegawaiId = Auth::user()->pegawai_id;

        return parent::getEloquentQuery()
            ->whereIn('stage', ['tahap_seleksi', 'tahap_lulus'])
            ->where('pegawai_id', $currentPegawaiId);
    }


}
