@extends('webpanel.layouts.base')
@section('title')
Add User
@parent
@stop
@section('body')

    <div class="row page-titles">
        <div class="col-md-12">
            <h3 class="text-themecolor">Add New User</h3>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('webpanel.includes.notifications')

                    <h4 class="card-title">Fill up the below form to create new user</h4>
                    <form class="form-material m-t-40 ajaxForm" method="post"
                          action="<?php echo URL::route('webpanel.users.store'); ?>"
                          role="form" data-result-container="#notificationArea">
                        <input type="hidden" name="user_type_id" value="10"/>

                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control form-control-line" name="first_name" required="required" value="{{ Input::old('first_name') }}">
                        </div>

                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control form-control-line" name="last_name" required="required" value="{{ Input::old('last_name') }}">
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-control form-control-line" name="email" required="required" value="{{ Input::old('email') }}">
                        </div>

                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control form-control-line" name="phone" value="{{ Input::old('phone') }}">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control form-control-line" name="password" value="{{ Input::get('password') }}">
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control form-control-line" name="password_confirmation" value="{{ Input::get('password_confirmation') }}">
                        </div>

                        <div class="form-group">
                            <label>User Status</label>
                            <div class="demo-radio-button">
                                <input name="status" id="value1" type="radio" value="1">
                                <label for="value1">Active</label>
                                <input name="status" type="radio" id="value2" value="1">
                                <label for="value2">In Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-info">Add User</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
