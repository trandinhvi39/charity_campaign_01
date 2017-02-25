@extends('layouts.master')

@section('content')

<div class="home-page site-content">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" role="listbox">
            @foreach ($campaigns as $element)
            <div class="item">
                <div class="slotholder">
                    <img src="{{ $element->image->image }}" alt="" class="tp-bgimg defaultimg">
                </div>
                <div class="slide_wrap box">
                    <div class="tp-loop-wrap">
                        <div class="tp-mask-wrap">
                            <div class="tp-caption tp-shape tp-shapewrapper tp-layer-selectable"></div>
                        </div>
                    </div>
                </div>
                <div class="slide_wrap box__content">
                    <div class="tp-loop-wrap">
                        <div class="tp-mask-wrap">
                            <div class="tp-caption tp-layer-selectable" >
                                <div class="donate-campaign">
                                    <div class="title-box">{{ trans('index.campaigns') }}</div>
                                    <div class="entry-header">
                                        <h2 class="blog_title">
                                            <a href="{{ action('CampaignController@show', $element->id) }}">{{ $element->name }}</a>
                                        </h2>
                                    </div>
                                    <div class="entry-content">
                                        <a href="{{ action('CampaignController@show', $element->id) }}" title="" class="description">{!! str_limit($element->description, config('constants.LIMIT_DESCRIPTION_CHARACTERS')) !!}</a>
                                    </div>
                                    <div class="circle" id="circles-309">
                                        <div class="circles-wrp">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="166" height="166">
                                                <path fill="transparent" stroke="#FFF" stroke-width="10" d="M 82.98411349011178 5.000001617828204 A 78 78 0 1 1 82.89165956483042 5.000075241381751 Z" class="circles-maxValueStroke"></path>
                                            </svg>
                                            <div class="circles-text">
                                                <div class="text-inner">{{ date('d', strtotime($element->start_time)) }} <br> {{ date('M', strtotime($element->start_time)) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="campaign_thumbnail_overlay">
                                        <a href="{{ action('CampaignController@show', $element->id) }}" class="donate_load_form">{{ trans('index.read-more') }}</a>
                                    </div>
                                    <div class="campaign_thumbnail_overlay">
                                        <a data-toggle="modal"  href='#modal-donate' class="donate_load_form" data-campaign-id="{{ $element->id }}">{{ trans('index.join-now') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div id="slide" >
            <div href="#myCarousel" role="button" data-slide="prev" class="tp-leftarrow tparrows zeus noSwipe">
                <div class="tp-title-wrap">
                    <div class="tp-arr-imgholder"></div>
                </div>
            </div>
            <div href="#myCarousel" role="button" data-slide="next" class="tp-rightarrow tparrows zeus noSwipe">
                <div class="tp-title-wrap">
                    <div class="tp-arr-imgholder"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-grid">
        <div class="siteorigin-panels-stretch panel-row-style">
            <div class="panel-grid-cell">
                <div class="so-panel widget_heading panel-first-child">
                    <div class="thim-widget-heading thim-widget-heading-base">
                        <div class="thim-heading text-center">
                            <div class="sc-heading article_heading">
                                <h3 class="wrapper-line-heading">
                                    <span>{{ trans('index.campaigns') }}</span>
                                </h3>
                                <p class="heading__secondary">{{ trans('index.preview-campaigns') }}</p>
                                <span class="line-heading"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="so-panel widget_campaign">
                    <div class="thim-widget-campaign thim-widget-campaign-base">
                        <div class="thim-campaign template-default">
                            <div class="campaigns archive-content row">
                                @foreach ($campaigns as $value)
                                <article class="col-xs-6 col-md-4 post-315 dn_campaign type-dn_campaign status-publish has-post-thumbnail hentry dn_campaign_cat-community-charities dn_campaign_cat-environmental-charities dn_campaign_cat-international-charities dn_campaign_tag-events dn_campaign_tag-winter">
                                    <div class="content-inner">
                                        <div class="entry-thumbnail">
                                            <div class="thumbnail">
                                                <a href="{{ action('CampaignController@show', $value->id) }}">
                                                    <img src="{{ $value->image->image }}" alt="{{ $value->name }}" title="{{ $value->name }}">
                                                </a>
                                            </div>
                                            <a data-toggle="modal" href='#modal-donate' class="donate_load_form thim-button style3" data-campaign-id="{{ $value->id }}">{{ trans('index.join-now') }}</a>
                                        </div>
                                        <div class="event-content">
                                            <div class="entry-header">
                                                <h2 class="blog_title">
                                                <a href="{{ action('CampaignController@show', $value->id) }}">{!! str_limit($value->name, config('constants.LIMIT_TITLE_CHARACTERS')) !!}</a>
                                                </h2>
                                            </div>
                                            <div class="entry-content">
                                                <p>{!! str_limit($value->description, config('constants.LIMIT_DESCRIPTION_CHARACTERS')) !!}</p>
                                            </div>
                                            <div class="entry-meta">
                                                <div class="date col-xs-2">
                                                    <div class="day">{{ date('d', strtotime($value->start_time)) }}</div>
                                                    <div class="month">{{ date('M', strtotime($value->start_time)) }}</div>
                                                    <div class="year">{{ date('Y', strtotime($value->start_time)) }}</div>
                                                </div>
                                                <div class="metas col-xs-10">
                                                    <div class="time"><i class="fa fa-clock-o"></i> {{ date('H:i', strtotime($value->start_time)) }} - {{ date('H:i', strtotime($value->end_time)) . '  (' . date('d/m/Y', strtotime($value->end_time)) . ')' }}</div>
                                                    <div class="location"><i class="fa fa-map-marker"></i>{{ $value->address }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="so-panel widget_button panel-last-child">
                    <div class="panel-widget-style view_all">
                        <div class="thim-widget-button thim-widget-button-base">
                            <div  class="text-center">
                                <a href="{{ action('CampaignController@showCampaigns') }}" class="thim-button default inner size-default">{{ trans('index.view-all') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-grid" id="pg-5008-3">
        <div class="siteorigin-panels-stretch panel-row-style number_total">
            <div class="panel-grid-cell col-xs-12 col-sm-3">
                <div class="so-panel widget_counters-box panel-first-child panel-last-child">
                    <div class="thim-widget-counters-box thim-widget-counters-box-base">
                        <div class="counter-box has-padding line-in-bottom visible full-visible">
                            <div class="content-box-percentage">
                                <div class="counter-number">
                                    <span class="display-percentage">{{ $countUsers }}</span>
                                </div>
                                <div class="counter-label">
                                    <span class="counter-box-content">{{ trans('index.member') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-grid-cell col-xs-12 col-sm-3">
                <div class="so-panel widget_counters-box panel-first-child panel-last-child">
                    <div class="thim-widget-counters-box thim-widget-counters-box-base">
                        <div class="counter-box has-padding line-in-bottom visible full-visible">
                            <div class="content-box-percentage">
                                <div class="counter-number">
                                    <span class="display-percentage">{{ $countCampaigns }}</span>
                                </div>
                                <div class="counter-label">
                                    <span class="counter-box-content">{{ trans('index.campaigns') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-grid-cell col-xs-12 col-sm-3">
                <div class="so-panel widget_counters-box panel-first-child panel-last-child">
                    <div class="thim-widget-counters-box thim-widget-counters-box-base">
                        <div class="counter-box has-padding line-in-bottom visible full-visible">
                            <div class="content-box-percentage">
                                <div class="counter-number">
                                    <span class="display-percentage">{{ $countEvents }}</span>
                                </div>
                                <div class="counter-label">
                                    <span class="counter-box-content">{{ trans('index.event') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-grid-cell col-xs-12 col-sm-3">
                <div class="so-panel widget_counters-box panel-first-child panel-last-child">
                    <div class="thim-widget-counters-box thim-widget-counters-box-base">
                        <div class="counter-box has-padding line-in-bottom visible full-visible">
                            <div class="content-box-percentage">
                                <div class="counter-number">
                                    <span class="display-percentage">{{ $countInteractives }}</span>
                                </div>
                                <div class="counter-label">
                                    <span class="counter-box-content">{{ trans('index.interactive') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-grid" id="pg-5008-4">
        <div class="siteorigin-panels-stretch panel-row-style">
            <div class="panel-grid-cell" id="pgc-5008-4-0">
                <div class="so-panel widget_heading panel-first-child panel-last-child">
                    <div class="thim-widget-heading thim-widget-heading-base">
                        <div class="thim-heading text-center ">
                            <div class="sc-heading article_heading ">
                                <h3 class="heading__primary wrapper-line-heading">
                                    <span>{{ trans('index.why') }}</span>
                                </h3>
                                <p class="heading__secondary">{{ trans('index.why-choose') }}</p>
                                <span class="line-heading"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-grid" id="pg-5008-5">
        <div class="siteorigin-panels-stretch panel-row-style">
            <div class="col-xs-6">
                <div class="panel-grid-cell col-xs-12">
                    <div class="so-panel widget_box panel-first-child">
                        <div class="panel-widget-style">
                            <div class="thim-widget-box thim-widget-box-base">
                                <div class="thim-box-simple not-line image-at-left">
                                    <div class="inner">
                                        <div class="media">
                                            <img src="{{ asset('img/home6-icon-4.png') }}" alt="home6-icon-4">
                                        </div>
                                        <div class="box-content">
                                            <h3 class="title">{{ trans('index.your-charitable-life') }}</h3>
                                            <div class="description">{{ trans('index.your-charitable-life-content') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-grid-cell col-xs-12">
                    <div class="so-panel widget_box panel-first-child">
                        <div class="panel-widget-style">
                            <div class="thim-widget-box thim-widget-box-base">
                                <div class="thim-box-simple not-line image-at-left">
                                    <div class="inner">
                                        <div class="media">
                                            <img src="{{ asset('img/home6-icon-2.png') }}" alt="home6-icon-4">
                                        </div>
                                        <div class="box-content">
                                            <h3 class="title">{{ trans('index.no-goal-requirements') }}</h3>
                                            <div class="description">{{ trans('index.no-goal-requirements-content') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="panel-grid-cell col-xs-12">
                    <div class="so-panel widget_box panel-first-child">
                        <div class="panel-widget-style">
                            <div class="thim-widget-box thim-widget-box-base">
                                <div class="thim-box-simple not-line image-at-left">
                                    <div class="inner">
                                        <div class="media">
                                            <img src="{{ asset('img/home6-icon-5.png') }}" alt="home6-icon-4">
                                        </div>
                                        <div class="box-content">
                                            <h3 class="title">{{ trans('index.most-trusted') }}</h3>
                                            <div class="description">{{ trans('index.most-trusted-content') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-grid-cell col-xs-12">
                    <div class="so-panel widget_box panel-first-child">
                        <div class="panel-widget-style">
                            <div class="thim-widget-box thim-widget-box-base">
                                <div class="thim-box-simple not-line image-at-left">
                                    <div class="inner">
                                        <div class="media">
                                            <img src="{{ asset('img/home6-icon-6.png') }}" alt="home6-icon-4">
                                        </div>
                                        <div class="box-content">
                                            <h3 class="title">{{ trans('index.our-experience') }}</h3>
                                            <div class="description">{{ trans('index.our-experience-content') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-grid">
        <div class="panel-row-style">
            <div class="panel-grid-cell">
                <div class="so-panel widget_heading panel-first-child">
                    <div class="thim-widget-heading thim-widget-heading-base">
                        <div class="thim-heading text-center ">
                            <div class="sc-heading article_heading ">
                                <h3 class=" wrapper-line-heading">
                                    <span>{{ trans('index.campaigned') }}</span>
                                </h3>
                                <span class="line-heading"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="so-panel widget_list-post panel-last-child">
                    <div class="thim-widget-list-post thim-widget-list-post-base">
                        <div class="thim-list-post-wrapper-simple list-post-style3">
                            <div class="inner-list">
                                <div class="list-posts col-xs-12">
                                    @for ($i = 0; $i < 3; $i++)
                                    <div class="item-post col-xs-6 col-md-4 post-364 post type-post status-publish format-standard has-post-thumbnail hentry category-our-projects">
                                        <div class="article-title-wrapper">
                                            <div class="article-inner">
                                                <div class="media">
                                                    <a href="">
                                                        <img src="{{ asset('public/img/about_1.jpg') }}" alt="" >
                                                    </a>
                                                </div>
                                                <div class="post-content">
                                                    <a href="" class="article-title">
                                                        <h3 class="title">{{ trans('index.title') }}</h3>
                                                    </a>
                                                    <div class="thim-post-content">
                                                        <p>{{ trans('index.content') }}</p>
                                                    </div>
                                                </div>
                                                <a href="" class="thim-button read-more style7">{{ trans('index.read-more') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
