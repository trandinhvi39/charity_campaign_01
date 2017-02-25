<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('message.project') }}</title>

    @section('css')

    {{ Html::style('bower_components/bootstrap/dist/css/bootstrap.min.css') }}
    {{ Html::style('http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css') }}


    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css') }}
    {{ Html::style('https://cdn.datatables.net/1.10.13/css/dataTables.material.min.css') }}
    {{ Html::style('bower_components/bootstrap/dist/css/bootstrap.min.css') }}
    {{ Html::style('bower_components/bootstrap-social/bootstrap-social.css') }}
    {{ Html::style('http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css') }}
    {{ Html::style('css/templates.css') }}
    {{ Html::style('css/master.css') }}
    {{ Html::style('css/app.css') }}
    {{ Html::style('https://fonts.googleapis.com/icon?family=Material+Icons') }}
    {{ Html::style('css/chat.css') }}
    {!! Html::style('bower_components/ms-Dropdown/css/msdropdown/dd.css') !!}
    {!! Html::style('bower_components/ms-Dropdown/css/msdropdown/flags.css') !!}

    @show

</head>
<body class="tp_event-template-default single single-tp_event postid-4934 siteorigin-panels thim_header_custom_style thim_header_style4 thim_fixedmenu archive post-type-archive post-type-archive-dn_campaign thim_header_custom_style thim_header_style4 thim_fixedmenu">

    @include('layouts.header')

    @yield('content')

    @include('layouts.footer')

    @section('js')
    {{ Html::script('bower_components/jquery/dist/jquery.min.js') }}
    {{ Html::script('bower_components/bootstrap/dist/js/bootstrap.min.js') }}
    {{ Html::script('js/plugins.js') }}
    {{ Html::script('js/app.js') }}
    {{ Html::script('js/new-app.js') }}
    {{ Html::script('js/new-plugins.js') }}
    {{ Html::script('js/base.js') }}
    {{ Html::script('https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js') }}
    {{ Html::script('https://cdn.datatables.net/1.10.13/js/dataTables.material.min.js') }}
    {{ Html::script('bower_components/typeahead.js/dist/typeahead.bundle.min.js') }}
    {{ Html::script('js/search.js') }}
    {{ Html::script('js/contribute.js') }}
    {{ Html::script('http://maps.google.com/maps/api/js?key=AIzaSyDluWcImjhXgQDLQcDvGi3Glu1TOYG6oew') }}

    <!-- DROPDOWN: multiple language -->
    {!! Html::script('bower_components/ms-Dropdown/js/msdropdown/jquery.dd.min.js') !!}
    {{ Html::script('js/multiple_language.js') }}
    {{ Html::script('js/share_social.js') }}
    {{ Html::script('js/custom.js') }}
    {{ Html::script('js/custom-scroll.min.js') }}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery(document).ready(function($) {
            var search = new Search();
            search.init();
        });
    </script>
    @show

</body>
</html>
