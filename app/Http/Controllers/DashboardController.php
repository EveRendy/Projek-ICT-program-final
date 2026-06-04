<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data user yang sedang login
        $user = Auth::user();

        // Meneruskan data user dan role ke halaman view dashboard
        return view('dashboard', [
            'user' => $user,
            'role' => $user->role
        ]);
    }
}