@extends('webpanel.layouts.base')
@section('title')
    List Vendor Products
    @parent
@stop
@section('body')

    <div class="row page-titles">
        <div class="col-md-8">
            <h3 class="text-themecolor">List of Vendor Products</h3>
        </div>
        <div class="col-md-4">
            {!! linkBtn('Import CSV', '#', ['icon' =>'icon-plus', 'class' => 'btn-primary pull-right',

              'data' => ['target' => '.importModal', 'toggle' => 'modal']]) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    <div class="panel-heading">
                        @include('webpanel.includes.notifications')
                        {!! linkBtn('Export CSV', sysUrl('vendor-products/export').'?'.http_build_query(Input::except('page')), [

                        'icon' => 'fa fa-download', 'class' => 'btn btn-primary pull-right']) !!}

                    </div>

                    <div class="table-responsive">
                        <table class="table deleteArena display nowrap table-striped table-bordered"
                               id="vendorProductsTable"
                               data-url="<?php echo route('webpanel.vendor-products.index'); ?>">
                            <thead>
                            <tr>
                                <th>Product Title</th>
                                <th>Vendor Item Number</th>
                                <th>UPC</th>
                                <th>Vendor Cost</th>
                                <th>Case Quantity</th>
                                <th>Weight</th>
                                <th>Category</th>
                                <th>Action</th>
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
@section('scripts')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>

    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>

    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script>
        $(document).on('click', '.confirm-action', function (e) {
            e.preventDefault();
            var btn = $(this);
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
            }).then(function () {
                console.log('hhhh');
                $.ajax({
                    url: btn.data('href'),
                    type: btn.data('method'),
                    data: {'_token': "{{csrf_token()}}", '_method': btn.data('method')},
                    dataType: 'json',
                    success: function (response) {
                        $('#vendorProductsTable').DataTable().ajax.reload();
                    }
                });
            });
        });
        (function ($, window, document, undefined) {


            $('#vendorProductsTable').dataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [[50, 100, -1], [50, 100, "All"]],
                ajax: '{{ route('vendor-products.paginate') }}',
                responsive: true,
                columns: [
                    {data: 'product_title', name: 'product_title', orderable: true, searchable: true},
                    {
                        data: 'vendor_item_number',
                        name: 'vendor_item_number',
                        orderable: true,
                        searchable: true
                    },
                    {data: 'upc', name: 'upc', orderable: true, searchable: true},
                    {
                        data: 'vendor_cost',
                        name: 'vendor_cost',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'case_quantity',
                        name: 'case_quantity',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'weight',
                        name: 'weight',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'category',
                        name: 'category',
                        orderable: true,
                        searchable: true
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            $('.dataTables_wrapper .dataTables_filter input').attr('placeholder', 'Search Product Title...');
        })(jQuery, window, document);

    </script>
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


                    <form method="post" action="{{ sysUrl('vendor-products/import') }}" enctype="multipart/form-data">


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

