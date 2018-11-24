@extends('webpanel.layouts.base')
@section('title')
Change Password
@parent
@stop
@section('body')

    <div class="row page-titles">
        <div class="col-md-12">
            <h3 class="text-themecolor">Change Password</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('webpanel.includes.notifications')

                    <form class="form-material m-t-40" method="post" action="{{ url('webpanel/my/password') }}" role="form">


                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" class="form-control form-control-line" name="password">
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control form-control-line" name="password_confirmation">
                        </div>

                        <div class="form-group">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-lg btn-primary">Update Password</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>


@stop