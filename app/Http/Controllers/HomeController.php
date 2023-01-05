<?php

namespace App\Http\Controllers;

use App\kpi;
use App\Metric;
use App\Traits\UsesApi;
use App\User;
use Exception;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $resp = $this->makeRequestAndGetResponse("/extintegration/metrics/", 'get', null, $user->api_token);
        
        $kpis = kpi::all();
        $metrics = Metric::all();

        if ($resp instanceof Exception) {
            return view('home', ['metrics' => $metrics, 'kpis' => $kpis]);
        }

        if ($resp->status() === 200) {
            Log::debug("status is 200");
            $datas = $resp['data'];

            $kpis = kpi::all(['metric_name', 'metric_code', 'metric_description', 'metric_category', 'metric_type', 'unit', 'unit_symbol', 'status']);

            $count = count($kpis);

            if (\count($datas) > $count) {
                $diff = \array_slice($datas, $count, null, true);
                kpi::insert($diff);
            }
            Log::debug("everything went well");
        } else if ($resp->status() === 401) {   // if unauthorized then try to re-authorize
            if (! $this->authorizeOnApi($user) ) {
                return view('home')->with('error', 'unable to update user api token');
            }
            $resp = Http::withToken($user->api_token)->get(Env::get('api_base_url')."/extintegration/metrics/");
            if ($resp->status() === 200) {
                return view('home', ['metrics' => $metrics, 'kpis' => $kpis]);
            } else {
                return view('home')->with('error', 'unable to fetch metrics');
            }
        } else {
            return view('home')->with('error', 'unable to fetch metrics');
        }

        return view('home', ['metrics' => $metrics, 'kpis' => $kpis]);
    }

    public function logout(){
        auth()->logout();
        return redirect()->route('login');
    }
}
