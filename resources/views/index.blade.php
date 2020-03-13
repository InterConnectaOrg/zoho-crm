@extends('interconnecta/connect-ui::layout.master')

@section('title')
Zoho CRM Wrapper
@endsection

@section('header')
@include('interconnecta/zoho-crm::partials.header')
@endsection

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Property Uploader</h1>
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
                    <div class="card-body">
                        <h5>Zoho OAuth2 Connection</h5>
                        <div class="form-group">
                            <label for="exampleInputFile">Client ID</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="XXXXXXXXXXX">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Client Secret</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="XXXXXXXXXXX">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Authorized Redirect URI</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-check"></i></span>
                                </div>
                                <input type="text" class="form-control"
                                    value="https://customer.toolbox.interconnecta.com/zoho-auth/">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Client Type</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-check"></i></span>
                                </div>
                                <input type="text" class="form-control" value="Web">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Scope</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-check"></i></span>
                                </div>
                                <input type="text" class="form-control" value="ZohoProfile.ALL">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </div>
                </div><!-- /.card -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@include('interconnecta/zoho-crm::partials.footer')
@endsection