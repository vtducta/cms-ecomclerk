@extends('webpanel.layouts.base')

@section('title')

    FBA Products

@stop



@section('body')

    <div class="page-titles">

        <h3 class="text-themecolor">

            Products

            {!! linkBtn('Import CSV', '#', ['icon' =>'icon-plus', 'class' => 'btn-primary pull-right',

             'data' => ['target' => '.importModal', 'toggle' => 'modal']]) !!}

        </h3>

    </div>





    <div class="row">

        <div class="col-md-12">

            @include('webpanel.includes.notifications')


            <div class="card card-outline-info">

                <div class="card-body">

                    <form method="post" action="#" class="form-horizontal">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                {!! linkBtn('Export CSV', sysUrl('fbaproducts/export').'?'.http_build_query(Input::except('page')), [

                                'icon' => 'fa fa-download', 'class' => 'btn btn-primary pull-right']) !!}

                            </div>

                            <div class="panel-body">

                                <div class="table-responsive">

                                    <table class="display nowrap table table-striped table-bordered" id="fba-productTable"

                                           data-url="<?php echo sysUrl('fbaproducts/datatable'); ?>">

                                        <thead>

                                        <tr>

                                            <th>Title</th>

                                            <th>Buy Box</th>

                                            <th>ASIN</th>

                                            <th>UPC</th>

                                            <th>Profit</th>

                                            <th>Estimated Monthly Sale</th>

                                        </tr>

                                        </thead>

                                        <tbody>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </form>

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
        $('#fba-productTable').dataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [[50, 100, -1], [50, 100, "All"]],
            ajax: '{{ route('fbaproducts.paginate') }}',
            responsive: true,
            columns: [
                {data: 'title', name: 'title', orderable: true, searchable: true},
                {data: 'buy_box', name: 'buy_box', orderable: true, searchable: true},
                {data: 'asin', name: 'asin', orderable: true, searchable: true},
                {data: 'upc', name: 'upc', orderable: true, searchable: true},
                {data: 'profit', name: 'profit', orderable: true, searchable: true},
                {data: 'estimated_monthly_sales', name: 'estimated_monthly_sales', orderable: true, searchable: true}
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


                    <form method="post" action="{{ sysUrl('fbaproducts/import') }}" enctype="multipart/form-data">


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