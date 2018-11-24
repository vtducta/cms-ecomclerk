<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ config('app.name', 'Intake DT') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/colors/blue.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('css/ajaxtable.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.6/sweetalert2.css" rel="stylesheet">
    @yield('styles')
    <?php
    if (isset($jsData) && !empty($jsData)) {
        event('js.transform', array($jsData));
    }
    ?>

   <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="fix-header card-no-border logo-center">
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
<div id="main-wrapper">
    <header class="topbar is_stuck">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo URL::to('webpanel/dashboard'); ?>">
                    <b>
                        <img src="{{ asset('assets/images/logo-icon.png') }}" alt="homepage" class="dark-logo" />
                        <img src="{{ asset('assets/images/logo-light-icon.png') }}" alt="homepage" class="light-logo" />
                    </b>
                        <span>
                         <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage" class="dark-logo" />
                         <img src="{{ asset('assets/images/logo-light-text.png') }}" class="light-logo" alt="homepage" />
                        </span>
                </a>
            </div>
            <div class="navbar-collapse">
                <ul class="navbar-nav mr-auto mt-md-0">
                    <li class="nav-item hidden-sm-down search-box"> <a class="nav-link hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                        <form class="app-search" _lpchecked="1" style="display: none;">
                            <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a> </form>
                    </li>
                </ul>

                <ul class="navbar-nav my-lg-0">
                    <li class="nav-item">
                        <a   class="nav-link text-muted waves-effect waves-dark" title="Import" data-toggle="modal" data-target="#importHeaderModal">
                            <i class="mdi mdi-upload"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('assets/images/profile.png') }}" alt="user" class="profile-pic" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right scale-up">
                            <ul class="dropdown-user">
                                <li><a href="{{ sysUrl('my/profile') }}"><i class="ti-user"></i> Update Profile</a></li>
                                <li><a href="{{ sysUrl('my/password') }}"><i class="mdi mdi-key-variant"></i> Change Password</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!--modal for upload in header-->

    <div id="importHeaderModal" class="modal fade importHeaderModal" tabindex="-1" role="dialog"

         aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Upload Mass Data</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>

                <div class="modal-body">


                    <form method="post" action="{{url('import')}}" enctype="multipart/form-data">
                        {!! csrf_field() !!}

                        <div class="form-group">

                            <label>&nbsp; &nbsp; Select CSV File</label><br/><br/>


                            <div class="col-md-12">

                                <input type="file" name="file">

                                <br/>

                                <p>
                                    <br/>

                                    Please only upload valid CSV file.

                                    <a href="{{ asset('uploads/Sample2.csv') }}">Click here</a> to view Sample

                                    csv for mass upload. Only .CSV file type allowed.</p>

                            </div>

                        </div>

                        <div class="form-group">

                            <div class="col-md-12">

                                <button type="submit" class="btn btn-block btn-primary">IMPORT</button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


        </div>

    </div>