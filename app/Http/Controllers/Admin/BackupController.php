<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Support\Facades\Storage;
use Exception;

class BackupController extends Controller
{
    /**
     * Generate and download a database backup.
     */
    public function download(Request $request)
    {
        try {
            // Retrieve DB connection details
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port', '3306');

            // Format DSN for mysqldump-php
            $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName}";

            $fileName = 'backup_' . date('Y_m_d_His') . '.sql';
            $filePath = sys_get_temp_dir() . '/' . $fileName;

            // Initialize Mysqldump
            $dump = new Mysqldump($dsn, $dbUser, $dbPass);
            
            // Create the dump file
            $dump->start($filePath);

            // Return file as a secure download and delete it after sending
            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (Exception $e) {
            \Log::error('Database Backup Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to generate database backup.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
