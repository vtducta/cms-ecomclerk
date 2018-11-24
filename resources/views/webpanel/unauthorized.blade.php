@extends('webpanel.layouts.base')
@section('title')
    Not Authorized
    @parent
@stop
@section('body')

    <div class="content-heading">Unauthorized Access</div>

    <div class="row">
        <div class="col-lg-12">
            <div id="panelDemo12" class="panel panel-danger">
                <div class="panel-heading">Permission Denied</div>
                <div class="panel-body">
                    <p>
                        Sorry! You do not have the sufficient permission to access the requested feature. Please contact
                        your administrator regarding this.
                    </p>
                </div>
                <div class="panel-footer">Administrator</div>
            </div>
        </div>
    </div>

@stop