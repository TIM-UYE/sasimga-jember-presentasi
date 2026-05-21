<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokLog;

class StokLogController extends Controller
{
    public function index()
    {
        $logs = StokLog::with('stok')
            ->orderByDesc('id')
            ->get();

        return view('admin.stok-log.index', compact('logs'));
    }
}