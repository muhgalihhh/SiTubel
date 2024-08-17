<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DownloadController extends Controller
{
    public function downloadFileLampiran(Request $request, $file)
    {
        // kita encode agar spasi tidak bermasalah
        $file = urldecode($file);
        $filePath = storage_path('app/public/lampiran_persyaratan_pengajuan_tugas_belajar/' . $file);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json([
                'message' => 'File not found!'
            ], 404);
        }

    }

    public function downloadFileBerkasSetelahLulus(Request $request, $file)
    {
        // kita encode agar spasi tidak bermasalah
        $file = urldecode($file);
        $filePath = storage_path('app/public/pemberkasan_setelah_lulus/' . $file);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json([
                'message' => 'File not found!'
            ], 404);
        }
    }

    public function downloadSurat(Request $request, $file)
    {
        // kita encode agar spasi tidak bermasalah
        $file = urldecode($file);
        $filePath = storage_path('app/public/surat/' . $file);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json([
                'message' => 'File not found!'
            ], 404);
        }
    }
    public function previewLampiran($file)
    {
        $fileName = basename($file);

        $filePath = public_path('storage/lampiran_persyaratan_pengajuan_tugas_belajar/' . $fileName);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->file($filePath);
    }

    public function previewLampiranLulus($file)
    {
        $fileName = basename($file);

        $filePath = public_path('storage/pemberkasan_setelah_lulus/' . $fileName);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->file($filePath);
    }

    public function previewSurat($file)
    {
        $fileName = basename($file);

        $filePath = public_path('storage/surat/' . $fileName);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->file($filePath);
    }

}