<!DOCTYPE html>
<html>
    <head>
        <title>Article Preview page</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        
         <link rel="stylesheet" type="text/css" href="{{ asset('css/articlepreview/froala_style.css') }}" />
         <link rel="stylesheet" type="text/css" href="{{ asset('css/articlepreview/reset.css') }}" />
         <link rel="stylesheet" type="text/css" href="{{ asset('css/articlepreview/font-awesome.css') }}" />
         <link rel="stylesheet" type="text/css" href="{{ asset('css/articlepreview/main-stylesheet-new-color-design.css') }}" />
         <link rel="stylesheet" type="text/css" href="{{ asset('css/articlepreview/desktop.css') }}" />
         <link rel="stylesheet" type="text/css" href="{{ asset('css/articlepreview/shortcodes.css') }}" />
         <link rel="stylesheet" type="text/css" href="{{ asset('css/articlepreview/ipad.css') }}" />
         <link rel="stylesheet" type="text/css" href="{{ asset('css/articlepreview/phone.css') }}" />
         
    </head>

    <body style="background: #fcf3ef">
        <div class="boxed active">
            <section class="content" style="padding-bottom:20px;">

                <div id="main_recent_article_div">
                    <div class="wrapper url-changer">
                        <div class="content-main with-sidebar left">
                            <div class="strict-block">
                                <div class="main-article" itemscope="" itemtype="http://schema.org/NewsArticle">
                                    <style>
                                        @media screen and (min-width: 976px) {
                                            .main-article .big_article_header{font-size:45px !important;}
                                            .main-article .big_article_summary{font-size:25px;}
                                        }
                                    </style>
                                    <span itemprop="publisher" itemtype="http://schema.org/Organization" style="display:none;">BW Businessworld</span>
                                    <h1 class="big_article_header capz" itemprop="headline">{{$title}}</h1>

                                    <div style="margin-top:20px;">
                                        <p class="big_article_summary" itemprop="disambiguatingDescription"><i>{!! html_entity_decode($summary) !!}</i></p>
                                    </div>

                                    <div style="width:100%; text-align:right;">
                                        <p style="font-size: 12px; line-height: 12px;">Photo Credit : Pti </p>
                                    </div>
                                    <div class="article-header">
                                        <div id="image_section"><img src="http://www.businessworld.in/static/images/BW_loading_image.png" class="article-photo" alt=""></div>

                                        <div class="article-meta">
                                            <div class="meta-date">
                                                <div datetime="July 9, 2018, 12:30 p.m." itemprop="datePublished"> <span class="date">{{date('d')}}</span> <span class="month"><a href="javascript:;">{{date('M,Y')}}</a></span> </div>
                                                <span class="author" itemprop="author" itemscope="" itemtype="http://schema.org/Person">by <a href="javascript:;" itemprop="url"> <span itemprop="name">BW Online Bureau</span> </a> </span> </div>
                                            <div class="meta-tools"> <a href="#"><i class="fa fa-print"></i>Print this article</a> <span><i class="fa fa-text-height"></i>Font size <span class="f-size"><a href="#font-size-down">-</a><span class="f-size-number">16</span><a href="#font-size-up">+</a></span></span> </div>
                                        </div>
                                    </div>

                                    <div class="article_text_desc fr-view" id="fadeMe">
                                        <div>                

                                            <p itemprop="description"></p>
                                            {!! html_entity_decode($description) !!}

                                        </div>
                                    </div>
                                    <hr>

                                    <!---------------------------------------------------->


                                </div>
                            </div>

                        </div>
                        <aside id="sidebar" class="right sidebar_new">

                            <div class="widget">
                                <h3>Quick Bytes</h3>
                                <a href="http://businessworld.in/all-quickbytes/" class="widget-top-b">View more</a>
                                <div class="w-news-list">

                                    <div class="item">
                                        <div class="item-photo">
                                            <a href="/quickbytes/5-Netflix-Documentary-That-Will-Make-You-Smarter-About-Business-/09-07-2018-353">
                                                <img data-original="http://static.businessworld.in/quickbyte/quickbytes_thumb/1531141036_zy2giJ_1-Henry-Ford-470.jpg" alt="" class="lazyed" src="http://static.businessworld.in/quickbyte/quickbytes_thumb/1531141036_zy2giJ_1-Henry-Ford-470.jpg" style="display: block;">
                                            </a>
                                        </div>
                                        <div class="item-content">
                                            <h4><a href="/quickbytes/5-Netflix-Documentary-That-Will-Make-You-Smarter-About-Business-/09-07-2018-353">5 Netflix Documentary That Will Make You Smarter About Business </a></h4>

                                            <div class="item-foot">
                                                <a href="/quickbytes/5-Netflix-Documentary-That-Will-Make-You-Smarter-About-Business-/09-07-2018-353"><i class="fa fa-reply"></i><b>read more</b></a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="item">
                                        <div class="item-photo">
                                            <a href="/quickbytes/Now-Personalize-Your-Galaxy-Smartphones-With-Good-Lock-2018/04-07-2018-352">
                                                <img data-original="http://static.businessworld.in/quickbyte/quickbytes_thumb/1530707902_S0oTac_Love-Your-Lock-Screen-470.jpg" alt="" class="lazyed" src="http://static.businessworld.in/quickbyte/quickbytes_thumb/1530707902_S0oTac_Love-Your-Lock-Screen-470.jpg" style="display: block;">
                                            </a>
                                        </div>
                                        <div class="item-content">
                                            <h4><a href="/quickbytes/Now-Personalize-Your-Galaxy-Smartphones-With-Good-Lock-2018/04-07-2018-352">Now Personalize Your Galaxy Smartphones With Good Lock 2018</a></h4>

                                            <div class="item-foot">
                                                <a href="/quickbytes/Now-Personalize-Your-Galaxy-Smartphones-With-Good-Lock-2018/04-07-2018-352"><i class="fa fa-reply"></i><b>read more</b></a>
                                            </div>
                                        </div>

                                    </div>
                                    
                                     <div class="item">
                                        <div class="item-photo">
                                            <a href="/quickbytes/5-Netflix-Documentary-That-Will-Make-You-Smarter-About-Business-/09-07-2018-353">
                                                <img data-original="http://static.businessworld.in/quickbyte/quickbytes_thumb/1531141036_zy2giJ_1-Henry-Ford-470.jpg" alt="" class="lazyed" src="http://static.businessworld.in/quickbyte/quickbytes_thumb/1531141036_zy2giJ_1-Henry-Ford-470.jpg" style="display: block;">
                                            </a>
                                        </div>
                                        <div class="item-content">
                                            <h4><a href="/quickbytes/5-Netflix-Documentary-That-Will-Make-You-Smarter-About-Business-/09-07-2018-353">5 Netflix Documentary That Will Make You Smarter About Business </a></h4>

                                            <div class="item-foot">
                                                <a href="/quickbytes/5-Netflix-Documentary-That-Will-Make-You-Smarter-About-Business-/09-07-2018-353"><i class="fa fa-reply"></i><b>read more</b></a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="item">
                                        <div class="item-photo">
                                            <a href="/quickbytes/Now-Personalize-Your-Galaxy-Smartphones-With-Good-Lock-2018/04-07-2018-352">
                                                <img data-original="http://static.businessworld.in/quickbyte/quickbytes_thumb/1530707902_S0oTac_Love-Your-Lock-Screen-470.jpg" alt="" class="lazyed" src="http://static.businessworld.in/quickbyte/quickbytes_thumb/1530707902_S0oTac_Love-Your-Lock-Screen-470.jpg" style="display: block;">
                                            </a>
                                        </div>
                                        <div class="item-content">
                                            <h4><a href="/quickbytes/Now-Personalize-Your-Galaxy-Smartphones-With-Good-Lock-2018/04-07-2018-352">Now Personalize Your Galaxy Smartphones With Good Lock 2018</a></h4>

                                            <div class="item-foot">
                                                <a href="/quickbytes/Now-Personalize-Your-Galaxy-Smartphones-With-Good-Lock-2018/04-07-2018-352"><i class="fa fa-reply"></i><b>read more</b></a>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </aside>
                    </div>
                </div>


            </section>
        </div>


    </body>
</html>