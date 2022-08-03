<?php

namespace App\Http\Controllers;

use App\Metric;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if ( !$user = User::find(Auth::user()->getAuthIdentifier())) {
            return view('home')->with('error', "error getting user");
        }
        if ($user->api_token === null) {
            if (! $this->authorizeOnApi($user) ) {
                return view('home')->with('error', 'unable to update user api token');
            }
        }
        $resp = Http::withToken($user->api_token)->get(Env::get('api_base_url')."/extintegration/metrics/");

        if ($resp->status() === 200) {
            return view('home')->with('metrics', $resp['data']);
        } else if ($resp->status() === 401) {   // if unauthorized then try to re-authorize
            if (! $this->authorizeOnApi($user) ) {
                return view('home')->with('error', 'unable to update user api token');
            }
            $resp = Http::withToken($user->api_token)->get(Env::get('api_base_url')."/extintegration/metrics/");
            if ($resp->status() === 200) {
                return view('home')->with('metrics', $resp['data']);
            } else {
                return view('home')->with('error', 'unable to fetch metrics');
            }
        } else {
            return view('home')->with('error', 'unable to fetch metrics');
        }
    }

    public function showCreateMetric(){
        return view('create_metric');
    }

    public function createKpi(Request $request){
        $request->validate([
            'code'=>'required',
            'type'=>'required',
            'value'=>'required',
            'comment'=>'required',
            'status'=>'required',
            'entry_type'=>'required',
            'entryDate'=>'required'
        ]);

         Metric::create([
             'user_id'=> Auth::user()->getAuthIdentifier(),
             'code'=>$request->code,
             'value'=>$request->value,
             'type'=>$request->type,
             'comment'=>$request->comment,
             'status'=>$request->status,
             'entry_type'=>$request->entry_type,
             'entryDate'=>$request->entry_date
         ]);
        return redirect()->route('metrics')->with('success','Metric added succesfully');
    }
    public function dbkpi(){
        $kpi = Metric::all();
        return view('db_kpi',['kpi'=>$kpi]);
    }

    private function authorizeOnApi(User $user)
    {
        $resp = Http::post(Env::get('api_base_url').'/extintegration/api/', [
            "grant_type" => "password",
            "username" => $user->email,
            "password" => Crypt::decryptString($user->api_pass)
        ]);

        if ($resp->status() === 200) {
            if (!$user->update(['api_token' => $resp['data']['access_token']])) {
                return false;
            } else { return true; }
        }
        return false;
    }

    public function logout(){
        auth()->logout();
        return redirect()->route('login');
    }
}
    

