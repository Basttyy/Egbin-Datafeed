@extends('adminlte::page')

@section('title', 'Datafeed')

@section('content_header')
    <h1>Datafeed Home</h1>
@stop

@section('content')


<form action="{{route('post_kpi')}}" method="POST">
    @csrf
{{-- With label, invalid feedback disabled and form group class --}}
<div class="row">
    <x-adminlte-input name="kpi_code" label="KPI Code" placeholder="Enter KPI Code"
        fgroup-class="col-md-6" disable-feedback/>
</div>

{{-- With label, invalid feedback disabled and form group class --}}
<div class="row">
    <x-adminlte-input name="kpi_name" label="KPI Name" placeholder="Enter KPI Name"
        fgroup-class="col-md-6" disable-feedback/>
</div>
{{-- With label, invalid feedback disabled and form group class --}}
<div class="row">
    <x-adminlte-input name="kpi_description" label="KPI Description" placeholder="Enter KPI Description"
        fgroup-class="col-md-6" disable-feedback/>
</div>
{{-- With label, invalid feedback disabled and form group class --}}
<div class="row">
    <x-adminlte-input name="kpi_category" label="KPI Category" placeholder="Enter KPI Category"
        fgroup-class="col-md-6" disable-feedback/>
</div>
<x-adminlte-button label="Submit" theme="primary" type="submit" />
</form>




@stop


@section('plugins.Datatables', true)


@section('css')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/custom.css') }}">
@stop
