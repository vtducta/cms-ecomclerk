@extends('layouts.auth')
@section('content')

<section id="wrapper">
    <div class="login-register" style="background-image:url({{ asset('assets/images/background/login-register.jpg') }});">
        <div class="login-box card">
            <div class="card-body">

                @if ($errors->has('email'))
                    <div class="alert alert-danger">
                          {{ $errors->first('email') }}
                        </div>
                @endif

                @if ($errors->has('password'))
                        <div class="alert alert-danger">
                            {{ $errors->first('password') }}
                        </div>
                @endif

                <form class="form-horizontal form-material" method="post" >
                    {!! csrf_field() !!}

                    <div class="text-center box-title m-b-20 text-uppercase"><img src="{{ asset('assets/images/logo.png') }}" class="dark-logo" /></div>


                    <br/>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="email" required="" placeholder="Enter Username"> </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" required="" name="password" placeholder="Enter Password"> </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-primary">
                                <input id="remember" name="remember" type="checkbox">
                                <label for="remember"> Remember me </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-block btn-rounded btn-primary btn-lg" type="submit"><i class="mdi mdi-login"></i> Log In</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</section>

@endsection