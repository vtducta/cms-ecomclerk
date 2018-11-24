@extends('webpanel.layouts.base')

@section('title')

    Import

@stop



@section('body')

    <div class="page-titles">

        <h3 class="text-themecolor">

            Files uploaded

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

                            </div>

                            <div class="panel-body">

                                <div class="table-responsive">

                                    <table data-order='[[ 0, "desc" ]]' class="display nowrap table table-striped table-bordered" id="importTable"

                                           data-url="<?php echo url('import/datatable'); ?>">

                                        <thead>

                                        <tr>

                                            <th>Job ID</th>

                                            <th>File name</th>

                                            <th>Date upload</th>

                                            <th>Date process</th>

                                            <th>Row count</th>

                                            <th>Result file</th>

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
            $('#importTable').dataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [[50, 100, 1000], [50, 100, "All"]],
                ajax: '{{ route('import.paginate') }}',
                responsive: true,
                columns: [
                    {data: 'job_id', name: 'job_id', orderable: true, searchable: true},
                    {data: 'file_name', name: 'file_name', orderable: true, searchable: true},
                    {data: 'created_at', name: 'created_at', orderable: true, searchable: true},
                    {data: 'updated_at', name: 'updated_at', orderable: true, searchable: true},
                    {data: 'row_count', name: 'row_count', orderable: true, searchable: true},
                    {data: 'result_file', name: 'result_file', orderable: true, searchable: true}
                ]
            });
            $('.dataTables_wrapper .dataTables_filter input').attr('placeholder', 'Search Title...');
        })(jQuery, window, document);

    </script>

@stop





