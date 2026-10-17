<?php

namespace App\Http\Controllers;

use App\Models\TeacherDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentDownloadController extends Controller
{
    /**
     * Securely download or view private teacher verification document.
     */
    public function download(TeacherDocument $document, Request $request): StreamedResponse
    {
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        $isOwner = $document->teacherProfile && $document->teacherProfile->user_id === $user->id;
        $isAdmin = $user->isAdmin();

        if (! $isOwner && ! $isAdmin) {
            abort(403, 'Akses ditolak: Dokumen identitas bersifat privat dan dilindungi.');
        }

        if (! Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'Berkas dokumen tidak ditemukan di penyimpanan server.');
        }

        return Storage::disk('local')->response(
            $document->file_path,
            $document->original_filename
        );
    }
}
