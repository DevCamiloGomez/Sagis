<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CommandController extends Controller
{

    public function runOptimize()
    {
        try {
            Artisan::call('optimize');

            return 'Optimized!';
        } catch (\Exception $th) {
            throw $th->getMessage();
        }
    }

    public function runMigrateFresh()
    {
        try {
            Artisan::call('migrate:fresh', [
                '--seed' => true
            ]);
        } catch (\Exception $th) {
            throw $th->getMessage();
        }
    }

    public function storageLink()
    {
        try {
            Artisan::call('storage:link');
        } catch (\Exception $th) {
            throw $th->getMessage();
        }
    }
}

