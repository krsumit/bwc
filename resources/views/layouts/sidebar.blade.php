 <div>
 @yield('sidebar')
   <div class="nav-fixed-left" style="visibility: hidden">
        <ul class="nav nav-side-menu">
            <li class="shadow-layer"></li>
            <li>
                <a href="/dashboard" >
                    <i class="icon-photon home"></i>
                    <span class="nav-selection">Dashboard</span>
                                    </a>
            </li>
            @if(count(array_diff(array('2','8','11','15','16','17','13','12','19','30','32','33','77'), Session::get('user_rights'))) != count(array('2','8','11','15','16','17','13','12','19','30','32','33','77')))
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon document_alt_stroke"></i>
                    <span class="nav-selection">Articles</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        @if(in_array('2',Session::get('user_rights')))
                        <li>
                            <a href="/article/create">Create New Articles</a>
                        </li>
			@endif			
                        @if(in_array('11',Session::get('user_rights')))
                        <li>
                            <a href="/article/list/new">New Articles</a>
                        </li>
                        @endif
                        @if(in_array('96',Session::get('user_rights')))
                        <li>
                            <a href="/article/list/channelarticles/published">Channel Articles</a>
                        </li>
                        @endif
                         @if(in_array('97',Session::get('user_rights')))
                        <li>
                            <a href="/article/trending">Trending</a>
                        </li>
                        @endif
                        
			@if(in_array('15',Session::get('user_rights')))			
                        <li>
                            <a href="/article/list/scheduled">Scheduled Articles</a>
                        </li>
                        @endif
                        @if(in_array('16',Session::get('user_rights')))
                        <li>
                            <a href="/article/list/published">Published Article</a>
                        </li>
                        @endif
			<li>
                            <a href="/article/list/drafts">My Drafts</a>
                        </li>
                        @if(in_array('17',Session::get('user_rights')))
                        <li>
                            <a href="/article/list/deleted">Deleted Articles</a>
                        </li>
                        @endif
			@if(in_array('19',Session::get('user_rights')))			
                        <li>
                            <a href="/featurebox">Feature Box Management</a>
                        </li>
                        @endif
			@if(in_array('30',Session::get('user_rights')))			
                        <li>
                            <a href="/campaing/add-management">Campaign Management</a>
                        </li>
                        @endif
                        @if(in_array('77',Session::get('user_rights')))
                        <li>
                            <a href="/magazineissue">Add A Magazine Issue</a>
                        </li>
                        @endif
                        
<!--                        <li>
                            <a href="tips.html">Tips</a>
                        </li>-->
                        
                        @if(in_array('32',Session::get('user_rights')))
                        <li>
                            <a href="#">Show reports (General)</a>
                        </li>
                        @endif
			@if(in_array('33',Session::get('user_rights')))			
                        <li>
                            <a href="#">Show reports (General)</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            @if(count(array_diff(array('20','21','34'), Session::get('user_rights'))) != count(array('20','21','34')))
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon comment_alt2_stroke"></i>
                    <span class="nav-selection">Tips &amp; Quotes</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        @if(in_array('20',Session::get('user_rights')))	
                        <li>
                            <a href="/tips">Tips</a>
                        </li>
                        @endif
                        @if(in_array('21',Session::get('user_rights')))	
                        <li>
                            <a href="/tip-tags">Tags</a>
                        </li>
                        @endif
                        @if(in_array('34',Session::get('user_rights')))	
                        <li>
                            <a href="/quotes">Quotes</a>
                        </li>
                        @endif
                        	
<!--                        <li>
                            <a href="#">Reports</a>
                        </li>	
                       	
                        <li>
                            <a href="#">Help</a>
                        </li>-->
                        
                    </ul>
                </div>
            </li>
            @endif
            @if(count(array_diff(array('79','80','81'), Session::get('user_rights'))) != count(array('79','80','81')))
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon mic"></i>
                    <span class="nav-selection">Debate</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        @if(in_array('79',Session::get('user_rights')))	
                        <li>
                            <a href="/debate/published">Published Debate</a>
                        </li>
                        @endif
                        @if(in_array('80',Session::get('user_rights')))	
                        <li>
                            <a href="#">Deleted Debate</a>
                        </li>
                        @endif
			@if(in_array('81',Session::get('user_rights')))				
                        <li>
                            <a href="/debate/create">Create New Debates</a>
                        </li>
                        @endif
<!--                        <li>
                            <a href="profanity-filter.html">Profanity Filter</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>-->
                    </ul>
                </div>
            </li>
            @endif
            @if(count(array_diff(array('9','44','45'), Session::get('user_rights'))) != count(array('9','44','45')))
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon pen"></i>
                    <span class="nav-selection">Authors Profile
                    </span>
                    <i class="icon-menu-arrow"></i> </a>
                <div class="sub-nav">
                    <ul class="nav">
                        
                        @if(in_array('2',Session::get('user_rights')))	
                        <li>
                            <a href="/article/add-edit-author">Add/Edit Columnist</a>
                        </li>
                        @endif
                         @if(in_array('44',Session::get('user_rights')))	
                        <li>
                            <a href="/guestauthor/add-edit-gustauthor">Add/Edit Guest Author</a>
                        </li>
                        @endif
                         @if(in_array('45',Session::get('user_rights')))	
                        <li>
                            <a href="/bwreporters/add-edit-bw-reporters">Add/Edit Reporters</a>
                        </li>
                        @endif
