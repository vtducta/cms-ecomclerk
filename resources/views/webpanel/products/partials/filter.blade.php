
<div >
        <form class="form-material row" method="get" id="filterForm">
            <div class="form-group col-md-3">
                <lablel>Gross Profit FBA (min)</lablel>
                <input type="text" name="min" id="min" class="form-control form-control-line" value="{{ Input::get('min') }}">
            </div>

            <div class="form-group col-md-3">
                <lablel>Gross Profit FBA (max)</lablel>
                <input type="text" id="max" name="max" class="form-control form-control-line" value="{{ Input::get('max') }}">
            </div>

            <div class="form-group col-md-2">
                <lablel>Gross ROI (min)</lablel>
                <input type="text" id="gross_min" name="gross_min" class="form-control form-control-line" value="{{ Input::get('gross_min') }}">
            </div>

            <div class="form-group col-md-2">
                <lablel>Gross ROI (max)</lablel>
                <input type="text" id="gross_max" name="gross_max" class="form-control form-control-line" value="{{ Input::get('gross_max') }}">
            </div>

            <div class="form-group col-md-2">
                <br/>
                {!! btn('Filter', ['icon' => 'fa fa-search']) !!}
                {!! linkBtn('Reset', sysRoute('products.index'), ['class' => 'btn btn-sm btn-danger', 'icon' => 'fa fa-reset']) !!}
            </div>

        </form>
</div>