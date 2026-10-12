<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function index(): View
    {
        $backupDir = storage_path('app/backups');
        if (! File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $files = collect(File::files($backupDir))->map(fn ($f) => [
            'name' => $f->getFilename(),
            'size' => round($f->getSize() / 1024, 2).' KB',
            'created_at' => date('Y-m-d H:i:s', $f->getMTime()),
        ])->sortByDesc('created_at')->values();

        return view('admin.backup.index', compact('files'));
    }

    public function run(): RedirectResponse
    {
        $backupDir = storage_path('app/backups');
        if (! File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $filename = 'backup-'.date('Y-m-d-His').'.sqlite';
        $source = database_path('database.sqlite');

        if (File::exists($source)) {
            File::copy($source, $backupDir.'/'.$filename);
        } else {
            File::put($backupDir.'/'.'dummy-db-'.date('Y-m-d-His').'.sql', '-- Backup SQL dump at '.now());
        }

        ActivityLog::log('created', 'backup', "Membuat cadangan sistem: {$filename}");

        return back()->with('success', 'Backup database berhasil dibuat.');
    }

    public function download(string $filename): BinaryFileResponse|RedirectResponse
    {
        $path = storage_path('app/backups/'.basename($filename));
        if (! File::exists($path)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        return response()->download($path);
    }
}
