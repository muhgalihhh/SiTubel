<?php

namespace App\Imports;

use App\Models\UnitKerja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;

class ImportUnitKerja implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new UnitKerja([
            'name' => $row[0],
            'address' => $row[1],
            'email' => $row[2],
            'website' => $row[3],
            'phone' => $row[4],
        ]);
    }
}
