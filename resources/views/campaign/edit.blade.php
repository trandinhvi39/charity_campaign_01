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
                        <h2 class="block-title-light campaign-title">{{ trans('campaign.edit') }}</h2>
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
                            {!! Form::open(['action' => ['CampaignController@update', $campaign->id], 'method' => 'PUT', 'id' => 'create-campaign', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}

                            <div class="col-lg-10 col-lg-offset-1">
                                <label for="image-upload" id="image-label">Old image</label>
                                <div class="row push">
                                    <div class="col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
                                        <a href="{{ $campaign->image->image }}" data-toggle="lightbox-image">
                                            <img src="{{ $campaign->image->image }}" alt="image">
                                        </a>
                                    </div>
                                </div>
                                <div id="image-preview">
                                    <label for="image-upload" id="image-label">{{ trans('campaign.image') }}</label>
                                    {{ Form::file('image', ['class' => 'form-control', 'id' => 'image-upload']) }}
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">{{ trans('campaign.name') }}</label>

                                <div class="col-md-8">
                                    {!! Form::text('name', $campaign->name, ['class' => 'form-control']) !!}

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="contribution">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-3 control-label">{{ trans('campaign.categories') }}</label>

                                    <div class="col-md-8 category">
                                        @foreach ($campaign->categories as $category)
                                            <div class="category-content">
                                                <div class="col-md-6 ">
                                                    {!! Form::hidden('category_id[]', $category->id) !!}
                                                    {!! Form::text('contribution_type[]', $category->name, ['class' => 'form-control category-name', 'placeholder' => trans('campaign.validate.contribution_type.contribution')] ) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    {!! Form::number('goal[]', $category->goal, ['class' => 'form-control category-goal', 'placeholder' => trans('campaign.validate.goal.goal'), 'min' => 1]) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    {!! Form::text('unit[]', $category->unit, ['class' => 'form-control category-unit', 'placeholder' => trans('campaign.validate.unit.unit')]) !!}
                                                </div>
                                            </div>
                                            <div>
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
                                <label for="start_date" class="col-md-3 control-label">{{ trans('campaign.start_date') }}</label>

                                <div class="col-md-8">
                                    {!! Form::text('start_date', Carbon\Carbon::parse($campaign->start_time)->format('Y/m/d'), ['class' => 'form-control datetimepicker', 'placeholder' => trans('campaign.validate.start_date.start_date') ]) !!}

                                    @if ($errors->has('start_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('start_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
                                <label for="end_date" class="col-md-3 control-label">{{ trans('campaign.end_date') }}</label>

                                <div class="col-md-8">
                                    {!! Form::text('end_date', Carbon\Carbon::parse($campaign->end_time)->format('Y/m/d'), ['class' => 'form-control datetimepicker', 'placeholder' => trans('campaign.validate.end_date.end_date')]) !!}

                                    @if ($errors->has('end_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('end_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                <label for="address" class="col-md-3 control-label">{{ trans('campaign.address') }}</label>

                                <div class="col-md-8">
                                    {!! Form::text('address',  $campaign->address, ['class' => 'form-control', 'id' => 'location']) !!}
                                    {!! Form::hidden('lattitude', '', ['id' => 'lat']) !!}
                                    {!! Form::hidden('longitude', '', ['id' => 'lng']) !!}
                                    {!! Form::hidden('id', $campaign->id) !!}

                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="description" class="col-md-3 control-label">{{ trans('campaign.description') }}</label>
                                <br>
                                <div class="col-lg-10 col-lg-offset-1">
                                    {!! Form::textarea('description',  $campaign->description, ['class' => 'form-control', 'id' => 'editor', 'rows' => '10']) !!}
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2 col-md-offset-1">
                                    <button type="submit" class="btn btn-raised btn-primary">
                                        {{ trans('campaign.save') }}
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
