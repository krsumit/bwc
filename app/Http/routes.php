<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['middleware' => 'guest',   'uses' =>'Auth\AuthController@getLogin']);
/*
Route::get('/', function () {
   // return view('welcome');
      return View::make('login');
});
*/
Route::get('/dashboard', ['middleware' => 'auth',   'uses' => function () {
    //echo '<pre>';
   //    echo print_r($_COOKIE);
    //   print_r(Auth::user());exit;
  //  echo '<pre>';
 //print_r(Auth::user()); exit;;
//    if(Auth::guest()){
//        echo '1'; exit;
//    }else{
//        echo '2'; exit;
//    }
 $posts = DB::table('articles')
        ->join('article_author', 'article_author.article_id', '=', 'articles.article_id')
        ->join('authors', 'article_author.author_id', '=', 'authors.author_id')
        ->select('articles.article_id','article_author.author_id','article_author.article_id','authors.*'  ) 
        ->where('articles.status', '=', 'p')  
        ->orderBy('articles.article_id', 'desc')
        ->groupBy('authors.author_id')
        ->take(5)->get();
 
 $article_publish = DB::table('articles')
         ->select('articles.*')
         ->where('articles.status', '=', 'p')  
         ->count();
 $quickbyte_publish = DB::table('quickbyte')
         ->select('quickbyte.*')
         ->where('quickbyte.status', '=', 'p')  
         ->count();
 
 $columns_publish = DB::table('columns')
         ->select('columns.*')
         ->where('columns.valid', '=', '1')  
         ->count();
 $photos_publish = DB::table('photos')
         ->select('photos.*')
         ->where('photos.valid', '=', '1')  
         ->count();
 $videos_publish = DB::table('videos')
         ->select('videos.*')
         ->where('videos.valid', '=', '1')  
         ->count();
 
    return view('layouts.dashboard',compact('posts','article_publish','quickbyte_publish','columns_publish','photos_publish','videos_publish') );
}]);

Route::get('/notlog', function () {
    // return view('welcome');
    return view('auth.notlog');
});

Route::get('child', function () {
    return view('layouts/dashboard2');
});

// Article - Module
Route::get('article/create', ['middleware' => 'auth',   'uses' => 'ArticlesController@create']);
Route::get('article/list/{option}', ['middleware' => 'auth',   'uses' => 'ArticlesController@index']);

//Route::get('article/list/{option}',['middleware' => 'auth', 'uses' => 'ArticlesController@index','as' => 'search']);



Route::post('article/update', ['middleware' => 'auth',   'uses' => 'ArticlesController@update' ]);
Route::post('article/image/upload', ['middleware' => 'auth','uses' => 'ArticlesController@imageUpload']);
Route::get('article/image/upload',['middleware' => 'auth','uses' => 'ArticlesController@imageUpload']);
Route::get('article/image/edit',['middleware' => 'auth','uses' => 'ArticlesController@imageEdit']);
Route::post('article/image/update',['middleware' => 'auth','uses' => 'ArticlesController@storeImageDetail']);
//Route::delete('article/image/upload', 'ArticlesController@imageUpload');
Route::post('article/sort/{id}','ArticlesController@sortImage');
Route::post('article', ['middleware' => 'auth',   'uses' => 'ArticlesController@store' ]);
Route::get('article/publishscheduled','ArticlesController@publishScheduledArticle');
Route::post('article/relatedimage', ['middleware' => 'auth',   'uses' => 'ArticlesController@relatedImage' ]);
Route::get('profile', ['middleware' => 'auth',   'uses' => 'ArticlesController@create' ]);
/*
 *  Delete Image from Create Article Form - Ajax Request
 */

/*
Topic  urls
 */
Route::get('topics/create', ['middleware' => 'auth',   'uses' => 'TopicsController@create']);
Route::post('topics', ['middleware' => 'auth',   'uses' => 'TopicsController@store' ]);
Route::get('topics/edit/{id}','TopicsController@show');
Route::post('topics/update', ['middleware' => 'auth',   'uses' => 'TopicsController@update' ]);
Route::get('topics', ['middleware' => 'auth',   'uses' => 'TopicsController@index']);
Route::match(['get', 'post'], 'topics/delete', ['middleware' => 'auth', 'uses' => 'TopicsController@destroy']);

