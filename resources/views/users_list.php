@extends('adminlte::page')

@section('title', 'Datafeed')

@section('content_header')
<h1>Users Page</h1>
@stop

@section('content')
<button type="button" id="btnOpenSaltB" class="btn btn-primary" data-toggle="modal" data-target="#metricsModal" style="float:right;">Add User</button>
<br>
<table id="table" class="table table-striped table-bordered" style="width:100%">

</table>
<!-- MODAL -->
<div class="modal fade" id="metricsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <form action="{{ $register_url }}" method="post">
                @csrf

                {{-- Name field --}}
                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="{{ __('adminlte::adminlte.full_name') }}" autofocus>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Email field --}}
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}">

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                {{-- Role field --}}
                <div class="input-group mb-3">
                    <select class="form-control" placeholder="Select Role" name="role">
                        @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->role}}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Password field --}}
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="{{ __('adminlte::adminlte.password') }}">

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Confirm password field --}}
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="{{ __('adminlte::adminlte.retype_password') }}">

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>

                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Register button --}}
                <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-user-plus"></span>
                    {{ __('adminlte::adminlte.register') }}
                </button>
            </form>

        </div>
    </div>
</div>

@stop


@section('plugins.Datatables', true)

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/custom.css') }}">
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
            metric.metricEntryType, metric.status, metric.item_status, metric.month, metric.entryDate
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
                    title: 'Month'
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