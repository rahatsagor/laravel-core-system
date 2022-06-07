@extends('laravel-core-system::main')
@section('content')
    <div class="container h-100 d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col-xl-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="mar-ver pad-btm text-center">
                            <h1 class="h3">{{config('app.name')}}</h1>
                            <p>You will need to know the following items before proceeding.</p>
                        </div>
                        <ol class="list-group">
                            <li class="list-group-item text-semibold"><i class="las la-chevron-right"></i> Database Name</li>
                            <li class="list-group-item text-semibold"><i class="las la-chevron-right"></i> Database Username</li>
                            <li class="list-group-item text-semibold"><i class="las la-chevron-right"></i> Database Password</li>
                            <li class="list-group-item text-semibold"><i class="las la-chevron-right"></i> Database Hostname</li>
                            <li class="list-group-item text-semibold"><i class="las la-chevron-right"></i> Frontend URL <span class="text-sm">(ex: example.com / app.example.com)</span></li>
                            <li class="list-group-item text-semibold"><i class="las la-chevron-right"></i> Admin URL <span class="text-sm">(ex: admin.example.com / panel.example.com)</span></li>
                        </ol>
                        <p class="mt-3">
                            During the installation process, we will check if the files that are needed to be written
                            (<strong>.env file</strong>) have
                            <strong>write permission</strong>. We will also check if <strong>curl</strong> are enabled on
                            your server or not.
                        </p>
                        <p>
                            Gather the information mentioned above before hitting the start installation button. If you are
                            ready....
                        </p>
                        <br>
                        <div class="text-center mt-3">
                            <a href="{{ route('install.step1') }}" class="btn btn-primary">
                                Start Installation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
