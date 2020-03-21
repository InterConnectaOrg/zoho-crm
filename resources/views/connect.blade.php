@extends('interconnecta/connect-ui::layout.default')

@section('additional-styles')
<link rel="stylesheet" href="{{ mix('app.css', 'vendor/zoho-crm') }}">
@endsection

@section('body')
<div class="content" id="zoho-crm">
	<example-component></example-component>
	<div class="container-fluid">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Zoho CRM Wrapper</h1>
					</div><!-- /.col -->
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="#">Apps</a></li>
							<li class="breadcrumb-item active">Property Uploader</li>
						</ol>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="card card-primary card-outline">
					<div class="card-header">
						<h5 class="card-title">Settings</h5>

						<div class="card-tools">
							<div class="btn-group">
								<button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown"
									aria-expanded="false">
									<i class="fas fa-wrench"></i>
								</button>
								<div class="dropdown-menu dropdown-menu-right" role="menu" x-placement="bottom-end"
									style="position: absolute; transform: translate3d(-123px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">
									<a href="#" class="dropdown-item">Action</a>
									<a href="#" class="dropdown-item">Another action</a>
									<a href="#" class="dropdown-item">Something else here</a>
									<a class="dropdown-divider"></a>
									<a href="#" class="dropdown-item">Separated link</a>
								</div>
							</div>
						</div>
					</div>
					<form action="/zoho-crm/save" method="post">
						<div class="card-body">
							<h5>Zoho OAuth2 Connection</h5>
							<div class="form-group">
								<label for="exampleInputFile">Client ID</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-key"></i></span>
									</div>
									<input type="text" class="form-control" id="clientid" name="clientid"
										placeholder="XXXXXXXXXXX">
								</div>
							</div>
							<div class="form-group">
								<label for="exampleInputFile">Client Secret</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-key"></i></span>
									</div>
									<input type="text" class="form-control" id="clientsecret" name="clientsecret"
										placeholder="XXXXXXXXXXX">
								</div>
							</div>
							<div class="form-group">
								<label for="exampleInputFile">Authorized Redirect URI</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-check"></i></span>
									</div>
									<input type="text" class="form-control" id="redirecturi" name="redirecturi">
								</div>
							</div>
							<div class="form-group">
								<label for="exampleInputFile">User Email</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-check"></i></span>
									</div>
									<input type="email" class="form-control" id="email" name="email">
								</div>
							</div>
							<div class="form-group">
								<label for="exampleInputFile">Access Type</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-check"></i></span>
									</div>
									<input type="text" class="form-control" value="online" id="accesstype"
										name="accesstype">
								</div>
							</div>
							<div class="form-group">
								<label for="exampleInputFile">Scope</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-check"></i></span>
									</div>
									<input type="text" class="form-control" value="" id="scope" name="scope">
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary float-right">Save</button>
						</div>
					</form>
				</div><!-- /.card -->
			</div>
		</div>
	</div>
</div>
@endsection

@section('additional-scripts')
<script src="{{ mix('app.js', 'vendor/zoho-crm')}}"></script>
@endsection