Route::get('topic/category/create', ['middleware' => 'auth',   'uses' => 'TopicCategoryController@create']);
Route::post('topic/category', ['middleware' => 'auth',   'uses' => 'TopicCategoryController@store' ]);
Route::get('topic/category/edit/{id}','TopicCategoryController@show');
Route::post('article/category/update', ['middleware' => 'auth',   'uses' => 'TopicCategoryController@update' ]);
Route::get('topic/category/list', ['middleware' => 'auth',   'uses' => 'TopicCategoryController@index']);
Route::match(['get', 'post'], 'topic/category/delete', ['middleware' => 'auth', 'uses' => 'TopicCategoryController@destroy']);

/*
Route::match(['get', 'post'], 'article/delPhotos', ['as' => 'article/delPhotos', 'uses' => 'PhotosController@destroy']);
/*
 *  Delete article using ajax
 */
Route::match(['get', 'post'], 'article/delete', ['as' => 'article/delete', 'uses' => 'ArticlesController@destroy']);

/*
 *  Publish image using ajax
 */
Route::match(['get', 'post'], 'article/publish', ['as' => 'article/publish', 'uses' => 'ArticlesController@publishBulk']);
/*
Route::controllers([
    'auth'  =>  'Auth\AuthController',
    'password'  => 'Auth\PasswordController'
]);
*/


// Authentication routes...
Route::get('auth/login', ['middleware' => 'guest',   'uses' =>'Auth\AuthController@getLogin']);
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

/*
 * Ajax GET Request for Category DropDowns on Create Article Page
 * Drop Down Request for SubCategories-2/3/4
 *
 * @returns JSON array of Subcategory Elements
 */
Route::get('article/dropdown1', function(){
    $input = Input::get('option');
    $iarrVals = explode('&',$input);
    $secondArr = explode('=',$iarrVals[1]);
    $type = $secondArr[1];
    //$type = Input::get('level');
    //$l = fopen('/home/sudipta/check.log','a+');
    //fwrite($l,"\n Option".$input." L:".$type);

    $key_id = "";

    // To select Right Foreign Key Name
    switch($type){
        case "_two":
             $key_id = 'category_id';
        break;
        case "_three":
             $key_id = 'category_two_id';
        break;
        case "_four":
             $key_id = 'category_three_id';
            break;
        default:
            $key_id = 'channel_id';
            break;
    }
    //fwrite($l,"\n Key ID ".$type);
    $arrSubCat = DB::table('category'.$type)->where('valid','=','1')->where($key_id, $input)->orderBy('name')->lists('category'.$type.'_id','name');

    return $arrSubCat;
});

/*
 * Get Relevant Event Array on channel_id passed + null channel_id via Ajax
 */
Route::get('/article/event/', function(){
    $option = Input::get('option');
    $eventList = DB::table('event')
        ->where('channel_id',$option)
        ->where('valid','=','1')    
        ->lists('event_id','title');
    return $eventList;
});
/*
 * Get Relevant Campaign Array on channel_id via Ajax
 */
Route::get('/article/campaign/', function(){
    $option = Input::get('option');
    $campaignList = DB::table('campaign')->where('valid','=','1')->where('channel_id',$option)->lists('campaign_id','title');
    return $campaignList;
});
/*
 * Get Relevant Magazine Array on channel_id via Ajax
 */
Route::get('/article/magazine/', function(){
    $option = Input::get('option');
    $magazineList = DB::table('magazine')->where('valid','=','1') ->where('channel_id',$option)->lists('magazine_id','title');
    return $magazineList;
});

//Drop Down Request for Author List Population in Create Form
Route::get('/article/authordd/', function(){
    $option = Input::get('option');
    $input = $option;
    $authorList = DB::table('authors')->where('author_type_id',$input)->orderBy('name')->lists('author_id','name');

    return $authorList;
});

//Ajax Post of New Author Addition
Route::post('article/addAuthor2', function(){
    //print("Here 1");
    //dd("Here");
    //$p = Input::get('option');
    //$p = Input::get('data');;
    //$p = $_POST['author_type'];
    $p = sizeof($_POST);
    //print_r($_POST);
    //$l = fopen('/home/sudipta/check.log','w+');
    //fwrite($l,"TRUCK".$p);

    $data1 = Request::all();
    //print_r($data1);
   $s = $data1['name'].$data1['bio'].$data1['email'].$data1['column_id'].$data1['mobile'].$data1['twitter'].$data1['author_type'];
    //echo "\n Request sz:".$s = sizeof($data1);
    //$s = sizeof($data1);
    //$s = $data1['name'];
    //fwrite($l,"\nTRUCK Size: ".$s);
    /*
     * Add sent Author Details to Author Table as New record
     *
     * @returns Confirmation text message
     */
    //$is = \App\Http\Controllers\ArticlesController->addAut;
    //echo $_POST['author_type'];
    //$input = Input::get('option');
    //$authorList = DB::table('authors')->where('author_type_id',$input)->lists('author_id','name');
    return;
    //return $authorList;
});

