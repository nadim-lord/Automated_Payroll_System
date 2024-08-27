<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function dashboard(){
        return view('admin.pages.dashboard');
    }
    public function users(){
        return view('admin.pages.users');
    }
    public function time(){
        return view('time.index');
    }
    public function leave(){
        return view('leave.index');
    }
}
