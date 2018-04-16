<?php
include 'const.php';
date_default_timezone_set('Asia/Calcutta');
$conn = new mysqli(HOST, USER, PASS, DATABASE) or die($conn->connect_errno);
mysqli_set_charset($conn, "utf8");
parse_str($argv['1']);

//$partner = $_GET['partner']; 

$partnerresult = $conn->query("select * from content_partner where name='$partner'") or die($this->conn->error);
$partnerRow = $partnerresult->fetch_object();
//print_r($partnerRow); exit;
$data_url = trim($partnerRow->url);
$string = file_get_contents($data_url);
$articles = simplexml_load_string($string);

if (count($articles) > 0) {
    $count = 0;
    foreach ($articles->channel->item as $article) {
     
        $checkArticle=$conn->query("select * from articles where partner_content_id='$article->id'") or die($this->conn->error);
        if($checkArticle->num_rows>0){
             /* Image test start */
            /* 
             $aleArticleRow=$checkArticle->fetch_object();
            $articleId=$aleArticleRow->article_id;
                   $pos=strrpos(__DIR__,'/');
                            $dest=substr(__DIR__,0,$pos).'/files/'; 
                            //Becasue " is at the end of file name
                            if(substr($article->image->url,-1,1)=='"'){
                                $source=substr($article->image->url,0,strlen($article->image->url)-1);
                            }
                            if(trim($source)){                
                                $filename=substr($source,strrpos($source,'/')+1);
                                $filename=preg_replace('/([^a-zA-Z0-9_.])+/', '_',$filename);
                                copy($source,$dest.$filename);
                                $key=md5(date('dmY') . 'businessworld');
                                $url='http://bwcms.in/api/article/insert/image/'.$key.'/'.$articleId.'/'.$filename;
                                $data=file_get_contents($url);
                                if(trim($data)=='1'){
                                    $articleImageInsertStmt = $conn->prepare("insert into photos set channel_id='1',owned_by='article',active='1',photopath=?,photo_by=?,title=?,owner_id=?,created_at=?,updated_at=?");
                                    $articleImageInsertStmt->bind_param('sssiss',$filename,$partner,$article->image->title,$articleId,$dtToInsert,$dtToInsert) or die($conn->error);;
                                    $articleImageInsertStmt->execute() or die($conn->error);
                                }
                            }
                 echo '1 image uploaded for '.$articleId;
                 */
                 /* Image test end  */
            continue;
        }
        $createDate = date('Y-m-d H:i:s');
        $updateDate = date('Y-m-d H:i:s');
        $publishDate = date('Y-m-d');
        $publishTime = date('H:i:s');
        $st = 'P';

        //echo '<pre>';
        //print_r($article);exit;

        $title = strip_tags($article->title);
        $summary = strip_tags($article->summary);
        $search_array = array('/\n\n/', '/\n\t/', '/\n\s/', '/\n/');
        $replace_array = array('<br><br>', '<br><br>', '<br><br>', ' ');
        $description = trim(preg_replace($search_array, $replace_array, trim($article->description)));
        //$description=addslashes($description);
        $newsType = 1;
        $canonical_option = 1;
        $canonical_url = $article->url;
        $stmt = $conn->prepare("insert into articles set channel_id=1,auto_published=1,user_id=1,author_type=1,is_old=0,send_mail_status=1,"
                . "title=?,summary=?,description=?,publish_date=?,publish_time=?,status=?,created_at=?,updated_at=?,news_type=?,canonical_options=?,canonical_url=?,partner_id=?,partner_content_id=?");
        $stmt->bind_param('ssssssssiisii', $title, $summary, $description, $publishDate, $publishTime, $st, $createDate, $updateDate, $newsType, $canonical_option, $canonical_url,$partnerRow->id,$article->id);
        if ($stmt->execute()) {
            $articleId = $stmt->insert_id;
            $authorId = 1;
            $authorstmt = $conn->prepare("insert into article_author set article_id=?,channel_id=1,article_author_rank=1,author_id=?,valid=1,created_at=?,updated_at=?");
            $authorstmt->bind_param('iiss', $articleId, $authorId, $createDate, $updateDate);
            $authorstmt->execute();
            // Insert category
            $categoryId = $partnerRow->category_id;
            $level = 1;
            $catstmt = $conn->prepare("insert into article_category set article_id=?,category_id=?,level=?,valid=1,created_at=?,updated_at=?");
            $catstmt->bind_param('iisss', $articleId, $categoryId, $level, $createDate, $updateDate);
            $catstmt->execute();
            if($partnerRow->category_id!=0){
                $categoryId = $partnerRow->category_id_level_2;
                $level = 2;
                $catstmt = $conn->prepare("insert into article_category set article_id=?,category_id=?,level=?,valid=1,created_at=?,updated_at=?");
                $catstmt->bind_param('iisss', $articleId, $categoryId, $level, $createDate, $updateDate);
                $catstmt->execute();
            }
            $count++;

            //Code to insert tag
            foreach ($article->tags->tag as $tag) {
                if (trim($tag)) {
                    $dtToInsert = date('Y-m-d H:i:s');
                    $tag = trim($tag);
                    $tagResult = $conn->query("select tags_id,tag from tags where tag='" . $tag . "'");
                    if ($tagResult->num_rows > 0) {
                        $tagRow = $tagResult->fetch_object();
                        $tagId = $tagRow->tags_id;
                    } else {
                        $tagInsertStmt = $conn->prepare("insert into tags set tag=?,created_at=?,updated_at=?");
                        $tagInsertStmt->bind_param('sss', $tag, $dtToInsert, $dtToInsert);
                        $tagInsertStmt->execute();
                        if ($tagInsertStmt->insert_id) {
                            $tagId = $tagInsertStmt->insert_id;
                        }
                    }
                    $articleTagInsertStmt = $conn->prepare("insert into article_tags set article_id=?,tags_id=?,created_at=?,updated_at=?");
                    $articleTagInsertStmt->bind_param('iiss', $articleId, $tagId, $dtToInsert, $dtToInsert);
                    $articleTagInsertStmt->execute();
                }
            }
            
            $pos=strrpos(__DIR__,'/');
            $dest=substr(__DIR__,0,$pos).'/files/'; 
            //Becasue " is at the end of file name
            if(substr($article->image->url,-1,1)=='"'){
                $source=substr($article->image->url,0,strlen($article->image->url)-1);
            }
            if(trim($source)){                
                $filename=substr($source,strrpos($source,'/')+1);
                $filename=preg_replace('/([^a-zA-Z0-9_.])+/', '_',$filename);
                copy($source,$dest.$filename);
                $key=md5(date('dmY') . 'businessworld');
                $url='http://bwcms.in/api/article/insert/image/'.$key.'/'.$articleId.'/'.$filename;
                $data=file_get_contents($url);
                if(trim($data)=='1'){
                    $articleImageInsertStmt = $conn->prepare("insert into photos set channel_id='1',owned_by='article',active='1',photopath=?,photo_by=?,title=?,owner_id=?,created_at=?,updated_at=?");
                    $articleImageInsertStmt->bind_param('sssiss',$filename,$partner,$article->image->title,$articleId,$dtToInsert,$dtToInsert) or die($conn->error);;
                    $articleImageInsertStmt->execute() or die($conn->error);
                }
            }
                    
        }
      
    }
}
?>