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


@section('plugins.Datatables', true)

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    var kpis = <?php echo json_encode($kpis); ?>;
    let dats = [];

    kpis.forEach(kpi => {
        console.log(kpi);
        dats.push([kpi.kpi_name, kpi.kpi_code, kpi.description, kpi.kpi_category]);
    });

    $(function() {
        $('#table').DataTable({
            data: dats,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            columns: [{
                    title: 'Kpi Name'
                },
                {
                    title: 'Kpi Code'
                },
                {
                    title: 'Kpi Description'
                },
                {
                    title: 'Kpi  Category'
                },

            ]
        });
    });
</script>
@stop