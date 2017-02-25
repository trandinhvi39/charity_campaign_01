<footer id="colophon" class="site-footer">
    <aside id="siteorigin-panels-builder-7" class="widget_siteorigin-panels-builder">
        <div class="panel-grid">
            <div class="siteorigin-panels-stretch panel-row-style thim-overlay-color ">
                <div class="panel-grid-cell volunteer">
                    <div class="so-panel widget_sow-editor panel-first-child">
                        <div class="panel-widget-style">
                            <div class="so-widget-sow-editor so-widget-sow-editor-base">
                                <div class="siteorigin-widget-tinymce textwidget">
                                    <h3 class="text-center">{{ trans('index.become-volunteer') }}</h3>
                                    <p class="text-center">{{ trans('index.register-volunteer') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="so-panel widget_button panel-last-child" >
                        <div class="thim-widget-button thim-widget-button-base">
                            <div id="button_58c7817bb8c2d" class="text-center">
                                <a href="" class="thim-button style4 inner size-default">{{ trans('index.now') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>
    <div class="container">
        <aside class="widget_siteorigin-panels-builder">
            <div>
                <div class="panel-grid footer__content">
                    <div class="panel-row-style">
                        <div class="panel-grid-cell col-md-4 col-xs-6" >
                            <div class="so-panel widget_sow-editor panel-first-child">
                                <div class="so-widget-sow-editor so-widget-sow-editor-base">
                                    <div class="siteorigin-widget-tinymce textwidget">
                                        <p>
                                            <img src="{{ asset('assets/images/footer-logo.png') }}" alt="footer-logo" class="alignnone size-full wp-image-419">
                                        </p>
                                        <div>
                                            <i class="fa fa-phone"></i> &nbsp;&nbsp;
                                            <a href="tel:+84437955417">+84 4 3795 5417</a>
                                            <p></p>
                                            <p>
                                                <i class="fa fa-envelope"></i> &nbsp;&nbsp;
                                                <a href="">{{ trans('index.mail') }}</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="so-panel widget_social panel-last-child">
                                <div class="panel-widget-style">
                                    <div class="thim-widget-social thim-widget-social-base">
                                        <div class="thim-social text-left style-default">
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
                        <div class="panel-grid-cell col-md-4 col-xs-6">
                            <div class="so-panel widget_nav_menu panel-first-child panel-last-child">
                                <div class="panel-widget-style">
                                    <h3 class="widget-title">{{ trans('index.company') }}</h3>
                                    <div class=" megaWrapper">
                                        <ul id="menu-company" class="menu">
                                            <li class="menu-item menu-item-type-post_type menu-item-object-page standard">
                                                <a href="{{ action('OrtherController@aboutUs') }}"><span>{{ trans('index.about') }}</span></a>
                                            </li>
                                            <li class="menu-item menu-item-type-post_type menu-item-object-page standard">
                                                <a href="{{ action('OrtherController@contact') }}"><span>{{ trans('index.contact') }}</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-grid-cell col-xs-12 col-md-4">
                            <div class="so-panel widget_single-images panel-first-child panel-last-child" id="panel-w581a8da399e18-0-3-0" data-index="4">
                                <div class="thim-widget-single-images thim-widget-single-images-base">
                                    <div class="thim-single-image no-effect">
                                        <h3 class="widget-title">{{ trans('index.our-worldwide-office') }}</h3>
                                        <div class="wrapper-image">
                                            <div class="single-image center">
                                                <img src="{{ asset('img/home1_map.png') }}" alt="" width="300" height="165">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-grid" id="pg-w581a8da399e18-1">
                    <div class="panel-grid-cell" id="pgc-w581a8da399e18-1-0">
                        <div class="so-panel widget_text panel-first-child panel-last-child" id="panel-w581a8da399e18-1-0-0">
                            <div class="textwidget">
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-grid" id="pg-w581a8da399e18-2">
                    <div  class="panel-row-style">
                        <div class="panel-grid-cell" id="pgc-w581a8da399e18-2-0">
                            <div class="so-panel widget_copyright panel-first-child panel-last-child" id="panel-w581a8da399e18-2-0-0" data-index="6">
                                <div class="thim-widget-copyright thim-widget-copyright-base">
                                    <div class="thim-widget-copyright-text text-left">
                                        <p class="text-copyright"></p>
                                        <p class="text-copyright">{{ trans('index.design') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</footer>
<a id="back-to-top" class="show scrollup" title="Go To Top"></a>
<div class="gallery-slider-content"></div>
<div class="modal fade" id="modal-donate"></div>
