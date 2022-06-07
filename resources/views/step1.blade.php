@extends('laravel-core-system::main')
@section('content')
    <div class="container h-auto py-4 d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col-xl-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="mar-ver pad-btm text-center">
                            <h1 class="h3">Checking Permissions</h1>
                            <p>We ran diagnosis on your server. Review the items that have a red mark on it. <br> If
                                everything is green, you are good to go to the next step.</p>
                        </div>

                        <ul class="list-group mt-2">
                            <li class="list-group-item text-semibold">
                                Php version 8.0.2 +

                                @if ($permission['php'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                Curl Enabled

                                @if ($permission['curl'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                Mysqli

                                @if ($permission['mysqli'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                Ctype

                                @if ($permission['ctype'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                Fileinfo

                                @if ($permission['fileinfo'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                JSON

                                @if ($permission['json'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                Mbstring

                                @if ($permission['mbstring'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                OpenSSL

                                @if ($permission['openssl'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                PDO

                                @if ($permission['pdo'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                Tokenizer

                                @if ($permission['tokenizer'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                XML

                                @if ($permission['xml'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>

                            <li class="list-group-item text-semibold">
                                <b>.env</b> File Permission

                                @if ($permission['env_write'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>
                            <li class="list-group-item text-semibold">
                                File Permission

                                @if ($permission['file_write'])
                                    <i class="la la-check text-success float-right"></i>
                                @else
                                    <i class="la la-close text-danger float-right"></i>
                                @endif
                            </li>
                        </ul>

                        <p class="text-center mt-3">
                            @if ($permission['php'] && $permission['curl'] && $permission['mysqli'] && $permission['ctype'] && $permission['fileinfo'] && $permission['json'] && $permission['mbstring'] && $permission['openssl'] && $permission['pdo'] && $permission['tokenizer'] && $permission['xml'] && $permission['env_write'] && $permission['file_write'])
                                <a href="{{route('install.step2')}}" class="btn btn-primary">Go To Next Step</a>
                            @else
                                <a href="{{route('install.start')}}" class="btn btn-danger">Go Back</a>
                                <a href="{{route('install.step1')}}" class="btn btn-secondary">Reload</a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
