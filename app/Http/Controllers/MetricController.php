<?php

namespace App\Http\Controllers;

use App\Metric;
use App\Traits\UsesApi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
        $this->middleware('auth')->except(['updateMetric']);
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
             'code'=>'required',
             'type'=>'required',
             'value'=>'required',
             'description'=>'required',
             'status'=>'required',
             'entry_type'=>'required',
         ]);

         Metric::create([
             'user_id'=> Auth::user()->getAuthIdentifier(),
             'code'=> $request->code,
             'value'=> $request->value,
             'type'=> $request->type,
             'description'=> $request->description,
             'status'=> $request->status,
             'entry_type'=> $request->entry_type,
             'entry_date'=> \date("Y-m-d H:i:s")
         ]);
        return redirect()->route('db_metrics')->with('success','Metric added succesfully');
    }

    public function updateMetric(Request $request, $id) {
        $request->validate([
            'code'=>'sometimes',
            'type'=>'sometimes',
            'value'=>'sometimes',
            'description'=>'sometimes',
            'status'=>'sometimes',
            'entry_type'=>'sometimes',
            'item_status' => 'sometimes',
            'reason' => 'sometimes'
        ]);

        Log::info($request->entry_status);

        Metric::where('id', $id)->first()->updateOrFail($request->all());

        // if (!$metric = Metric::where('id', $id)->first()) {
        //     return \response()->json([
        //         'message' => 'metric does not exist',
        //     ], Response::HTTP_NOT_FOUND);
        // }

        // if (!$metric->update($request->only(['entry_status']))) {
        //     return \response()->json([
        //         'message' => 'unable to update metric',
        //     ], Response::HTTP_INTERNAL_SERVER_ERROR);
        // }

        $metrics = Metric::where('item_status', Metric::SAVED)->get();

        return \response()->json([
            'message' => 'metric updated success',
            'metrics' => $metrics
        ]);
    }

    public function dbMetrics(){
        $metrics = Metric::all();
        return view('db_metrics',['metrics'=>$metrics]);
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
        $metrics = Metric::where('items_status', Metric::APPROVED)->get();

        $this->syncData($metrics);

        return view('db_metrics', ['metrics'=>$metrics]);
    }
}
