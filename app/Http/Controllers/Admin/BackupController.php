<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
   public function backupDB(){

       Artisan::call('backup:run');

       $status = ['type' => 'success', 'message' => 'Backup Done Successfully'];

       return back()->with('status', $status);
   }
}
