@extends('webpanel.layouts.base')
@section('title')
Product Details
@stop

@section('body')
    <div class="page-titles">
        <h3 class="text-themecolor">
            {{ $product->title }}
        </h3>
    </div>

    <div class="row">
        <div class="col-md-12">
            @include('webpanel.includes.notifications')
            <div class="card card-outline-info">
                <div class="card-body">
                    <strong>Title: </strong>{{ $product->title }}<br>
                    <strong>Amazon Title: </strong>{{ $product->amazon_title }}<br>
                    <strong>ASIN: </strong><a href="https://www.amazon.com/dp/{{$product->asin}}">{{ $product->asin }}</a><br>
                    <strong>Brand: </strong>{{ $product->brand }}<br>
                    <strong>Amazon UPC EAN: </strong>{{ $product->amazon_upc_ean }}<br>
                    <strong>UPC EAN: </strong>{{ $product->upc_ean }}<br>
                    <strong>Weight: </strong>{{$product->weight}}
                    <hr/>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="round align-self-center round-success"><i class=" ti-shopping-cart"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0">{{$product->sales}}</h3>
                                            <h5 class="text-muted m-b-0">Sales per month</h5></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="round align-self-center round-success"><i class=" ti-bar-chart"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0">{{$product->sales_rank_30}}</h3>
                                            <h5 class="text-muted m-b-0">Average rank 30 days</h5></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="round align-self-center round-success"><i class=" ti-bar-chart"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0">{{$product->sales_rank_90}}</h3>
                                            <h5 class="text-muted m-b-0">Average rank 90 days</h5></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Cost: </strong>${{ $product->cost }}<br>
                            <strong>Number of Packs: </strong>{{ $product->number_of_packs }}<br>
                            <strong>Pack Cost: </strong>${{ $product->pack_cost }}<br>
                            <strong>Amazon Box Price: </strong>${{ $product->amazon_buy_box_price }}<br>
                            <strong>Net after FBA: </strong>${{ number_format($product->net_after_fba,2) }}<br>
                            <strong>Gross Profit FBA: </strong>${{ number_format($product->gross_profit_fba,2) }}<br>
                            <strong>Gross ROI: </strong>{{ number_format($product->gross_roi,2) }}%<br>
                            <hr>
                            <strong>Buy Box Win: {{$product->buybox_win}}</strong><br/>
                            <strong>Number of Sellers: {{$product->number_of_sellers}}</strong><br/>
                            <strong>Number of Prime Sellers: {{$additional_data['number_of_prime_sellers']}}</strong><br/>
                            <strong>Quantity Buy in weekly: {{$additional_data['quantity_buy_in']}}</strong>

                            @if ($additional_data['status'] == 'Go')
                                <div class="alert alert-success">
                                    You can go with this product
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    Please be careful with this product
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <img src="https://dyn.keepa.com/pricehistory.png?domain=com&asin={{$product->asin}}" />
                        </div>
                    </div>

                    <div class="col-md-12">
                        <strong>Denied Reason: </strong>   {{$product->reason}}
                    </div>

                    <iframe src="https://keepa.com/#!product/1-{{$product->asin}}" allowfullscreen style="width: 100%; height: 500px;"></iframe>

                    <hr/>

                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-lg  btn-success btn-mark-as-po">Mark as PO</button>
                        </div>
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-lg  btn-danger btn-mark-as-no">Mark as No PO</button>
                        </div>
                    </div>

                    <br/><br/>
                    <a href="{{ sysRoute('products.index') }}" class="btn btn-primary">Back to List</a>
                    <input type="hidden" id="product_id" value="{{$product->id}}" />
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                $('.btn-mark-as-po').click(function(e) {
                    e.preventDefault();
                    $(this).html('<i class="fa fa-spinner fa-pulse"></i> Loading...');
                    $this = $(this);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{url('/markpo')}}",
                        method: "POST",
                        data: {id: $('#product_id').val(), status: 'Yes'}
                    }).done(function() {
                        $this.html('Mark as PO');
                        $.toast({
                            heading: 'Success!',
                            text: 'We have marked this product as PO',
                            position: 'top-right',
                            loaderBg:'#ff6849',
                            icon: 'success',
                            hideAfter: 3500,
                            stack: 6
                        });
                    });

                })

                $('.btn-mark-as-maybe').click(function(e) {
                    e.preventDefault();
                    $(this).html('<i class="fa fa-spinner fa-pulse"></i> Loading...');
                    $this = $(this);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{url('/markpo')}}",
                        method: "POST",
                        data: {id: $('#product_id').val(), status: 'Maybe'}
                    }).done(function() {
                        $this.html('Mark as Maybe');
                        $.toast({
                            heading: 'Success!',
                            text: 'We have marked this product as Maybe',
                            position: 'top-right',
                            loaderBg:'#ff6849',
                            icon: 'success',
                            hideAfter: 3500,
                            stack: 6
                        });
                    });

                });

                $('.btn-mark-as-no').click(function(e) {
                    e.preventDefault();
                    $(this).html('<i class="fa fa-spinner fa-pulse"></i> Loading...');
                    $this = $(this);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    swal({
                        title: 'Please state the reason why No',
                        input: 'textarea',
                    }).then(function(text) {
                        if (text) {
                            $.ajax({
                                url: "{{url('/markpo')}}",
                                method: "POST",
                                data: {id: $('#product_id').val(), status: 'No', reason: text}
                            }).done(function() {
                                $this.html('Mark as No PO');
                                $.toast({
                                    heading: 'Success!',
                                    text: 'We have marked this product as No',
                                    position: 'top-right',
                                    loaderBg:'#ff6849',
                                    icon: 'success',
                                    hideAfter: 3500,
                                    stack: 6
                                });
                            });
                        }
                    })
                })
            })
        })(jQuery)
    </script>
@stop