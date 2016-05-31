<?php
namespace App\Http\Controllers;
use Redirect;
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

class TopicsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct() {
        
        $this->rightObj = new Right();
    }
    
    public function index()
    {   
        /* Right mgmt start */
        $rightId = 69;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */ 
       $query = DB::table('topics');
       $query->Leftjoin('topic_category','topics.category_id','=','topic_category.id');
       $query->select('topics.topic','topics.id','topics.created_at','topics.updated_at','topic_category.name');
       if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
                $query->where('topics.topic', 'LIKE', '%'.$queryed.'%');
	
        }
        
        $query->where('valid','=','1');
        $query->orderby('topics.updated_at','desc');
        $topics=$query->paginate(config('constants.recordperpage'));
        return view('topic.topic',compact('topics'));
    }
 

    
    public function create()
    {
        $rightId = 69;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        $categories=TopicCategory::where('is_deleted','=','0')->get();
        return view('topic.create',compact('categories'));
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        
        $rightId = 69;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        
        $topic = new Topic();
        $topic->topic=$request->title;
        $topic->category_id=$request->category;
        $topic->save();
        Session::flash('message', 'Topic created sucessfully.');
        return Redirect::to('topics');
       
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $rightId = 69;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        $categories=TopicCategory::where('is_deleted','=','0')->get();
        $topic=Topic::find($id);
        return view('topic.edit',compact('categories','topic'));
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
         $rightId = 69;
         if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
         
        $topic =Topic::find($request->id);
        $topic->topic=$request->title;
        $topic->category_id=$request->category;
        $topic->save();
        
         Session::flash('message', 'Topic updated sucessfully.');
         return Redirect::to('topics');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
         $rightId = 69;
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
             $topic=Topic::find($d);
             $topic->valid=0;
             $topic->save();
        }
      return 'success';
    }
    
   
}
