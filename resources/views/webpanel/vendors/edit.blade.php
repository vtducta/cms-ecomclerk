@extends('webpanel.layouts.base')
@section('title')
    Edit Vendor
    @parent
@stop
@section('body')

    <div class="row page-titles">
        <div class="col-md-12">
            <h3 class="text-themecolor">Update Vendor</h3>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('webpanel.includes.notifications')

                    <h4 class="card-title">Update vendor information</h4>
                    <form class="form-material m-t-40 ajaxForm" method="post"
                          action="<?php echo route('webpanel.vendors.update', array('id' => $vendor->id)); ?>"
                          role="form" data-result-container="#notificationArea">


                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control form-control-line" name="title"
                                   value="{{ $vendor->title }}">
                        </div>

                        <div class="form-group">
                            <label>Minimum Purchase Amount</label>
                            <input type="text" class="form-control form-control-line" name="minimum_purchase_amount"
                                   value="{{ $vendor->minimum_purchase_amount }}">
                        </div>

                        <div class="form-group">
                            <label>Minimum Weight Amount</label>
                            <input type="text" class="form-control form-control-line" name="minimum_weight_amount"
                                   value="{{ $vendor->minimum_weight_amount }}">
                        </div>

                        <div class="form-group">
                            <label>Minimum Case Quantity</label>
                            <input type="text" class="form-control form-control-line" name="minimum_case_quantity"
                                   value="{{ $vendor->minimum_case_quantity }}">
                        </div>

                        <div class="form-group">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-info">Update Vendor</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@stop
