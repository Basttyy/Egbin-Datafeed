<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\kpi;
use App\Traits\UsesApi;
use Exception;
use Illuminate\Support\Facades\Log;

class kpiController extends Controller
{

    use UsesApi;
    public function __construct()
    {
        $this->middleware('auth');

    }


    public function showKpi(){
        Log::debug("this controller was called");
        try {
            if(!$user = User::find(Auth::user()->getAuthIdentifier())){
                return view('kpi')->with('error','error getting list');
            }
            if($user->api_token === null){
                if(!$this->authorizeOnApi($user)){
                    return view('kpi')->with('error', 'unable to update user api token');
                }
            }
            $response = $this->makeRequestAndGetResponse("extintegration/kpi", 'get', null, $user->api_token);

            if($response->status() === 200){
                Log::debug("status is 200");
                $datas = $response['data'];

                $kpis = kpi::all(['kpi_code', 'kpi_name', 'description', 'kpi_category'])->toArray();

                $count = count($kpis);

                if (\count($datas) > $count) {
                    $diff = \array_slice($datas, $count, null, true);
                    kpi::insert($diff);
                }
                Log::debug("everything went well");
                return view('kpi')->with('kpis', $response['data']);
            }
            else{
                Log::debug("unable to get data");
                Log::debug($response->status());
                return view('kpi')->with('error','unable to get data');
            }
        } catch (Exception $ex) {
            Log::debug($ex->getTraceAsString());
            return view('kpi')->with('errors', ['err' => $ex->getMessage()]);
        }
    }

    // public function showCreateKpi(){
    //     return view('create_kpi');
    // }

    // public function createKpi(Request $request){
    //     $request->validate([
    //         'kpi_code'=>'required',
    //         'kpi_name'=>'required',
    //         'kpi_description'=>'required',
    //         'kpi_category'=>'required',
            
    //     ]);



    //      kpi::create([
    //          'email'=> Auth::user()->email,
    //          'kpi_code'=>$request->kpi_code,
    //          'kpi_name'=>$request->kpi_name,
    //          'kpi_description'=>$request->kpi_description,
    //          'kpi_category'=>$request->kpi_category,
    //      ]);
    //     return redirect()->route('kpi')->with('success','KPI added succesfully');
    // }
    public function dbkpi(){
        $kpis = kpi::all();
        return view('db_kpi',['kpis'=>$kpis]);
    }
}
