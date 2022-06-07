@extends('laravel-core-system::main')
@section('content')
    <div class="container h-100 d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col-xl-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h1 class="h3">Database setup</h1>
                            <p>Fill this form with valid database credentials</p>
                        </div>

                        @if (Request::route('error'))
                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        <strong>Invalid Database Credentials!! </strong>Please check your database
                                        credentials carefully
                                    </div>
                                </div>
                            </div>
                        @endif

                        <p class="text-muted font-13">
                        <form method="POST" action="{{ route('install.db') }}">
                            @csrf
                            <div class="form-group">
                                <label for="db_host">Database Host</label>
                                <input type="text" class="form-control" id="db_host" value="{{ config('database.connections.mysql.host') }}" name="DB_HOST" required
                                       autocomplete="off">
                                <input type="hidden" name="types[]" value="DB_HOST">
                            </div>
                            <div class="form-group">
                                <label for="db_port">Database Port</label>
                                <input type="text" class="form-control" id="db_port" value="{{ config('database.connections.mysql.port') }}" name="DB_PORT" required
                                       autocomplete="off">
                                <input type="hidden" name="types[]" value="DB_PORT">
                            </div>
                            <div class="form-group">
                                <label for="db_name">Database Name</label>
                                <input type="text" class="form-control" id="db_name" name="DB_DATABASE" required
                                       autocomplete="off">
                                <input type="hidden" name="types[]" value="DB_DATABASE">
                            </div>
                            <div class="form-group">
                                <label for="db_user">Database Username</label>
                                <input type="text" class="form-control" id="db_user" name="DB_USERNAME" required
                                       autocomplete="off">
                                <input type="hidden" name="types[]" value="DB_USERNAME">
                            </div>
                            <div class="form-group">
                                <label for="db_pass">Database Password</label>
                                <input type="password" class="form-control" id="db_pass" name="DB_PASSWORD"
                                       autocomplete="off">
                                <input type="hidden" name="types[]" value="DB_PASSWORD">
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