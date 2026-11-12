<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /** GET /admin -> langsung ke modul pertama (Daerah), seperti versi lama */
    public function index(): RedirectResponse
    {
        $pertama = array_key_first(config('admin.modul'));

        return redirect()->route("admin.$pertama.index");
    }
}
