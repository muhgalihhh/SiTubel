<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TugasBelajar;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\Alignment;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\View;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use App\Http\Controllers\SuratController;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\DaftarPengajuanTugasBelajarResource\Pages;
use App\Filament\Resources\DaftarPengajuanTugasBelajarResource\RelationManagers;
use App\Filament\Resources\DaftarPengajuanTugasBelajarResource\Pages\EditDaftarPengajuanTugasBelajar;
use App\Filament\Resources\DaftarPengajuanTugasBelajarResource\Pages\ViewDaftarPengajuanTugasBelajar;
use App\Filament\Resources\DaftarPengajuanTugasBelajarResource\Pages\ListDaftarPengajuanTugasBelajars;

class DaftarPengajuanTugasBelajarResource extends Resource
{
    protected static ?string $model = TugasBelajar::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-up-down';
    protected static ?string $label = 'Pengajuan Tugas Belajar';

    protected static ?string $navigationGroup = 'Tugas Belajar';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->can('pengajuan-tugas-belajar')) {
            return true;
        } else {
            return false;
        }
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

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
                    ->badge()
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
                            'pending' => 'Menunggu Hasil',
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
                Action::make('Upload Surat Perintah')
                    ->form([
                        FileUpload::make('surat_perintah')
                            ->label('Surat Perintah Tugas Belajar (image/.pdf)')
                            ->required()
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->panelLayout('compact')
                            ->directory('surat'),
                    ])
                    ->label('Upload Surat Perintah')
                    ->button()
                    ->color('gray')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->visible(fn(TugasBelajar $record): bool => $record->stage === 'tahap_lulus' && $record->status === 'pending')->action(function (TugasBelajar $record, array $data) {
                        foreach ($data as $key => $value) {
                            if ($value instanceof \Illuminate\Http\UploadedFile) {
                                $filename = $value->store('surat');
                                $record->{$key} = $filename;
                            } elseif (is_string($value)) {
                                $record->{$key} = $value;
                            }
                        }
                        Notification::make()
                            ->title('Berhasil Diupload: Pegawai telah diizinkan untuk melakukan Tugas Belajar')
                            ->success()
                            ->send();

                        $user = User::where('pegawai_id', $record->pegawai_id)->first();

                        if ($user) {
                            Notification::make()
                                ->title('Surat Perintah Tugas Belajar Telah Diupload Oleh BKPSDM')
                                ->body('Pegawai telah diizinkan untuk melakukan Tugas Belajar')
                                ->info()
                                ->sendToDatabase([$user]);
                        }

                        $record->status = 'approved';
                        $record->stage = 'tahap_lulus';

                        $record->save();
                    }),

                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('Surat Tugas Belajar WaliKota')
                        ->label('Surat Tugas Belajar WaliKota')
                        ->action(
                            function (TugasBelajar $record) {
                                Notification::make()
                                    ->title('Surat Telah Didownload: Tolong Upload yang sudah di ttd basah')
                                    ->success()
                                    ->send();
                                return redirect()->route('download.suratPerintahTubelWalikota', ['recordId' => $record->id]);
                            }
                        )
                        ->icon('heroicon-o-document-duplicate')
                        ->visible(
                            fn(TugasBelajar $record): bool =>
                            ($record->stage === 'tahap_seleksi' || $record->stage === 'tahap_lulus') &&
                            in_array($record->jenjang_tujuan, ['S2', 'S3']) // For jenjang tujuan above S1
                        ),

                    // Action for Surat Tugas Belajar Sekda
                    Action::make('Surat Tugas Belajar Sekda')
                        ->label('Surat Tugas Belajar Sekda')
                        ->action(
                            function (TugasBelajar $record) {
                                Notification::make()
                                    ->title('Surat Telah Didownload: Tolong Upload yang sudah di ttd basah')
                                    ->success()
                                    ->send();
                                return redirect()->route('download.suratPerintahTubelSekda', ['recordId' => $record->id]);
                            }
                        )
                        ->icon('heroicon-o-document-duplicate')
                        ->visible(
                            fn(TugasBelajar $record): bool =>
                            ($record->stage === 'tahap_seleksi' || $record->stage === 'tahap_lulus') &&
                            in_array($record->jenjang_tujuan, ['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'D4', 'S1']) // For jenjang tujuan from SD to S1
                        ),

                    DeleteAction::make(),
                ])->icon('heroicon-o-document-duplicate')->button(),
                ActionGroup::make([
                    Action::make('Setujui')->form([
                        TextInput::make('catatan')
                            ->label('Catatan')
                            ->placeholder('Catatan persetujuan')
                            ->required()
                            ->default(function ($record) {
                                return $record->catatan;
                            }),
                    ])->action(function (array $data, TugasBelajar $record) {
                        $record->catatan = $data['catatan'];
                        $record->status = 'pending';
                        $record->stage = 'tahap_lulus';
                        $record->save();
                        Notification::make()
                            ->title('Tugas Belajar Telah Disetujui: Menunggu Surat Perintah')
                            ->success()
                            ->send();
                        $user = User::where('pegawai_id', $record->pegawai_id)->first();

                        if ($user) {
                            Notification::make()
                                ->title('Tugas Belajar Anda Telah Disetujui: Menunggu Surat Perintah di Upload')
                                ->success()
                                ->sendToDatabase([$user]);
                        }
                    }),
                    Action::make('Tolak')->form([
                        TextInput::make('catatan')
                            ->label('Catatan')
                            ->placeholder('Catatan penolakan')
                            ->required()
                            ->default(function ($record) {
                                return $record->catatan;
                            }),
                    ])->action(function (array $data, TugasBelajar $record) {
                        $record->catatan = $data['catatan'];
                        $record->status = 'rejected';
                        $record->stage = 'tahap_lulus';
                        $record->save();
                        Notification::make()
                            ->title('Tugas Belajar Telah Di Tolak')
                            ->danger()
                            ->send();
                        $user = User::where('pegawai_id', $record->pegawai_id)->first();
                        if ($user) {
                            Notification::make()
                                ->title('Tugas Belajar Anda Telah Ditolak')
                                ->danger()
                                ->body('Silahkan cek catatan penolakan untuk melakukan perbaikan')
                                ->sendToDatabase([$user]);
                        }
                    }),
                ])->button()->color('warning')->label('Verif')->icon('heroicon-o-check-circle')->visible(fn(TugasBelajar $record): bool => ($record->stage === 'tahap_seleksi' && $record->status === 'approved') || $record->status === 'rejected'),
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
            'index' => Pages\ListDaftarPengajuanTugasBelajars::route('/'),
            'edit' => Pages\EditDaftarPengajuanTugasBelajar::route('/{record}/edit'),
            'view' => Pages\ViewDaftarPengajuanTugasBelajar::route('/{record}'),

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('stage', ['tahap_seleksi', 'tahap_lulus']);
    }


    public static function getNavigationBadge(): string
    {
        return TugasBelajar::whereIn('stage', ['tahap_seleksi', 'tahap_lulus'])->count();
    }

}