<!--                        <li>
                            <a href="#">Help</a>
                        </li>-->
                    </ul>
                </div>
            </li>
            @endif
			
	    @if(count(array_diff(array('48','49','50'), Session::get('user_rights'))) != count(array('48','49','50')))	 
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon movie"></i>
                    <span class="nav-selection">Events</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        @if(in_array('48',Session::get('user_rights')))
                        <li>
                            <a href="{{url('event/add-event-management')}}">Add New Events</a>
                        </li>
                        @endif
                        @if(in_array('49',Session::get('user_rights')))
                        <li>
                            <a href="{{url('event/published')}}">Published Events</a>
                        </li>
                        @endif
                        @if(in_array('50',Session::get('user_rights')))
                        <li>
                            <a href="#">Reports</a>
                        </li>
                        @endif
<!--                        <li>
                            <a href="#">Help</a> 
                        </li>-->
                    </ul>
                </div>
            </li>
            @endif
            @if(count(array_diff(array('23','24','25'), Session::get('user_rights'))) != count(array('23','24','25')))	 
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon steering_wheel"></i>
                    <span class="nav-selection">Quick Bytes</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                         @if(in_array('23',Session::get('user_rights')))
                        <li>
                            <a href="/quickbyte/create">Create New Quick Byte</a>
                        </li>
                         @endif
                         @if(in_array('24',Session::get('user_rights')))
                        <li>
                            <a href="/quickbyte/list/published">Published Quick Bytes</a>
                        </li>
                         @endif
			 @if(in_array('25',Session::get('user_rights')))			
                        <li>
                            <a href="/quickbyte/list/deleted">Deleted Quick Bytes</a>
                        </li>
                         @endif
<!--                         <li>
                             <a href="#">Reports</a>
                         </li>
                         <li>
                             <a href="#">Help</a>
                         </li>-->
                    </ul>
                </div>
            </li>
            @endif
	    @if(count(array_diff(array('27','28','29'), Session::get('user_rights'))) != count(array('27','28','29')))	 	
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon document_stroke"></i>
                    <span class="nav-selection">Sponsored Post</span>
                    <i class="icon-menu-arrow"></i>     </a>
                <div class="sub-nav">
                    <ul class="nav">
                        @if(in_array('27',Session::get('user_rights')))
                        <li>
                            <a href="/sposts/create">Create New Sponsored Post</a>
                        </li>
                         @endif
                         @if(in_array('28',Session::get('user_rights')))
                        <li>
                            <a href="/sposts/list/published">Published Sponsored Posts</a>
                        </li>
                         @endif
                         @if(in_array('29',Session::get('user_rights')))
                        <li>
                            <a href="/sposts/list/deleted">Deleted Sponsored Posts</a>
                        </li>
                         @endif
<!--                        <li>
                            <a href="#">Reports</a>
                        </li>
                        <li>
                            <a href="#">Help</a>
                        </li>-->
                    </ul>
                </div>
            </li>
	    @endif
            @if(count(array_diff(array('57','58','59'), Session::get('user_rights'))) != count(array('57','58','59')))	 	
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon new_window"></i>
                    <span class="nav-selection">Photos</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                         @if(in_array('59',Session::get('user_rights')))
                       <li>
                            <a href="{{url('album/create')}}">Upload New Album</a>
                        </li>
                         @endif
                         @if(in_array('57',Session::get('user_rights')))
                        <li>
                            <a href="{{url('album/list/published')}}">Published Album</a>
                        </li>
                         @endif
			 @if(in_array('58',Session::get('user_rights')))			
                        <li>
                            <a href="{{url('album/list/deleted')}}">Deleted Photos</a>
                        </li>
                         @endif
						
<!--                         <li>
                            <a href="#">Reports</a>
                        </li>
						
                        <li>
                            <a href="#">Help</a>
                        </li>-->
                    </ul>
                </div>
            </li>
            @endif
            @if(count(array_diff(array('62','63','64','65'), Session::get('user_rights'))) != count(array('62','63','64','65')))	 	
          <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon play"></i>
                    <span class="nav-selection">Videos</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        @if(in_array('65',Session::get('user_rights')))
                        <li>
                            <a href="/video/create">Upload New Video</a>
                        </li>
                        @endif
                        @if(in_array('62',Session::get('user_rights')))
                        <li>
                            <a href="/video/list">Published Videos</a>
                        </li>
                        @endif
                        @if(in_array('63',Session::get('user_rights')))
                        <li>
                            <a href="#">Deleted Videos</a>
                        </li>
                        @endif
                        @if(in_array('64',Session::get('user_rights')))
                        <li>
                            <a href="#">Featured Videos</a>
                        </li>
                        @endif
