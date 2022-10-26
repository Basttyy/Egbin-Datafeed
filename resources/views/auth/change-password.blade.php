@extends('adminlte::page')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('title', 'Datafeed')

@php( $change_pass_url = View::getSection('change_passurl') ?? config('adminlte.change_pass_url', 'change_pass') )

@php($change_pass_message = 'Change Your Password')
@if (config('adminlte.use_route_url', false))
    @php( $change_pass_url = $change_pass_url ? route($change_pass_url) : '' )
@else
    @php( $change_pass_url = $change_pass_url ? url($change_pass_url) : '' )
@endif

@section('content_header')
<h1>{{$change_pass_message}}</h1>
@stop

@section('content')
<div class="register-card">
    <form action="{{ $change_pass_url }}" method="post">
        @csrf
        
        {{-- Current Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror"
                   placeholder="Current Password">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('current_password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
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

        {{-- Confirm button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-user-plus"></span>
            Confirm Change
        </button>
    </form>
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/custom.css') }}">
@stop

@section('js')
<!-- <script>
    console.log('Hi!');
    var message = <?php if (isset($message)) echo json_encode([$message]); else echo json_encode([]); ?>;
    console.log(message);

    $(function() {
        if (message[0])
            alert(message[0]);
    });
</script> -->
@stop