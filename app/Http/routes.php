<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Auth\AuthController@getLogin');
/*
Route::get('/', function () {
   // return view('welcome');
      return View::make('login');
});
*/
Route::get('/dashboard', function () {
    // return view('welcome');
    return view('layouts.dashboard');
});

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
Route::post('article/image/upload', ['middleware' => 'auth',   'uses' => 'ArticlesController@imageUpload' ]);
Route::get('article/image/upload','ArticlesController@imageUpload');
//Route::delete('article/image/upload', 'ArticlesController@imageUpload');
Route::post('article', ['middleware' => 'auth',   'uses' => 'ArticlesController@store' ]);
Route::get('article/publishscheduled','ArticlesController@publishScheduledArticle');
Route::get('profile', ['middleware' => 'auth',   'uses' => 'ArticlesController@create' ]);

/*
Route::controllers([
    'auth'  =>  'Auth\AuthController',
    'password'  => 'Auth\PasswordController'
]);
*/


// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
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
    $arrSubCat = DB::table('category'.$type)->where($key_id, $input)->orderBy('name')->lists('category'.$type.'_id','name');

    return $arrSubCat;
});

/*
 * Get Relevant Event Array on channel_id passed + null channel_id via Ajax
 */
Route::get('/article/event/', function(){
    $option = Input::get('option');
    $eventList = DB::table('event')
        ->where('channel_id',$option)
        ->orWhere('channel_id', '=', '')
        ->lists('event_id','title');
    return $eventList;
});
/*
 * Get Relevant Campaign Array on channel_id via Ajax
 */
Route::get('/article/campaign/', function(){
    $option = Input::get('option');
    $campaignList = DB::table('campaign')->where('channel_id',$option)->lists('campaign_id','title');
    return $campaignList;
});
/*
 * Get Relevant Magazine Array on channel_id via Ajax
 */
Route::get('/article/magazine/', function(){
    $option = Input::get('option');
    $magazineList = DB::table('magazine')->where('channel_id',$option)->lists('magazine_id','title');
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

/*
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
Route::get('sposts/{id}','SponsoredPostsController@show');

/*
 * CMS Rights - Management
 */
Route::get('rights/', ['middleware' => 'auth',   'uses' => 'RightsController@index']);
Route::get('rights/edit', ['middleware' => 'auth',   'uses' => 'RightsController@edit']);
Route::post('rights', ['middleware' => 'auth',   'uses' => 'RightsController@store' ]);
Route::post('rights/manage', ['middleware' => 'auth',   'uses' => 'RightsController@update' ]);
Route::match(['get', 'post'], '/rights/delete', ['as' => '/rights/delete', 'uses' => 'RightsController@destroy']);
Route::get('rights/{id}','RightsController@edit');


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
