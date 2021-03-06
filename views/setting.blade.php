{{ XeFrontend::css(app('xe.plugin.ga')->assetPath() . '/skin.css')->load() }}

@section('page_title')
    <h2>Google Analytics</h2>
@endsection

@section('page_description')
    setting for tracking & widget
@endsection


<form method="post" action="{{ route('manage.google_analytics.update') }}" enctype="multipart/form-data" data-rule="analyticsSetting">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ xe_trans('ga:projectName') }}</label>
                <input type="text" class="form-control" name="projectName" value="{{ $setting->get('projectName') ?: Input::old('projectName') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ xe_trans('ga:accountEmail') }}</label>
                <input type="email" class="form-control" name="accountEmail" value="{{ $setting->get('accountEmail') ?: Input::old('accountEmail') }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ xe_trans('ga:clientId') }}</label>
                <input type="text" class="form-control" name="clientId" value="{{ $setting->get('clientId') ?: Input::old('clientId') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ xe_trans('ga:profileId') }}</label>
                <input type="text" class="form-control" name="profileId" value="{{ $setting->get('profileId') ?: Input::old('profileId') }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group" style="position: relative;">
                <label>{{ xe_trans('ga:keyFile') }} (.p12)</label>
                <div class="{{ $setting->getKeyFile() ? 'collapse' : '' }}" id="__xe_file_input">
                    <input type="file" class="form-control" name="keyFile">
                </div>
                @if($setting->getKeyFile())
                <div class="collapse in info-collapse" id="__xe_file_info">
                    <input type="text" class="form-control" readonly value="{{ $setting->getKeyFile()->clientname }}">
                    <span id="__xe_btn_remove_key_file" class="close">
                        <i class="glyphicon glyphicon-remove"></i>
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ xe_trans('ga:trackingId') }}</label>
                <input type="text" class="form-control" name="trackingId" value="{{ $setting->get('trackingId') ?: Input::old('trackingId') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ xe_trans('ga:trackingDomain') }} ({{ xe_trans('ga:optional') }})</label>
                <input type="text" class="form-control" name="domain" value="{{ $setting->get('domain') ?: Input::old('domain') }}">
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ xe_trans('ga:save') }}</button>
</form>

<script type="text/javascript">
    $(function () {
        $('#__xe_btn_remove_key_file').click(function (e) {
            e.preventDefault();

            $('#__xe_file_info').collapse('hide');
            $('#__xe_file_input').collapse('show');
        });
    });
</script>