/*
 * Add Ajax sent Author Details to Table
 *
 */

Route::post('article/addAuthor1', function(){

    $s = sizeof($_FILES);
    //$v = $_POST['photo']['name'];
    //print_r($_POST);
    //$l = fopen('/home/sudipta/check.log','a+');
    //fwrite($l,"TRUCK :".$s);

    return;
});


/*
 *  Adds Author from CreateArticle to Authors Table - Ajax Request
 */
Route::match(['get', 'post'], 'article/addAuthor', ['as' => 'article/addAuthor', 'uses' => 'AuthorsController@store']);

Route::match(['get', 'post'], 'article/add-edit-author', ['as' => 'article/add-edit-author', 'uses' => 'AuthorsController@index']);
Route::match(['get', 'post'], 'columnist/edit', ['as' => 'columnist/edit', 'uses' => 'AuthorsController@edit']);

Route::match(['get', 'post'], 'guestauthor/add-edit-gustauthor', ['as' => 'guestauthor/add-edit-gustauthor', 'uses' => 'AuthorsController@gustauthor']);
Route::match(['get', 'post'], 'bwreporters/add-edit-bw-reporters', ['as' => 'bwreporters/add-edit-bw-reporters', 'uses' => 'AuthorsController@bwreporters']);
Route::match(['get', 'post'], 'author/delete', ['as' => 'author/delete', 'uses' => 'AuthorsController@destroy']);
/*
 *  Adds category from Createcategory to category Table 
 */

Route::match(['get', 'post'], 'category/add-master-category', ['as' => 'category/add-master-category', 'uses' => 'categoryController@index']);
Route::match(['get', 'post'], 'category/add', ['as' => 'category/add', 'uses' => 'categoryController@store']);

Route::match(['get', 'post'], 'mastercategory/delete', ['as' => 'mastercategory/delete', 'uses' => 'categoryController@destroy']);

Route::match(['get', 'post'], 'sub-category-master/add/', ['as' => 'sub-category-master/add/', 'uses' => 'categoryController@subcategoryindex']);
Route::match(['get', 'post'], 'secondcategory/delete', ['as' => 'secondcategory/delete', 'uses' => 'categoryController@destroysecond']);
Route::match(['get', 'post'], 'sub-category_second_master/add/', ['as' => 'sub-category_second_master/add/', 'uses' => 'categoryController@subcategorythirdindex']);
Route::match(['get', 'post'], 'thirdcategory/delete', ['as' => 'thirdcategory/delete', 'uses' => 'categoryController@destroysthird']);
Route::match(['get', 'post'], 'sub-category_third_master/add/', ['as' => 'sub-category_third_master/add/', 'uses' => 'categoryController@subcategoryfourindex']);
Route::match(['get', 'post'], 'fourcategory/delete', ['as' => 'fourcategory/delete', 'uses' => 'categoryController@destroysfour']);
/*
 *  Adds campaing from campaing-management to campaing Table 
 */
Route::match(['get', 'post'], 'campaing/add-management', ['as' => 'campaing/add-management', 'uses' => 'campaingController@index']);
Route::match(['get', 'post'], 'campaing/add', ['as' => 'campaing/add', 'uses' => 'campaingController@store']);

Route::match(['get', 'post'], 'campaing/edit', ['as' => 'campaing/edit', 'uses' => 'campaingController@edit']);
Route::match(['get', 'post'], 'campaing/delete', ['as' => 'campaing/delete', 'uses' => 'campaingController@destroy']);

/*
 *  Adds events from add-new-events to events Table 
 */

Route::match(['get', 'post'], 'event/add-event-management', ['as' => 'event/add-event-management', 'uses' => 'eventController@index']);
Route::match(['get', 'post'], 'event/add', ['as' => 'event/add', 'uses' => 'eventController@store']);
Route::match(['get', 'post'], 'event/published', ['as' => 'event/published', 'uses' => 'eventController@published']);
Route::match(['get', 'post'], 'event/edit', ['as' => 'event/edit', 'uses' => 'eventController@edit']);
Route::match(['get', 'post'], 'event/delete', ['as' => 'event/delete', 'uses' => 'eventController@destroy']);
Route::match(['get', 'post'], 'event/update', ['as' => 'event/update', 'uses' => 'eventController@update']);




