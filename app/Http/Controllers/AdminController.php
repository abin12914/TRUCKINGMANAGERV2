<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;

class AdminController extends Controller
{
    public function takeDbBackup()
    {
        try {
            $exitCode = Artisan::call('backup:run --only-db');
            if($exitCode == 0) {
                return redirect(route('dashboard'))
                    ->with("message","Backup generated and ready for download.")
                    ->with("alert-class", "success");
            }
        } catch (\Exception $e) {
        }
        return redirect(route('dashboard'))
            ->with("message","Failed to take the backup! Contact admin.")
            ->with("alert-class", "error");
    }
}
