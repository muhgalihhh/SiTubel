<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PegawaiResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Models\Pegawai; // Import the Pegawai model

class ManagePegawais extends ManageRecords
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $data = [];

        // Retrieve and join the necessary tables
        $pegawai = Pegawai::leftJoin('users', 'pegawai.id', '=', 'users.pegawai_id')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('pegawai.id', 'pegawai.nama', 'roles.name as role_name', 'users.id as user_id')
            ->orderByRaw('CASE WHEN users.id IS NULL THEN 1 ELSE 0 END')
            ->get();

        // Separate employees with null roles
        $nullRoleEmployees = $pegawai->filter(fn($item) => is_null($item->role_name));

        // Group pegawai by their roles (excluding null roles)
        $groupedByRole = $pegawai->filter(fn($item) => !is_null($item->role_name))
            ->groupBy('role_name');
        // Add tab for each role
        foreach ($groupedByRole as $roleName => $group) {
            $data[$roleName] = Tab::make($roleName)
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('id', $group->pluck('id')))
                ->label($roleName);
        }

        // Add tab for employees without accounts
        $data['No Account'] = Tab::make('No Account')
            ->modifyQueryUsing(function (Builder $query) {
                return $query->whereDoesntHave('user'); // Ensure the 'user' relationship is correctly defined
            })
            ->label('Belum Memiliki Akun');

        return $data;
    }



}
