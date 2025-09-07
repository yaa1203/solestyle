<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        return view('user.index');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
}