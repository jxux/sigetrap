<?php

namespace App\Http\Controllers\Binnacle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BinnacleController extends Controller{

    public function index(){
        return view('binnacles.index');
    }
}
