@extends('laravel-core-system::main')
@section('content')
    <div class="container h-100 d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col-xl-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h1 class="h3">App Settings</h1>
                        </div>
                        @if (Request::route('error'))
                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        Something Went Wrong, Try Again !
                                    </div>
                                </div>
                            </div>
                        @endif
                        <p class="text-muted font-13">
                        <form method="POST" action="{{ route('install.complete') }}">
                            @csrf

                            <div class="form-group">
                                <label for="admin_name">App Name</label>
                                <input type="text" class="form-control" id="app_name" placeholder="{{config('app.name')}}" name="app_name" required>
                            </div>

                            <div class="form-group">
                                <label for="admin_email">Frontend URL</label>
                                <input type="url" class="form-control" placeholder="https://yourdomain.com" id="frontend_url" name="frontend_url" required>
                            </div>

                            <div class="form-group">
                                <label for="admin_email">Admin URL</label>
                                <input type="url" class="form-control" placeholder="https://admin.yourdomain.com" id="admin_url" name="admin_url" required>
                            </div>


                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary">Continue</button>
                            </div>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
