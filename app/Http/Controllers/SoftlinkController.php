<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SoftlinkController extends Controller
{
    //
    public function movimiento()
    {
        // $movimiento = DB::connection('softLink')->table('movimien')->limit(5)->get();
        // $movimiento = DB::connection('softLink')->table('movimien')->limit(5)->get();
        return response()->json([
            "success"=>true,
            "status"=>200,
            // "movimiento"=>$movimiento
        ]);
    }
}
