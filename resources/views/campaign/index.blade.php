@extends('layouts.app')

@section('js')
    @parent
    {{ Html::script('js/follow_user.js') }}
    <script type="text/javascript">
        $(document).ready(function () {
            var follow = new Follow(
                    '{{ action('FollowController@followOrUnFollowUser') }}',
                    '{{ trans('user.follow') }}',
                    '{{ trans('user.un_follow') }}'
            );
            follow.init();
        });
    </script>
@stop

@section('content')
    <div id="page-content">
        <div class="hide" data-route-filter="{{ url('') }}" data-token="{{ csrf_token() }}"></div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <div class="input-group-btn search-panel">
                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                            <span id="search_concept"><b>{{ trans('campaign.filter_by') }}</b></span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a class="btn-filter-hotest">{{ trans('campaign.hotest') }}</a>
                            </li>
                            <li>
                                <a class="btn-filter-newest">{{ trans('campaign.newest') }}</a>
                            </li>
                            <li>
                                <a class="btn-filter-oldest">{{ trans('campaign.oldest') }}</a>
                            </li>
                            <li>
                                <a class="btn-filter-open">{{ trans('campaign.open') }}</a>
                            </li>
                            <li>
                                <a class="btn-filter-closed">{{ trans('campaign.closed') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8 center-panel list-campaign">
                @include('campaign.list_campaign_layouts', ['campaign' => $campaigns])
            </div>

            <div class="col-md-4 right-panel">
                <div class="block">
                    <div class="widget">
                        <div class="block-title themed-background-dark">
                            <h4 class="widget-content-light">
                                <strong>{{ trans('user.top_user') }}</strong>
                            </h4>
                        </div>
                        <div class="widget-extra active-user">
                            <ul class="active-user-list">
                                @foreach ($users as $user)
                                    <li class="active-user-item">
                                        <div class="row">
                                            <div class="col-md-4 avatar ">
                                                <a href="{{ action('UserController@show', ['id' => $user->id]) }}">
                                                    <img src="{{ $user->avatar }}" alt="avatar"
                                                         class="img-responsive img-circle">
                                                </a>
                                            </div>
                                            <div class="col-md-8 active-user-item-info">
                                                <a href="{{ action('UserController@show', ['id' => $user->id]) }}"
                                                   class="active-user-name">
                                                    {{ $user->name }}
                                                </a>
                                                <ul class="active-user-social">
                                                    <li class="campaign">
                                                        <p class="title">{{ trans('user.stars') }}</p>
                                                        <p class="number">{{ $user->star }}</p>
                                                    </li>
                                                    <li class="followers">
                                                        <p class="title">{{ trans('user.followers') }}</p>
                                                        <p class="number">{{ $user->followers($user->id) }}</p>
                                                    </li>
                                                    <li class="">
                                                        @if (auth()->id() != $user->id)
                                                            @if (Auth::guest())
                                                                <a class="btn active btn-default"
                                                                   href="{{ url('/login') }}"><i class="fa fa-users"></i> Follow</a>
                                                            @else
                                                                <div data-user-id="{{ $user->id }}">
                                                                    @if (Auth()->user()->checkFollow($user->id))
                                                                        {!! Form::button('<i class="fa fa-users"></i> ' . trans('user.un_follow'), ['class' => 'btn btn-raised btn-success follow' ]) !!}
                                                                    @else
                                                                        {!! Form::button('<i class="fa fa-users"></i> ' . trans('user.follow'), ['class' => 'btn active btn-default follow' ]) !!}
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
