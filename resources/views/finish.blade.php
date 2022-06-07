@extends('laravel-core-system::main')
@section('content')
    <div class="container h-100 d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col-xl-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h1 class="h3">Congratulations!!!</h1>
                            <p>You have successfully completed the installation process. You'll find more settings in the admin panel. Please Log in to continue.</p>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="fs-16 mb-0 card-title">
                                    Admin Credentials
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul class="">
                                    <li class="">admin</li>
                                    <li class="">123456</li>
                                </ul>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="fs-16 mb-0 card-title">
                                    User Credentials
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul class="">
                                    <li class="">user</li>
                                    <li class="">123456</li>
                                </ul>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('install.frontend') }}" class="btn btn-primary">Go to Frontend Website</a>
                            <a href="{{ route('install.admin') }}" class="btn btn-success">Go to Admin panel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
