@extends('laravel-core-system::main')
@section('content')
    <div class="container h-100 d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col-xl-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        @if (Request::route('error'))
                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        {{Request::route('error')}}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="text-center">
                            <h1 class="h3">Purchase Code</h1>
                            <p class="my-2">
                                {{$r['message']}} <br><br>
                                <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code"
                                   target="_blank">Where to get purchase code?</a>
                            </p>
                        </div>
                        <p class="text-muted font-13">
                        <form method="POST" action="{{ route('install.verify_code') }}">
                            @csrf
                            <div class="form-group">
                                <label for="domain">Domain:</label>
                                <input type="text" class="form-control" value="{{$_SERVER['SERVER_NAME']}}" id="domain" name="domain"
                                       disabled>
                            </div>
                            <div class="form-group">
                                <label for="code">Purchase Code:</label>
                                <input type="text" class="form-control" value="" id="code" name="code"
                                       placeholder="**** **** **** ****" required="">
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary">Continue</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
