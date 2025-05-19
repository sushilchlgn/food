<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // You can pass data to the view, e.g., counts of users, restaurants, etc.
        return view('admin.dashboard');
    }
}