@extends('adminlte::page')

@section('title', 'Datafeed')

@section('content_header')
<h1>Datafeed Home</h1>
@stop

@section('content')
<button type="button" id="btnOpenSaltB" class="btn btn-info" data-toggle="modal" data-target="#metricsModal" style="float:right;">Add Metrics</button>
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
                            <select id="metric-select" class="form-control" placeholder="Select Metrics Code" name="metricCode" onchange="handleSelChange()">
                                <option value="null">Select Metric Code</option>
                                @foreach($kpis as $kpi)
                                    <option value="{{$kpi->metric_code}}">{{$kpi->metric_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input id="metric_name" name="metricName" type="hidden" value="">
                        <div class='col-md-12'>
                            <label for="">Entry Date</label>
                            <div class="input-group date">
                                <input type='text' class="form-control" placeholder="Select Record Date" name="entryDate" />

                                <span class="input-group-text">
                                    <span class="fas fa-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="">Metric Type</label>
                            <input type="text" id="metric_type" class="form-control" placeholder="Metric Type" name="metricType">
                        </div>
                        <div class="col-md-12">
                            <label for="">Value</label>
                            <input type="number" class="form-control" placeholder="Value" name="value" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        </div>
                        <div class="col-md-12">
                            <label for="">Description</label>
                            <input id="description" type="text" class="form-control" placeholder="Description" name="comment">
                        </div>
                        <div class="col-md-12">
                            <label for="">Status</label>
                            <input id="status" type="text" class="form-control" placeholder="Status" name="status" value="good">
                        </div>
                        <div class="col-md-12">
                            <label for="">Metric Entry Type </label>
                            <input id = "entry_type" type="text" class="form-control" placeholder="Entry Type" name="metricEntryType" value="actual">
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
<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/custom.css') }}">
<link href="{{ asset('vendor/adminlte/dist/css/jquery.datetimepicker.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="{{ asset('vendor/adminlte/dist/js/jquery.datetimepicker.full.min.js') }}"></script>
<script>
    console.log('Hi!');
    var errors = <?php if (isset($errors)) echo json_encode($errors); else echo json_encode([]); ?>;
    var metrics = <?php  if (isset($metrics)) echo json_encode($metrics); else echo json_encode([]); ?>;
    var kpis = <?php if (isset($kpis)) echo json_encode($kpis); else echo json_encode([]); ?>;
    let dats = [];

    if (errors.length > 0) {
        console.log("length is greater than 0")
        errors.forEach(error => {
            alert(error['error'])
        })
    }

    metrics.forEach(metric => {
        console.log(metric);
        dats.push([metric.id, metric.metricName, metric.metricCode, metric.value, metric.comment, metric.metricType,
            metric.metricEntryType, metric.status, metric.item_status, metric.entryDate
        ]);
    });

    function handleSelChange() {
        var slected_code = document.getElementById('metric-select').value;

        kpis.forEach(kpi => {
            if (kpi.metric_code == slected_code) {
                document.getElementById('description').value = kpi.metric_description;
                document.getElementById('metric_type').value = kpi.metric_type;
                document.getElementById('metric_name').value = kpi.metric_name;
                return;
            }
        });
    }

    $(function() {
        metricTable = $('#table').DataTable({
            scrollX: true,
            data: dats,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            columns: [{
                    title: 'Id'
                },
                {
                    title: 'Name'
                },
                {
                    title: 'Code'
                },
                {
                    title: 'Value'
                },
                {
                    title: 'Description',
                    width: "20%"
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

        $('#entry_type, #description, #status, #metric_type').focus(function(e) {
            $(this).blur();
        });

        var logic = function (datetime, $input) {
            $input.find('input:first').val(datetime.toLocaleString('ng-NG', {
                hour12: false,
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit"
            }).replace(',', ''))
        };

        var bindDatePicker = function() {
            $(".date").datetimepicker({
                maxDate: "+1d",
                format:'DD/MM/YYYY HH:mm:ss',
                icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                },
                onChangeDateTime:logic
            });
        }
        
        bindDatePicker();
    });
</script>
@stop