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
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon document_alt_stroke"></i>
                    <span class="nav-selection">Articles</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="/article/create">Create New Articles</a>
                        </li>
						<li>
                            <a href="/article/list/new">New Articles</a>
                        </li>
						<li>
                            <a href="/article/list/scheduled">Scheduled Articles</a>
                        </li>
                        <li>
                            <a href="/article/list/published">Published Article</a>
                        </li>
						<li>
                            <a href="/article/list/drafts">My Drafts</a>
                        </li>
                        <li>
                            <a href="/article/list/deleted">Deleted Articles</a>
                        </li>
						<li>
                            <a href="/featurebox">Feature Box Management</a>
                        </li>
						<li>
                            <a href="campaign-management.html">Campaign Management</a>
                        </li>
                        <li>
                            <a href="add-a-magazine-issue.html">Add A Magazine Issue</a>
                        </li>
                        <li>
                            <a href="tips.html">Tips</a>
                        </li>
                        <li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon comment_alt2_stroke"></i>
                    <span class="nav-selection">Tips &amp; Quotes</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="/tips">Tips</a>
                        </li>
                        <li>
                            <a href="/tip-tags">Tags</a>
						<li>
                            <a href="/quotes">Quotes</a>
                        </li>
                        <li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			<li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon mic"></i>
                    <span class="nav-selection">Debate</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="published-debates.html">Published Debate</a>
                        </li>
                        <li>
                            <a href="create-new-debate.html">Create New Debates</a>
                        </li>
						<li>
                            <a href="new-comments-for-debate.html">Debate Comments</a>
                        </li>
                        <li>
                            <a href="profanity-filter.html">Profanity Filter</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			   <li>
			     <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon pen"></i>
                    <span class="nav-selection">Columnist and Guest Author
					</span>
                         <i class="icon-menu-arrow"></i> </a>
									<div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="add-edit-columnist.html">Add/Edit Columnist</a>
                        </li>
						<li>
                            <a href="add-new-guest-author.html">Add New Guest Author</a>
                        </li>
                        <li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			
			 
			<li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon movie"></i>
                    <span class="nav-selection">Events</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="add-new-events.html">Add New Events</a>
                        </li>
                        <li>
                            <a href="published-events.html">Published Events</a>
                        </li>
						<li>
                            <a href="deleted-events.html">Deleted Events</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a> 
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon steering_wheel"></i>
                    <span class="nav-selection">Quick Bytes</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="/quickbyte/create">Create New Quick Byte</a>
                        </li>
                        <li>
                            <a href="/quickbyte/list/published">Published Quick Bytes</a>
                        </li>
						<li>
                            <a href="/quickbyte/list/deleted">Deleted Quick Bytes</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			
			<li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon document_stroke"></i>
                    <span class="nav-selection">Sponsored Post</span>
                   <i class="icon-menu-arrow"></i>     </a>
                 <div class="sub-nav">
                 	<ul class="nav">
                        <li>
                        	<a href="/sposts/create">Create New Sponsored Post</a>
                        </li>
                        <li>
                            <a href="/sposts/list/published">Published Sponsored Posts</a>
                        </li>
						<li>
                            <a href="/sposts/list/deleted">Deleted Sponsored Posts</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			
         <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon new_window"></i>
                    <span class="nav-selection">Photos</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="<?php echo url('album/create')?>">Upload New Album</a>
                        </li>
                        <li>
                            <a href="<?php echo url('album/list/published')?>">Published Album</a>
                        </li>
						<li>
                            <a href="<?php echo url('album/list/deleted')?>">Deleted Photos</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon play"></i>
                    <span class="nav-selection">Videos</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="upload-new-Video.html">Upload New Video</a>
                        </li>
                        <li>
                            <a href="published-Videos.html">Published Videos</a>
                        </li>
						<li>
                            <a href="deleted-Videos.html">Deleted Videos</a>
                        </li>
						<li>
                            <a href="featured-Videos.html">Featured Videos</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>

			<li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon cog"></i>
                    <span class="nav-selection">Site Management</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="category-master.html">Category Master</a>
                        </li>
						<li>
                            <a href="location-master.html">Location Master</a>
                        </li>
						<li>
                            <a href="topic-master.html">Topic Master</a>
                        </li>
                        <li>
                            <a href="tags.html">Tags Master</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon cog"></i>
                    <span class="nav-selection">Rights Management</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="/rights">CMS Rights</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>

			<li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon info"></i>
                    <span class="nav-selection">Help</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="#">FAQ</a>
                        </li>
						<li>
                            <a href="#">Sitemap</a>
                        </li>
                    </ul>
                </div>
            </li>

        <li class="nav-logout">
                <a href="/auth/logout">
                    <i class="icon-photon key_stroke"></i><span class="nav-selection">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
