@extends('adminlte::page')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Datafeed')

@section('content_header')
<h1>Approve Uploaded Metrics</h1>
@stop

@section('content')
<p>Welcome to this beautiful admin panel.</p>

<table id="table" class="table table-striped table-bordered" style="width:100%">

</table>

@stop


@section('plugins.Datatables', true)

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    console.log('Hi!');
    var metrics = <?php echo json_encode($metrics); ?>;
    var base_url = decodeURIComponent("<?php echo rawurlencode(route('update_metric', ['id' => 1])); ?>")

    let dats = [];

    metrics.forEach(metric => {
        console.log(metric);
        dats.push([metric.id, metric.code, metric.value, metric.description, metric.type,
            metric.entry_type, metric.status, metric.item_status, metric.entry_date,
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
                },
                {
                    title: 'Approve'
                },
                {
                    title: 'Disapprove'
                }
            ],
            columnDefs: [
                {
                    targets: 9,
                    render: function (data, type, row, meta) {
                        return '<input type="button" class="approve" id=n-"' + meta.row + '" value="Approve"/>';
                    }
                },
                {
                    targets: 10,
                    render: function (data, type, row, meta) {
                        return '<input type="button" class="disapprove" id=n-"' + meta.row + '" value="Disapprove"/>';
                    }
                }
            ]
        });

        metricTable.on('click', '.approve', function () {
            var id = $(this).attr("id").match(/\d+/)[0]
            var data = metricTable.row( id ).data()
            console.log(data[0])

            var feature_id = data[0]

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
                    item_status: 'approved'
                })
            }).then( resp => {
                // smsDataSet = smsDataSet.filter(function(value, index, arr) {
                //     return value[0] != id
                // })
                metricTable.row(feature_id).remove().draw( false )
            }).catch(error => {
                alert("Unable to approve metric: "+ error)
            })
        })

        // metricTable.on('click', '.disapprove', function () {
        //     var id = $(this).attr("id").match(/\d+/)[0];
        //     var data = metricTable.row( id ).data();
        //     console.log(data[0]);

        //     var feature_id = $data[0];

        //     fetch(base_url.replace('1', feature_id), {
        //         method: 'PUT',
        //         mode: 'cors',
        //         cache: 'no-cache',
        //         credentials: 'same-origin',
        //         headers: {
        //             'Content-Type': 'application/json'
        //         },
        //         body: JSON.stringify({
        //             item_status: 'disapproved'
        //         })
        //     }).then( resp => {
        //         // smsDataSet = smsDataSet.filter(function(value, index, arr) {
        //         //     return value[0] != id
        //         // })
        //         metricTable.row(feature_id).remove().draw( false )
        //     }).catch(error => {
        //         alert("Unable to disapprove metric: "+ error);
        //     })
        // });

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