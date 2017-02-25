@extends('layouts.app')

@section('css')
    @parent
    {{ Html::style('bower_components/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}
@endsection

@section('js')
    @parent
    {{ Html::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyAvCSKMKzElwzRaHpcURMmS6J4z4qGP0ZM&libraries=places') }}
    {{ Html::script('bower_components/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
    {{ Html::script('bower_components/ckeditor/ckeditor.js') }}
    {{ Html::script('http://opoloo.github.io/jquery_upload_preview/assets/js/jquery.uploadPreview.min.js') }}
    {{ Html::script('js/campaign.js') }}
    {{ Html::script('bower_components/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}
    {{ Html::script('bower_components/jquery-validation/dist/jquery.validate.min.js') }}
    {{ Html::script('bower_components/jquery-validation/dist/additional-methods.js') }}
    {{ Html::script('js/event.js') }}
    <script type="text/javascript">
        $(document).ready(function () {

            var campaign = new Campaign(
                '{!! action('CampaignController@uploadImage').'?_token='.csrf_token() !!}',
                '{!! $validateMessage !!}'
            );
            campaign.init();
        });
    </script>
@stop

@section('content')
    <div id="page-content">
        <div class="row">
            <div class="col-md-12 center-panel">
                <div class="block">
                    <div class="block-title themed-background-dark">
                        <h2 class="block-title-light campaign-title">{{ trans('event.create') }}</h2>
                    </div>

                    <div class="panel-body">
                        @if (Session::has('message'))
                            <div class="col-lg-12">
                                <div class="col-lg-10 col-lg-offset-1 alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {!! Session::get('message') !!}
                                </div>
                            </div>
                        @endif

                        <div class="campaign">
                            {!! Form::open(['action' => 'EventController@store', 'method' => 'POST', 'id' => 'create-campaign', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}

                            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} add-images">
                                @include('layouts.images_append')
                            </div>
                            <div data-add-image-layouts="{{ $add_image_layouts }}">
                                {!! Form::button(trans('event.add_image'), ['class' => 'btn btn-primary add-image']) !!}
                            </div>

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title" class="col-md-3 control-label">{{ trans('event.title') }}</label>

                                <div class="col-md-8">
                                    {!! Form::text('title', old('title'), ['class' => 'form-control']) !!}

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="description" class="col-md-3 control-label">{{ trans('event.description') }}</label>
                                <br>
                                <div class="col-lg-10 col-lg-offset-1">
                                    {!! Form::hidden('campaign_id', $campaign_id) !!}
                                    {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'id' => 'editor', 'rows' => '10']) !!}
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <h3>{{ trans('event.schedules') }}</h3>
                            <div class="list-schedules">
                                @include('layouts.schedule')
                            </div>

                            <div data-add-schedule-layouts="{{ $add_schedule_layouts }}">
                                {!! Form::button(trans('event.add_schedule'), ['class' => 'btn btn-primary add-schedule']) !!}
                            </div>

                            <br><br>
                            <div class="form-group">
                                <div class="col-md-2 col-md-offset-1">
                                    <button type="submit" class="btn btn-raised btn-success">
                                        {{ trans('event.create') }}
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
