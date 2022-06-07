@extends('laravel-core-system::main')
@section('content')
    <div class="container h-100 d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col-xl-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h1 class="h3">Import SQL</h1>
                        </div>
                        @if (Request::route('error'))
                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        Error while importing data, try again !
                                    </div>
                                </div>
                            </div>
                        @endif
                        <p class="text-muted font-13 text-center">
                            <strong>Your database is successfully connected</strong>. <br>
                            The auto installer will run a sql file, will do all the tiresome works and set up your
                            application automatically.
                        </p>
                        <div class="text-center mt-3">
                            <a href="{{ route('install.import_sql') }}" class="btn btn-primary">Import SQL</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
