@extends('webpanel.layouts.base')
@section('title')
List Users
@parent
@stop
@section('body')

<div class="row page-titles">
    <div class="col-md-8">
        <h3 class="text-themecolor">List of Users</h3>
    </div>
    <div class="col-md-4">
        <a class="pull-right btn btn-primary btn-sm" href="{{ route('webpanel.users.create') }}">+ Add User</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline-info">
            <div class="card-body">
                @include('webpanel.includes.notifications')
                <div class="table-responsive">
                    <table class="table ajaxTable deleteArena" data-request-url="<?php echo route('webpanel.users.index'); ?>">
                        <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <nav id="paginationWrapper"></nav>
                </div>
            </div>
        </div>
    </div>

</div>

@stop
