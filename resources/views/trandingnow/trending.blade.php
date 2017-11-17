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
        <input type="hidden" name="id"  value="{{@$trending->id}}">
            <div class="container-fluid" id="msg">
                <div class="form-legend" id="Notifications">Notifications</div>
                <!--Notifications begin-->
                <div class="control-group row-fluid">
                    <div class="span12 span-inset">
                        <div   class="alert alert-success alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>This is Success Notification</strong>
                            <span>Your data has been successfully modified.</span>
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
                            <input type="radio" name="optionsRadios" class="uniformRadio" value="1" {{ @$trending->optionsRadios == '1' ? 'checked' : '' }}>
                                 On
                        </label>
                    </div>
                    <div class="span3">
                        <label class="radio">
                            <input type="radio" name="optionsRadios" class="uniformRadio" value="2" {{ @$trending->optionsRadios == '2' ? 'checked' : '' }}>
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
                        <label class="control-label">Topic (80 Characters) </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t1topic" id="t1topic" value="{{@$trending->t1topic}}" maxlength="80">
                        </div>
                         <div id="t1topic_err"></div>
                         
                    </div>
                </div>   	
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t1url" id="t1url" value="{{@$trending->t1url}}">
                        </div>
                         <div id="t1url_err"></div>
                    </div>
                </div>

                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 1 (80 Characters)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t1article1" id="t1article1" value="{{@$trending->t1article1}}" maxlength="80">
                        </div>
                        <div id="t1article1_err"></div>
                    </div>
                </div>   	
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t1a1url" id="t1a1url" value="{{@$trending->t1a1url}}">
                        </div>
                        <div id="t1a1url_err"></div>
                    </div>
                </div>

                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 2 (80 Characters)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t1article2" id="t1article2" value="{{@$trending->t1article2}}" maxlength="80">
                        </div>
                         <div id="t1article2_err"></div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t1a1url2" id="t1a1url2" value="{{@$trending->t1a1url2}}">
                        </div>
                        <div id="t1a1url2_err"></div>
                     </div>
                </div>
            </div>
            <!--end container-->
            <div class="container-fluid">
                <div class="form-legend" id="t2">Trending 2</div>			
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Topic (80 Characters)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t2topic" id="t2topic" value="{{@$trending->t2topic}}" maxlength="80">
                            
                        </div>
                        <div id="t2topic_err"></div>
                    </div>
                </div>      	
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t2url" id="t2url" value="{{@$trending->t2url}}">
                        </div>
                         <div id="t2url_err"></div>
                    </div>
                </div>
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 1 (80 Characters)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t2article1" id="t2article1" value="{{@$trending->t2article1}}" maxlength="80">
                        </div>
                        <div id="t2article1_err"></div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t2a1url" id="t2a1url" value="{{@$trending->t2a1url}}">
                        </div>
                        <div id="t2a1url_err"></div>
                    </div>
                </div>

                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 2 (80 Characters)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t2article2" id="t2article2" value="{{@$trending->t2article2}}" maxlength="80">
                        </div>
                        <div id="t2article2_err"></div>
                    </div>
                </div> 	
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t2a1url2" id="t2a1url2" value="{{@$trending->t2a1url2}}">
                        </div>
                         <div id="t2a1url2_err"></div>
                    </div>
                </div>			
            </div>
            <!--end container-->
            <div class="container-fluid">
                <div class="form-legend" id="t3">Trending 3</div>		
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Topic (80 Characters)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t3topic" id="t3topic" value="{{@$trending->t3topic}}" maxlength="80">
                        </div>
                        <div id="t3topic_err"></div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t3url" id="t3url" value="{{@$trending->t3url}}">
                        </div>
                        <div id="t3url_err"></div>
                    </div>
                </div>						
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 1 (80 Characters)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t3article1" id="t3article1" value="{{@$trending->t3article1}}" maxlength="80">
                        </div>
                         <div id="t3article1_err"></div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t3a1url" id="t3a1url" value="{{@$trending->t3a1url}}">
                        </div>
                        <div id="t3a1url_err"></div>
                    </div>
                </div>			
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Article 2 (80 Characters)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="t3article2" id="t3article2" value="{{@$trending->t3article2}}" maxlength="80">
                        </div>
                        <div id="t3article2_err"></div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="t3a1url2" id="t3a1url2" value="{{@$trending->t3a1url2}}">
                        </div>
                         <div id="t3a1url2_err"></div>
                    </div>
                </div>						
            </div>
            <!--end container-->
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="button" class="btn btn-info" onclick="Savetrending()">Save</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                    <!--<button type="button" class="btn btn-success">Publish</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>-->
                </div>
            </div>
             {{ csrf_field() }}
        </form>
    </div>
 <script>
        $(document).ready(function () {
        document.getElementById("msg").style.display = "none"; 
        var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
            if (!re.test(url)) { 
                alert("url error");
                return false;
            }

        });

    function Savetrending() {
    var maxLength = 80;
    
    
     var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
     var area = document.getElementById("t1topic");
    if(document.getElementById("t1topic").value=="") 
    {

    document.getElementById("t1topic_err").style.display = "inline";
    document.getElementById("t1topic_err").innerHTML="Please enter  topic";
    $('html, body').scrollTop($("#t1topic").offset().top);
    return false;
    }
    
 else{
    document.getElementById("t1topic_err").style.display = "none"; 
    }
    if(document.getElementById("t1url").value=="") 
    {
    document.getElementById("t1url_err").style.display = "inline";
    document.getElementById("t1url_err").innerHTML="Please enter  url";
    $('html, body').scrollTop($("#t1url").offset().top);
    return false;
    }
    else if (!re.test(document.getElementById("t1url").value)) {

    document.getElementById("t1url_err").style.display = "inline";
    document.getElementById("t1url_err").innerHTML="Please Enter Valid url";
    $('html, body').scrollTop($("#t1url").offset().top);
      return false; 
      }
    else{
    document.getElementById("t1url_err").style.display = "none";
    } 
    if(document.getElementById("t1article1").value=="") 
    {
    document.getElementById("t1article1_err").style.display = "inline";
    document.getElementById("t1article1_err").innerHTML="Please enter article";
    $('html, body').scrollTop($("#t1article1").offset().top);
    return false;
    }else{
        document.getElementById("t1article1_err").style.display = "none";

    }
    if(document.getElementById("t1a1url").value=="") 
    {
    document.getElementById("t1a1url_err").style.display = "inline";
    document.getElementById("t1a1url_err").innerHTML="Please enter  url";
    $('html, body').scrollTop($("#t1a1url").offset().top);
    return false;
    }
    else if (!re.test(document.getElementById("t1a1url").value)) {

    document.getElementById("t1a1url_err").style.display = "inline";
    document.getElementById("t1a1url_err").innerHTML="Please Enter Valid url";
     $('html, body').scrollTop($("#t1a1url").offset().top);  
      return false;
      }
    else{
    document.getElementById("t1a1url_err").style.display = "none";
    } 
    
if(document.getElementById("t1article2").value=="") 
    {
    document.getElementById("t1article2_err").style.display = "inline";
    document.getElementById("t1article2_err").innerHTML="Please enter Message";
    $('html, body').scrollTop($("#t1article2").offset().top);
    return false;
    }else{
        document.getElementById("t1article2_err").style.display = "none";

    }
if(document.getElementById("t1a1url2").value=="") 
    {
    document.getElementById("t1a1url2_err").style.display = "inline";
    document.getElementById("t1a1url2_err").innerHTML="Please enter  url";
    $('html, body').scrollTop($("#t1a1url2").offset().top);
    return false;
    }
    else if (!re.test(document.getElementById("t1a1url").value)) {

    document.getElementById("t1a1url2_err").style.display = "inline";
    document.getElementById("t1a1url2_err").innerHTML="Please Enter Valid url";
     $('html, body').scrollTop($("#t1a1url2").offset().top);
    return false; 
      }
    else{
    document.getElementById("t1a1url2_err").style.display = "none";
    }
if(document.getElementById("t2topic").value=="") 
    {
    document.getElementById("t2topic_err").style.display = "inline";
    document.getElementById("t2topic_err").innerHTML="Please enter topic";
    $('html, body').scrollTop($("#t2topic").offset().top);
    return false;
    }else{
        document.getElementById("t2topic_err").style.display = "none";

    }
if(document.getElementById("t2url").value=="") 
    {
    document.getElementById("t2url_err").style.display = "inline";
    document.getElementById("t2url_err").innerHTML="Please enter  url";
    $('html, body').scrollTop($("#t2url").offset().top);
    return false;
    }
    else if (!re.test(document.getElementById("t2url").value)) {

    document.getElementById("t2url_err").style.display = "inline";
    document.getElementById("t2url_err").innerHTML="Please Enter Valid url";
    $('html, body').scrollTop($("#t2url").offset().top);
    return false;  
      }
    else{
    document.getElementById("t2url_err").style.display = "none";
    }
if(document.getElementById("t2article1").value=="") 
    {
    document.getElementById("t2article1_err").style.display = "inline";
    document.getElementById("t2article1_err").innerHTML="Please enter article1";
    $('html, body').scrollTop($("#t2article1").offset().top);
    return false;
    }else{
        document.getElementById("t2article1_err").style.display = "none";

    }
if(document.getElementById("t2a1url").value=="") 
    {
    document.getElementById("t2a1url_err").style.display = "inline";
    document.getElementById("t2a1url_err").innerHTML="Please enter  url";
     $('html, body').scrollTop($("#t2a1url").offset().top);
    return false;
    }
    else if (!re.test(document.getElementById("t2a1url").value)) {

    document.getElementById("t2a1url_err").style.display = "inline";
    document.getElementById("t2a1url_err").innerHTML="Please Enter Valid url";
      $('html, body').scrollTop($("#t2a1url").offset().top);
    return false;  
      }
    else{
    document.getElementById("t2a1url_err").style.display = "none";
    }

if(document.getElementById("t2article2").value=="") 
    {
    document.getElementById("t2article2_err").style.display = "inline";
    document.getElementById("t2article2_err").innerHTML="Please enter Message";
    $('html, body').scrollTop($("#t2article2").offset().top);
    return false;
    }else{
        document.getElementById("t2article2_err").style.display = "none";

    }
if(document.getElementById("t2a1url2").value=="") 
    {
    document.getElementById("t2a1url2_err").style.display = "inline";
    document.getElementById("t2a1url2_err").innerHTML="Please enter  url";
     $('html, body').scrollTop($("#t2a1url2").offset().top);
    return false;
    }
    else if (!re.test(document.getElementById("t2a1url2").value)) {

    document.getElementById("t2a1url2_err").style.display = "inline";
    document.getElementById("t2a1url2_err").innerHTML="Please Enter Valid url";
     $('html, body').scrollTop($("#t2a1url2").offset().top);
    return false;   
      }
    else{
    document.getElementById("t2a1url2_err").style.display = "none";
    }

if(document.getElementById("t3topic").value=="") 
    {
    document.getElementById("t3topic_err").style.display = "inline";
    document.getElementById("t3topic_err").innerHTML="Please enter topic";
    $('html, body').scrollTop($("#t3topic").offset().top);
    return false;
    }else{
        document.getElementById("t3topic_err").style.display = "none";

    }
if(document.getElementById("t3url").value=="") 
    {
    document.getElementById("t3url_err").style.display = "inline";
    document.getElementById("t3url_err").innerHTML="Please enter  url";
    $('html, body').scrollTop($("#t3url").offset().top);
    return false;
    }
    else if (!re.test(document.getElementById("t3url").value)) {

    document.getElementById("t3url_err").style.display = "inline";
    document.getElementById("t3url_err").innerHTML="Please Enter Valid url";
    $('html, body').scrollTop($("#t3url").offset().top);
    return false;  
      }
    else{
    document.getElementById("t3url_err").style.display = "none";
    }
if(document.getElementById("t3article1").value=="") 
    {
    document.getElementById("t3article1_err").style.display = "inline";
    document.getElementById("t3article1_err").innerHTML="Please enter article1";
    $('html, body').scrollTop($("#t3article1").offset().top);
    return false;
    }else{
        document.getElementById("t3article1_err").style.display = "none";

    }
if(document.getElementById("t3a1url").value=="") 
    {
    document.getElementById("t3a1url_err").style.display = "inline";
    document.getElementById("t3a1url_err").innerHTML="Please enter  url";
    $('html, body').scrollTop($("#t3a1url").offset().top);
    return false;
    }
    else if (!re.test(document.getElementById("t3a1url").value)) {

    document.getElementById("t3a1url_err").style.display = "inline";
    document.getElementById("t3a1url_err").innerHTML="Please Enter Valid url";
     $('html, body').scrollTop($("#t3a1url").offset().top);
    return false;  
      }
    else{
    document.getElementById("t3a1url_err").style.display = "none";
    }
if(document.getElementById("t3article2").value=="") 
    {
    document.getElementById("t3article2_err").style.display = "inline";
    document.getElementById("t3article2_err").innerHTML="Please enter article2";
    $('html, body').scrollTop($("#t3article2").offset().top);
    return false;
    }else{
        document.getElementById("t3article2_err").style.display = "none";

    }
if(document.getElementById("t3a1url2").value=="") 
    {
    document.getElementById("t3a1url2_err").style.display = "inline";
    document.getElementById("t3a1url2_err").innerHTML="Please enter  url";
     $('html, body').scrollTop($("#t3a1url2").offset().top);
    return false;
    }
    else if (!re.test(document.getElementById("t3a1url2").value)) {

    document.getElementById("t3a1url2_err").style.display = "inline";
    document.getElementById("t3a1url2_err").innerHTML="Please Enter Valid url";
       $('html, body').scrollTop($("#t3a1url2").offset().top);
    return false;  
      }
    
else{
    document.getElementById("t3a1url2_err").style.display = "none";
               
               $.ajax({
                    url : "/article/trendinginsert", // the endpoint
                    type : "POST", // http method
                    data : $('#trendinginfo').serialize(), // data sent with the post request
                    //cache: false,
                    // handle a successful response
                        success: function( response ) {
                            //alert(response);
                             document.getElementById("msg").style.display = "block";
                             $('html, body').scrollTop($("#msg").offset().top);
                        }

                });
               }
        }

    </script>

@stop

