@extends('interconnecta/connect-ui::layout.master')

@section('title')
Zoho CRM Wrapper
@endsection

@section('favicon')
<link rel="shortcut icon" href="{{ asset('vendor/zoho-crm/img/favicon.ico') }}">
@endsection

@section('additional-styles')
<link rel="stylesheet" href="{{ mix('app.css', 'vendor/zoho-crm') }}">
@endsection

@section('header')
@include('interconnecta/zoho-crm::partials.header')
@endsection

@section('content')
<div class="content" id="zoho-crm">
</div>
@endsection

@section('footer')
@include('interconnecta/zoho-crm::partials.footer')
@endsection

@section('additional-scripts')
<script>
    window.ZohoCRM = @json($zohoCRMJsVariables);
</script>
<script src="{{ mix('app.js', 'vendor/zoho-crm')}}"></script>
@endsection