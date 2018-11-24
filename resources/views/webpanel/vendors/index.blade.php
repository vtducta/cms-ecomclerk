@extends('webpanel.layouts.base')
@section('title')
    List Vendors
    @parent
@stop
@section('body')

    <div class="row page-titles">
        <div class="col-md-8">
            <h3 class="text-themecolor">List of Vendors</h3>
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
                        {!! linkBtn('Export CSV', sysUrl('vendors/export').'?'.http_build_query(Input::except('page')), [

                        'icon' => 'fa fa-download', 'class' => 'btn btn-primary pull-right']) !!}

                    </div>

                    <div class="table-responsive">
                        <table class="table deleteArena display nowrap table-striped table-bordered" id="vendorsTable"
                               data-url="<?php echo route('webpanel.vendors.index'); ?>">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Minimum Purchase Amount</th>
                                <th>Minimum Weight Amount</th>
                                <th>Minimum Case Quantity</th>
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

        (function ($, window, document, undefined) {
            $('#vendorsTable').dataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [[50, 100, -1], [50, 100, "All"]],
                ajax: '{{ route('vendors.paginate') }}',
                responsive: true,
                columns: [
                    {data: 'title', name: 'title', orderable: true, searchable: true},
                    {
                        data: 'minimum_purchase_amount',
                        name: 'minimum_purchase_amount',
                        orderable: true,
                        searchable: true
                    },
                    {data: 'minimum_weight_amount', name: 'minimum_weight_amount', orderable: true, searchable: true},
                    {
                        data: 'minimum_case_quantity',
                        name: 'minimum_case_quantity',
                        orderable: true,
                        searchable: true
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            $('.dataTables_wrapper .dataTables_filter input').attr('placeholder', 'Search Title...');
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



                    <form method="post" action="{{ sysUrl('vendors/import') }}" enctype="multipart/form-data">



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