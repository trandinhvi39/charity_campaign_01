<div class="thim-menu not-line mobile-not-line">
    <span class="close-menu"><i class="fa fa-times"></i></span>
    <div class="main-menu">
        <ul class="nav navbar-nav">
            <li class="menu-item">
                <a href="{{ action('CampaignController@index') }}"><span>{{ trans('index.home') }}</span></a>
            </li>
            <li class="menu-item">
                <a href="{{ action('CampaignController@showCampaigns') }}"><span>{{ trans('index.campaigns') }}</span></a>
            </li>
            <li class="menu-item">
                <a href="{{ action('BlogController@index') }}"><span>{{ trans('index.blog') }}</span></a>
                <span class="icon-toggle"></span>
            </li>
            <li class="menu-item menu-item-has-children">
                <a href="{{ action('OrtherController@aboutUs') }}"><span>{{ trans('index.about') }}</span></a>
                <span class="icon-toggle"><i class="fa fa-angle-down"></i></span>
                <ul class="sub-menu">
                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                        <a href="{{ action('OrtherController@aboutUs') }}">{{ trans('index.introduce') }}</a>
                    </li>
                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                        <a href="{{ action('OrtherController@faq') }}">{{ trans('index.faq') }}</a>
                    </li>
                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                        <a href="{{ action('OrtherController@contact') }}">{{ trans('index.contact') }}</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="menu-sidebar thim-hidden-768px">
        <div class="widget_siteorigin-panels-builder">
            <div id="pl-w570233562915f">
                <div class="panel-grid" id="pg-w570233562915f-0">
                    <div class="panel-grid-cell" id="pgc-w570233562915f-0-0">
                        <div class="so-panel widget_social panel-first-child panel-last-child" id="panel-w570233562915f-0-0-0" data-index="0">
                            <div class="thim-widget-social thim-widget-social-base">
                                <div class="thim-social text-right style-color">
                                    <ul class="social_link">
                                        <li>
                                            <a class="facebook" href="" target="_self"><i class="fa fa-facebook"></i></a>
                                        </li>
                                        <li>
                                            <a class="twitter" href="" target="_self"><i class="fa fa-twitter"></i></a>
                                        </li>
                                        <li>
                                            <a class="linkedin" href="" target="_self"><i class="fa fa-linkedin"></i></a>
                                        </li>
                                        <li>
                                            <a class="instagram" href="" target="_self"><i class="fa fa-instagram"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="wrapper-container" class="wrapper-container" >
    <div class="content-pusher " >
        <nav class="navbar navbar-inverse ">
            <div class="container-fluid">
                <div class="navbar-header hidden-xs">
                    <p class="navbar-branda" href="#">
                        <i class="fa fa-map-marker"></i>{{ trans('index.address') }}
                        <i class="fa fa-phone"></i> +84-4-3795-5417
                    </p>
                </div>
                <div class=" navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-nav-custom navbar-right ">
                        <li>
                            <ul class="nav navbar-nav hidden-xs">
                                <li>
                                    <a class="facebook" href="" target="_self"><i class="fa fa-facebook"></i></a>
                                </li>
                                <li>
                                    <a class="twitter" href="" target="_self"><i class="fa fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a class="linkedin" href="" target="_self"><i class="fa fa-linkedin"></i></a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div class="hide_language" data-route="{{ url('language') }}" data-token="{{ csrf_token() }}"></div>
                            <div class="multiple-lang">
                                <select name="lang" id="countries" class="form-control btn-multiple-language">
                                    <option value='en' {{ Session::get('locale') == 'en' ? 'selected' : '' }} data-image="{{ asset('bower_components/ms-Dropdown/images/msdropdown/icons/blank.gif') }}" data-imagecss="flag england" data-title="{{ config('settings.language.en') }}">
                                        {{ config('settings.language.en') }}
                                    </option>
                                    <option value='vi' {{ Session::get('locale') == 'vi' ? 'selected' : '' }} data-image="{{ asset('bower_components/ms-Dropdown/images/msdropdown/icons/blank.gif') }}" data-imagecss="flag vn" data-title="{{ config('settings.language.vi') }}">
                                        {{ config('settings.language.vi') }}
                                    </option>
                                </select>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <header id="masthead" class="site-header line">
        <div class="top-header col-xs-12">
            <div class="row">
                <div class="thim-toggle-mobile-menu">
                    <span class="inner">{{ trans('index.menu') }}</span>
                </div>
                <div class="thim-logo col-xs-2">
                    <a href="{{ action('CampaignController@index') }}" rel="home">
                        <img class="logo" src="{{ asset('logo.png') }}" alt="{{ trans('message.project') }}">
                    </a>
                </div>
                <div class="thim-menu col-xs-10">
                    <div class="main-menu col-xs-9">
                        <ul class="nav navbar-nav">
                            <li class="menu-item">
                                <a href="{{ action('CampaignController@index') }}"><span data-hover="Home">{{ trans('index.home') }}</span></a>
                            </li>
                            <li class="menu-item ">
                                <a href="{{ action('CampaignController@showCampaigns') }}"><span>{{ trans('index.campaigns') }}</span></a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ action('BlogController@index') }}"><span>{{ trans('index.blog') }}</span></a>
                            </li>
                            <li class="menu-item menu-item-has-children"><a href="{{ action('OrtherController@aboutUs') }}"><span>{{ trans('index.about') }}</span></a>
                                <span class="icon-toggle"><i class="fa fa-angle-down"></i></span>
                                <ul class="sub-menu">
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <a href="{{ action('OrtherController@aboutUs') }}">{{ trans('index.introduce') }}</a>
                                    </li>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <a href="{{ action('OrtherController@faq') }}">{{ trans('index.faq') }}</a>
                                    </li>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <a href="{{ action('OrtherController@contact') }}">{{ trans('index.contact') }}</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                {!! Form::open(['class' => 'navbar-form-custom form-group typeahead', 'role' => 'search']) !!}
                                {!! Form::text('search', '', ['class'=>'searchAll typeahead-search', 'placeholder'=> trans('campaign.search_campaign'), 'id' => 'typeahead-search']) !!}
                                {!! Form::close() !!}
                            </li>
                        </ul>
                    </div>
                    <div class="top-sidebar col-xs-3">
                        <ul class="nav navbar-nav-custom pull-right">
                            @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">{{ trans('message.login') }}</a></li>
                            <li><a href="{{ url('/register') }}">{{ trans('message.register') }}</a></li>
                            @else
                            <li class="thimpress_donate_button">
                                <a class="donate_button_title" href="{{ action('CampaignController@create') }}">
                                    <i class="glyphicon glyphicon-pencil"></i>
                                    <span>{{ trans('campaign.create_campaign') }}</span>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ Auth()->user()->avatar }}" alt="avatar"> <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                    <li><a href="{{ action('UserController@show', ['id' => Auth::user()->id]) }}" ><span class="glyphicon glyphicon-user"></span> {{ trans('user.home') }}</a></li>
                                    <li><a href="{{ url('/logout') }}"><span class="glyphicon glyphicon-log-out"></span> {{ trans('message.logout') }}</a></li>
                                </ul>
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div id="main-content">
