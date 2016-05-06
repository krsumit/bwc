<?php
namespace App\Http\Controllers;
use App\Video;
use Illuminate\Http\Request;
use App\Article;
use App\MasterVideo;
use App\Http\Controllers\Auth;
use App\Http\Controllers\AuthorsController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes\Zebra_Image;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller {

    private $key;
    private $aws_obj;

    public function __construct() {
        $this->key = md5('apivideogist@bw@businessworld@businessworld.in');
    }

    public function videoApi(Request $request) {
        $msgArray = array();
        $flagArray = array();
        if ($request->has('access_key') && $request->has('article_id') && $request->has('video_url') && $request->has('thumb_url')) {
            if ($this->key == trim($request->access_key)) {
                $article = Article::find($request->article_id);
                
                if ($article) {
                    $this->aws_obj = $s3 = AWS::createClient('s3');
                    $dir = $_SERVER['DOCUMENT_ROOT'] . '/files/';
                    $dir_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/';
                    $api_constant = 'gist' . time();
                    $v_array = array_filter(explode('/', $request->video_url));
                    $i_array = array_filter(explode('/', $request->thumb_url));
                    $video_name = $api_constant . array_pop($v_array);
                    $image_name = $api_constant . array_pop($i_array);

                    copy($request->video_url, $dir . $video_name);
                    copy($request->thumb_url, $dir . $image_name);

                    // Transfering video to aws
                    $result = $s3->putObject(array(
                        'ACL' => 'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.awvideo') . $video_name,
                        'SourceFile' => $dir . $video_name
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($dir . $video_name);
                        $flagArray[] = 'video';
                    }

                    // Transfering video thumb small image 
                    $image = new Zebra_Image();
                    $image->source_path = $dir . $image_name;
                    $image->target_path = $dir_thumb . $image_name;
                    $image->preserve_aspect_ratio = false;
                    if ($image->resize(221, 119, ZEBRA_IMAGE_BOXED, -1)) {
                        $result = $s3->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => config('constants.awbucket'),
                            'Key' => config('constants.awvideothumb_small') . $image_name,
                            'SourceFile' => $image->target_path,
                        ));
                        if ($result['@metadata']['statusCode'] == 200) {
                            unlink($image->target_path);
                            $flagArray[] = 'thumbsmall';
                        }
                    }
                    // Transfering thumb orignal image 
                    $result = $s3->putObject(array(
                        'ACL' => 'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.awvideothumb') . $image_name,
                        'SourceFile' => $dir . $image_name
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($dir . $image_name);
                        $flagArray[] = 'thumb';
                    }

                    if (count($flagArray) == 3) {
                        $video = new MasterVideo();
                        $video->channel_id = $article->channel_id;
                        if ($article->status == 'P')
                            $video->video_status = '1';
                        else
                            $video->video_status = '0';
                        $video->video_by = 'gist';
                        $video->video_name = $video_name;
                        $video->video_title = $article->title;
                        $video->video_summary = $article->summary;
                        $video->video_thumb_name = $image_name;
                        $video->save();
                        
                         $articleVideo=DB::table('articles')->leftjoin('video_master','articles.video_id','=','video_master.id')
                                ->select('article_id','video_type','video_id','id','video_by','video_name','video_thumb_name')
                                ->where('article_id',$request->article_id)->first();
                         if($articleVideo->video_id && trim($articleVideo->video_by)=='gist'){ // Deleting old gist video
                             $this->deleteAwsObject(config('constants.awvideothumb'), $articleVideo->video_thumb_name);
                             $this->deleteAwsObject(config('constants.awvideothumb_small'), $articleVideo->video_thumb_name);
                             $this->deleteAwsObject(config('constants.awvideo'), $articleVideo->video_name);
                             $oldVideo=MasterVideo::find($articleVideo->id);
                             $oldVideo->video_status='0';
                             $oldVideo->save();
                         }
                        if ($video->id) {
                            if(trim($articleVideo->video_by)!='inhouse'){ // Link Video to article (If old video is not inhouse) 
                            //DB::select("select article_id,video_type,video_id,id,video_by,video_name from articles left join video_master on articles.video_id=video_master.id where article_id=92170");
                            $article->video_type = 'uploadedvideo';
                            $article->video_Id = $video->id;
                            if ($article->save()) {
                                $msgArray = array('code' => '200', 'msg' => 'Congrats, Video linked with the article "' . $article->title . '"');
                            } else {
                                // If any error found while updating article, remove video from database and files from aws
                                $vdo = MasterVideo::find($video->id);
                                $vdo->delete();
                                $this->deleteAwsObject(config('constants.awvideothumb'), $image_name);
                                $this->deleteAwsObject(config('constants.awvideothumb_small'), $image_name);
                                $this->deleteAwsObject(config('constants.awvideo'), $video_name);
                                $msgArray = array('code' => '201', 'msg' => 'Error while linking the video with article, Please try again.');
                            }
                            }else{ // No need to connect
                               $msgArray = array('code' => '200', 'msg' => 'Congrats, upload but can\'t link with article becasue article already has inhouse video');

                            }
                        }
                    } else {
                        //If any one of three file not tranfered on aws, remove rest of the files
                        foreach ($flagArray as $item) {
                            if ($item == 'thumb')
                                $this->deleteAwsObject(config('constants.awvideothumb'), $image_name);
                            elseif ($item == 'thumbsmall')
                                $this->deleteAwsObject(config('constants.awvideothumb_small'), $image_name);
                            else
                                $this->deleteAwsObject(config('constants.awvideo'), $video_name);
                        }
                        $msgArray = array('code' => '201', 'msg' => 'Unable to upload on our CDN, Please check try again latter.');
                    }
                }else {
                    $msgArray = array('code' => '201', 'msg' => 'Article id does not exist in our database.');
                }
            } else {
                $msgArray = array('code' => '201', 'msg' => 'Key mismatch,check and try again.');
            }
        } else {
            $msgArray = array('code' => '201', 'msg' => 'Insufficient parameters.');
        }

        if (count($msgArray) == 0) {
            $msgArray = array('code' => '201', 'msg' => 'Something went wrong, Please try again latter.');
        }
        return json_encode($msgArray);
    }

    //Function to delte video from aws in case of any exception
    function deleteAwsObject($awsdir, $name) {
        $this->aws_obj->deleteObject(array(
            'Bucket' => config('constants.awbucket'),
            'Key' => $awsdir . $name
        ));
    }

}
