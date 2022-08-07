@extends('adminlte::page')

@section('title', 'Datafeed')

@section('content_header')
<h1>Approved Metrics</h1>
@stop

@section('content')
<p>Welcome to this beautiful admin panel.</p>

<form action="{{route('sync_data')}}" method="post">
    @csrf
    @method('PUT')
    <button type="submit" class="btn btn-primary" style="float:right;">SYNC </button>
</form>
<br>
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
    let dats = [];

    metrics.forEach(metric => {
        console.log(metric);
        dats.push([metric.value, metric.code, metric.comment, metric.type,
            metric.type, metric.value, metric.value, metric.value
        ]);
    });

    $(function() {
        $('#table').DataTable({
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
                },

            ]
        });
    });
</script>
@stop