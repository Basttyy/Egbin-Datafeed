@extends('adminlte::page')

@section('title', 'Datafeed')

@section('content_header')
<h1>Datafeed Home</h1>
@stop

@section('content')
<p>Welcome to this beautiful admin panel.</p>

<button type="button" id="btnOpenSaltB" class="btn btn-primary" data-toggle="modal" data-target="#metricsModal" style="float:right;">Add Metrics</button>
<br>

<table id="table" class="table table-striped table-bordered" style="width:100%">

</table>


<!-- MODAL -->
<div class="modal fade" id="metricsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <form action="{{route('post_metric')}}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Metrics </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <!-- <div class="col-md-12">
                            <label for="">KPI Code</label>
                            <input type="text" class="form-control" placeholder="KPI code" name="kpi_code">
                        </div> -->


                        <div class="col-md-12">
                            <label for="">Metrics Code</label>
                            <input type="text" class="form-control" placeholder="Metrics Code" name="code">
                        </div>
                        <div class="col-md-12">
                            <label for="">Metrics Type</label>
                            <input type="text" class="form-control" placeholder="Metrics Type" name="type">
                        </div>
                        <div class="col-md-12">
                            <label for="">Value</label>
                            <input type="text" class="form-control" placeholder="Value" name="value">
                        </div>
                        <div class="col-md-12">
                            <label for="">Description</label>
                            <input type="text" class="form-control" placeholder="Description" name="description">
                        </div>
                        <div class="col-md-12">
                            <label for="">Status</label>
                            <input type="text" class="form-control" placeholder="Status" name="status">
                        </div>
                        <div class="col-md-12">
                            <label for="">Metric Type </label>
                            <input type="text" class="form-control" placeholder="Entry Type" name="entry_type">
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>

        </div>
    </div>
</div>




@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop



@section('js')
<script>
    console.log('Hi!');
    var metrics = <?php echo json_encode($metrics); ?>;
    let dats = [];

    metrics.forEach(metric => {
        // console.log(metric);
        dats.push([metric.metric_name, metric.metric_code, metric.metric_description, metric.metric_category,
            metric.metric_type, metric.unit, metric.unit_symbol, metric.status
        ]);
    });

    $(function() {
        metricTable = $('#table').DataTable({
            data: dats,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            columns: [{
                    title: 'Metric Name'
                },
                {
                    title: 'Metric Code'
                },
                {
                    title: 'Metric Description'
                },
                {
                    title: 'Metric Category'
                },
                {
                    title: 'Metric Type'
                },
                {
                    title: 'Unit'
                },
                {
                    title: 'Unit Symbol'
                },
                {
                    title: 'Status'
                }
            ]
        });
        
        metricTable.on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                metricTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
    });


    // const names = {
    //         male:'KPAMSAR',
    //         AGE:699
    // };
    // const jsonString = JSON.stringify(names);
    // console.log(jsonString);
</script>
@stop