/*
 * 
 *  Adds Tag from CreateArticle to Tags Table - Ajax Request
 */
//Route::post('article/addTag','TagsController@store');
Route::match(['get', 'post'], 'article/addTag', ['as' => 'article/addTag', 'uses' => 'TagsController@store']);

Route::get('tags/getJson','TagsController@returnJson');


/*
 *  Adds Images from CreateArticle to Images Table - Ajax Request
 */
Route::match(['get', 'post'], 'article/addPhotos', ['as' => 'article/addPhotos', 'uses' => 'PhotosController@store']);
/*
 *  Delete Image from Create Article Form - Ajax Request
 */
Route::get('photo/crop', ['middleware' => 'auth',   'uses' => 'PhotosController@cropImage']);
Route::get('photo/resize/crop', ['middleware' => 'auth',   'uses' => 'PhotosController@resizeCropImage']);

Route::match(['get', 'post'], 'article/delPhotos', ['as' => 'article/delPhotos', 'uses' => 'PhotosController@destroy']);

/*
 *  Adds Video from CreateArticle to Images Table - Ajax Request
 */
Route::match(['get', 'post'], 'article/addVideos', ['as' => 'article/addVideos', 'uses' => 'VideosController@store']);

/*
 *  Generates Topics from Article Description
 */
Route::match(['get', 'post'], 'article/generateTopics', ['as' => 'article/generateTopics', 'uses' => 'TopicsController@generate']);

//
Route::get('article/{id}','ArticlesController@show');

/*
 * Feature Box Management
 */
Route::get('/featurebox', ['middleware' => 'auth',   'uses' => 'FeatureBoxController@create']);
Route::post('/featurebox', ['middleware' => 'auth',   'uses' => 'FeatureBoxController@store']);
Route::match(['get', 'post'], 'featurebox/edit', ['as' => 'featurebox/edit', 'uses' => 'FeatureBoxController@edit']);
Route::match(['get', 'post'], 'featurebox/delete', ['as' => 'featurebox/delete', 'uses' => 'FeatureBoxController@destroy']);


/*
 * magazine issue Management
 */
Route::get('/magazineissue', ['middleware' => 'auth',   'uses' => 'MagazineissueController@create']);
Route::post('/magazineissue/add', ['middleware' => 'auth',   'uses' => 'MagazineissueController@store']);
Route::match(['get', 'post'], 'magazineissue/edit', ['as' => 'magazineissue/edit', 'uses' => 'MagazineissueController@edit']);
Route::match(['get', 'post'], 'magazineissue/delete', ['as' => 'magazineissue/delete', 'uses' => 'MagazineissueController@destroy']);
Route::match(['get', 'post'], 'magazineissue/update', ['as' => 'magazineissue/update', 'uses' => 'MagazineissueController@update']);



/*
 * Tips, Tip-Tags, Quotes
 */
Route::get('/tips', ['middleware' => 'auth',   'uses' => 'TipsController@create']);
Route::post('/tips', ['middleware' => 'auth',   'uses' => 'TipsController@store']);
Route::get('/tips/list', ['middleware' => 'auth',   'uses' => 'TipsController@show']);
Route::match(['get', 'post'], '/tips/edit', ['as' => '/tips/edit', 'uses' => 'TipsController@edit']);
Route::match(['get', 'post'], '/tips/delete', ['as' => '/tips/delete', 'uses' => 'TipsController@destroy']);

Route::get('/tip-tags', ['middleware' => 'auth',   'uses' => 'TipTagsController@create']);
Route::get('/tip-tags', ['middleware' => 'auth',   'uses' => 'TipTagsController@create']);
Route::post('/tip-tags', ['middleware' => 'auth',   'uses' => 'TipTagsController@store']);
Route::match(['get', 'post'], '/tiptags/edit', ['as' => '/tiptags/edit', 'uses' => 'TipTagsController@edit']);

