@extends('webpanel.layouts.base')
@section('title')
Profile
@parent
@stop
@section('body')

    <div class="row page-titles">
        <div class="col-md-12">
            <h3 class="text-themecolor">Update Profile</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    @include('webpanel.includes.notifications')
                    <h4 class="card-title">Update user information</h4>
                    <form class="form-material m-t-40" method="post" action="<?php echo URL::route('admin.profile.update'); ?>"
                          role="form" enctype="multipart/form-data">

                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control form-control-line" name="first_name" value="{{ $user->first_name }}">
                        </div>

                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text"  class="form-control form-control-line" name="last_name" value="{{ $user->last_name }}">
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="text" class="form-control form-control-line" name="email" value="{{ $user->email }}">
                        </div>

                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control form-control-line" name="phone" value="{{ $user->phone }}">
                        </div>


                        <div class="form-group">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-info">Update User</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

@stop