@extends('layouts/master')

@section('title', 'trending Articles - BWCMS')


@section('content')

    <div class="panel">
        <div class="panel-content filler">
            <div class="panel-logo"></div>
            <div class="panel-header">
                <h1><small>Trending</small></h1>    
            </div>
            <div class="panel-header">
                <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
            </div>
        
            <script type="text/javascript">
            $(function () {
                $("#jstree").jstree({ 
                    "json_data" : {
                        "data" : [
                                                     {
                                "data" : { 
                                    "title" : "Section Active", 
                                    "attr" : { "href" : "#sa" } 
                                }
                            },
                                                    {
                                "data" : { 
                                    "title" : "Trending 1", 
                                    "attr" : { "href" : "#t1" } 
                                }
                            },
                                                                                                    {
                                "data" : { 
                                    "title" : "Trending 2", 
                                    "attr" : { "href" : "#t2" } 
                                }
                            },
                                                                                                    {
                                "data" : { 
                                    "title" : "Trending 3", 
                                    "attr" : { "href" : "#t3" } 
                                }
                            },
                                                     ]
                    },
                    "plugins" : [ "themes", "json_data", "ui" ]
                })
                .bind("click.jstree", function (event) {
                    var node = $(event.target).closest("li");
                    document.location.href = node.find('a').attr("href");
                    return false;
                })
                .delegate("a", "click", function (event, data) { event.preventDefault(); });
            });
        </script>
        <div class="sidebarMenuHolder">
            <div class="JStree">
                <div class="Jstree_shadow_top"></div>
                    <div id="jstree"></div>
                    <div class="Jstree_shadow_bottom"></div>
                </div>
            </div>   
        </div>
        <div class="panel-slider">
            <div class="panel-slider-center">
                <div class="panel-slider-arrow"></div>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="breadcrumb-container">
            <ul class="xbreadcrumbs">
                <li>
                    <a href="dashboard.html">
                        <i class="icon-photon home"></i>
                    </a>
                </li>
                <li class="current">
                    <a href="javascript:;">
                    Trending
                    </a>
                </li>
            </ul>
        </div>      
        <header>
            <i class="icon-big-notepad"></i>
            <h2><small>Trending</small></h2>
        </header>
        <form class="form-horizontal" id="trendinginfo" method="post">
            <div class="container-fluid">
                <div class="form-legend" id="Notifications">Notifications</div>
                <!--Notifications begin-->
                <div class="control-group row-fluid">
                    <div class="span12 span-inset">
                        <div class="alert alert-success alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>This is Success Notification</strong>
                            <span>Your data has been successfully modified.</span>
                        </div>
                        <div class="alert alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>This is Alert Notification</strong>
                            <span>No result found.</span>
                        </div>
                        <div class="alert alert-error alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>This is Error Notification</strong>
                            <span>Please select a valid search criteria.</span>
                        </div>
                        <div class="alert alert-error alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>This is Error Notification</strong>
                            <span>Please enter a valid email id.</span>
                        </div>
                    </div>
                </div>
                <!--Notifications end-->
            </div>
            <div class="container-fluid">
                <div class="form-legend" id="sa">Section Active</div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" >Section Active</label>
                    </div>
                    <div class="span3">		
                        <label class="radio">
                            <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
                                 On
                        </label>
                    </div>
                    <div class="span3">
                        <label class="radio">
                            <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
                                Off
                        </label>
                    </div>
                </div>
						
                <script>
                    $().ready(function(){
                        $(".uniformRadio").uniform({
                            radioClass: 'uniformRadio'
                         });

                      });            
                </script>					
            </div>			
            <div class="container-fluid">
                <div class="form-legend" id="t1">Trending 1</div>			
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Topic </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t1topic" id="t1topic" value="">
                        </div>
                    </div>
                </div>   	
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t1url" id="t1url" value="">
                        </div>
                    </div>
                </div>

                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 1 </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t1article1" id="t1article1" value="">
                        </div>
                    </div>
                </div>   	
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t1a1url" id="t1a1url" value="">
                        </div>
                    </div>
                </div>

                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 2 </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t1article2" id="t1article2" value="">
                        </div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t1a1url2" id="t1a1url2" value="">
                        </div>
                     </div>
                </div>
            </div>
            <!--end container-->
            <div class="container-fluid">
                <div class="form-legend" id="t2">Trending 2</div>			
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Topic </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t2topic" id="t2topic" value="">
                        </div>
                    </div>
                </div>      	
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t2url" id="t2url" value="">
                        </div>
                    </div>
                </div>
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 1 </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t2article1" id="t2article1" value="">
                        </div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t2a1url" id="t2a1url" value="">
                        </div>
                    </div>
                </div>

                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 2 </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t2article2" id="t2article2" value="">
                        </div>
                    </div>
                </div> 	
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t2a1url2" id="t2a1url2" value="">
                        </div>
                    </div>
                </div>			
            </div>
            <!--end container-->
            <div class="container-fluid">
                <div class="form-legend" id="t3">Trending 3</div>		
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Topic </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t3topic" id="t3topic" value="">
                        </div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t3url" id="t3url" value="">
                        </div>
                    </div>
                </div>						
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 1 </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t3article1" id="t3article1" value="">
                        </div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t3a1url" id="t3a1url" value="">
                        </div>
                    </div>
                </div>			
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 2 </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t3article2" id="t3article2" value="">
                        </div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t3a1url2" id="t3a1url2" value="">
                        </div>
                    </div>
                </div>						
            </div>
            <!--end container-->
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="button" class="btn btn-info" onclick="Savetrending()>Save</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                    <!--<button type="button" class="btn btn-success">Publish</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>-->
                </div>
            </div>			
        </form>
    </div>
@stop

