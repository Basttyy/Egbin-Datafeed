<?php

namespace App\Http\Controllers;

use App\Metric;
use App\Traits\UsesApi;
use App\User;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    use UsesApi;
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

    public function logout(){
        auth()->logout();
        return redirect()->route('login');
    }
}