<!--                        <li>
                            <a href="#">Reports</a>
                        </li>
                        <li>
                            <a href="#">Help</a>
                        </li>-->
                    </ul>
                </div>
            </li>
            @endif
            
            @if(count(array_diff(array('68','69','70','75','85'), Session::get('user_rights'))) != count(array('68','69','70','75','85')))	 	
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon cog"></i>
                    <span class="nav-selection">Site Management</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        @if(in_array('75',Session::get('user_rights')))
                        <li>
                            <a href="/category/add-master-category">Category Master</a>
                        </li>
                        @endif
                        @if(in_array('68',Session::get('user_rights')))
                        <li>
                            <a href="location-master.html">Location Master</a>
                        </li>
                        @endif
                        @if(in_array('69',Session::get('user_rights')))
                        <li>
                            <a href="/topics">Topic Master</a>
                        </li>
                        @endif
                        @if(in_array('85',Session::get('user_rights')))
                        <li>
                            <a href="/topic/category/list">Topic Category</a>
                        </li>
                        @endif
                        @if(in_array('70',Session::get('user_rights')))
                        <li>
                            <a href="#">Tags Master</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            
            @if(count(array_diff(array('82','83','84','95'), Session::get('user_rights'))) != count(array('82','83','84','95')))	 	
                <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon mail"></i>
                    <span class="nav-selection">Newsletter</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        @if(in_array('95',Session::get('user_rights')))
                        <li>
                            <a href="{{url('newsletter/subscriber')}}">Subscriber</a>
                        </li>                       
                        @endif
                        @if(in_array('83',Session::get('user_rights')))
                        <li>
                            <a href="{{url('newsletter')}}">Manage Newsletter</a>
                        </li>
                        @endif
                        @if(in_array('84',Session::get('user_rights')))
                        <li>
                            <a href="{{url('newsletter/create')}}">Create Newsletter</a>
                        </li>
                        @endif
                        
                    </ul>
                </div>
            </li>
            @endif
            
            @if(count(array_diff(array('72','73'), Session::get('user_rights'))) != count(array('72','73')))	 	
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon cog"></i>
                    <span class="nav-selection">Rights Management</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        @if(in_array('72',Session::get('user_rights')))
                        <li>
                            <a href="{{url('rights')}}">CMS Rights</a>
                        </li>
                        
                        @endif
                        @if(in_array('73',Session::get('user_rights')))
                        <li>
                            <a href="{{url('roles/manage')}}">Manage Roles</a>
                        </li>
                        
                        <li>
                            <a href="{{url('roles/add')}}">Add Roles</a>
                        </li>
                        @endif
<!--                        <li>
                            <a href="#">Reports</a>
                        </li>
                        <li>
                            <a href="#">Help</a>
                        </li>-->
                    </ul>
                </div>
            </li>
            @endif
            
            @if(count(array_diff(array('87','88','89','90','91','92','93'), Session::get('user_rights'))) != count(array('87','88','89','90','91','92','93')))	 	
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon book"></i>
                    <span class="nav-selection">Subscription</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        @if(in_array('87',Session::get('user_rights')))
                        <li>
                            <a href="/subscription/packages">Manage Package</a>
                        </li>
                        @endif
                        @if(in_array('88',Session::get('user_rights')))
                        <li>
                            <a href="/subscription/discounts">Manage Discount</a>
                        </li>
                        @endif
                        @if(in_array('93',Session::get('user_rights')))                        
                        <li>
                            <a href="/subscription/freebies">Manage Freebies</a>
                        </li>
                        @endif
                        @if(in_array('89',Session::get('user_rights')))
                        <li>
                            <a href="/subscribers">Subscriber</a>
                        </li>
                        <li>
                            <a href="/subscribers/deleted">Deleted Subscriber</a>
                        </li>
                        @endif
                        @if(in_array('90',Session::get('user_rights')))
                        <li>
                            <a href="/subscriptions/active">Active Orders</a>
                        </li>
                        @endif
                        @if(in_array('91',Session::get('user_rights')))
                        <li>
                            <a href="/subscriptions/expiring">Expiring Soon</a>
                        </li>
                        @endif
                         @if(in_array('92',Session::get('user_rights')))
                        <li>
                            <a href="/subscriptions/expired">Expired Orders</a>
                        </li>
                        @endif
                        
                         @if(in_array('94',Session::get('user_rights')))
                        <li>
                            <a href="/subscriptions/pending">Pending Orders</a>
                        </li>
                        @endif
                        
                        
                    </ul>
                </div>
            </li>
            @endif
        <li class="nav-logout">
                <a href="/auth/logout">
                    <i class="icon-photon key_stroke"></i><span class="nav-selection">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
