<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\kpi;

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
        $response = Http::withToken($user->api_token)->get(Env::get('api_base_url')."/extintegration/kpi");

        if($response->status() === 200){
            return view('kpi')->with('kpi', $response['data']);
        }
        else{
            return view('kpi')->with('error','unable to get data');
        }
        
    }

    public function showCreateKpi(){
        return view('create_kpi');
    }

    public function createKpi(Request $request){
        $request->validate([
            'kpi_code'=>'required',
            'kpi_name'=>'required',
            'kpi_description'=>'required',
            'kpi_category'=>'required',
            
        ]);



         kpi::create([
             'email'=> Auth::user()->email,
             'kpi_code'=>$request->kpi_code,
             'kpi_name'=>$request->kpi_name,
             'kpi_description'=>$request->kpi_description,
             'kpi_category'=>$request->kpi_category,
         ]);
        return redirect()->route('kpi')->with('success','KPI added succesfully');
    }
    public function dbkpi(){
        $kpi = kpi::all();
        return view('db_kpi',['kpi'=>$kpi]);
    }
}
