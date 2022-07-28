@extends('adminlte::page')

@section('title', 'Datafeed')

@section('content_header')
    <h1>Datafeed Home</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    <table id="table" class="display" width="100%">
   
    </table>
@stop


@section('plugins.Datatables', true)

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        var kpi = <?php echo json_encode($kpi); ?>;
        let dats = [];

        kpi.forEach(response => {
            console.log(response);
            dats.push([response.kpi_name, response.kpi_code, response.kpi_description, response.kpi_category
                   ]);
        });

        $(function() {
            $('#table').DataTable({
                data: dats,
                columns: [
                    { title: 'Kpi Name' },
                    { title: 'Kpi Code' },
                    { title: 'Kpi Description' },
                    { title: 'Kpi  Category' },
                    
                ]
            });
        });
    </script>
@stop