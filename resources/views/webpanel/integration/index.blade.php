@extends('webpanel.layouts.base')
@section('title')
    Integrations
    @parent
@stop
@section('body')
    <div class="row page-titles">
        <div class="col-md-8">
            <h3 class="text-themecolor">Integrate New Channel In Your Account</h3>
        </div>
    </div>

    <div class="row">
        @foreach ($integrations as $integration)
        <div class="col-md-6 col-lg-6 col-xlg-4">
            <div class="card card-body">
                <div class="row">
                    <div class="col-md-4 col-lg-3 text-center">
                        <img src="{{$integration->image}}" alt="user" class="img-circle img-responsive">
                    </div>
                    <div class="col-md-8 col-lg-9">
                        <h3 class="box-title m-b-0">{{$integration->name}}</h3>
                        <br/>
                        <a class="btn waves-effect waves-light btn-info" href="{{ URL::to('integrations/' . $integration->url_key)  }}/edit ">Add This Integration</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
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
@stop

