<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(){
        //$this->model_cantones = new Cantones();
    }
    public function index(Request $request){
        return view('layouts.app');
    }

}
