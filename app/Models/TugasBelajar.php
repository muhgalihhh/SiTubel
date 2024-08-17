<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TugasBelajar extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi default Laravel
    protected $table = 'tugas_belajar';

    // Kolom-kolom yang dapat diisi massal
    protected $fillable = [
        'pegawai_id',

        // Identitas Kampus Tujuan
        'universitas',
        'fakultas',
        'prodi',
        'jenjang_tujuan',

        // Data Kebutuhan Izin Seleksi
        'keputusan_cpns_sk_pns_pangkat',
        'skp_dua_tahun',
        'foto_kopi_ijazah_terakhir',
        'foto_kopi_transkrip_terakhir',
        'keputusan_jabatan',
        'surat_keterangan_akreditasi',
        'brosur_pamflet',
        'surat_keterangan_konversi',
        'sertifikat_akreditasi',
        'dokumen_pengembangan_kompetensi',

        // Data Kebutuhan Tugas Belajar
        'surat_usulan',
        'rekomendasi_kepala',
        'foto_kopi_rekomendasi_sekda',
        'surat_keterangan_lulus',
        'fotocopi_sk_cpns',
        'fotocopi_sk_pns',
        'fotocopi_sk_pangkat',
        'skp_satu_tahun',
        'ijazah_terakhir',
        'bukti_pendaftaran',
        'surat_pernyataan_biaya',
        'pas_foto',
        'surat_pernyataan_disiplin',
        'surat_keterangan_kesehatan',

        // Catatan
        'catatan',

        // Status and stage
        'status',
        'stage',
    ];

    // Relasi dengan model Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
