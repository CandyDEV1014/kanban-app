<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $tasks = Column::with('tasks')->get();
        return view('home', compact('tasks'));
        
    }
}
