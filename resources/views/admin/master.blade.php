<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>@yield('title')</title>
        <!-- Favicon-->
        <link rel="icon" href="favicon.ico" type="image/x-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

        {{ Html::style('bower_components/adminbsb-materialdesign/plugins/bootstrap/css/bootstrap.css') }}
        {{ Html::style('bower_components/adminbsb-materialdesign/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}
        {{ Html::style('bower_components/adminbsb-materialdesign/plugins/node-waves/waves.css') }}
        {{ Html::style('bower_components/adminbsb-materialdesign/plugins/animate-css/animate.css') }}
        {{ Html::style('bower_components/adminbsb-materialdesign/plugins/material-design-preloader/md-preloader.css') }}
        {{ Html::style('bower_components/adminbsb-materialdesign/plugins/jquery-spinner/css/bootstrap-spinner.css') }}
        {{ Html::style('bower_components/adminbsb-materialdesign/plugins/morrisjs/morris.css') }}
        {{ Html::style('bower_components/adminbsb-materialdesign/css/style.css') }}
        {{ Html::style('css/admin/master.css') }}
        {{ Html::style('bower_components/adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.css') }}
        {{ Html::style('bower_components/adminbsb-materialdesign/css/themes/all-themes.css') }}
        {{ Html::style('bower_components/adminbsb-materialdesign/plugins/sweetalert/sweetalert.css') }}
        {{ Html::style('bower_components/bootstrap-toggle/css/bootstrap-toggle.min.css') }}
        {{ Html::style('css/admin.css') }}

    </head>

    <body class="theme-cyan">
        <!-- Page Loader -->
        <div class="page-loader-wrapper">
            <div class="loader">
                <div class="md-preloader pl-size-md">
                    <svg viewbox="0 0 75 75">
                        <circle cx="37.5" cy="37.5" r="33.5" class="pl-red" stroke-width="4" />
                    </svg>
                </div>
            </div>
        </div>
        <!-- #END# Page Loader -->
        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>
        <!-- #END# Overlay For Sidebars -->
        <!-- Top Bar -->
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
                       data-target="#navbar-collapse" aria-expanded="false">
                    </a>
                    <a href="javascript:void(0);" class="bars"></a>
                    <a class="navbar-brand">{{ trans('label.name_admin_page') }}</a>
                </div>
            </div>
        </nav>
        <!-- #Top Bar -->
        <section>
            <!-- Left Sidebar -->
            <aside id="leftsidebar" class="sidebar">
                <!-- User Info -->
                <div class="user-info">
                    <div class="image">
                        <img src="{{ auth()->user()->avatar }}" width="48" height="48" alt="User" />
                    </div>
                    <div class="info-container">
                        <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ auth()->user()->name }}</div>
                        <div class="email">{{ auth()->user()->email }}</div>
                        <div class="btn-group user-helper-dropdown">
                            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></i>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="{{ URL::action('UserController@show', auth()->user()->id) }}"><i class="material-icons">person</i>{{ trans('label.profile') }}</a>
                                </li>
                                <li role="seperator" class="divider"></li>
                                <li>
                                    <a href="{{ url('/logout') }}"><i class="material-icons">input</i>{{ trans('label.logout') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- #User Info -->
                <!-- Menu -->
                <div class="menu">
                    <ul class="list">
                        <li class="header">{{ trans('label.main_menu') }}</li>
                        <li class="active">
                            <a href="{{ URL::action('CampaignController@index') }}">
                                <i class="material-icons">home</i>
                                <span>{{ trans('label.home') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">person</i>
                                <span>{{ trans('label.nav_menu.user') }}</span>
                            </a>
                            <ul class="ml-menu">
                                <li>
                                    <a href="{{ URL::action('Admin\UserController@index') }}">
                                        <i class="material-icons">person_add</i>
                                        <span>{{ trans('user.panel_head.index') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL::action('Admin\UserController@create') }}">
                                        <i class="material-icons">people</i>
                                        <span>{{ trans('user.panel_head.create') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">person</i>
                                <span>{{ trans('label.nav_menu.campaign') }}</span>
                            </a>
                            <ul class="ml-menu">
                                <li>
                                    <a href="{{ URL::action('Admin\CampaignController@index') }}">
                                        <i class="material-icons">person_add</i>
                                        <span>{{ trans('campaign.panel_head.index') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL::action('Admin\CampaignController@create') }}">
                                        <i class="material-icons">people</i>
                                        <span>{{ trans('campaign.panel_head.create') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- #Menu -->
                <!-- Footer -->
                <div class="legal">
                    <div class="copyright">
                         {{ trans('label.footer.copyright') }}
                    </div>
                </div>
                <!-- #Footer -->
            </aside>
            <!-- #END# Left Sidebar -->
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-lg-10 col-lg-offset-1">
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>

        {{ Html::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyCzfBLqeROyZ1xGhOWb_oG7zmdYcCQdaI8&v=3.exp&sensor=false&libraries=places') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/jquery/jquery.min.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/bootstrap/js/bootstrap.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/jquery-slimscroll/jquery.slimscroll.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/node-waves/waves.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/jquery-countto/jquery.countTo.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/raphael/raphael.min.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/morrisjs/morris.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/chartjs/Chart.bundle.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/jquery-sparkline/jquery.sparkline.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/jquery-spinner/js/jquery.spinner.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/js/admin.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/js/pages/ui/tooltips-popovers.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/js/demo.js') }}
        {{ Html::script('js/admin/master.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/jquery-validation/jquery.validate.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/jquery-steps/jquery.steps.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/sweetalert/sweetalert.min.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/node-waves/waves.js') }}
        {{ Html::script('bower_components/bootstrap-toggle/js/bootstrap-toggle.min.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}
        {{ Html::script('bower_components/adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.js') }}

    </body>
</html>
