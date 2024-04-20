<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SystemBackupController extends Controller
{
    public function backup(): BinaryFileResponse
    {
        // Define the backup file path
        $backupFilePath = storage_path('app/backup.sql');

        // Get the database connection details
        $connection = config('database.default');
        $host = config("database.connections.{$connection}.host");
        $port = config("database.connections.{$connection}.port");
        $database = config("database.connections.{$connection}.database");
        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");

        // Create the backup command
        $command = "mysqldump --user={$username} --password={$password} --host={$host} --port={$port} {$database} > {$backupFilePath}";

        // Execute the backup command
        system($command);

        // Download the backup file
        return response()->download($backupFilePath);
    }
}
