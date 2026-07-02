<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\MySql;

class BackupRestoreController extends Controller
{
    public function index()
    {
        return view('admin.backup_restore.index');
    }

    public function backup()
    {
        try {
            $filename = 'backup_erapor_' . date('Y_m_d_His') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            MySql::create()
                ->setDbName(env('DB_DATABASE'))
                ->setUserName(env('DB_USERNAME'))
                ->setPassword(env('DB_PASSWORD') ?? '')
                ->setHost(env('DB_HOST', '127.0.0.1'))
                ->setPort(env('DB_PORT', '3306'))
                ->dumpToFile($path);

            return redirect()->back()->with('success_backup', $filename)->with('tab', 'backup');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan backup: ' . $e->getMessage())->with('tab', 'backup');
        }
    }

    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        if (file_exists($path)) {
            return response()->download($path);
        }
        return redirect()->back()->with('error', 'File backup tidak ditemukan.');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'file_sql' => 'required|file|mimes:sql,txt'
        ], [
            'file_sql.required' => 'Pilih file backup (SQL) terlebih dahulu.'
        ]);

        try {
            $file = $request->file('file_sql');
            $sql = file_get_contents($file->getRealPath());

            DB::unprepared($sql);

            return redirect()->back()->with('success', 'Data e-Rapor berhasil direstore!')->with('tab', 'restore');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal merestore data: ' . $e->getMessage())->with('tab', 'restore');
        }
    }

    public function restoreSp(Request $request)
    {
        $request->validate([
            'file_sql_sp' => 'required|file|mimes:sql,txt'
        ], [
            'file_sql_sp.required' => 'Pilih file backup Rapor SP (SQL) terlebih dahulu.'
        ]);

        try {
            $file = $request->file('file_sql_sp');
            $sql = file_get_contents($file->getRealPath());

            DB::unprepared($sql);

            return redirect()->back()->with('success', 'Data dari Rapor SP 2025 berhasil direstore dan dimigrasikan!')->with('tab', 'restore_sp');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal merestore data Rapor SP: ' . $e->getMessage())->with('tab', 'restore_sp');
        }
    }
}
