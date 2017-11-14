<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use App\TopicCategory;
use App\Topic;
use Auth;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TopicCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct() {
        $this->middleware('auth');
        $this->rightObj = new Right();
    }
    
    public function index()
    {   
        /* Right mgmt start */
        $rightId = 85;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */ 
       $query = DB::table('topic_category');
 
       if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
                $query->where('topic_category.name', 'LIKE', '%'.$queryed.'%');
	
        }
        $query->where('is_deleted','=','0');
        $query->orderby('updated_at','desc');
        $categories=$query->paginate(config('constants.recordperpage'));
        return view('topic.category',compact('categories'));
    }
 

    
    public function create()
    {
        $rightId = 85;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        
        return view('topic.category_create');
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        
        $rightId = 85;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        $topicCategory = new TopicCategory();
        $topicCategory->name=$request->title;
        $topicCategory->save();
        Session::flash('message', 'Topic category created sucessfully.');
        return Redirect::to('topic/category/list');
       
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $rightId = 85;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        $category=TopicCategory::find($id);
        return view('topic.category_edit',compact('category'));

        
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
         $rightId = 85;
         if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
         $category=TopicCategory::find($request->category_id);
         $category->name=$request->title;
         $category->update();
         Session::flash('message', 'Topic category updated sucessfully.');
         return Redirect::to('topic/category/list');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
         $rightId = 85;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
            
        }
           // echo $id; die;
        //fwrite($asd, " Del Ids: ".$id." \n\n");
        $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
             $category=TopicCategory::find($d);
             $category->is_deleted=1;
             $category->save();
        }
      return 'success';
    }
    
   
}
