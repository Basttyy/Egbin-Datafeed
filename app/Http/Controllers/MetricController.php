<?php

namespace App\Http\Controllers;

use App\kpi;
use App\Metric;
use App\Traits\UsesApi;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MetricController extends Controller
{
    use UsesApi;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except([]);
    }

    public function savemetric(Request $request){
        $request->validate([
            'metric-name'=>'required',
            'metric-code'=>'required',
            'metric-description'=> 'required',
            'metric-type'=>'required',
            'metric-unit'=>'required',
            'metric-symbol'=>'required',
            'status'=>'required',
        ]);
    }

    public function showCreateMetric(){
        return view('create_metric');
    }

    public function createMetric(Request $request){
         $request->validate([
             'metricCode'=>'required',
             'metricType'=>'required',
             'value'=>'required',
             'comment'=>'required',
             'status'=>'required',
             'metricEntryType'=>'required',
         ]);

         Metric::create([
             'user_id'=> Auth::user()->getAuthIdentifier(),
             'metricCode'=> (int)$request->metricCode,
             'value'=> $request->value,
             'metricType'=> $request->metricType,
             'comment'=> $request->comment,
             'status'=> $request->status,
             'metricEntryType'=> $request->metricEntryType,
             'entryDate'=> \date("Y-m-d H:i:s")
         ]);
        return redirect()->route('db_metrics')->with('success','Metric added succesfully');
    }

    public function updateMetric(Request $request, $id) {
        $request->validate([
            'metricCode'=>'sometimes',
            'metricType'=>'sometimes',
            'value'=>'sometimes',
            'comment'=>'sometimes',
            'status'=>'sometimes',
            'metricEntryType'=>'sometimes',
            'item_status' => 'sometimes',
            'reason' => 'sometimes'
        ]);

        Log::info($request->entry_status);

        Metric::where('id', $id)->first()->updateOrFail($request->all());

        $metrics = Metric::where('item_status', Metric::SAVED)->get();

        return \response()->json([
            'message' => 'metric updated success',
            'metrics' => $metrics
        ]);
    }

    public function dbMetrics(){
        $kpis = kpi::all();
        $metrics = Metric::all();

        return view('db_metrics',['metrics'=>$metrics, 'kpis'=>$kpis]);
    }

    public function showApprovedMetrics(){
        $metrics = Metric::where('item_status', Metric::APPROVED)->get();
        return view('approved_metrics',['metrics'=>$metrics]);
    }

    public function showDisapprovedMetrics(){
        $metrics = Metric::where('item_status', Metric::DISAPPROVED)->get();
        return view('disapproved_metrics',['metrics'=>$metrics]);
    }

    public function showPushedMetrics(){
        $metrics = Metric::where('item_status', Metric::SAVED)->get();
        return view('pushed',['metrics'=>$metrics]);
    }

    public function syncData() {
        try{
            $errors = [];
            DB::beginTransaction();

            if(!$user = User::find(Auth::user()->getAuthIdentifier())){
                $errors = ['error' => 'error getting list'];
                Log::debug("data could not be synced 3");
                return view('approved_metrics',['errors' => $errors]);
            }
            if($user->api_token === null){
                if(!$this->authorizeOnApi($user)){
                    $errors = ['error' => 'unable to update user api token'];
                    Log::debug("data could not be synced 3");
                    return view('approved_metrics',['errors' => $errors]);
                }
            }
            $metrics = Metric::where('item_status', Metric::APPROVED)->get(['metricCode', 'metricType', 'value', 'comment', 'status', 'metricEntryType', 'entryDate'])->toArray();
    
            if ($this->syncDataWithApi(['data' => $metrics, 'companyId' => $user->companyId], $user->api_token)) {
                DB::commit();
                Log::debug("data has been synced");
                Metric::where('item_status', Metric::APPROVED)->update(['item_status' => Metric::SYNCED]);
            } else {
                DB::rollBack();
                Log::debug("data could not be synced");
                $errors = ['error' => 'unable to sync data with api'];
            }
    
            $metrics = Metric::where('item_status', Metric::APPROVED)->get();
    
            return view('approved_metrics',['metrics'=>$metrics, 'errors' => $errors]);
        } catch (Exception $ex) {
            DB::rollBack();
            Log::debug($ex->getTraceAsString());
            return view('approved_metrics',['errors' => $errors]);
        }

    }
}
