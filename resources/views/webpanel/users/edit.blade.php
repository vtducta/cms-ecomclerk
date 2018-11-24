@extends('webpanel.layouts.base')
@section('title')
Edit User
@parent
@stop
@section('body')

    <div class="row page-titles">
        <div class="col-md-12">
            <h3 class="text-themecolor">Update User</h3>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('webpanel.includes.notifications')

                    <h4 class="card-title">Update user information</h4>
                    <form class="form-material m-t-40 ajaxForm" method="post"
                          action="<?php echo route('webpanel.users.update', array('id' => encryptIt($user->id))); ?>"
                          role="form" data-result-container="#notificationArea">

                        <input type="hidden" name="user_type_id" value="10"/>
                        <input type="hidden" name="_method" value="put">

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
                            <label>User Status</label>
                            <div class="demo-radio-button">
                                <input type="radio" name="status" id="value1" value="1" <?php echo isChecked(1, $user->status); ?>>
                                <label for="value1">Active</label>
                                <input type="radio" name="status" id="value2" value="0" <?php echo isChecked(0, $user->status); ?>>
                                <label for="value2">In Active</label>
                            </div>
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

