<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;
use App\Models\Settings;

class AdminController extends Controller
{
    public function downloadLatestDbBackup()
    {
        try {
            $generalSettings = Settings::firstOrFail();

            if(empty($generalSettings)) {
                return redirect(route('company.settings.edit'))
                    ->with("message","Backed up file not found. Generate backup and try again.")
                    ->with("alert-class", "error");
            } elseif(Carbon::now()->subDays(1) > $generalSettings->last_db_backup_created_at) {
                return redirect(route('company.settings.edit'))
                    ->with("message","Last generated file is too old. Generate backup and try again.")
                    ->with("alert-class", "error");
            }
             return Storage::disk('backup')->download($generalSettings->last_db_backup_file_name);
        } catch (Exception $exception) {
        }
        return redirect(route('company.settings.edit'))
            ->with("message","Backup failed to download.")
            ->with("alert-class", "error");
    }

    public function createDbBackup()
    {
        $fileName  = Carbon::now()->format('Y-m-d-H-m-s'). ".zip";

        try {
            $generalSettings = Settings::first();

            if(empty($generalSettings)) {
                $generalSettings = new Settings();
            }

            //if backup created within the last 30 min discard new request
            if(Carbon::now()->subMinutes(30) > $generalSettings->last_db_backup_created_at) {
                //clear older backups
                Artisan::call('backup:clean');
                //create new backup
                $backupJob = BackupJobFactory::createFromArray(config('backup'));
                $backupJob->setFilename($fileName);
                $backupJob->dontBackupFilesystem();
                $backupJob->disableNotifications();
                $backupJob->run();

                $fileName = (config('backup.backup.name'). "/". config('backup.backup.destination.filename_prefix'). $fileName);
                $generalSettings->last_db_backup_file_name  = $fileName;
                $generalSettings->last_db_backup_created_at = Carbon::now();
                $generalSettings->save();

                return redirect(route('company.settings.edit'))
                    ->with("message","Backup generated and ready for download.")
                    ->with("alert-class", "success");
            }
            return redirect(route('company.settings.edit'))
                ->with("message","Request dismissed! Backup already generated in last 30 minutes.")
                ->with("alert-class", "warning");
        } catch (Exception $exception) {
        }
        return redirect(route('company.settings.edit'))
            ->with("message","Failed to take the backup! Contact admin.")
            ->with("alert-class", "error");
    }
}
