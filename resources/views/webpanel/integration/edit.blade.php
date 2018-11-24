@extends('webpanel.layouts.base')
@section('title')
    Edit Integration Settings
    @parent
@stop
@section('body')
    <div class="row page-titles">
        <div class="col-md-8">
            <h3 class="text-themecolor">{{$integration->name}} Settings</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('webpanel.includes.notifications')
                    <form class="form-material m-t-40 ajaxForm" method="put"
                          action="<?php echo route('webpanel.integrations.update', array('id' => $integration->url_key)); ?>"
                          role="form" data-result-container="#notificationArea">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">

                        @foreach ($controls as $key => $control)
                                @if ($control['type'] == 'text')
                                    <div class="form-group">
                                        <label>{{$control['label']}}</label>
                                        <input type="text" class="form-control form-control-line" name="option_name[{{$control['name']}}]" value="<?php echo (isset($values[$control['name']])) ? $values[$control['name']] : '' ?>">
                                    </div>
                                @endif

                                @if ($control['type'] == 'checkbox')
                                    <div class="form-group">
                                        <input type="checkbox" name="option_name[{{$control['name']}}]" @if(values[$control['name']]) ? checked : '' @endif class="filled-in" />
                                        <label>{{$control['label']}}</label>
                                    </div>
                                @endif
                        @endforeach

                        <div class="form-group">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-info">Save Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')

@stop
