<?php

namespace App\Filament\OPD\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TugasBelajar;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Tabs;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\OPD\Resources\IzinSeleksiResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\OPD\Resources\IzinSeleksiResource\RelationManagers;

class IzinSeleksiResource extends Resource
{
    protected static ?string $model = TugasBelajar::class;

    protected static ?string $label = 'Izin Seleksi';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('fakultas')
                    ->label('Fakultas')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('prodi')
                    ->label('Prodi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenjang_tujuan')
                    ->label('Jenjang')
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
                        }
                    )
                    ->formatStateUsing(
                        fn($record) => match ($record->status) {
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'passed' => 'Selesai',
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
                        }
                    )
                    ->formatStateUsing(
                        fn($record) => match ($record->stage) {
                            'tahap_opd' => 'Tahap OPD',
                            'tahap_bkpsdm' => 'Tahap BKPSDM',
                            'tahap_seleksi' => 'Tahap Seleksi Masuk PT',
                            'tahap_lulus' => 'Tahap Lulus',
                        }
                    )
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Upload Rekomendasi Kepala OPD')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('rekomendasi_kepala')
                            ->label('Surat Rekomendasi Kepala OPD (Scan yang sudah di TTD)')
                            ->required()
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->panelLayout('compact')
                            ->directory('surat'),
                    ])->action(function (array $data, TugasBelajar $record) {
                        $record->status = 'pending';
                        $record->stage = 'tahap_bkpsdm';
                        $record->rekomendasi_kepala = $data['rekomendasi_kepala'];
                        $user = $record->pegawai->user;

                        $record->save();
                        Notification::make()
                            ->title('Rekomendasi Kepala OPD Diupload')
                            ->body('Surat rekomendasi kepala OPD telah diupload oleh OPD dan menunggu proses lebih lanjut.')
                            ->success()
                            ->sendToDatabase($user); // Kirim notifikasi ke pengguna yang terkait

                        $user = User::whereHas('roles', function ($query) {
                            $query->where('name', 'admin');
                        })->first();

                        Notification::make()
                            ->title('Izin Seleksi Diajukan ke BKPSDM')
                            ->body('Izin seleksi telah diajukan ke BKPSDM, mohon proses lebih lanjut.')
                            ->success()
                            ->sendToDatabase($user);

                    })->visible(fn(TugasBelajar $record): bool => ($record->status === 'approved' && $record->stage === 'tahap_opd') || $record->stage === 'tahap_bkpsdm'),

                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('Surat Rekomendasi Kepala OPD S1')
                        ->label('Surat Rekomendasi Kepala OPD')
                        ->action(
                            function (TugasBelajar $record) {
                                Notification::make()
                                    ->title('Surat Telah Didownload: Tolong Upload yang sudah di ttd basah')
                                    ->success()
                                    ->send();
                                return redirect()->route('download.suratDua', ['recordId' => $record->id]);
                            }
                        )->icon('heroicon-o-document-duplicate')->visible(fn(TugasBelajar $record): bool => $record->jenjang_tujuan === 'S1'),
                    Action::make('Surat Rekomendasi Kepala OPD S2, Keatas')
                        ->label('Surat Rekomendasi Kepala OPD')
                        ->action(
                            function (TugasBelajar $record) {
                                Notification::make()
                                    ->title('Surat Telah Didownload: Tolong Upload yang sudah di ttd basah')
                                    ->success()
                                    ->send();
                                return redirect()->route('download.suratDuaWalkot', ['recordId' => $record->id]);
                            }
                        )->icon('heroicon-o-document-duplicate')->visible(fn(TugasBelajar $record): bool => $record->jenjang_tujuan != 'S1'),
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
                        $record->status = 'approved';
                        $record->save();
                        Notification::make()
                            ->title('Izin Seleksi Telah Di Setujui, Mohon Upload Surat Rekomendasi Kepala masing-masing OPD')
                            ->success()
                            ->send();
                        $user = $record->pegawai->user;
                        Notification::make()
                            ->title('Izin Seleksi Telah Di Setujui Oleh OPD')
                            ->body('Izin seleksi telah disetujui oleh OPD, Mohon tunggu surat rekomendasi kepala OPD.')
                            ->success()
                            ->sendToDatabase($user); // Kirim notifikasi ke pengguna yang terkait
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
                        $record->save();
                        Notification::make()
                            ->title('Izin Seleksi Telah Di Tolak')
                            ->danger()
                            ->send();
                        $user = $record->pegawai->user;
                        Notification::make()
                            ->title('Izin Seleksi Telah Di Tolak Oleh OPD')
                            ->body('Mohon periksa kembali persyaratan yang diajukan dan Catatan.')
                            ->danger()
                            ->sendToDatabase($user);
                    }),
                ])->button()->color('warning')->label('Verif')->icon('heroicon-o-check-circle')->visible(fn(TugasBelajar $record): bool => $record->stage === 'tahap_opd' && $record->status !== 'rejected'),
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
            'view' => Pages\ViewIzinSeleksi::route('/{record}'),
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
        // Get the currently logged-in user's pegawai_id
        $currentPegawaiId = Auth::user()->pegawai_id;

        // Get the unit_kerja of the currently logged-in user
        $currentPegawai = \App\Models\Pegawai::find($currentPegawaiId);
        $unitKerja = $currentPegawai->unit_kerja;

        // Return the filtered query
        return parent::getEloquentQuery()
            ->whereHas('pegawai', function ($query) use ($unitKerja) {
                $query->where('unit_kerja', $unitKerja);
            });
    }

}