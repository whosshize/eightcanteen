<?php

// app/Http/Controllers/MenuController.php
namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index($booth_id)
    {
        // Ambil booth berdasarkan ID
        $booth = Booth::findOrFail($booth_id);

        // Ambil semua menu terkait booth
        $menus = $booth->menus;

        // Tampilkan view dengan data menu
        return view('menus.index', compact('booth', 'menus'));
    }
}