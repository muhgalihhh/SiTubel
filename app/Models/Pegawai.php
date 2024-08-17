<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Notifiable;


    // Tentukan nama tabel jika tidak sesuai dengan konvensi default Laravel
    protected $table = 'pegawai';

    // Kolom-kolom yang dapat diisi massal
    protected $fillable = [
        'NIP',
        'nama',
        'no_telp',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan',
        'tahun_lulus',
        'pangkat_golongan',
        'jabatan',
        'unit_kerja',
        'foto_pegawai',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'pegawai_id');
    }

    public function tugasBelajar()
    {
        return $this->hasMany(TugasBelajar::class, 'pegawai_id');
    }

}
