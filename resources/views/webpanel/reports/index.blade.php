@extends('webpanel.layouts.base')
@section('title')
    FBA Reports
    @parent
@stop
@section('body')

<div class="row page-titles">
        <div class="col-md-8">
            <h3 class="text-themecolor">FBA Reports</h3>
        </div>
        <div class="col-md-4">
            {!! linkBtn('Upload Transactions', '#', ['icon' =>'icon-plus', 'class' => 'btn-primary pull-right',

              'data' => ['target' => '.importModal', 'toggle' => 'modal']]) !!}
        </div>
</div>
  
  <div class="row">
        <div class="col-md-12">
            @include('webpanel.includes.notifications')
            <div class="card card-outline-info">
                <div class="card-body">
                    <div class="panel-body">
                       <h3 class="sum_of_cost">Sum of Cost : <span>{{@$sum_of_cost}}</span></h3> 
                       <h3 class="sum_of_prdct_sales">Sum of Product Sales : {{@$sum_of_product_sales}}</h3> 
                       <h3 class="sum_of_prdct_sales">Sum of Selling Fees : {{@$selling_fees}}</h3> 
                       <h3 class="sum_of_prdct_sales">Total : {{ (@$sum_of_cost + @$selling_fees + @$sum_of_product_sales) - @$sum_of_cost}}</h3> 
                       <table class="nowrap display table table-striped table-bordered" id="fbaReportsTable" data-url="<?php echo sysUrl('reports/datatable'); ?>">
                            <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Order ID</th>
                                <th>Quantity</th>
                                <th>Product Sales</th>
                                <th>Selling Fees</th>
                                <th>Sales Tax Collected</th>
                                <th>Date Time</th>
                                <th>Cost</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop


@section('modals')

    <div id="myModal importModal" class="modal fade importModal" tabindex="-1" role="dialog"

         aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Upload Mass Data</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>

                <div class="modal-body">


                    <form method="post" action="{{ sysUrl('reports/import') }}" enctype="multipart/form-data">


                        <div class="form-group">

                            <label>&nbsp; &nbsp; Select CSV File</label><br/><br/>


                            <div class="col-md-12">

                                <input type="file" name="file">

                                <br/>

                                <p><br/>

                                    Please only upload valid CSV file.

                                    <a href="{{ asset('uploads/Sample.csv') }}">Click here</a> to view Sample

                                    csv for mass upload. Only .CSV file type allowed.</p>

                            </div>

                        </div>


                        <div class="form-group">

                            <label>&nbsp; &nbsp; Upload Type</label>

                            <div class="col-md-12">

                                <div class="demo-radio-button">

                                    <input name="type" id="update" type="radio" value="update">

                                    <label for="update">Update/Replace</label>

                                    <input name="type" type="radio" id="new" value="new" checked>

                                    <label for="new">Clear all and upload</label>

                                </div>

                            </div>

                        </div>


                        <div class="form-group">

                            <div class="col-md-12">

                                <button type="submit" class="btn btn-block btn-primary">IMPORT</button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


        </div>

    </div>



@stop

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>

<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>

<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>

<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

<script>

    (function ($, window, document, undefined) {
        $('#fbaReportsTable').dataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [[50, 100, -1], [50, 100, "All"]],
            ajax: '{{ route('reports.paginate') }}',
            responsive: true,
            columns: [
                {data: 'sku', name: 'title', orderable: true, searchable: true},
                {data: 'order_id', name: 'order_id', orderable: true, searchable: true},
                {data: 'quantity', name: 'quantity', orderable: true, searchable: true},
                {data: 'product_sales', name: 'product_sales', orderable: true, searchable: true},
                {data: 'selling_fees', name: 'selling_fees', orderable: true, searchable: true},
                {data: 'sales_tax_collected', name: 'sales_tax_collected', orderable: true, searchable: true},
                {data: 'date_time', name: 'date_time', orderable: true, searchable: true},
                {data: 'cost', name: 'cost', orderable: true, searchable: true},
                {data: 'total', name: 'total', orderable: true, searchable: true}
            ]
        });
        
        $('.dataTables_wrapper .dataTables_filter input').attr('placeholder', 'Search Title...');
        //$('.sum_of_cost span').html(data.sum_of_cost);
    })(jQuery, window, document);

    $(document).ready(function () {
        $('#datepicker').datepicker({
            format: 'yyyy-dd-mm',
            todayHighlight:'TRUE',
            autoclose: true,
        })
    });
</script>
@stop
