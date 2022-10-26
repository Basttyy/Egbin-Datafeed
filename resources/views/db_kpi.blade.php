@extends('adminlte::page')

@section('title', 'Datafeed')

@section('content_header')
    <h1>Datafeed Home</h1>
@stop

@section('content')
    <table id="table" class="table table-striped table-bordered" style="width:100%">
   
    </table>
@stop


@section('plugins.Datatables', true)

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/custom.css') }}">
@stop

@section('js')
    <script>
        console.log('Hi!'); 
        var kpis = <?php  if (isset($kpis)) echo json_encode($kpis); else echo json_encode([]); ?>;
        let dats = [];

        kpis.forEach(kpi => {
            console.log(kpi);
            dats.push([kpi.kpi_name, kpi.kpi_code, kpi.kpi_description, kpi.kpi_category]);
        });

        $(function() {
            kpiTable = $('#table').DataTable({
                scrollX: true,
                data: dats,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                columns: [
                    { title: 'Kpi Name' },
                    { title: 'Kpi Code' },
                    { title: 'Kpi Description' },
                    { title: 'Kpi  Category' },
                ]
            });
            
            kpiTable.on( 'click', 'tr', function () {
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