Route::get('/quotes', ['middleware' => 'auth',   'uses' => 'QuotesController@create']);
Route::post('/quotes', ['middleware' => 'auth',   'uses' => 'QuotesController@store']);
Route::match(['get', 'post'], '/quotes/edit', ['as' => '/quotes/edit', 'uses' => 'QuotesController@edit']);
Route::match(['get', 'post'], '/quotes/delete', ['as' => '/quotes/delete', 'uses' => 'QuotesController@destroy']);
Route::match(['get', 'post'], 'quotes/addTag', ['as' => '/quotes/addTag', 'uses' => 'QuoteTagsController@store']);
Route::get('/quotes/tags/getJson','QuoteTagsController@returnJson');
Route::get('/author/getJson','QuoteTagsController@returnauthorJson');


/*
 * QuickByte - Create, Published List, Deleted List
 */
Route::get('quickbyte/create', ['middleware' => 'auth',   'uses' => 'QuickBytesController@create']);
Route::get('quickbyte/list/{option}', ['middleware' => 'auth',   'uses' => 'QuickBytesController@index']);
Route::post('quickbyte/update', ['middleware' => 'auth',   'uses' => 'QuickBytesController@update' ]);
Route::match(['get', 'post'], 'quickbyte/upload', ['middleware' => 'auth',   'uses' => 'QuickBytesController@uploadImg']);
Route::post('quickbyte', ['middleware' => 'auth',   'uses' => 'QuickBytesController@store' ]);
Route::match(['get', 'post'], '/quickbyte/delete', ['as' => '/quickbyte/delete', 'uses' => 'QuickBytesController@destroy']);
Route::match(['get', 'post'], '/quickbyte/publish', ['as' => '/quickbyte/publish', 'uses' => 'QuickBytesController@publishBulk']);
Route::get('quickbyte/{id}','QuickBytesController@show');
Route::post('quickbyte/image/upload', ['middleware' => 'auth',   'uses' => 'ArticlesController@imageUpload' ]);
Route::get('quickbyte/image/upload','ArticlesController@imageUpload');
Route::post('quickbyte/sort/{id}','QuickBytesController@sortImage');


/* Debate start here */
Route::get('debate/create', ['middleware' => 'auth',   'uses' => 'DebateController@create']);
Route::post('debate', ['middleware' => 'auth',   'uses' => 'DebateController@store' ]);
Route::get('debate/published', ['middleware' => 'auth',   'uses' => 'DebateController@index']);
Route::get('debate/edit/{id}',['middleware' => 'auth',   'uses' =>'DebateController@edit']);
Route::post('debate/update', ['middleware' => 'auth',   'uses' => 'DebateController@update']);
Route::match(['get', 'post'], '/debate/delete', ['middleware' => 'auth','as' => '/debate/delete', 'uses' => 'DebateController@destroy']);
/* Debate ends*/



/*Album routs start here */
Route::get('album/create', ['middleware' => 'auth',   'uses' => 'AlbumController@create']);
Route::get('album/list/{option}', ['middleware' => 'auth',   'uses' => 'AlbumController@index']);
Route::post('album/update', ['middleware' => 'auth',   'uses' => 'AlbumController@update' ]);
Route::match(['get', 'post'], 'album/upload', ['middleware' => 'auth',   'uses' => 'AlbumController@uploadImg']);
Route::post('album', ['middleware' => 'auth',   'uses' => 'AlbumController@store' ]);
Route::match(['get', 'post'], '/album/delete', ['as' => '/album/delete', 'uses' => 'AlbumController@destroy']);
Route::match(['get', 'post'], '/album/publish', ['as' => '/album/publish', 'uses' => 'AlbumController@publishBulk']);
Route::get('album/{id}','AlbumController@show');
Route::post('album/image/upload', ['middleware' => 'auth',   'uses' => 'AlbumController@imageUpload' ]);
Route::get('album/image/upload','AlbumController@imageUpload');
Route::post('album/sort/{id}','AlbumController@sortImage');
/*Album routs end here */
/*
 * Sponsored Post - Create, Published List, Deleted List
 */
Route::get('sposts/create', ['middleware' => 'auth',   'uses' => 'SponsoredPostsController@create']);
Route::get('sposts/list/{option}', ['middleware' => 'auth',   'uses' => 'SponsoredPostsController@index']);
Route::post('sposts/update', ['middleware' => 'auth',   'uses' => 'SponsoredPostsController@update' ]);
//Route::match(['get', 'post'], 'sposts/upload', ['middleware' => 'auth',   'uses' => 'SponsoredPostsController@uploadImg']);
Route::post('sposts', ['middleware' => 'auth',   'uses' => 'SponsoredPostsController@store' ]);
Route::match(['get', 'post'], '/sposts/delete', ['as' => '/sposts/delete', 'uses' => 'SponsoredPostsController@destroy']);
Route::get('sposts/publish','SponsoredPostsController@publishBulk');
Route::get('sposts/{id}','SponsoredPostsController@show');
Route::post('sposts/image/upload', ['middleware' => 'auth',   'uses' => 'SponsoredPostsController@imageUpload' ]);
Route::get('sposts/image/upload','SponsoredPostsController@imageUpload');

