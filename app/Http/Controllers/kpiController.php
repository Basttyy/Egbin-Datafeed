<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class kpiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }


    public function showKpi(){
        if(!$user = User::find(Auth::user()->getAuthIdentifier())){
            return view('kpi')->with('error','error getting list');
        }
        if($user->api_token === null){
            if(!$this->authorizeOnApi($user)){
                return view('kpi')->with('error', 'unable to update user api token');
                
            }
        }
        $response = Http::withToken($user->api_token)->get(Env::get('api_base_url')."extintegration/kpi");

        if($response->status() === 200){
            return view('kpi')->with('kpi', $response['data']);
        }
        else{
            return view('kpi')->with('error','unable to get data');
        }
        
    }
}
