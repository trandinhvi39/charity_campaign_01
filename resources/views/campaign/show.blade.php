@extends('layouts.app')

@section('css')
    @parent
    {{ Html::style('bower_components/bootstrap-star-rating/css/star-rating.css') }}
    {{ Html::style('bower_components/bootstrap-star-rating/css/theme-krajee-fa.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/css/bootstrap-dialog.min.css') }}
@stop

@section('js')
    @parent

    {{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/js/bootstrap-dialog.min.js') }}
    {{ Html::script('bower_components/bootstrap-star-rating/js/star-rating.js') }}
    {{ Html::script('js/comment.js') }}
    {{ Html::script('js/rating.js') }}
    {{ Html::script('js/contribute.js') }}
    {{ Html::script('http://maps.google.com/maps/api/js?key=AIzaSyDluWcImjhXgQDLQcDvGi3Glu1TOYG6oew') }}
    {{ Html::script('js/helpers/gmaps.min.js') }}
    {{ Html::script('https://cdn.socket.io/socket.io-1.3.4.js') }}
    {{ Html::script('js/chat.js') }}
    {{ Html::script('js/comment_socket.js') }}
    {{ Html::script('js/contributions_socket.js') }}

    <script type="text/javascript">
        $(document).ready(function () {
            Dashboard.init();

            var comment = new Comment('{{ action('CommentController@store') }}',
                    '{{ config('path.to_avatar_default') }}',
                    '{{ action('CampaignController@joinOrLeaveCampaign') }}',
                    '{{ trans('campaign.request_sent') }}',
                    '{{ trans('campaign.request_join') }}'
            );
            comment.init();

            var rating = new Rating(
                    '{{ action('RatingController@ratingCampaign') }}',
                    '{{ trans('campaign.must_join_campaign') }}',
                    '{{ trans('campaign.close') }}',
                    '{{ $averageRanking['average'] }}',
                    '{{ action('RatingController@ratingUser') }}',
                    '{{ trans('campaign.must_login') }}',
                    '{{ $averageRankingUser['average'] }}',
                    {!! $ratingChart !!},
                    '{{ trans('campaign.star') }}',
                    '{{ config('constants.ONE_STAR') }}',
                    '{{ config('constants.TWO_STAR') }}',
                    '{{ config('constants.THREE_STAR') }}',
                    '{{ config('constants.FOUR_STAR') }}',
                    '{{ config('constants.FIVE_STAR') }}',
                    '{{ trans('campaign.rating') }}',
                    '{{ trans('user.rating_your_self') }}'
            );
            rating.init();

            var contribute = new Contribute('{{ action('ContributionController@store') }}');
            contribute.init();
        });
    </script>
@stop

@section('content')
    <div id="page-content">
        <div class="hide-comment" data-campaign-id="{{ $campaign->id }}"
            data-host="{{ config('app.key_program.socket_host') }}"
            data-port="{{ config('app.key_program.socket_port') }}">
        </div>
        <div class="hide" data-token="{{ csrf_token() }}"></div>
        <div class="message-note" data-message-note="{{ trans('campaign.message.note') }}"></div>
        <div class="row">
            <div class="col-md-8 center-panel">
                <div class="block">
                    <div class="block-title themed-background-dark">
                        <h2 class="block-title-light campaign-title header-campaign-name">
                            <strong>
                            @if (!$campaign->status)
                                <span class="closed"> [{{ trans('campaign.closed') }}] </span>
                            @endif
                            {{{ $campaign->name }}}
                            <br>
                            @foreach ($campaign->getTags() as $tag)
                                <a href="{{ URL::action('CampaignController@campaignWithTags', $tag) }}" class="label label-default">{{ $tag }}</a>
                            @endforeach
                            </strong>
                            <br>
                            @if (!$campaign->note)
                                @if (auth()->check() && $campaign->status  && ($campaign->owner->user_id == auth()->id()))
                                    <a data-toggle="modal" data-target="#createNote" class="label label-primary btn-note"><i class="glyphicon glyphicon-plus"></i> {{ trans('campaign.btn_create_note') }}</a>
                                @endif
                            @else
                                @if (auth()->check() && $campaign->checkMemberOfCampaignByUserId(auth()->id()))
                                    <a data-toggle="modal" data-target="#editNote" class="label label-primary btn-note"><i class="glyphicon glyphicon-pencil"></i>  {{ trans('campaign.btn_edit_note') }}</a>
                                @endif
                            @endif
                        </h2>
                    </div>

                    <!-- Create Note Modal -->
                    <div class="modal fade" id="createNote" role="dialog">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{ trans('campaign.create_note') }}</h4>
                                </div>
                                {!! Form::open(['action' => 'NoteController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'create-note-campaign']) !!}
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <p class="closed create-message-note"></p>
                                            </div>
                                            <div class="col-md-12">
                                             {!! Form::textarea('content', old('content'), ['class' => 'form-control  create-content-note', 'placeholder' => trans('campaign.placeholder.note')]) !!}
                                                @if ($errors->has('content'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('content') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            {!! Form::hidden('campaign_id', $campaign->id) !!}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-raised btn-primary create-save-note">
                                            {{ trans('campaign.save') }}
                                        </button>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>

                    @if ($campaign->note)
                        <!-- Edit Note Modal -->
                        <div class="modal fade" id="editNote" role="dialog">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ trans('campaign.edit_note') }}</h4>
                                        @if ($campaign->note->editUser)
                                            <i>{{ trans('campaign.latest_update') }}: {{ $campaign->note->updated_at->diffForHumans() }}
                                            <br>
                                            {{ trans('campaign.by') }} {{ $campaign->note->editUser->name }}</i>
                                        @endif
                                    </div>
                                    {!! Form::open(['action' => ['NoteController@update', $campaign->note->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'edit-note-campaign']) !!}
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <p class="closed edit-message-note"></p>
                                                </div>
                                                <div class="col-md-12">
                                                 {!! Form::textarea('content', $campaign->note->content, ['class' => 'form-control edit-content-note', 'placeholder' => trans('campaign.placeholder.note')]) !!}
                                                    @if ($errors->has('content'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('content') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                {!! Form::hidden('campaign_id', $campaign->id) !!}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-raised btn-primary edit-save-note">
                                                {{ trans('campaign.save') }}
                                            </button>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="block-content-full">
                        @if (auth()->check() && $campaignChat->status  && ($campaignChat->owner->user_id == auth()->id()
                            || $campaignChat->checkMemberOfCampaignByUserId(auth()->id())))
                            @if ($campaign->note)
                                <div class="timeline show-note" data-toggle="tooltip" data-placement="bottom" title="{{ $campaign->note->content }}">
                                    <p><b class="note">{{ trans('campaign.note') }}: </b>{{ $campaign->note->content }}
                                    <br>
                                    @if ($campaign->note->editUser)
                                        <i>{{ trans('campaign.latest_update') }}: {{ $campaign->note->updated_at->diffForHumans() }}
                                        {{ trans('campaign.by') }} {{ $campaign->note->editUser->name }}</i>
                                    @else
                                        <i>{{ trans('campaign.created') }}: {{ $campaign->note->created_at->diffForHumans() }}
                                        {{ trans('campaign.by') }} {{ $campaign->note->creatorUser->name }}</i>
                                    @endif
                                    </p>
                                </div>
                            @endif
                        @endif
                        <div class="timeline">
                            <ul class="timeline-list">
                                <li class="active">
                                    <div class="timeline-icon"><i class="gi gi-calendar"></i></div>
                                    <div class="timeline-time">
                                        <small>{{ trans('campaign.start_date') }}</small>
                                    </div>
                                    <div class="timeline-content">
                                        <p class="push-bit"><strong>{{{ date('Y-m-d', strtotime($campaign->start_time)) }}}</strong></p>
                                        <div class="row push">
                                            <div class="col-sm-8 col-md-8">
                                                <a href="{{ $campaign->image->image }}" data-toggle="lightbox-image">
                                                    <img src="{{ $campaign->image->image }}" alt="image">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="active">
                                    <div class="timeline-icon"><i class="fa fa-smile-o"></i></div>
                                    <div class="timeline-time">
                                        <small>{{{ trans('campaign.author') }}}</small>
                                    </div>
                                    <div class="timeline-content">
                                        <p class="push-bit">
                                            <a href="{{ action('UserController@show', ['id' => $campaign->owner->user->id]) }}"><strong>{{ $campaign->owner->user->name }}</strong></a>
                                        </p>
                                        <div class="row push">
                                            <div class="col-sm-6 col-md-6 profile_thumb">
                                                <a href="{{ action('UserController@show', ['id' => $campaign->owner->user->id]) }}">
                                                    <img src="{{ $campaign->owner->user->avatar }}"
                                                         class="img-responsive img-circle" alt="image">
                                                </a>
                                                @if (Auth::user() && auth()->id() != $campaign->owner->user_id)
                                                    {!! Form::hidden('target_id', $campaign->owner->user->id, ['id' => 'target_id']) !!}
                                                    <input id="allow-rating-user" name="input-1"
                                                        class="rating rating-loading" data-min="0" data-max="5"
                                                        data-step="1" data-size="xs">
                                                @elseif (Auth::user() && auth()->id() == $campaign->owner->user_id)
                                                    <input id="not-allow-rating-user-myself" name="input-1"
                                                           class="rating rating-loading" data-min="0" data-max="5"
                                                           data-step="1" data-size="xs">
                                                @else
                                                    <input id="not-allow-rating-user" name="input-1"
                                                        class="rating rating-loading" data-min="0" data-max="5"
                                                        data-step="1" data-size="xs">
                                                @endif
                                                <div class="reviews-stats"> {{ trans('campaign.total') }}
                                                    <span class="glyphicon glyphicon-user"></span>
                                                    <span class="reviews-num-user">{{ $averageRankingUser['amount'] }}</span>
                                                    <a href=".list-user-rating" data-toggle="modal" data-target=".list-user-rating">{{ trans('campaign.users') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="active">
                                    <div class="timeline-icon"><i class="fa fa-map-marker"></i></div>
                                    <div class="timeline-time">
                                        <small>{{ trans('campaign.address') }}</small>
                                    </div>
                                    <div class="timeline-content">
                                        <p class="push-bit"><strong>{{{ $campaign->address }}}</strong>
                                        </p>
                                        <div id="gmap-timeline-ID" class="gmap gmap-timeline"
                                             data-lat="{{ $campaign->lat }}" data-lng="{{ $campaign->lng }}"
                                             data-address="{{ $campaign->address }}"></div>
                                    </div>
                                </li>
                                <li class="active">
                                    <div class="timeline-icon"><i class="fa fa-user"></i></div>
                                    <div class="timeline-time">
                                        <small>{{ trans('campaign.members') }}</small>
                                    </div>
                                    <div class="timeline-content">
                                        <span class="push-bit">
                                            <a href=".list-members" data-toggle="modal" data-target=".list-members">{{ trans('campaign.members') }}</a>
                                            @foreach ($members as $member)
                                                <a href="{{ action('UserController@show', ['id' => $member->user->id]) }}">
                                                    <img class="img-member img-circle" src="{{ $member->user->avatar }}">
                                                </a>
                                            @endforeach

                                        </span>

                                    </div>
                                </li>
                                <li class="active">
                                    <div class="timeline-icon"><i class="gi gi-suitcase"></i></div>
                                    <div class="timeline-time">
                                        <small>{{ trans('campaign.description') }}</small>
                                    </div>
                                    <div class="timeline-content">
                                        <p class="push-bit"><strong>{{ trans('campaign.description') }}</strong></p>
                                        <p class="push-bit">{!! $campaign->description !!}</p>
                                    </div>
                                </li>
                                <li class="active">
                                    <div class="timeline-icon"><i class="gi gi-calendar"></i></div>
                                    <div class="timeline-time">
                                        <small>{{ trans('campaign.end_date') }}</small>
                                    </div>
                                    <div class="timeline-content">
                                        <p class="push-bit"><strong>{{{ date('Y-m-d', strtotime($campaign->end_time)) }}}</strong></p>
                                        <!-- <p>
                                            <span>{{ trans('campaign.message_end_campaign', ['time' => Carbon\Carbon::now()->addSeconds(strtotime($campaign->end_time) - time())->diffForHumans()]) }}</span>
                                        </p> -->
                                    </div>
                                </li>
                            </ul>
                        </div>
                         <div class="timeline-controls">
                            <div class="timeline-controls-list">
                                <div class="timeline-controls-item">
                                    <a href="javascript:void(0)" class="comment" data-toggle="tooltip" title=""
                                       data-original-title="Comments">
                                        <i class="gi gi-comments"></i>
                                        <span class="count-comments">{{ $campaign->countComment($campaign->id) }}</span>
                                    </a>
                                </div>
                                 <div class="timeline-controls-item">
                                    <a href="javascript:void(0)" class="comment" data-toggle="tooltip" title=""
                                       data-original-title="Views">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                        <span>{{ Counter::showAndCount('campaign', $campaign->id) }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        @include('campaign.comment')
                    </div>
                </div>
            </div>

            @include('campaign.create_contribution')
            @include('campaign.list_contribution_confirmed')
            <div class="model_list_contribution_unconfirmed">
                @include('campaign.list_contribution_unconfirmed', [
                    'contributionUnConfirmed' => $contributionUnConfirmed
                ])
            </div>
            @include('layouts.members')
            @include('layouts.user_rating')

            <div class="col-md-4 right-panel">
                @if ($results)
                    <div class="block">
                        <div class="block-title themed-background-dark">
                            <h4 class="block-title-light campaign-title">
                                <strong>{{ trans('campaign.progress') }}</strong>
                            </h4>
                        </div>
                        <div class="widget-extra">
                            <div class="timeline">
                                <ul class="">
                                    @foreach ($results as $result)
                                        <li class="media event active fix-float font-size-progress-bar">
                                            <div class="pull-left">
                                            <span>
                                                <strong>{{ $result['name'] }}</strong> :
                                                <span>{{ $result['value'] . '/' . $result['goal'] }}</span>
                                                <strong>{{ $result['unit'] }}</strong>
                                            </span>
                                            </div>
                                        </li>

                                        <div class="progress">
                                            @if ($result['progress'] < 100)
                                                <div class="progress-bar progress-bar-danger progress-bar-striped  active"
                                                     role="progressbar"
                                                     aria-valuenow="{{ $result['progress'] }}"
                                                     aria-valuemin="0" aria-valuemax="100"
                                                     style="width:{{ $result['progress'] }}%">
                                                    <span class="show">{{ $result['progress'] }} %</span>
                                                </div>
                                            @else
                                                <div class="progress-bar progress-bar-success progress-bar-striped  active"
                                                     role="progressbar"
                                                     style="width:{{ round(100 / $result['progress'] * 100) }}%">
                                                    <span class="show">100%</span>
                                                </div>
                                                <div class="progress-bar progress-bar-warning progress-bar-striped  active"
                                                     style="width:{{ 100 - round(100 / $result['progress'] * 100) }}%">
                                                    <span class="show">{{ $result['progress'] - 100 }}%</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($campaign->status && $campaignChat->owner->user_id != auth()->id())
                    <div class="block">
                        <div class="block-title themed-background-dark">
                            <h4 class="block-title-light campaign-title">
                                <strong>{{ trans('campaign.action') }}</strong>
                            </h4>
                        </div>
                        <div class="widget-extra">
                            <div class="timeline">
                                <div class="request-join">
                                    @if (auth()->check())
                                        {!! Form::open(['method' => 'POST', 'id' => 'formRequest']) !!}
                                        {!! Form::hidden('campaign_id', $campaign->id) !!}
                                        @if (empty($userCampaign))
                                            {!! Form::submit(trans('campaign.request_join'), ['class' => 'btn btn-raised btn-success joinOrLeave']) !!}
                                        @elseif (empty($userCampaign->status) && empty($userCampaign->is_owner))
                                            {!! Form::submit(trans('campaign.request_sent'), ['class' => 'btn btn-raised btn-success joinOrLeave']) !!}
                                        @elseif ($userCampaign->status && empty($userCampaign->is_owner))
                                            {!! Form::submit(trans('campaign.leave_campaign'), ['class' => 'btn btn-raised btn-success joinOrLeave']) !!}
                                        @endif
                                        {!! Form::close() !!}
                                    @else
                                        <a href="{{ action('Auth\UserLoginController@getLogin') }}"
                                           class="btn btn-raised btn-success join">{{ trans('campaign.request_join') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="block contributor">
                    <div class="widget">
                        <div class="block-title themed-background-dark">
                            <h4 class="widget-content-light">
                                <strong>{{ trans('campaign.contributor') }}</strong>
                            </h4>
                        </div>

                        <div class="widget-extra active-user">
                            <div>
                                <ul class="nav nav-tabs border-tab">
                                    <li class="active"><a href="#confirmed" data-toggle="tab">{{ trans('campaign.confirmed') }}</a></li>
                                    <li><a href="#unconfirmed" data-toggle="tab">{{ trans('campaign.unconfirmed') }}</a></li>
                                </ul>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="confirmed">
                                        <ul class="active-user-list">
                                            @foreach ($contributionConfirmed->take(10) as $contribution)
                                                <li class="active-user-item">
                                                    <div class="row">
                                                        @if ($contribution->user)
                                                            <div class="col-md-4">
                                                                <a class="pull-left border-aero profile_thumb">
                                                                    <img src="{{ $contribution->user->avatar }}" alt="avatar"
                                                                         class="img-responsive img-circle">
                                                                </a>
                                                            </div>
                                                            <div class="col-md-8 active-user-item-info">
                                                                <a class="title" href="{{ action('UserController@show', ['id' => $contribution->user->id]) }}">
                                                                    {{ $contribution->user->name }}
                                                                </a><br>
                                                                <span>{{ $contribution->user->email }}</span><br>
                                                                <span>{{ Carbon\Carbon::now()->subSeconds(time() - strtotime($contribution->created_at))->diffForHumans() }}</span>
                                                            </div>
                                                        @else
                                                            <div class="col-md-4">
                                                                <a class="pull-left border-aero profile_thumb">
                                                                    <img src="{{ config('path.to_avatar_default') }}" alt="avatar"
                                                                         class="img-responsive img-circle">
                                                                </a>
                                                            </div>
                                                            <div class="col-md-8 active-user-item-info">
                                                                <span>{{ $contribution->name }}</span><br>
                                                                <span>{{ $contribution->email }}</span><br>
                                                                <span>{{ Carbon\Carbon::now()->subSeconds(time() - strtotime($contribution->created_at))->diffForHumans() }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                            <a class="pull-right" href=".list-contribute-confirmed" data-toggle="modal"
                                               data-target=".list-contribute-confirmed">{{ trans('campaign.show_detail') }}</a>
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" id="unconfirmed">
                                        <ul class="active-user-list list-contribution-unconfirm">
                                            @if ($campaign->status && $campaign->categories)
                                                @include('layouts.contributions_unconfirm', [
                                                    'contributionUnConfirmed' => $contributionUnConfirmed
                                                ])
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @if ($campaign->status && $campaign->categories)
                                <div class="contribution pull-right">
                                    {{ Form::button(trans('campaign.contribute'), [
                                        'class' => 'btn btn-raised btn-success',
                                        'data-toggle'=>'modal',
                                        'data-target'=>'.contribute'
                                    ]) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="block">
                    <div class="block-title themed-background-dark">
                        <h4 class="block-title-light campaign-title">
                            <strong>{{ trans('campaign.suggest_campaign') }}</strong>
                        </h4>
                    </div>
                    <div>
                        <ul class="nav nav-tabs border-tab">
                            <li class="active"><a href="#suggest-related" data-toggle="tab">{{ trans('campaign.related') }}</a></li>
                            <li><a href="#suggest-nearest" data-toggle="tab">{{ trans('campaign.nearest') }}</a></li>
                            <li><a href="#suggest-hotest" data-toggle="tab">{{ trans('campaign.hotest') }}</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="suggest-related">
                                @if ($relatedCampaigns)
                                   @foreach ($relatedCampaigns as $campaign)
                                        <center>
                                            @if ($campaign->image)
                                                <a href="{{ $campaign->image->image }}" data-toggle="lightbox-image">
                                                <img class="img-suggest-campaign" src="{{ $campaign->image->image }}" alt="image">
                                                </a>
                                            @endif
                                        </center>
                                        <h3><a href="{{ URL::action('CampaignController@show', $campaign->id) }}">{{ $campaign->name }}</a></h3>
                                        <i>{{ $campaign->address }}</i>
                                        <hr>
                                    @endforeach
                                @endif
                            </div>

                            <div class="tab-pane fade" id="suggest-hotest">
                                @if ($hotestCampaigns)
                                   @foreach ($hotestCampaigns as $campaign)
                                        <center>
                                            @if ($campaign->image)
                                                <a href="{{ $campaign->image->image }}" data-toggle="lightbox-image">
                                                <img class="img-suggest-campaign" src="{{ $campaign->image->image }}" alt="image">
                                                </a>
                                            @endif
                                        </center>
                                        <h3><a href="{{ URL::action('CampaignController@show', $campaign->id) }}">{{ $campaign->name }}</a></h3>
                                        <i>{{ $campaign->address }}</i>
                                        <hr>
                                    @endforeach
                                @endif
                            </div>

                            <div class="tab-pane fade" id="suggest-nearest">
                                @if ($nearestCampaigns)
                                   @foreach ($nearestCampaigns as $campaign)
                                        <center>
                                            @if ($campaign->image)
                                                <a href="{{ $campaign->image->image }}" data-toggle="lightbox-image">
                                                <img class="img-suggest-campaign" src="{{ $campaign->image->image }}" alt="image">
                                                </a>
                                            @endif
                                        </center>
                                        <h3><a href="{{ URL::action('CampaignController@show', $campaign->id) }}">{{ $campaign->name }}</a></h3>
                                        <i>{{ $campaign->address }}</i>
                                        <hr>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @if (auth()->check() && $campaignChat->status  && ($campaignChat->owner->user_id == auth()->id()
        || $campaignChat->checkMemberOfCampaignByUserId(auth()->id()))
    ))
        @include('layouts.chat')
    @endif

@endsection

