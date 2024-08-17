<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tugas_belajar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');

            // Identitas Prodi

            $table->string('universitas')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('prodi')->nullable();
            $table->string('jenjang_tujuan')->nullable();

            // Data Kebutuhan Izin Seleksi
            $table->string('keputusan_cpns_sk_pns_pangkat')->nullable(); // Keputusan CPNS, SK PNS dan Pangkat Terakhir
            $table->string('skp_dua_tahun')->nullable(); // SKP 2 (dua) Tahun terakhir bernilai baik
            $table->string('foto_kopi_ijazah_terakhir')->nullable(); // Foto kopi Ijazah terakhir di legalisir
            $table->string('foto_kopi_transkrip_terakhir')->nullable(); // Foto kopi Transkrip nilai terakhir di legalisir
            $table->string('keputusan_jabatan')->nullable(); // Keputusan jabatan terakhir
            $table->string('surat_keterangan_akreditasi')->nullable(); // Surat Keterangan dari Perguruan Tinggi tempat pendidikan yang menyatakan Akreditasi dan bukan kelas jarak jauh
            $table->string('brosur_pamflet')->nullable(); // Brosur/pamflet penerimaan mahasiswa baru dari lembaga pendidikan
            $table->string('surat_keterangan_konversi')->nullable(); // Melampirkan Surat Keterangan konversi nilai bagi PNS yang pindah kampus


            $table->string('sertifikat_akreditasi')->nullable(); // Sertifikat Akreditasi Program Studi yang akan diambil
            $table->string('dokumen_pengembangan_kompetensi')->nullable(); // Dokumen kebutuhan dan Rencana Pengembangan Kompetensi

            // Data Kebutuhan Tugas Belajar
            $table->string('surat_usulan')->nullable(); // Surat usulan calon PNS Tugas Belajar Mandiri dari kepala Perangkat Daerah
            $table->string('rekomendasi_kepala')->nullable(); // Rekomendasi dari kepala Perangkat Daerah
            $table->string('foto_kopi_rekomendasi_sekda')->nullable(); // Foto kopi rekomendasi mengikuti seleksi dari Sekretaris Daerah
            $table->string('surat_keterangan_lulus')->nullable(); // Surat keterangan lulus seleksi dari lembaga pendidikan
            $table->string('fotocopi_sk_cpns')->nullable(); // Fotocopi SK CPNS dilegalisir
            $table->string('fotocopi_sk_pns')->nullable(); // Fotocopi SK PNS dilegalisir
            $table->string('fotocopi_sk_pangkat')->nullable(); // Fotocopi SK Pangkat terakhir dilegalisir
            $table->string('skp_satu_tahun')->nullable(); // SKP 1 (satu) tahun terakhir bernilai baik
            $table->string('ijazah_terakhir')->nullable(); // Ijazah dan transkip nilai terakhir dilegalisir
            $table->string('bukti_pendaftaran')->nullable(); // Bukti pendaftaran dan/atau bukti penerimaan calon mahasiswa/i serta surat keterangan rencana masa pendidikan dan jadwal perkuliahan
            $table->string('surat_pernyataan_biaya')->nullable(); // Surat Pernyataan biaya pendidikan ditanggung oleh PNS yang bersangkutan
            $table->string('pas_foto')->nullable(); // Pas foto ukuran 4x6 pakaian PDH berlatar belakang warna merah sebanyak 3 (tiga) lembar
            $table->string('surat_pernyataan_disiplin')->nullable(); // Surat Pernyataan tidak pernah dijatuhi hukuman disiplin tingkat sedang atau berat
            $table->string('surat_keterangan_kesehatan')->nullable(); // Surat Keterangan Kesehatan dari dokter pemerintah

            // Catatan
            $table->text('catatan')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected', 'passed'])->default('pending');
            $table->enum('stage', ['tahap_opd', 'tahap_bkpsdm', 'tahap_seleksi', 'tahap_lulus'])->default('tahap_opd');
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_belajar');
    }
};
