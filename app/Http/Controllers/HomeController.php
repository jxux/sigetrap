<?php

namespace App\Http\Controllers;

use App\Models\System\Binnacle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('home');
    }

    public function planning(Request $request){
        $hoy = Carbon::now()->format('Y-m-d');
        // $countries = Binnacle::join('persons','persons.id','=','binnacles.cliente_id')
        //                     ->join('users','users.id','=','binnacles.user_id')
        //                     ->select('binnacles.*', 'name','usuario')
        //                     ->where([['fecha', '=',  $hoy ],['cliente_id','=','1']])
        //                     ->get();

        $planning = Binnacle::where([['date', '=', $request->value],['client_id','=','1']])->get();
        
        return compact('planning');
    }
}

//"2021-06-14"