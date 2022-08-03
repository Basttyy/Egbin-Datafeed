@extends('adminlte::page')

@section('title', 'Datafeed')

@section('content_header')
    <h1>Datafeed Home</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    <table id="table" class="table table-striped table-bordered" style="width:100%">
   
    </table>
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
            console.log(metric);
            dats.push([metric.metric_name, metric.metric_code, metric.metric_description, metric.metric_category,
                    metric.metric_type, metric.unit, metric.unit_symbol, metric.status]);
        });

        $(function() {
            $('#table').DataTable({
                data: dats,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                columns: [
                    { title: 'Metric Name' },
                    { title: 'Metric Code' },
                    { title: 'Metric Description' },
                    { title: 'Metric Category' },
                    { title: 'Metric Type' },
                    { title: 'Unit' },
                    { title: 'Unit Symbol' },
                    { title: 'Status' }
                ]
            });
        });
    </script>
@stop
