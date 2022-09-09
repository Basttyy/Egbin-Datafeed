@extends('adminlte::page')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Datafeed')

@section('content_header')
<h1>Disapproved Metrics</h1>
@stop

@section('content')
<p></p>

<br>
<table id="table" class="table table-striped table-bordered" style="width:100%">

</table>

<!-- MODAL -->
<div class="modal fade" id="metricsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <form onsubmit="return submitForm(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enter Reason </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Reason</label>
                            <input type="textarea" id="textarea" class="form-control" placeholder="Metrics Code" name="code">
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>

        </div>
    </div>
</div>

@stop


@section('plugins.Datatables', true)

@section('css')
<link rel="stylesheet" href="/css/custom.css">
@stop

@section('js')
<script>
    console.log('Hi!');
    var metrics = <?php echo json_encode($metrics); ?>;
    var base_url = decodeURIComponent("<?php echo rawurlencode(route('update_metric', ['id' => 1])); ?>")
    let dats = []
    let clickedData = []
    let clickedId = -1
    let status = ""
    var row = null

    metrics.forEach(metric => {
        console.log(metric);
        dats.push([metric.id, metric.metricCode, metric.value, metric.comment, metric.metricType,
            metric.metricEntryType, metric.status, metric.item_status, metric.entryDate,
        ]);
    });

    function submitForm (event) {
        alert(clickedData)
        var feature_id = clickedData[0]
        var textarea = document.getElementById('textarea')
        event.preventDefault()

        fetch(base_url.replace('1', feature_id), {
            method: 'PUT',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                item_status: status,
                reason: textarea.value
            })
        }).then( resp => {
            metricTable.row( row ).remove().draw(false)
            
            clickedData = []
            clickedId = -1
            status = ""
            row = null
        }).catch(error => {
            clickedData = []
            clickedId = -1
            row = null
            if (status != '') {
                status === 'approved' ? alert("Unable to approve metric: "+ error) : alert("Unable to disapprove metric: "+ error)
            }
            status = ""
        })
        $('#metricsModal').modal('hide')
        event.preventDefault();
    }

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
                },
                {
                    title: 'Approve'
                }
            ],
            columnDefs: [
                {
                    targets: 9,
                    render: function (data, type, row, meta) {
                        return '<input type="button" class="btn btn-primary approve" data-toggle="modal" data-target="#metricsModal" style="float:right;" id=n-"' + meta.row + '" value="Approve"/>';
                    }
                }
            ]
        });

        metricTable.on('click', '.approve', function () {
            row = $(this).closest('tr');
            clickedData = metricTable.row( row ).data()
            clickedId = clickedData[0]
            console.log(clickedData)
            status = 'approved'
            console.log(clickedId)
        })

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