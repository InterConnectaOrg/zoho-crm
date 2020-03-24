@extends('interconnecta/connect-ui::layout.default')

@section('additional-styles')
<link rel="stylesheet" href="{{ mix('app.css', 'vendor/zoho-crm') }}">
@endsection

@section('body')
<div class="content" id="zoho-crm">
</div>
@endsection

@section('additional-scripts')
<script>
	window.ZohoCRM = @json($zohoCRMJsVariables);
</script>
<script src="{{ mix('app.js', 'vendor/zoho-crm')}}"></script>
@endsection