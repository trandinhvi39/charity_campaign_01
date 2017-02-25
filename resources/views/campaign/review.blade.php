@if (Auth()->user())
<div class="modal-dialog">
    <div class="modal-content">
        <div id="donate_hidden">
            <div class="thimpress_donate_form">
                <h2 class="text-center">{{ $campaign->name }}</h2>
                <p class="text-center">{!! str_limit($campaign->description, config('constants.LIMIT_DESCRIPTION_CHARACTERS')) !!}</p>
                <div class="entry-meta well ">
                    <div class="date col-xs-2">
                        <div class="day">{{ date('d', strtotime($campaign->start_time)) }}</div>
                        <div class="month">{{ date('M', strtotime($campaign->start_time)) }}</div>
                        <div class="year">{{ date('Y', strtotime($campaign->start_time)) }}</div>
                    </div>
                    <div class="metas col-xs-10">
                        <div class="time"><i class="fa fa-clock-o"></i> {{ date('H:i', strtotime($campaign->start_time)) }} - {{ date('H:i', strtotime($campaign->end_time)) . '  (' . date('d/m/Y', strtotime($campaign->end_time)) . ')' }}</div>
                        <div class="location"><i class="fa fa-map-marker"></i> {{ $campaign->address }}</div>
                    </div>
                </div>
                <div>
                    <div class="notify"></div>
                    <div class="campaign-id" data-campaign-id="{{ $campaign->id }}"></div>
                    {!! Form::open(['method' => 'POST', 'action' =>'ContributionController@store', 'class' => 'donate_form form-horizontal', 'id' => 'form-contribute' ]) !!}
                    @foreach ($campaign->categories as $category)
                    <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                        <div class="col-md-11 col-md-offset-1">
                            <div class="col-md-3">
                                <span>{{ $category->name }}</span>
                            </div>
                            <div class="col-md-9 category">
                                <div class="input-group">
                                    <div class="col-md-9">
                                        {!! Form::number('amount[' . $category->id . ']', '', ['class' => 'form-control', 'placeholder' => trans('campaign.amount'), 'min' => 0]) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <p class="pull-right"><span>{{ $category->unit }}</span></p>
                                    </div>
                                    @if ($errors->has('amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                        <div class="col-md-10 col-md-offset-1">
                            {!! Form::textarea('description', null, ['rows' => 4, 'class' => 'form-control', 'placeholder' => trans('campaign.description')]) !!}
                            @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="donate_form_footer center">
                        {!! Form::submit(trans('index.join-now'), ['class' => 'donate_submit button payment', "id" => "btn-contribute"]) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ trans('index.sory-not-login') }}</h4>
        </div>
        <div class="modal-body">
            {{ trans('index.notlogin') }} <a href="{{ route('get_login') }}">{{ trans('index.login') }}</a>
        </div>
    </div>
</div>
@endif
