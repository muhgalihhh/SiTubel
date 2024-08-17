<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\UnitKerja;
use App\Models\TugasBelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SuratController extends Controller
{
    public function createSuratPermohonanSatu(Request $request, $recordId)
    {
        $record = TugasBelajar::findOrFail($recordId);
        $templatePath = public_path('template_surat/Surat_Tugas_Belajar_1.docx');
        $fileName = "Surat_Tugas_Belajar_" . $record->pegawai->nama . ".docx";
        $outputPath = storage_path("app/public/surat/{$fileName}");

        // Periksa apakah file sudah ada dan hapus jika ada
        if (File::exists($outputPath)) {
            File::delete($outputPath);
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Ganti placeholder dengan data dari $record
        $templateProcessor->setValue('nama', $record->pegawai->nama);
        $templateProcessor->setValue('nip', $record->pegawai->NIP);
        $templateProcessor->setValue('no_telp', $record->pegawai->no_telp);
        $templateProcessor->setValue('tempat_lahir', $record->pegawai->tempat_lahir);
        $templateProcessor->setValue('tanggal_lahir', $this->formatIndonesianDate($record->pegawai->tanggal_lahir));
        $templateProcessor->setValue('pendidikan', $record->pegawai->pendidikan);
        $templateProcessor->setValue('tahun_lulus', $record->pegawai->tahun_lulus);
        $templateProcessor->setValue('pangkat_golongan', $record->pegawai->pangkat_golongan);
        $templateProcessor->setValue('jabatan', $record->pegawai->jabatan);
        $templateProcessor->setValue('unit_kerja', $record->pegawai->unit_kerja);
        $templateProcessor->setValue('universitas', $record->universitas);
        $templateProcessor->setValue('jurusan', $record->prodi);
        $templateProcessor->setValue('tahun_pelajaran', Carbon::now()->format('Y'));
        $templateProcessor->setValue('tanggal_sekarang', $this->formatIndonesianDate(Carbon::now()));

        $unit_kerja = UnitKerja::where('name', 'LIKE', '%' . $record->pegawai->unit_kerja . '%')->first();
        $templateProcessor->setValue('alamat_unit_kerja', $unit_kerja->address);
        $templateProcessor->setValue('phone', $unit_kerja->phone);
        $templateProcessor->setValue('email', $unit_kerja->email);
        $templateProcessor->setValue('website', $unit_kerja->website);
        // Simpan dokumen yang sudah diproses
        $templateProcessor->saveAs($outputPath);

        // Periksa apakah file sudah berhasil dibuat

        return response()->download($outputPath);
    }


    public function createSuratPermohonanDua(Request $request, $recordId)
    {
        $record = TugasBelajar::findOrFail($recordId);
        $templatePath = public_path('template_surat/Surat_Tugas_Belajar_2.docx');
        $fileName = "Surat_Tugas_Belajar_2" . $record->pegawai->nama . ".docx";
        $outputPath = storage_path("app/public/surat/{$fileName}");

        // Periksa apakah file sudah ada dan hapus jika ada
        if (File::exists($outputPath)) {
            File::delete($outputPath);
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('nama', $record->pegawai->nama);
        $templateProcessor->setValue('nip', $record->pegawai->NIP);
        $templateProcessor->setValue('no_telp', $record->pegawai->no_telp);
        $templateProcessor->setValue('tempat_lahir', $record->pegawai->tempat_lahir);
        $templateProcessor->setValue('tanggal_lahir', $this->formatIndonesianDate($record->pegawai->tanggal_lahir));
        $templateProcessor->setValue('pendidikan', $record->pegawai->pendidikan);
        $templateProcessor->setValue('tahun_lulus', $record->pegawai->tahun_lulus);
        $templateProcessor->setValue('pangkat_golongan', $record->pegawai->pangkat_golongan);
        $templateProcessor->setValue('jabatan', $record->pegawai->jabatan);
        $templateProcessor->setValue('unit_kerja', $record->pegawai->unit_kerja);
        $templateProcessor->setValue('universitas', $record->universitas);
        $templateProcessor->setValue('jurusan', $record->prodi);
        $templateProcessor->setValue('tahun_pelajaran', Carbon::now()->format('Y'));
        $templateProcessor->setValue('tanggal_sekarang', $this->formatIndonesianDate(Carbon::now()));

        $unit_kerja = UnitKerja::where('name', 'LIKE', '%' . $record->pegawai->unit_kerja . '%')->first();
        $templateProcessor->setValue('alamat_unit_kerja', $unit_kerja->address);
        $templateProcessor->setValue('phone', $unit_kerja->phone);
        $templateProcessor->setValue('email', $unit_kerja->email);
        $templateProcessor->setValue('website', $unit_kerja->website);
        // Simpan dokumen yang sudah diproses
        $templateProcessor->saveAs($outputPath);
        // Periksa apakah file sudah berhasil dibuat
        return response()->download($outputPath);

    }


    public function createSuratPermohonanDuaWalikota(Request $request, $recordId)
    {
        $record = TugasBelajar::findOrFail($recordId);
        $templatePath = public_path('template_surat/Surat_Tugas_Belajar_2_Walikota.docx');
        $fileName = "Surat_Tugas_Belajar_2_Walikota" . $record->pegawai->nama . ".docx";
        $outputPath = storage_path("app/public/surat/{$fileName}");

        // Periksa apakah file sudah ada dan hapus jika ada
        if (File::exists($outputPath)) {
            File::delete($outputPath);
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('nama', $record->pegawai->nama);
        $templateProcessor->setValue('nip', $record->pegawai->NIP);
        $templateProcessor->setValue('no_telp', $record->pegawai->no_telp);
        $templateProcessor->setValue('tempat_lahir', $record->pegawai->tempat_lahir);
        $templateProcessor->setValue('tanggal_lahir', $this->formatIndonesianDate($record->pegawai->tanggal_lahir));
        $templateProcessor->setValue('pendidikan', $record->pegawai->pendidikan);
        $templateProcessor->setValue('tahun_lulus', $record->pegawai->tahun_lulus);
        $templateProcessor->setValue('pangkat_golongan', $record->pegawai->pangkat_golongan);
        $templateProcessor->setValue('jabatan', $record->pegawai->jabatan);
        $templateProcessor->setValue('unit_kerja', $record->pegawai->unit_kerja);
        $templateProcessor->setValue('universitas', $record->universitas);
        $templateProcessor->setValue('jurusan', $record->prodi);
        $templateProcessor->setValue('tahun_pelajaran', Carbon::now()->format('Y'));
        $templateProcessor->setValue('tanggal_sekarang', $this->formatIndonesianDate(Carbon::now()));

        $unit_kerja = UnitKerja::where('name', 'LIKE', '%' . $record->pegawai->unit_kerja . '%')->first();
        $templateProcessor->setValue('alamat_unit_kerja', $unit_kerja->address);
        $templateProcessor->setValue('phone', $unit_kerja->phone);
        $templateProcessor->setValue('email', $unit_kerja->email);
        $templateProcessor->setValue('website', $unit_kerja->website);
        // Simpan dokumen yang sudah diproses
        $templateProcessor->saveAs($outputPath);
        // Periksa apakah file sudah berhasil dibuat
        return response()->download($outputPath);
    }

    public function createSuratSekdaMengikutiSeleksi(Request $request, $recordId)
    {
        $record = TugasBelajar::findOrFail($recordId);
        $templatePath = public_path('template_surat/Tugas_Belajar_ttd_SEKDA_mengikuti_seleksi_2024_paraf hirarki.docx');
        $fileName = "Surat_TUBEL_ttd_Sekda_Mengikuti_Seleksi_" . $record->pegawai->nama . ".docx";
        $outputPath = storage_path("app/public/surat/{$fileName}");

        // Periksa apakah file sudah ada dan hapus jika ada
        if (File::exists($outputPath)) {
            File::delete($outputPath);
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('nama', $record->pegawai->nama);
        $templateProcessor->setValue('nip', $record->pegawai->NIP);
        $templateProcessor->setValue('no_telp', $record->pegawai->no_telp);
        $templateProcessor->setValue('tempat_lahir', $record->pegawai->tempat_lahir);
        $templateProcessor->setValue('tanggal_lahir', $this->formatIndonesianDate($record->pegawai->tanggal_lahir));
        $templateProcessor->setValue('pendidikan', $record->pegawai->pendidikan);
        $templateProcessor->setValue('tahun_lulus', $record->pegawai->tahun_lulus);
        $templateProcessor->setValue('pangkat_golongan', $record->pegawai->pangkat_golongan);
        $templateProcessor->setValue('jabatan', $record->pegawai->jabatan);
        $templateProcessor->setValue('unit_kerja', $record->pegawai->unit_kerja);
        $templateProcessor->setValue('universitas', $record->universitas);
        $templateProcessor->setValue('jurusan', $record->prodi);
        $templateProcessor->setValue('jenjang_tujuan', $record->jenjang_tujuan);
        $currentYear = Carbon::now()->format('Y');
        $nextYear = Carbon::now()->addYear()->format('Y');
        $tahunPelajaran = $currentYear . '/' . $nextYear;
        $templateProcessor->setValue('tahun_pelajaran', $tahunPelajaran);
        $templateProcessor->setValue('tanggal_sekarang', $this->formatIndonesianDate(Carbon::now()));

        $unit_kerja = UnitKerja::where('name', 'LIKE', '%' . $record->pegawai->unit_kerja . '%')->first();
        $templateProcessor->setValue('alamat_unit_kerja', $unit_kerja->address);
        $templateProcessor->setValue('phone', $unit_kerja->phone);
        $templateProcessor->setValue('email', $unit_kerja->email);
        $templateProcessor->setValue('website', $unit_kerja->website);
        // Simpan dokumen yang sudah diproses
        $templateProcessor->saveAs($outputPath);
        // Periksa apakah file sudah berhasil dibuat
        return response()->download($outputPath);

    }
    public function createSuratPerintahTubelSekda(Request $request, $recordId)
    {
        $record = TugasBelajar::findOrFail($recordId);
        $templatePath = public_path('template_surat/Tugas_Belajar_ttd_SEKDA_2024_paraf_hirarki.docx');
        $fileName = "Surat_TUBEL_ttd_Sekda_" . $record->pegawai->nama . ".docx";
        $outputPath = storage_path("app/public/surat/{$fileName}");

        // Periksa apakah file sudah ada dan hapus jika ada
        if (File::exists($outputPath)) {
            File::delete($outputPath);
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('nama', $record->pegawai->nama);
        $templateProcessor->setValue('nip', $record->pegawai->NIP);
        $templateProcessor->setValue('no_telp', $record->pegawai->no_telp);
        $templateProcessor->setValue('tempat_lahir', $record->pegawai->tempat_lahir);
        $templateProcessor->setValue('tanggal_lahir', $this->formatIndonesianDate($record->pegawai->tanggal_lahir));
        $templateProcessor->setValue('pendidikan', $record->pegawai->pendidikan);
        $templateProcessor->setValue('tahun_lulus', $record->pegawai->tahun_lulus);
        $templateProcessor->setValue('pangkat_golongan', $record->pegawai->pangkat_golongan);
        $templateProcessor->setValue('jabatan', $record->pegawai->jabatan);
        $templateProcessor->setValue('unit_kerja', $record->pegawai->unit_kerja);
        $templateProcessor->setValue('universitas', $record->universitas);
        $templateProcessor->setValue('jurusan', $record->prodi);
        $templateProcessor->setValue('jenjang_tujuan', $record->jenjang_tujuan);
        $currentYear = Carbon::now()->format('Y');
        $nextYear = Carbon::now()->addYear()->format('Y');
        $tahunPelajaran = $currentYear . '/' . $nextYear;
        $templateProcessor->setValue('tahun_pelajaran', $tahunPelajaran);
        $templateProcessor->setValue('tanggal_sekarang', $this->formatIndonesianDate(Carbon::now()));

        $unit_kerja = UnitKerja::where('name', 'LIKE', '%' . $record->pegawai->unit_kerja . '%')->first();
        $templateProcessor->setValue('alamat_unit_kerja', $unit_kerja->address);
        $templateProcessor->setValue('phone', $unit_kerja->phone);
        $templateProcessor->setValue('email', $unit_kerja->email);
        $templateProcessor->setValue('website', $unit_kerja->website);

        // Simpan dokumen yang sudah diproses
        $templateProcessor->saveAs($outputPath);
        // Periksa apakah file sudah berhasil dibuat
        return response()->download($outputPath);
    }
    public function createSuratPerintahTubelWalikota(Request $request, $recordId)
    {
        $record = TugasBelajar::findOrFail($recordId);
        $templatePath = public_path('template_surat/Tugas_Belajar_ttd_WALI_KOTA_BANJAR_2024_paraf_hirarki.docx');
        $fileName = "Surat_TUBEL_ttd_Walikota_" . $record->pegawai->nama . ".docx";
        $outputPath = storage_path("app/public/surat/{$fileName}");

        // Periksa apakah file sudah ada dan hapus jika ada
        if (File::exists($outputPath)) {
            File::delete($outputPath);
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('nama', $record->pegawai->nama);
        $templateProcessor->setValue('nip', $record->pegawai->NIP);
        $templateProcessor->setValue('no_telp', $record->pegawai->no_telp);
        $templateProcessor->setValue('tempat_lahir', $record->pegawai->tempat_lahir);
        $templateProcessor->setValue('tanggal_lahir', $this->formatIndonesianDate($record->pegawai->tanggal_lahir));
        $templateProcessor->setValue('pendidikan', $record->pegawai->pendidikan);
        $templateProcessor->setValue('tahun_lulus', $record->pegawai->tahun_lulus);
        $templateProcessor->setValue('pangkat_golongan', $record->pegawai->pangkat_golongan);
        $templateProcessor->setValue('jabatan', $record->pegawai->jabatan);
        $templateProcessor->setValue('unit_kerja', $record->pegawai->unit_kerja);
        $templateProcessor->setValue('universitas', $record->universitas);
        $templateProcessor->setValue('jurusan', $record->prodi);
        $templateProcessor->setValue('jenjang_tujuan', $record->jenjang_tujuan);
        $currentYear = Carbon::now()->format('Y');
        $nextYear = Carbon::now()->addYear()->format('Y');
        $tahunPelajaran = $currentYear . '/' . $nextYear;
        $templateProcessor->setValue('tahun_pelajaran', $tahunPelajaran);
        $templateProcessor->setValue('tanggal_sekarang', $this->formatIndonesianDate(Carbon::now()));

        $unit_kerja = UnitKerja::where('name', 'LIKE', '%' . $record->pegawai->unit_kerja . '%')->first();
        $templateProcessor->setValue('alamat_unit_kerja', $unit_kerja->address);
        $templateProcessor->setValue('phone', $unit_kerja->phone);
        $templateProcessor->setValue('email', $unit_kerja->email);
        $templateProcessor->setValue('website', $unit_kerja->website);
        // Simpan dokumen yang sudah diproses
        $templateProcessor->saveAs($outputPath);
        // Periksa apakah file sudah berhasil dibuat
        return response()->download($outputPath);

    }


    private function formatIndonesianDate($date)
    {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $carbonDate = Carbon::parse($date);
        $day = $carbonDate->format('d');
        $month = $months[$carbonDate->format('F')];
        $year = $carbonDate->format('Y');

        return "$day $month $year";
    }
}
