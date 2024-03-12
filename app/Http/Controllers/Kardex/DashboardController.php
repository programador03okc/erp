<?php

namespace App\Http\Controllers\Kardex;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {

        return view('kardex.index', get_defined_vars());
    }
}
