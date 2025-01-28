<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    /**
     * Show the user dashboard with available consultation packages.
     *
     * @return \Illuminate\View\View
     */
    public function showDashboard()
    {
        // Fetch all the packages from the 'tabel_pakets' table
        $pakets = Paket::all();

        // Return the dashboard view and pass the 'pakets' data to it
        return view('dashboard', compact('pakets')); // Replace 'dashboard' with your dashboard view's name
    }
    
}