/*
 * CMS Rights - Management
 */
Route::get('rights/', ['middleware' => 'auth',   'uses' => 'RightsController@index']);
//Route::get('rights/edit', ['middleware' => 'auth',   'uses' => 'RightsController@edit']);
Route::post('rights', ['middleware' => 'auth',   'uses' => 'RightsController@store' ]);
Route::post('rights/manage', ['middleware' => 'auth',   'uses' => 'RightsController@update' ]);
Route::match(['get', 'post'], '/rights/delete', ['as' => '/rights/delete', 'uses' => 'RightsController@destroy']);
Route::get('rights/{id}','RightsController@edit');
// Roles management
Route::get('roles/manage',['middleware' => 'auth','uses' => 'RightsController@manageRole']);
Route::get('roles/add', ['middleware' => 'auth',   'uses' => 'RightsController@addRole']);
Route::get('roles/edit/{id}', ['middleware' => 'auth',   'uses' => 'RightsController@editRole']);
Route::post('roles/store', ['middleware' => 'auth',   'uses' => 'RightsController@storeRole']);
Route::post('roles/update', ['middleware' => 'auth',   'uses' => 'RightsController@updateRole']);
Route::match(['get', 'post'], 'roles/delete', ['as' => 'roles/delete', 'uses' => 'RightsController@destroyRole']);
Route::get('roles/get/channel/permission',['middleware' => 'auth','uses' => 'RightsController@getRoleChannelPermission']);

/*Video routs start here */
Route::get('video/create', ['middleware' => 'auth',   'uses' => 'VideoController@create']);
Route::get('video/list', ['middleware' => 'auth',   'uses' => 'VideoController@index']);
Route::post('video/update', ['middleware' => 'auth',   'uses' => 'VideoController@update' ]);
Route::match(['get', 'post'], 'video/upload', ['middleware' => 'auth',   'uses' => 'VideoController@uploadImg']);
Route::post('video','VideoController@store');
Route::match(['get', 'post'], '/video/delete', ['as' => '/video/delete', 'uses' => 'VideoController@destroy']);
Route::match(['get', 'post'], '/video/publish', ['as' => '/video/publish', 'uses' => 'VideoController@publishBulk']);
Route::get('video/{id}','VideoController@show');
Route::post('video/image/upload', ['middleware' => 'auth',   'uses' => 'VideoController@imageUpload' ]);
Route::get('video/image/upload','VideoController@imageUpload');

/*Video routs end here */

/* Newsletter start here*/
Route::get('newsletter/create', ['middleware' => 'auth',   'uses' => 'MasternewsletterController@create']);
Route::get('newsletter/manage/{id}',['middleware' => 'auth',   'uses' => 'MasternewsletterController@show']);
Route::get('newsletter', ['middleware' => 'auth',   'uses' => 'MasternewsletterController@index']);
Route::post('newsletter', ['middleware' => 'auth',   'uses' => 'MasternewsletterController@store' ]);
Route::post('newsletter/update', ['middleware' => 'auth',   'uses' => 'MasternewsletterController@update' ]);
Route::post('newsletter/assign', ['middleware' => 'auth',   'uses' => 'MasternewsletterController@assign' ]);
Route::post('newsletter/sort/{id}', ['middleware' => 'auth',   'uses' => 'MasternewsletterController@sortNewsletter' ]);
Route::match(['get', 'post'], 'newsletter/delete', ['middleware' => 'auth', 'uses' => 'MasternewsletterController@destroy']);
Route::match(['get', 'post'], 'newsletter/deletens', ['middleware' => 'auth', 'uses' => 'MasternewsletterController@destroyNewsletter']);

/* Newsletter end here*/

/* Api route start here */

Route::match(['get', 'post'], 'api/video','ApiController@videoApi');

/* Api route ends here*/

//Test Purpose - Get
Route::get('newform', function () {
    return view('layouts/testpost');
});

//Test Purpose - Post
Route::post('api/contact_store', function () {

    print_r($_POST);
    print_r($_FILES);
    echo ($_POST['_token']);

    //return view('layouts/testpost');

});
