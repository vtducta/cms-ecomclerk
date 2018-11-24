@extends('webpanel.layouts.base')
@section('title')
Dashboard
@parent
@stop
@section('body')

    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor">Dashboard</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-warning"><i class="mdi mdi-cellphone-link"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-lgiht">{{ \App\Modules\Products\Product::count('id') }}</h3>
                            <h5 class="text-muted m-b-0">Total Products</h5></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-info"><i class="ti-wallet"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-lgiht">${{ number_format( \App\Modules\Products\Product::sum('gross_profit_fba'),2) }}</h3>
                            <h5 class="text-muted m-b-0">Total Profit</h5></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-cart-outline"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-lgiht">{{ \App\Modules\Products\Product::where('is_eligible_for_prime', '=', '1')->count('id') }}</h3>
                            <h5 class="text-muted m-b-0">Prime Products</h5></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-danger"><i class="mdi mdi-bullseye"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-lgiht">${{ (float) \App\Modules\Products\Product::sum('cost') }}</h3>
                            <h5 class="text-muted m-b-0">Pack Cost</h5></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        (function ($, window, document, undefined) {
            $(function () {

            })

        })(jQuery, window, document);
    </script>
@stop