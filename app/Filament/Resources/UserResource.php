<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Pegawai;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\EditAction;
use Filament\Infolists\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Konfigurasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Akun')
                    ->required()
                    ->placeholder('John Doe'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->placeholder('example@email.com'),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord),
                Select::make('roles')->multiple()->relationship('roles', 'name'),
                Select::make('pegawai_id')
                    ->label('Pegawai')
                    ->options(function () {
                        // Get all Pegawai IDs that are already associated with a User
                        $usedPegawaiIds = User::pluck('pegawai_id')->toArray();

                        // Get all Pegawai that do not have an associated User
                        $availablePegawai = Pegawai::whereDoesntHave('user')->pluck('nama', 'id');

                        return $availablePegawai;
                    })
                    ->searchable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('pegawai.nama')
                    ->searchable()
                    ->label('Nama Pegawai')
                    ->sortable(),
                TextColumn::make('pegawai.NIP')
                    ->searchable()
                    ->label('NIP')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->label('Username')
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->label('Email')
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->searchable()
                    ->label('Role')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('name')
                    ->label('Name')
                    ->query(fn(Builder $query, string $value) => $query->where('name', 'like', "%{$value}%")),
                Filter::make('email')
                    ->label('Email')
                    ->query(fn(Builder $query, string $value) => $query->where('email', 'like', "%{$value}%")),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->button()->icon('heroicon-o-document-duplicate')->label('Aksi'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Card pertama
                Card::make()
                    ->schema([
                        TextEntry::make('pegawai.nama')
                            ->label('Nama Pegawai'),
                        TextEntry::make('pegawai.NIP')
                            ->label('NIP'),
                    ])
                    ->heading('Informasi Pegawai')->columns(3),

                // Card kedua
                Card::make()
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('roles.name')
                            ->label('Role'),
                    ])
                    ->heading('Informasi Akun')->columns(3),
            ]);

    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
    public static function getModelLabel(): string
    {
        return 'Akun Pengguna';
    }

    public static function getEloquentQuery(): Builder
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get()->pluck('id');

        return parent::getEloquentQuery()->whereNotIn('id', $admins);
    }
}
