@extends('adminlte::page')

@section('title', 'Datafeed')

@section('content_header')
<h1>Database Metrics</h1>
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
                        <div class="col-md-12">
                            <label for="">Metrics Code</label>
                            <select class="form-control" placeholder="Select Metrics Code" name="metricCode">
                                @foreach($kpis as $kpi)
                                    <option value="{{$kpi->kpi_code}}">{{$kpi->kpi_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="">Metrics Type</label>
                            <input type="text" class="form-control" placeholder="Metrics Type" name="metricType">
                        </div>
                        <div class="col-md-12">
                            <label for="">Value</label>
                            <input type="text" class="form-control" placeholder="Value" name="value">
                        </div>
                        <div class="col-md-12">
                            <label for="">Comment</label>
                            <input type="text" class="form-control" placeholder="Comment" name="comment">
                        </div>
                        <div class="col-md-12">
                            <label for="">Status</label>
                            <input type="text" class="form-control" placeholder="Status" name="status">
                        </div>
                        <div class="col-md-12">
                            <label for="">Metric Entry Type </label>
                            <input type="text" class="form-control" placeholder="Metric Entry Type" name="metricEntryType">
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


@section('plugins.Datatables', true)

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    console.log('Hi!');
    var errors = <?php if (isset($errors)) echo json_encode($errors); else echo json_encode([]); ?>;
    var metrics = <?php  if (isset($metrics)) echo json_encode($metrics); else echo json_encode([]); ?>;
    let dats = [];

    if (errors.length > 0) {
        console.log("length is greater than 0")
        errors.forEach(error => {
            alert(error['error'])
        })
    }

    metrics.forEach(metric => {
        console.log(metric);
        dats.push([metric.id, metric.metricCode, metric.value, metric.comment, metric.metricType,
            metric.metricEntryType, metric.status, metric.item_status, metric.entryDate,
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
                    title: 'Id'
                },
                {
                    title: 'Code'
                },
                {
                    title: 'Value'
                },
                {
                    title: 'Description'
                },
                {
                    title: 'Type'
                },
                {
                    title: 'Entry Type'
                },
                {
                    title: 'Status'
                },
                {
                    title: 'Item Status'
                },
                {
                    title: 'Entry Date'
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
</script>
@stop