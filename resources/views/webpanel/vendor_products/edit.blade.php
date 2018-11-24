@extends('webpanel.layouts.base')
@section('title')
    Edit Vendor Products
    @parent
@stop
@section('body')

    <div class="row page-titles">
        <div class="col-md-12">
            <h3 class="text-themecolor">Update Vendor Product</h3>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('webpanel.includes.notifications')

                    <h4 class="card-title">Update vendor Product information</h4>
                    <form class="form-material m-t-40 ajaxForm" method="post"
                          action="<?php echo route('webpanel.vendor-products.update', array('id' => $vendorProducts->id)); ?>"
                          role="form" data-result-container="#notificationArea">


                        <input type="hidden" name="_method" value="put">

                        <div class="form-group">
                            <label>Product Title</label>
                            <input type="text" class="form-control form-control-line" name="product_title"
                                   value="{{ $vendorProducts->product_title }}">
                        </div>

                        <div class="form-group">
                            <label>Vendor Item Number</label>
                            <input type="text" class="form-control form-control-line" name="vendor_item_number"
                                   value="{{ $vendorProducts->vendor_item_number }}">
                        </div>

                        <div class="form-group">
                            <label>UPC</label>
                            <input type="text" class="form-control form-control-line" name="upc"
                                   value="{{ $vendorProducts->upc }}">
                        </div>

                        <div class="form-group">
                            <label>Vendor Cost</label>
                            <input type="text" class="form-control form-control-line" name="vendor_cost"
                                   value="{{ $vendorProducts->vendor_cost }}">
                        </div>

                        <div class="form-group">
                            <label>Case Quantity</label>
                            <input type="text" class="form-control form-control-line" name="case_quantity"
                                   value="{{ $vendorProducts->case_quantity }}">
                        </div>

                        <div class="form-group">
                            <label>Weight</label>
                            <input type="text" class="form-control form-control-line" name="weight"
                                   value="{{ $vendorProducts->weight }}">
                        </div>

                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" class="form-control form-control-line" name="category"
                                   value="{{ $vendorProducts->category }}">
                        </div>

                        <div class="form-group">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-info">Update Vendor Product</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@stop
