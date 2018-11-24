@extends('webpanel.layouts.base')
@section('title')
    List Purchase Orders
    @parent
@stop
@section('body')

    <div class="row page-titles">
        <div class="col-md-8">
            <h3 class="text-themecolor">List of Purchase Orders</h3>
        </div>
        <div class="col-md-4">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-info">
                @include('webpanel.includes.notifications')
                @foreach ($productvendors as $vendor_id => $productvendor)
                    <div class="card-body purchase-order">
                        <div class="panel-heading">
                            <div class="vendor-heading">
                                <h5> Vendor {{$productvendors[$vendor_id][0]['title']}} </h5>
                                @if($prodTotalInfo[$vendor_id]['status'])
                                <h6> Status : {{$prodTotalInfo[$vendor_id]['status']}}</h6>
                                @endif
                            </div>

                            {!! linkBtn('Export products from this PO', sysUrl('purchaseorders/export/'.$vendor_id).'?'.http_build_query(Input::except('page')), [

                            'icon' => 'fa fa-download', 'class' => 'btn btn-primary pull-right']) !!}
                        </div>
                        <div class="table-responsive">
                            <table class="table deleteArena display nowrap table-striped table-bordered vendor_table"
                                   id="poTable_{{$vendor_id}}"
                                   data-url="<?php echo route('webpanel.purchaseorders.paginate', ['id' => $vendor_id]); ?>">
                                <thead>
                                <tr>
                                    <th>UPC</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-footer">
                            <nav id="paginationWrapper"></nav>
                        </div>
                        <div class="total-content">

                            <h5>Total quantity purchase : {{$prodTotalInfo[$vendor_id]['total_quantity']}}</h5>
                            <h5>Total amount : {{$prodTotalInfo[$vendor_id]['total_amount']}}</h5>

                        </div>
                    </div>
                @endforeach
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

        function datatableLoad(id, url) {
            $('#' + id).dataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [[50, 100, -1], [50, 100, "All"]],
                ajax: url,
                responsive: true,
                columns: [
                    {data: 'UPC', name: 'UPC', orderable: true, searchable: true},
                    {data: 'Qty', name: 'Qty', orderable: true, searchable: true},
                    {data: 'Amount', name: 'Amount', orderable: true, searchable: true},
                ]
            });

            $('.dataTables_wrapper .dataTables_filter input').attr('placeholder', 'Search Title...');
        }

        $(document).ready(function () {
            $('.vendor_table').each(function (i, val) {
                url = $(this).data('url');
                id = $(this).attr('id');
                datatableLoad(id, url);
            });
        });

    </script>
@stop
