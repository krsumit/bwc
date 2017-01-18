<?php
class Cron {

    var $conn;
    var $conn2;
    var $message;
    var $keyarray;
    var $categoryMapping;
    var $channelId;

     function __construct() {
        $this->conn = new mysqli(HOST, USER, PASS, DATABASE) or die($this->conn->connect_errno);
        mysqli_set_charset($this->conn,"utf8");
        $this->conn2 = new mysqli(LHOST, LUSER, LPASS, LDATABASE) or die($this->conn2->connect_errro);
        mysqli_set_charset($this->conn2,"utf8");
        $this->channelId=9;
    }


    function migrateData($section){ 
	//echo $section;exit;
        switch ($section):
            case 'author':
                $this->migrateBwAuthor();
                break;
            case 'event':
                $this->migrateBwEvent();
                break;
            case 'articleviewcount':
                $this->Articleviewcount();
                break;
           
             case 'sponsorviewcount':
                $this->Sponsorviewcount();
                break;
            case 'newsletter':
                $this->migrateMasterNewsLetter();
                break;
            case 'category':
                $this->migrateBwCategory();
                break;
            case 'tag':
                $this->migrateBwTag();
                break;
            case 'article':
                $this->migrateBwArticle();
                break;
            case 'mastervideo':
                $this->migrateMasterVideo();
                break;
            case 'topics' : 
                $this->migrateBwTopics();
                 break;
            case 'newstype':
                $this->migrateBwNewsType();
                 break;
           case 'photoshoot':
                $this->migrateBWPhotoshoot();
                break;
          
        endswitch;

        $_SESSION['message'] = $this->message;
    }

    function migrateBwAuthor() { 
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedauthor' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
           //$condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $authorResults = $this->conn->query("SELECT * FROM authors where 1 $condition");
        //echo $authorResults->num_rows; exit;
        if ($authorResults->num_rows > 0) {

            while ($authorRow = $authorResults->fetch_assoc()) {
                // print_r($authorRow); exit;
                $authorId = $authorRow['author_id'];
                $checkAthorExistResultSet = $this->conn2->query("select author_id,author_name from author where author_id=$authorId");
                if ($checkAthorExistResultSet->num_rows > 0) { //echo 'going to update';exit;  
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $authorUpdateStmt = $this->conn2->prepare("update author set author_name=?,author_photo=?,author_bio=?,author_type=?,column_id=?,valid=?,updated_at=? where author_id=?");
                    $authorUpdateStmt->bind_param('sssiiisi', $authorRow['name'], $authorRow['photo'], $authorRow['bio'], $authorRow['author_type_id'], $authorRow['column_id'], $authorRow['valid'],$authorRow['updated_at'], $authorId);
                    $authorUpdateStmt->execute();
                    if ($authorUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                   // echo  $_SESSION['noofupd'];
                }else {
                    $authorInsertStmt = $this->conn2->prepare("insert into author set author_id=?,author_name=?,author_photo=?,author_bio=?,author_type=?,column_id=?,valid=?,updated_at=?");
                    //echo $this->conn2->error; exit;
                    $authorInsertStmt->bind_param('isssiiis', $authorRow['author_id'], $authorRow['name'], $authorRow['photo'], $authorRow['bio'], $authorRow['author_type_id'], $authorRow['column_id'], $authorRow['valid'],$authorRow['updated_at']);
                    $authorInsertStmt->execute();
                    if ($authorInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwedauthor',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' bwedauthor(s) inserted and ' . $_SESSION['noofupd'] . ' bwedauthor(s) updated.</h5>';
    }


    
    
    function Sponsorviewcount() {
	///echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn2->query("select start_time from cron_log where section_name='bwedsponsorviewcount' order by  start_time desc limit 0,1") or die($this->conn2->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            //$condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        //echo "SELECT * FROM event  WHERE 1 $condition";exit;
        $sponsoredpostsrResults = $this->conn2->query("SELECT sponsoredposts_id,count(*) as sponsoredpostscout FROM sponsoredposts_view WHERE 1  $condition group by sponsoredposts_id");
        //echo $articleviewcountrResults->num_rows;exit;
        if ($sponsoredpostsrResults->num_rows > 0) {

            while ($viewsRow = $sponsoredpostsrResults->fetch_assoc()) {
                // print_r($viewsRow);exit;
               $viewId = $viewsRow['sponsoredposts_id'];
               //echo $viewId;
               // exit;
                $checksponsoredpoststExistResultSet = $this->conn->query("select id,title from sponsoredposts where id=$viewId");
                if ($checksponsoredpoststExistResultSet->num_rows > 0) { //echo 'test';exit;
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $sponsoredpostsviewcountUpdateStmt = $this->conn->prepare("update sponsoredposts set views=?  where id=?") or die($this->conn->error);
                    $sponsoredpostsviewcountUpdateStmt->bind_param('ii', $viewsRow['sponsoredpostscout'],$viewId) or die($this->conn->error);
                    //echo $this->conn2->error;exit;
                    $sponsoredpostsviewcountUpdateStmt->execute()or die($this->conn->error);
                    //print_r($eventUpdateStmt);exit;
                    //echo $eventUpdateStmt->affected_rows;exit;
                    if ($sponsoredpostsviewcountUpdateStmt->affected_rows)    
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {//echo 'goint to insert';exit;
                    $sponsoredpostsviewcountInsertStmt = $this->conn->prepare("insert into sponsoredposts set id=?,view_count=?");
                    //echo $this->conn2->error; exit;
                    $sponsoredpostsviewcountInsertStmt->bind_param('is', $viewsRow['sponsoredposts_id'],$viewsRow['sponsoredpostscout']);
                    $sponsoredpostsviewcountInsertStmt->execute();
                    if ($sponsoredpostsviewcountInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn2->prepare("insert into cron_log set section_name='bwedsponsorviewcount',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' bwedsponsorviewcount(s) inserted and ' . $_SESSION['noofupd'] . ' bwedsponsorviewcount   (s) updated.</h5>';
    } 
    
    function Articleviewcount() {
	///echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn2->query("select start_time from cron_log where section_name='bwedarticleviewcount' order by  start_time desc limit 0,1") or die($this->conn2->error);
        
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];    
        }
        
        $articleviewcountrResults = $this->conn2->query("SELECT article_id,count(*) as articleidcount FROM article_view WHERE 1   group by article_id");
        //echo $articleviewcountrResults->num_rows;exit;
        if ($articleviewcountrResults->num_rows > 0) {

            while ($viewsRow = $articleviewcountrResults->fetch_assoc()) {
                 //print_r($viewsRow);exit;
               $viewId = $viewsRow['article_id'];
               //echo $viewId;
               // exit;
                $checkArticleviewcountExistResultSet = $this->conn->query("select article_id,title from articles where article_id=$viewId");
                if ($checkArticleviewcountExistResultSet->num_rows > 0) { //echo 'test';exit;
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $articleviewcountUpdateStmt = $this->conn->prepare("update articles set view_count=?  where article_id=?") or die($this->conn->error);
                    $articleviewcountUpdateStmt->bind_param('ii', $viewsRow['articleidcount'],$viewId) or die($this->conn->error);
                    //echo $this->conn2->error;exit;
                    $articleviewcountUpdateStmt->execute()or die($this->conn->error);
                    //print_r($eventUpdateStmt);exit;
                    //echo $eventUpdateStmt->affected_rows;exit;
                    if ($articleviewcountUpdateStmt->affected_rows)    
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {//echo 'goint to insert';exit;
                    $articleviewcountInsertStmt = $this->conn->prepare("insert into articles set article_id=?,view_count=?");
                    //echo $this->conn2->error; exit;
                    $articleviewcountInsertStmt->bind_param('ii', $viewsRow['article_id'],$viewsRow['articleidcount']);
                    $articleviewcountInsertStmt->execute();
                    if ($articleviewcountInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn2->prepare("insert into cron_log set section_name='bwedarticleviewcount',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' bwedarticleviewcount(s) inserted and ' . $_SESSION['noofupd'] . ' bwedarticleviewcount(s) updated.</h5>';
    } 
    
 
    
    
    
    function migrateBwEvent() {
	///echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedevent' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $eventrResults = $this->conn->query("SELECT * FROM event  WHERE channel_id = $this->channelId  $condition");
        if ($eventrResults->num_rows > 0) {
            while ($eventRow = $eventrResults->fetch_assoc()) {
               $eventId = $eventRow['event_id'];
                $checkEventExistResultSet = $this->conn2->query("select event_id,title from event where event_id=$eventId");
                if ($checkEventExistResultSet->num_rows > 0) { //echo 'here'; exit;
                    $eventUpdateStmt = $this->conn2->prepare("update event set title=?,description=?,imagepath=?,image_url=?,start_date=?,end_date=?,start_time=?,end_time=?,venue=?,valid=?,created_at=?,updated_at=? where event_id=?") or die($this->conn->error);
                    $eventUpdateStmt->bind_param('ssssssssiissi',$eventRow['title'], $eventRow['description'], $eventRow['imagepath'],$eventRow['image_url'], $eventRow['start_date'], $eventRow['end_date'], $eventRow['start_time'], $eventRow['end_time'],$eventRow['country'],$eventRow['valid'],$eventRow['created_at'],$eventRow['updated_at'],$eventRow['event_id']) or die($this->conn->error);
                    $eventUpdateStmt->execute()or die($this->conn->error);
                    if ($eventUpdateStmt->affected_rows)    
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {
                    $eventInsertStmt = $this->conn2->prepare("insert into event set event_id=?,title=?,description=?,imagepath=?,image_url=?,start_date=?,end_date=?,start_time=?,end_time=?,venue=?,valid=?,created_at=?,updated_at=?");
                    $eventInsertStmt->bind_param('issssssssiiss', $eventRow['event_id'],$eventRow['title'], $eventRow['description'], $eventRow['imagepath'],$eventRow['image_url'], $eventRow['start_date'], $eventRow['end_date'], $eventRow['start_time'], $eventRow['end_time'],$eventRow['country'],$eventRow['valid'],$eventRow['created_at'],$eventRow['updated_at']);
                    $eventInsertStmt->execute();
                    if ($eventInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwedevent',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' bwedevent(s) inserted and ' . $_SESSION['noofupd'] . ' bwedevent(s) updated.</h5>';
    }
 
   
function migrateBwCategory() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedcategory' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        $cronLastExecutionTime = 0;
        if ($cronresult->num_rows > 0) {
			//echo 'here--'; 
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

         $catresults = $this->conn->query("SELECT category_id as id,name,channel_id as parent_id,valid,'1' as level  FROM category where channel_id=$this->channelId $condition");
        //echo $catresults->num_rows;exit;    
        while ($catrow = $catresults->fetch_assoc()) {
            $categoryCheckStmt = $this->conn2->prepare("select category_id,category_name from channel_category where cms_cat_id=? and cms_cat_level=?");
            $categoryCheckStmt->bind_param('ii', $catrow['id'], $catrow['level']);
            $categoryCheckStmt->execute();
            $categoryCheckStmt->store_result();
            $categoryCheckStmt->num_rows;
            // If category alreay exit
            if ($categoryCheckStmt->num_rows > 0) {
                $categoryCheckStmt->bind_result($catId, $catName);
                $categoryCheckStmt->fetch();
                $categoryCheckStmt->free_result();
                $localUpdateStmt = $this->conn2->prepare("update channel_category set category_name=?,valid=? where category_id=?");
                $localUpdateStmt->bind_param('sii', $catrow['name'], $catrow['valid'], $catId);
                $localUpdateStmt->execute();
                if ($localUpdateStmt->affected_rows)
                    $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                $this->migrateSubCategory($catId, $catrow['id'], 2, $cronLastExecutionTime);
            } else { // if category not exist, create new category
                $categoryCheckStmt->free_result();

                $localcatstmt = $this->conn2->prepare('insert into channel_category set category_name=?,category_parent=?,cms_cat_id=?,cms_cat_level=?,valid=?');
                $parentid = 0;

                $localcatstmt->bind_param('siiii', $catrow['name'], $parentid, $catrow['id'], $catrow['level'], $catrow['valid']);
                $localcatstmt->execute();
                if ($localcatstmt->insert_id) {
                    $this->migrateSubCategory($localcatstmt->insert_id, $catrow['id'], 2, $cronLastExecutionTime);
                    $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                }
            }
            $categoryCheckStmt->free_result();
        }
        $this->migrateSubCategoryDirect($cronLastExecutionTime);
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwedcategory',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' category/categories inserted and ' . $_SESSION['noofupd'] . ' category/categories updated.</h5>';
  
    }
function migrateSubCategory($frontparentId, $cmsParentId, $cmsLevel, $cronLastExecutionTime) {
        $condition = '';
        if ($cronLastExecutionTime) {
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        if ($cmsLevel == '2') {
            $query = "select category_two_id as id,name,category_id as parent,valid from category_two where  category_id='$cmsParentId' $condition";
        } elseif ($cmsLevel == '3') {
            $query = "select category_three_id as id,name,category_two_id as parent,valid from category_three where category_two_id='$cmsParentId' $condition";
        } elseif ($cmsLevel == '4') {
            $query = "select category_four_id as id,name,category_three_id as parent,valid from category_four where category_three_id='$cmsParentId' $condition";
        }
        $catresults = $this->conn->query($query);
        if ($catresults->num_rows > 0) {
            while ($catrow = $catresults->fetch_assoc()) {
                $categoryCheckStmt = $this->conn2->prepare("select category_id,category_name from channel_category where cms_cat_id=? and cms_cat_level=?");
                $categoryCheckStmt->bind_param('ii', $catrow['id'], $cmsLevel);
                $categoryCheckStmt->execute();
                $categoryCheckStmt->store_result();
                if ($categoryCheckStmt->num_rows > 0) {
                    $categoryCheckStmt->bind_result($catId, $catName);
                    $categoryCheckStmt->fetch();
                    $categoryCheckStmt->free_result();
                    $localUpdateStmt = $this->conn2->prepare("update channel_category set category_name=?,valid=? where category_id=?");
                    $localUpdateStmt->bind_param('sii', $catrow['name'], $catrow['valid'], $catId);
                    $localUpdateStmt->execute();
                    if ($localUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    if ($cmsLevel < 4) {
                        $this->migrateSubCategory($catId, $catrow['id'], $cmsLevel + 1, $cronLastExecutionTime);
                    }
                } else {
                    $categoryCheckStmt->free_result();
                    $localcatstmt = $this->conn2->prepare('insert into channel_category set category_name=?,category_parent=?,cms_cat_id=?,cms_cat_level=?,valid=?');
                    $localcatstmt->bind_param('siiii', $catrow['name'], $frontparentId, $catrow['id'], $cmsLevel, $catrow['valid']);
                    $localcatstmt->execute();
                    if ($localcatstmt->insert_id && $cmsLevel < 4) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                        $this->migrateSubCategory($localcatstmt->insert_id, $catrow['id'], $cmsLevel + 1, $cronLastExecutionTime);
                    }
                }
            }
        }
    }
function migrateSubCategoryDirect($cronLastExecutionTime) {
        $condition = '';
        if ($cronLastExecutionTime) {
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $query2 = "select category_two_id as id,name,category_id as parent,valid from category_two where 1 $condition group by category_id";

        $catresults2 = $this->conn->query($query2);
        //echo $catresults2->num_rows.'--'; exit;
        if ($catresults2->num_rows > 0) {
            while ($catrow = $catresults2->fetch_assoc()) {

                $categoryCheckStmt = $this->conn2->prepare("select category_id,category_name from channel_category where cms_cat_id=? and cms_cat_level=1") or die($this->conn2->error);

                $categoryCheckStmt->bind_param('i', $catrow['parent']);
                $categoryCheckStmt->execute();
                $categoryCheckStmt->store_result();
                if ($categoryCheckStmt->num_rows > 0) {
                    $categoryCheckStmt->bind_result($catId, $catName);
                    $categoryCheckStmt->fetch();
                    $categoryCheckStmt->free_result();
                }
                //    echo $catId.'##'.$catPid;exit;  
                if ($this->getCategoryChannel($catrow['parent'], 1) == $this->channelId)
                    $this->migrateSubCategory($catId, $catrow['parent'], 2, $cronLastExecutionTime);
            }
        }
        $query3 = "select category_three_id as id,name,category_two_id as parent,valid from category_three where 1 $condition group by category_two_id";
        $catresults3 = $this->conn->query($query3);

        if ($catresults3->num_rows > 0) {
            while ($catrow = $catresults3->fetch_assoc()) {
                //print_r($catrow); // exit;
                $categoryCheckStmt = $this->conn2->prepare("select category_id,category_name from channel_category where cms_cat_id=? and cms_cat_level=2");
                $categoryCheckStmt->bind_param('i', $catrow['parent']);
                $categoryCheckStmt->execute();
                $categoryCheckStmt->store_result();
                if ($categoryCheckStmt->num_rows > 0) {
                    $categoryCheckStmt->bind_result($catId, $catName);
                    $categoryCheckStmt->fetch();
                    $categoryCheckStmt->free_result();
                }
                //echo $catId.'--'.$catrow['parent']; exit;
                if ($this->getCategoryChannel($catrow['parent'], 2) == $this->channelId)
                    $this->migrateSubCategory($catId, $catrow['parent'], 3, $cronLastExecutionTime);
            }
        }
        $query4 = "select category_four_id as id,name,category_three_id as parent,valid from category_four where 1 $condition group by category_three_id";
        $catresults4 = $this->conn->query($query4);
        //echo $catresults4->num_rows; exit;
        if ($catresults4->num_rows > 0) {
            while ($catrow = $catresults4->fetch_assoc()) {
                $categoryCheckStmt = $this->conn2->prepare("select category_id,category_name from channel_category where cms_cat_id=? and cms_cat_level=3");
                $categoryCheckStmt->bind_param('i', $catrow['parent']);
                $categoryCheckStmt->execute();
                $categoryCheckStmt->store_result();
                if ($categoryCheckStmt->num_rows > 0) {
                    $categoryCheckStmt->bind_result($catId, $catName);
                    $categoryCheckStmt->fetch();
                    $categoryCheckStmt->free_result();
                }
                if ($this->getCategoryChannel($catrow['parent'], 3) == $this->channelId)
                    $this->migrateSubCategory($catId, $catrow['parent'], 4, $cronLastExecutionTime);
            }
        }
    }
    
    
    function getCategoryChannel($catId, $level) {
        if ($level == 1) {
            $query = "SELECT category_id,channel_id FROM `category` WHERE category_id=$catId";
            $catresults = $this->conn->query($query);
            $catrow = $catresults->fetch_assoc();
            return $catrow['channel_id'];
        } else if ($level == 2) {
            $query2 = "select category_two_id as id,name,category_id as parent,valid from category_two where category_two_id=$catId";
            $catresults2 = $this->conn->query($query2);
            $catrow = $catresults2->fetch_assoc();
            return $this->getCategoryChannel($catrow['parent'], 1);
        } else if ($level == 3) {
            $query3 = "select category_three_id as id,name,category_two_id as parent,valid from category_three where category_three_id=$catId";
            $catresults3 = $this->conn->query($query3);
            $catrow = $catresults3->fetch_assoc();
            return $this->getCategoryChannel($catrow['parent'], 2);
        } else if ($level == 4) {
            $query4 = "select category_four_id as id,name,category_three_id as parent,valid from category_four where category_four_id=$catId";
            $catresults4 = $this->conn->query($query4);
            $catrow = $catresults4->fetch_assoc();
            return $this->getCategoryChannel($catrow['parent'], 3);
        }
    }
    function migrateBwTag() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedtag' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $tagResults = $this->conn->query("SELECT tags_id as id,tag,valid  FROM tags where 1 $condition");
        //echo $tagResults->num_rows;exit;
        if ($tagResults->num_rows > 0) {
            while ($tagrow = $tagResults->fetch_assoc()) {
                $tid = $tagrow['id'];
                $checkTagExistResultSet = $this->conn2->query("select tag_id,tag_name from channel_tags where tag_id=$tid");
                if ($checkTagExistResultSet->num_rows > 0) {
                    $checkTagExistResultSet->close();
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $tagUpdateStmt = $this->conn2->prepare("update channel_tags set tag_name=?,valid=? where tag_id=?");
                    //echo $this->conn2->error;exit;
                    $tagUpdateStmt->bind_param('sii', $tagrow['tag'], $tagrow['valid'], $tid);
                    $tagUpdateStmt->execute();
                    if ($tagUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {
                    $tagInsertStmt = $this->conn2->prepare("insert into channel_tags set tag_id=?,tag_name=?");
                    $tagInsertStmt->bind_param('is', $tagrow['id'], $tagrow['tag']);
                    $tagInsertStmt->execute();
                    if ($tagInsertStmt->insert_id) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwedtag',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();

        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' tag(s) inserted and ' . $_SESSION['noofupd'] . ' tag(s) updated.</h5>';
    }

function migrateBwNewsType() {
        
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwednewstype' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {  
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $newTypeResults = $this->conn->query("SELECT * FROM news_type where   $condition");
        if ($newTypeResults->num_rows > 0) {
            while ($newsTypeRow = $newTypeResults->fetch_assoc()) {
                //print_r($newsTypeRow);exit;
                $id = $newsTypeRow['news_type_id'];
                $checkNewsTypeResultSet = $this->conn2->query("select news_type_id from news_type where news_type_id=$id");
                 //echo $checkNewsTypeResultSet->num_rows;exit;
                if ($checkNewsTypeResultSet->num_rows > 0) {
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $newTypeUpdateStmt = $this->conn2->prepare("update news_type set name=?,valid=? where news_type_id=?");
                    $newTypeUpdateStmt->bind_param('sii',$newsTypeRow['name'],$newsTypeRow['valid'],$id);
                    $newTypeUpdateStmt->execute();
                    if ($newTypeUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {
                    $newTypeInsertStmt = $this->conn2->prepare("insert into news_type set name=?,valid=?,news_type_id=?");
                    $newTypeInsertStmt->bind_param('sii',$newsTypeRow['name'],$newsTypeRow['valid'],$id);
                    $newTypeInsertStmt->execute();
                    if ($newTypeInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }
        
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwednewstype',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' bwednewstype(s) inserted and ' . $_SESSION['noofupd'] . ' bwednewstype(s) updated.</h5>';

    }
function migrateBwTopics(){
        //$this->migrateCategory();
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedtopics' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {  
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        
        $topicsResults = $this->conn->query("SELECT * FROM topics where channel_id = $this->channelId  $condition");
       
    }
function migrateBwArticle() {
	//echo 'shekhar'; exit;
        $this->conn->query("update articles set status='P' where status='SD' and concat(publish_date,' ',publish_time) <= '".date('Y-m-d h:i:s')."'") or die($this->conn->error);; 
        $this->migrateBwAuthor();
        $this->migrateBwCategory();
        $this->migrateBwTag();
        $this->migrateBwTopics();
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedarticle' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = "and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $articleResults = $this->conn->query("SELECT *  FROM articles where channel_id = $this->channelId  $condition");
        //echo $articleResults->num_rows ;exit;
         if ($articleResults->num_rows > 0) {
            // exit;
            $catMapArray = array();
            $articleCatDataRst = $this->conn2->query("select * from channel_category");
            while ($articleCatDataRow = $articleCatDataRst->fetch_assoc()) {
                $key = $articleCatDataRow['cms_cat_id'] . '_' . $articleCatDataRow['cms_cat_level'];
                $catMapArray[$key] = $articleCatDataRow['category_id'];
            }
            $this->categoryMapping = $catMapArray;
            //print_r( $this->categoryMapping);exit;
            //print_r($catMapArray);exit;
            // echo $articleCatDataRst->num_rows;exit;
            //$this->categoryMapping = $catMapArray;
           // echo  $articleResults->num_rows;exit;
            while ($articleRow = $articleResults->fetch_assoc()) {
                $id = $articleRow['article_id'];
                $checkArticleResult = $this->conn2->query("select article_title from articles where article_id=$id");
                //echo $checkArticleResult->num_rows.'-' ;exit;
                if ($checkArticleResult->num_rows > 0) {
                    $checkArticleResult->close();
                    if ($articleRow['status'] == 'P') {
                        $pubDate = $articleRow['publish_date'] . ' ' . $articleRow['publish_time'];
                        $status = 'published';
                        $articleUpdateStmt = $this->conn2->prepare("update articles set article_title=?,article_description=?,article_summary=?,"
                                . "article_type=?,article_published_date=?,article_slug=?,article_status=?,important_article=?,video_Id=?,display_to_homepage=?,is_exclusive=?,"
                                . "magzine_issue_name=?,canonical_options=?,video_type=?,canonical_url=?,article_location_country=?,article_location_state=?,hide_image=?,updated_at=? where article_id=?") or die($this->conn2->error);;
                        $articleUpdateStmt->bind_param('sssisssiiiiiissiiisi', $articleRow['title'], $articleRow['description'], $articleRow['summary'], $articleRow['news_type']
                                , $pubDate, $articleRow['slug'], $status,$articleRow['important'],$articleRow['video_Id'], $articleRow['for_homepage'],$articleRow['web_exclusive'], $articleRow['magazine_id'],$articleRow['canonical_options'],$articleRow['video_type'],$articleRow['canonical_url'], $articleRow['country'], $articleRow['state'], $articleRow['hide_image'],$articleRow['updated_at'],$articleRow['article_id']
                        );
                        $articleUpdateStmt->execute();
                        if ($articleUpdateStmt->affected_rows) {
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                        }
                        $this->callArticleRelatedContent($articleRow['article_id'], 0, $condition);
                    } else {
						
						$this->deleteArticleRelated($id);
                        $delStmt = $this->conn2->prepare("delete from articles where article_id=?");
                        $delStmt->bind_param('i', $id);
                        $delStmt->execute();
                        if ($delStmt->affected_rows) {
                            $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                           
                        }
                        $delStmt->close();
                    }
                } else { //echo 'here'.'------'.$articleRow['status']; exit;
                    if ($articleRow['status'] == 'P') {
                        $pubDate = $articleRow['publish_date'] . ' ' . $articleRow['publish_time'];
                        $status = 'published';
                        
                        $articleInsertStmt = $this->conn2->prepare("insert articles set article_id=?,article_title=?,article_description=?,article_summary=?,"
                                . "article_type=?,article_published_date=?,article_slug=?,article_status=?,video_Id=?,important_article=?,display_to_homepage=?,is_exclusive=?,"
                                . "magzine_issue_name=?,canonical_options=?,video_type=?,canonical_url=?,article_location_country=?,article_location_state=?,is_old=?,hide_image=?,updated_at=?") or die($this->conn2->error);
                       
                        $articleInsertStmt->bind_param('isssisssiiiiiissiiiis', $articleRow['article_id'], $articleRow['title'], $articleRow['description'], $articleRow['summary'], $articleRow['news_type']
                                , $pubDate, $articleRow['slug'], $status, $articleRow['video_Id'],$articleRow['important'], $articleRow['for_homepage'],$articleRow['web_exclusive'], $articleRow['magazine_id'],$articleRow['canonical_options'],$articleRow['video_type'],$articleRow['canonical_url'], $articleRow['country'], $articleRow['state'],$articleRow['is_old'],$articleRow['hide_image'],$articleRow['updated_at']) or die($this->conn2->error);
                       
                        $articleInsertStmt->execute() or die($this->conn2->error);;
                        //print_r($articleInsertStmt);exit;
						//echo '----------'.$articleInsertStmt->insert_id;exit;    
                        if ($articleInsertStmt->insert_id) {
                            $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                            $this->callArticleRelatedContent($articleInsertStmt->insert_id, 1, $condition);
                        }
                    }
                }
                //echo '1 done ';exit;
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwedarticle',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' bwedarticle(s) inserted, ' . $_SESSION['noofupd'] . ' bwedarticle(s) updated and ' . $_SESSION['noofdel'] . ' bwedarticle(s) deleted.</h5>';
        $key= md5(date('dmY').'businessworld');
		  file_get_contents('http://bweducation.businessworld.in/create-json/'.$key);
    }
   

 
    
    function callArticleRelatedContent($articleId, $isNew = 0, $condition) {
        $this->migrateArticleTag($articleId, $isNew,$condition);
        $this->migrateArticleCategory($articleId, $isNew,$condition);
        $this->migrateArticleAuthor($articleId, $isNew,$condition);
        $this->migrateArticleTopic($articleId, $isNew, $condition);
        $this->migrateArticleImage($articleId, $isNew, $condition);
        $this->migrateArticleVideo($articleId, $isNew, $condition);
    }

    function deleteArticleRelated($articleId) {
       $this->conn2->query("delete from article_author where article_id=$articleId");
       $this->conn2->query("delete from article_category where article_id=$articleId");
       $this->conn2->query("delete from article_images where article_id=$articleId");
       $this->conn2->query("delete from article_tags where article_id=$articleId");
       $this->conn2->query("delete from article_topics where article_id=$articleId");
       $this->conn2->query("delete from article_video where article_id=$articleId");
       $this->conn2->query("delete from article_view where article_id=$articleId");
		
    }

    function migrateArticleTag($articleId, $isNew = 0, $condition) {
        if ($isNew == '1') {
            $articleTagResultset = $this->conn->query("select * from article_tags where article_id=$articleId and valid='1'");
            while (($tagRow = $articleTagResultset->fetch_assoc())) {
                $tagInsertStmt = $this->conn2->prepare("insert into article_tags set article_id=?,tag_id=?");
                $tagInsertStmt->bind_param('ii', $tagRow['article_id'], $tagRow['tags_id']);
                $tagInsertStmt->execute();
                $tagInsertStmt->close();
            }
        } else {
            /*$checkTagResult = $this->conn->query("select * from article_tags where article_id=$articleId $condition");
            if ($checkTagResult->num_rows > 0) {
                $checkTagResult->close();*/
                $delRst = $this->conn2->query("delete from article_tags where article_id=$articleId");
                $articleTagResultset = $this->conn->query("select * from article_tags where article_id=$articleId and valid='1'");
                while (($tagRow = $articleTagResultset->fetch_assoc())) {
                    $tagInsertStmt = $this->conn2->prepare("insert into article_tags set article_id=?,tag_id=?");
                    $tagInsertStmt->bind_param('ii', $tagRow['article_id'], $tagRow['tags_id']);
                    $tagInsertStmt->execute();
                    $tagInsertStmt->close();
                }
            //}
        }
    }

    function migrateArticleCategory($articleId, $isNew = 0, $condition) {
       // $this->categoryMapping
       // echo $articleId.'   '.$isNew; exit;
        $catResultSet = '';
        if ($isNew == '1') {
            $articleCatRst = $this->conn->query("SELECT concat(`category_id`,'_',`level`) as catlevel,level FROM `article_category` WHERE  `article_id`='$articleId'");
            while ($catRow = $articleCatRst->fetch_assoc()) {
                $insertArticleCategoryStmt = $this->conn2->prepare("insert into article_category set article_id=?,category_id=?,category_level=?");
                $insertArticleCategoryStmt->bind_param('iii', $articleId, $this->categoryMapping[$catRow['catlevel']], $catRow['level']);
                $insertArticleCategoryStmt->execute();
                $insertArticleCategoryStmt->close();
            }
        } else { 
           /* $checkCatResult = $this->conn->query("select * from article_category where article_id=$articleId $condition");
            if ($checkCatResult->num_rows > 0) {
                $checkCatResult->close();*/
                $this->conn2->query("delete from article_category where article_id=$articleId");
                
                $articleCatRst = $this->conn->query("SELECT concat(`category_id`,'_',`level`) as catlevel,level FROM `article_category` WHERE  `article_id`='$articleId'");

                while ($catRow = $articleCatRst->fetch_assoc()) {//print_r($catRow);exit;
                    $insertArticleCategoryStmt = $this->conn2->prepare("insert into article_category set article_id=?,category_id=?,category_level=?");
                    $insertArticleCategoryStmt->bind_param('iii', $articleId, $this->categoryMapping[$catRow['catlevel']], $catRow['level']);
                    $insertArticleCategoryStmt->execute();
                    $insertArticleCategoryStmt->close();
                }
           // }
        }
    }

    function migrateArticleAuthor($articleId, $isNew = 0, $condition='') {
		$allauthorRst=$this->conn2->query("select author_id,author_type from author");
		$allAuthArr=array();
		while($autRow=$allauthorRst->fetch_object()){
			$allAuthArr[$autRow->author_id]=$autRow->author_type;
			
		}
		//echo '<pre>';
		//echo print_r($allAuthArr); exit;
        if ($isNew == '1') {
            $articleAuthorResultset = $this->conn->query("select * from article_author where article_id=$articleId and valid='1'");
            while ($authorRow = $articleAuthorResultset->fetch_assoc()) {
				$tauthId=$authorRow['author_id'];
                $auInsertStmt = $this->conn2->prepare("insert into article_author set article_id=?,author_type=?,author_id=?");
                $auInsertStmt->bind_param('iii', $authorRow['article_id'], $allAuthArr[$tauthId], $authorRow['author_id']);
                $auInsertStmt->execute();
                $auInsertStmt->close();
            }
        } else {
            /*$checkAuthorResult = $this->conn->query("select * from article_author where article_id=$articleId $condition");
            if ($checkAuthorResult->num_rows > 0) {
                $checkAuthorResult->close(); */
                $this->conn2->query("delete from article_author where article_id=$articleId");
                $articleAuthorResultset = $this->conn->query("select * from article_author where article_id=$articleId and valid='1'");
                while ($authorRow = $articleAuthorResultset->fetch_assoc()) {
					$tauthId=$authorRow['author_id'];
                    $auInsertStmt = $this->conn2->prepare("insert into article_author set article_id=?,author_type=?,author_id=?");
                    $auInsertStmt->bind_param('iii', $authorRow['article_id'],$allAuthArr[$tauthId], $authorRow['author_id']);
                    $auInsertStmt->execute();
                    $auInsertStmt->close();
                }
            //}
        }
    }

    function migrateArticleTopic($articleId, $isNew = 0, $condition) {
        // $articleId=74;
        if ($isNew == '1') {
                $articleTopicsResultset = $this->conn->query("select * from article_topics where article_id=$articleId and valid='1'");
                while ($topicsRow = $articleTopicsResultset->fetch_assoc()) {
                    $tpInsertStmt = $this->conn2->prepare("insert into article_topics set article_id=?,topic_id=?");
                    $tpInsertStmt->bind_param('ii', $topicsRow['article_id'], $topicsRow['topic_id']);
                    $tpInsertStmt->execute();
                    $tpInsertStmt->close();
                }
        } else {
           /* $checkTopicResult = $this->conn->query("select * from article_topics where article_id=$articleId $condition");

            if ($checkTopicResult->num_rows > 0) {
                $checkTopicResult->close();*/
                $this->conn2->query("delete from article_topics where article_id=$articleId");
                $articleTopicsResultset = $this->conn->query("select * from article_topics where article_id=$articleId and valid='1'");

                while ($topicsRow = $articleTopicsResultset->fetch_assoc()) {
                    $tpInsertStmt = $this->conn2->prepare("insert into article_topics set article_id=?,topic_id=?");
                    $tpInsertStmt->bind_param('ii', $topicsRow['article_id'], $topicsRow['topic_id']);
                    $tpInsertStmt->execute();
                    $tpInsertStmt->close();
                }
           // }
        }
    }

    function migrateArticleImage($articleId, $isNew = 0, $condition) {
        if ($isNew == '1') {
            $articleImageResultset = $this->conn->query("select * from photos where owned_by='article' and owner_id=$articleId and valid='1'");
            while ($imageRow = $articleImageResultset->fetch_assoc()) {
                $imInsertStmt = $this->conn2->prepare("insert into article_images set article_id=?,photopath=?,image_url=?,image_title=?"
                        . ",image_source_name=?,image_source_url=?,photo_by=?,image_status=?,sequence=?");
                $status = ($imageRow['active'] == '1') ? 'enabled' : 'disabled';
                $imInsertStmt->bind_param('isssssssi', $imageRow['owner_id'],$imageRow['photopath'],$imageRow['imagefullPath'], $imageRow['title'], $imageRow['source'], $imageRow['source_url'],$imageRow['photo_by'],$status,$imageRow['sequence']);
                $imInsertStmt->execute();
                $imInsertStmt->close();
            }
        } else {

           /* $checkImResult = $this->conn->query("select * from photos where owned_by='article' and owner_id=$articleId $condition");

            if ($checkImResult->num_rows > 0) {
                $checkImResult->close(); */
                $this->conn2->query("delete from article_images where article_id=$articleId");


                $articleImageResultset = $this->conn->query("select * from photos where owned_by='article' and owner_id=$articleId and valid='1'");
                while ($imageRow = $articleImageResultset->fetch_assoc()) {
                    $imInsertStmt = $this->conn2->prepare("insert into article_images set article_id=?,photopath=?,image_url=?,image_title=?"
                            . ",image_source_name=?,image_source_url=?,photo_by=?,image_status=?,sequence=?");
                    $status = ($imageRow['active'] == '1') ? 'enabled' : 'disabled';
                    $imInsertStmt->bind_param('isssssssi', $imageRow['owner_id'],$imageRow['photopath'], $imageRow['imagefullPath'], $imageRow['title'], $imageRow['source'], $imageRow['source_url'],$imageRow['photo_by'], $status,$imageRow['sequence']);
                    $imInsertStmt->execute();
                    $imInsertStmt->close();
                }
           // }
        }
    }

    function migrateArticleVideo($articleId, $isNew = 0, $condition) {
         //$articleId= 71; 
        //$isNew = 1;
         if ($isNew == '1') {
          $articleVideoResultset = $this->conn->query("select * from videos where owned_by='article' and owner_id=$articleId and valid='1'");
                while ($videoRow = $articleVideoResultset->fetch_assoc()) {
                    $vdInsertStmt = $this->conn2->prepare("insert into article_video set article_id=?,video_url=?,"
                            . "video_embed_code=?,video_title=?");
                    $vdInsertStmt->bind_param('isss', $videoRow['owner_id'], $videoRow['url'], $videoRow['code'], $videoRow['title']);
                    $vdInsertStmt->execute();
                    $vdInsertStmt->close();
                }
             
         }else{
           /* $checkVdResult = $this->conn->query("select video_id from videos where owned_by='article' and owner_id=$articleId $condition");
            if ($checkVdResult->num_rows > 0) {
                $checkVdResult->close(); */
                $this->conn2->query("delete from article_video where article_id=$articleId");
                $articleVideoResultset = $this->conn->query("select * from videos where owned_by='article' and owner_id=$articleId and valid='1'");
                while ($videoRow = $articleVideoResultset->fetch_assoc()) {
                    $vdInsertStmt = $this->conn2->prepare("insert into article_video set article_id=?,video_url=?,"
                            . "video_embed_code=?,video_title=?");
                    $vdInsertStmt->bind_param('isss', $videoRow['owner_id'], $videoRow['url'], $videoRow['code'], $videoRow['title']);
                    $vdInsertStmt->execute();
                    $vdInsertStmt->close();
                }
            //}
        }
    }
    
    
    
    // Sponsored Post
    
     function migrateBwSponsored() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedsponsoredposts' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $sponsoredResults = $this->conn->query("SELECT *  FROM sponsoredposts where channel_id = $this->channelId $condition");
        //echo $articleResults->num_rows ;exit;
        if ($sponsoredResults->num_rows > 0) {
            // exit;
            
            $catMapArray = array();
            $articleCatDataRst = $this->conn2->query("select * from channel_category");
            while ($articleCatDataRow = $articleCatDataRst->fetch_assoc()) {
                $key = $articleCatDataRow['cms_cat_id'] . '_' . $articleCatDataRow['cms_cat_level'];
                $catMapArray[$key] = $articleCatDataRow['category_id'];
            }
            $this->categoryMapping = $catMapArray;
            
           // echo  $articleResults->num_rows;exit;
            while ($sponsoredRow = $sponsoredResults->fetch_assoc()) {
                $id = $sponsoredRow['article_id'];
                $checkSponsoredResult = $this->conn2->query("select title from sponsoredposts where id=$id");
                //echo $checkArticleResult->num_rows.'-' ;exit;
                if ($checkSponsoredResult->num_rows > 0) {
                    $checkSponsoredResult->close();
                    if ($sponsoredRow['status'] == 'P') {
                        $pubDate = $sponsoredRow['publish_date'] . ' ' . $sponsoredRow['publish_time'];
                        $status = 'published';
                        $sponsoredUpdateStmt = $this->conn2->prepare("update sponsoredposts set sponsoredposts_title=?,sponsoredposts_description=?,sponsoredposts_summary=?,"
                                . "sponsoredposts_type=?,sponsoredposts_published_date=?,sponsoredposts_status=?,important_sponsoredposts=?,"
                                . "where sponsoredposts_id=?");
                        $sponsoredUpdateStmt->bind_param('ssssssii', $sponsoredRow['title'], $sponsoredRow['description'], $sponsoredRow['summary'], $sponsoredRow['event_id']
                                , $pubDate,  $status, $sponsoredRow['feature_this'],  $sponsoredRow['id']
                        );
                        $sponsoredUpdateStmt->execute();
                        if ($sponsoredUpdateStmt->affected_rows) {
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                        }
                        $this->callSponsoredRelatedContent($sponsoredRow['id'], 0, $condition);
                    } else {
                        $delStmt = $this->conn2->prepare("delete from sponsoredposts where sponsoredposts_id=?");
                        $delStmt->bind_param('i', $id);
                        $delStmt->execute();
                        if ($delStmt->affected_rows) {
                            $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                            //$this->deleteArticleRelated($id);
                        }
                        $delStmt->close();
                    }
                } else {
                    if ($sponsoredRow['status'] == 'P') {
                        $pubDate = $sponsoredRow['publish_date'] . ' ' . $sponsoredRow['publish_time'];
                        $status = 'published';
                        $sponsoredpostsInsertStmt = $this->conn2->prepare("insert sponsoredposts set sponsoredposts_id=?,sponsoredposts_title=?,sponsoredposts_description=?,sponsoredposts_summary=?,"
                                . "sponsoredposts_type=?,sponsoredposts_published_date=?,sponsoredposts_status=?"
                                . ",important_sponsoredposts=?");
                        $sponsoredpostsInsertStmt->bind_param('issssssi', $sponsoredRow['id'], $sponsoredRow['title'], $articleRow['description'], $sponsoredRow['summary'], $sponsoredRow['event_id']
                                , $pubDate,  $status, $sponsoredRow['feature_this']);
                        $sponsoredpostsInsertStmt->execute();
                        //print_r($articleInsertStmt);exit;
                        // echo $articleInsertStmt->insert_id;exit;    
                        if ($sponsoredpostsInsertStmt->insert_id) {
                            $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                            $this->callSponsoredRelatedContent($sponsoredpostsInsertStmt->insert_id, 1, $condition);
                        }
                    }
                }
                //echo '1 done ';exit;
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwedsponsoredposts',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' bwedsponsoredposts(s) inserted, ' . $_SESSION['noofupd'] . ' bwedsponsoredposts(s) updated and ' . $_SESSION['noofdel'] . ' bwedsponsoredposts(s) deleted.</h5>';
        exit;

        
    }
   function callSponsoredRelatedContent($SponsoredId, $isNew = 0, $condition) {
        $this->migrateSponsoredImage($SponsoredId, $isNew, $condition);
        $this->migrateSponsoredVideo($SponsoredId, $isNew, $condition);
        $this->migrateSponsoredCategory($SponsoredId,$condition);
    }
   function migrateSponsoredImage($SponsoredId, $isNew = 0, $condition) {
        if ($isNew == '1') {
            $sponsoredpostImageResultset = $this->conn->query("select * from photos where owned_by='sponsoredpost' and owner_id=$SponsoredId and valid='1'");
            while ($imageRow = $sponsoredpostImageResultset->fetch_assoc()) {
                $imInsertStmt = $this->conn2->prepare("insert into sponsoredposts_images set sponsoredposts_id=?,image_url=?,image_title=?"
                        . ",image_source_name=?,image_source_url=?,image_status=?");
                $status = ($imageRow['active'] == '1') ? 'enabled' : 'disabled';
                $imInsertStmt->bind_param('isssss', $imageRow['owner_id'],$imageRow['photopath'],$imageRow['imagefullPath'], $imageRow['title'], $imageRow['source'], $imageRow['source_url'], $status);
                $imInsertStmt->execute();
                $imInsertStmt->close();
            }
        } else {

            $checkImResult = $this->conn->query("select * from photos where owned_by='sponsoredpost' and owner_id=$SponsoredId $condition");

            if ($checkImResult->num_rows > 0) {
                $checkImResult->close();
                $this->conn2->query("delete from sponsoredposts_images where sponsoredposts_id=$SponsoredId");


                $sponsoredImageResultset = $this->conn->query("select * from photos where owned_by='sponsoredpost' and owner_id=$SponsoredId and valid='1'");
                while ($imageRow = $sponsoredImageResultset->fetch_assoc()) {
                    $imInsertStmt = $this->conn2->prepare("insert into article_images set article_id=?,image_url=?,image_title=?"
                            . ",image_source_name=?,image_source_url=?,image_status=?");
                    $status = ($imageRow['active'] == '1') ? 'enabled' : 'disabled';
                    $imInsertStmt->bind_param('isssss', $imageRow['owner_id'],$imageRow['photopath'], $imageRow['imagefullPath'], $imageRow['title'], $imageRow['source'], $imageRow['source_url'], $status);
                    $imInsertStmt->execute();
                    $imInsertStmt->close();
                }
            }
        }
    }

    
        
   function migrateSponsoredCategory($sponsId,$condition) {
            $catResultSet = '';
            $this->conn2->query("delete from sponsoredposts_category where sponsoredposts_id=$sponsId");
            
            $sponCatRst = $this->conn->query("SELECT category1,category2,category3,category4 FROM `sponsoredposts` WHERE  `id`='$sponsId'");
            $catTempRow = $sponCatRst->fetch_assoc();
            $catRowArray=array();
            if($catTempRow['category1']!=0){
                $catRowArray[]=array('catlevel'=>$catTempRow['category1'],'level'=>'1');
                if($catTempRow['category2']!=0){
                    $catRowArray[]=array('catlevel'=>$catTempRow['category2'],'level'=>'2');    
                    if($catTempRow['category3']!=0){
                        $catRowArray[]=array('catlevel'=>$catTempRow['category3'],'level'=>'3');    
                        if($catTempRow['category4']!=0){
                            $catRowArray[]=array('catlevel'=>$catTempRow['category4'],'level'=>'4');    
                        }
                    }
                }
            }
            foreach ($catRowArray as $catRow) {
                $insertSponsCategoryStmt = $this->conn2->prepare("insert into sponsoredposts_category set sponsoredposts_id=?,category_id=?,category_level=?");
                $insertSponsCategoryStmt->bind_param('iii', $sponsId, $this->categoryMapping[$catRow['catlevel'].'_'.$catRow['level']], $catRow['level']);
                $insertSponsCategoryStmt->execute();
                $insertSpnsCategoryStmt->close();
            }
       
    }
    
    
    
    function migrateSponsoredVideo($SponsoredId, $isNew = 0, $condition) {
         //$articleId= 71; 
        //$isNew = 1;
         if ($isNew == '1') {
          $articleVideoResultset = $this->conn->query("select * from videos where owned_by='sponsoredpost' and owner_id=$SponsoredId and valid='1'");
                while ($videoRow = $articleVideoResultset->fetch_assoc()) {
                    $vdInsertStmt = $this->conn2->prepare("insert into sponsoredposts_video set sponsoredposts_id=?,sponsoredposts_url=?,"
                            . "video_embed_code=?,video_title=?");
                    $vdInsertStmt->bind_param('isss', $videoRow['owner_id'], $videoRow['url'], $videoRow['code'], $videoRow['title']);
                    $vdInsertStmt->execute();
                    $vdInsertStmt->close();
                }
             
         }else{
            $checkVdResult = $this->conn->query("select video_id from videos where owned_by='sponsoredpost' and owner_id=$SponsoredId $condition");
            if ($checkVdResult->num_rows > 0) {
                $checkVdResult->close();
                $this->conn2->query("delete from sponsoredposts_video where sponsoredposts_id=$SponsoredId");
                $articleVideoResultset = $this->conn->query("select * from videos where owned_by='article' and owner_id=$SponsoredId and valid='1'");
                while ($videoRow = $articleVideoResultset->fetch_assoc()) {
                    $vdInsertStmt = $this->conn2->prepare("insert into sponsoredposts_video set sponsoredposts_id=?,sponsoredposts_url=?,"
                            . "video_embed_code=?,video_title=?");
                    $vdInsertStmt->bind_param('isss', $videoRow['owner_id'], $videoRow['url'], $videoRow['code'], $videoRow['title']);
                    $vdInsertStmt->execute();
                    $vdInsertStmt->close();
                }
            }
        }
    }
    
    function migrateBwMagazineissue() {
	//echo 'test';die;
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] =0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedmagazineissue' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $magazinerResults = $this->conn->query("SELECT * FROM magazine  WHERE channel_id = $this->channelId $condition");
        if ($magazinerResults->num_rows > 0) {
            //echo $magazinerResults->num_rows; exit;
            //echo 'testsumit';
            //die;
            while ($magazineRow = $magazinerResults->fetch_assoc()) {
                //print_r($magazineRow); die;
                $magazinevalid = $magazineRow['valid'];
               //die;
                 $magazineId = $magazineRow['magazine_id'];
                if($magazinevalid=='1'){ 
                    //echo 'test'; exit;
                    //echo $magazineId;
                    //echo 'testsumitkumar';
                      //die;
                     $checkMagazineExistResultSet = $this->conn2->query("select magazine_id,title from magazine where magazine_id=$magazineId");
                     if ($checkMagazineExistResultSet->num_rows > 0) { //echo 'here'; exit;
                         $magazineUpdateStmt = $this->conn2->prepare("update magazine set title=?,imagepath=?,publish_date_m=?,story1_title=?,story1_url=?,story2_title=?,story2_url=?,story3_title=?,story3_url=?,story4_title=?, story4_url=?,story5_title=?,story5_url=?,created_at=?,updated_at=? where magazine_id=?") or die($this->conn2->error);
                         $magazineUpdateStmt->bind_param('sssssssssssssssi',$magazineRow['title'], $magazineRow['imagepath'], $magazineRow['publish_date_m'],$magazineRow['story1_title'], $magazineRow['story1_url'], $magazineRow['story2_title'], $magazineRow['story2_url'], $magazineRow['story3_title'],$magazineRow['story3_url'],$magazineRow['story4_title'],$magazineRow['story4_url'],$magazineRow['story5_title'],$magazineRow['story5_url'],$magazineRow['created_at'],$magazineRow['updated_at'],$magazineRow['magazine_id']) or die($this->conn2->error);
                         $magazineUpdateStmt->execute() or die($this->conn2->error);
                         if ($magazineUpdateStmt->affected_rows)    
                             $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                     }else {//echo 'here sumit';echo $magazineRow['magazine_id']; exit;
                     
                         $magazineInsertStmt = $this->conn2->prepare("insert into magazine set magazine_id=?,title=?,imagepath=?,publish_date_m=?,story1_title=?,story1_url=?,story2_title=?,story2_url=?,story3_title=?,story3_url=?,story4_title=?,story4_url=?,story5_title=?,story5_url=?,created_at=?,updated_at=?") or die($this->conn2->error);
                         $magazineInsertStmt->bind_param('isssssssssssssss', $magazineRow['magazine_id'],$magazineRow['title'], $magazineRow['imagepath'], $magazineRow['publish_date_m'],$magazineRow['story1_title'], $magazineRow['story1_url'], $magazineRow['story2_title'], $magazineRow['story2_url'], $magazineRow['story3_title'],$magazineRow['story3_url'],$magazineRow['story4_title'],$magazineRow['story4_url'],$magazineRow['story5_title'],$magazineRow['story5_url'],$magazineRow['created_at'],$magazineRow['updated_at']) or die($this->conn2->error);
                         $magazineInsertStmt->execute() or die($this->conn2->error);
                         if ($magazineInsertStmt->affected_rows) {
                             $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                         }
                    }
                }else{
                   // echo 'test'; exit;
                    $delStmt = $this->conn2->prepare("delete from magazine where magazine_id=?") or die($this->conn2->error);
                    $delStmt->bind_param('i', $magazineId) or die($this->conn2->error);
                    $delStmt->execute() or die($this->conn2->error);
                        if ($delStmt->affected_rows) {
                            $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                            //$this->deleteFeaturRelated($id);
                        }
                        $delStmt->close();
                    
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwedmagazineissue',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        
        //echo 'test1';die;
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' bwedmagazineissue(s) inserted,  ' . $_SESSION['noofupd'] . ' bwedmagazineissue(s) updated and '.$_SESSION['noofdel'].' bwedmagazineissue(s) deleted.</h5>';
    }
    
    function migrateBWPhotoshoot(){
        //echo 'test';exit;
        //$this->migrateTag();
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedphotoshoot' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
       $photoshootResults = $this->conn->query("SELECT *  FROM album where channel_id=$this->channelId $condition");
        //echo $photoshootResults->num_rows;exit;
        if ($photoshootResults->num_rows > 0) {
               while ($photoshootRow = $photoshootResults->fetch_assoc()) {
                   $id=$photoshootRow['id'];
                   //echo  $id=$photoshootBytesRow['id']; exit;
                   $checkResult = $this->conn2->query("select photo_shoot_title from photo_shoot where photo_shoot_id=$id");
                    if ($checkResult->num_rows > 0) {  //echo 'test';exit;
                        if($photoshootRow['valid']=='1'){//echo 'sumit'; exit();
                            
                            $photoshootUpdateStmt = $this->conn2->prepare("update photo_shoot set photo_shoot_title=?,photo_shoot_description=?,photo_shoot_sponsered=?,photo_shoot_featured=?,photo_shoot_published_date=?,photo_shoot_updated_at=? where photo_shoot_id=?");
                            //echo 'sumit';
                            $photoshootUpdateStmt->bind_param('ssiissi',$photoshootRow['title'],$photoshootRow['description'],$photoshootRow['sponsored'],$photoshootRow['featured'],$photoshootRow['created_at'],$photoshootRow['updated_at'],$id) or die($this->conn2->error);
                            //echo 'abcd'.$this->conn2->error;exit;
                            $photoshootUpdateStmt->execute();
                            //print_r($photoshootUpdateStmt);exit;
                            if ($photoshootUpdateStmt->affected_rows)
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                            $this->migrateBWPhotoshootPhoto($id,0,$condition);
                            
                        }else{
                            $delStmt = $this->conn2->prepare("delete from photo_shoot where photo_shoot_id=?");
                            $delStmt->bind_param('i', $id);
                            $delStmt->execute();
                                                 
                            
                            if ($delStmt->affected_rows) {
                                $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                                $this->deleteBWPhotoshootRelatedRelated($id);
                            }
                            $delStmt->close();
                            
                        }
                        
                    }else{//echo 'test4';exit;
                        
                             $insertStmt = $this->conn2->prepare("insert into photo_shoot set photo_shoot_id=?,"
                                     . "photo_shoot_title=?,photo_shoot_description=?,photo_shoot_sponsered=?,photo_shoot_featured=?,photo_shoot_published_date=?,photo_shoot_updated_at=?");
                            $insertStmt->bind_param('issiiss',$photoshootRow['id']
                                    ,$photoshootRow['title'],$photoshootRow['description'],$photoshootRow['sponsored'],$photoshootRow['featured'],$photoshootRow['created_at'],$photoshootRow['updated_at']);
                            $insertStmt->execute();
                            //print_r($insertStmt);exit;
                            // echo $articleInsertStmt->insert_id;exit;    
                            if ($insertStmt->insert_id) {
                                $iid=$insertStmt->insert_id;
                                $insertStmt->close();           
                                $tags=  explode(',', $photoshootRow['tags']);                               
                                foreach($tags as $tag){
                                    $this->conn2->query("insert into photo_shoot_tags set photo_shoot_id=$iid,tag_id=$tag");
                                }
                                $this->migrateBWPhotoshootPhoto($iid,1,$condition);
                                $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                                
                            }
                            
                        }
                    
               }

        }
        
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwedphotoshoot',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' Bwedhotoshoot(s) inserted, ' . $_SESSION['noofupd'] . ' BWHPhotoshoot(s) updated and ' . $_SESSION['noofdel'] . ' BWHPhotoshoot(s) deleted.</h5>';
        

    }
    function migrateBWPhotoshootPhoto($id,$is_new=0,$condition){
       if ($is_new) {
            $photos = $this->conn->query("select * from photos where owned_by='album' and owner_id=$id");
            while ($photo = $photos->fetch_object()) {
                //print_r($photo);exit;
                $photoInsStmt = $this->conn2->prepare("insert into photo_shoot_photos set photo_shoot_id=?,photo_shoot_photo_name=?,photo_shoot_photo_url=?"
                        . ",photo_shoot_photo_title=?,photo_by=?,photo_shoot_photo_description=?,sequence=?");
                $photoInsStmt->bind_param('isssssi', $id, $photo->photopath, $photo->imagefullPath, $photo->title,$photo->photo_by,$photo->description,$photo->sequence);
                $photoInsStmt->execute();
            }
        } else {
           
            $checkImResult = $this->conn->query("select * from photos where owned_by='album' and owner_id=$id $condition");

            //$checkImResult = $this->conn->query("select * from photos where owned_by='article' and owner_id=$articleId $condition");
            //echo $checkImResult->num_rows; echo '<br>'; exit;
            if ($checkImResult->num_rows > 0) {
                $checkImResult->close();
                $this->conn2->query("delete from photo_shoot_photos where photo_shoot_id=$id");

                $photos = $this->conn->query("select * from photos where owned_by='album' and owner_id=$id");
                //echo $photos->num_rows; exit;
                while ($photo = $photos->fetch_object()) {
                    //print_r($photo);exit;
                    $photoInsStmt = $this->conn2->prepare("insert into photo_shoot_photos set photo_shoot_id=?,photo_shoot_photo_name=?,photo_shoot_photo_url=?"
                            . ",photo_shoot_photo_title=?,photo_by=?,photo_shoot_photo_description=?,sequence=?") or die($this->conn2->error);;
                    $photoInsStmt->bind_param('isssssi', $id, $photo->photopath, $photo->imagefullPath, $photo->title,$photo->photo_by,$photo->description,$photo->sequence) or die($this->conn2->error);
                    $photoInsStmt->execute() or die($this->conn2->error);
                }
            }
        }
    }
   
   function deleteBWPhotoshootRelatedRelated($id){
       $delStmt = $this->conn2->prepare("delete from photo_shoot_photos where photo_shoot_id=?");
       $delStmt->bind_param('i', $id);
       $delStmt->execute();
        
    } 
    
    
    function migrateMasterVideo() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
      //  echo "select start_time from cron_log where section_name='bwedmastervideo' order by  start_time desc limit 0,1"; exit;
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedmastervideo' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            // $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $masterVideoResults = $this->conn->query("SELECT * FROM video_master where channel_id=$this->channelId  $condition");
        //echo $masterVideoResults->num_rows; exit;
        if ($masterVideoResults->num_rows > 0) {

            $catMapArray = array();
            $videoCatDataRst = $this->conn2->query("select * from channel_category");
            while ($videoCatDataRow = $videoCatDataRst->fetch_assoc()) {
                $key = $videoCatDataRow['cms_cat_id'] . '_' . $videoCatDataRow['cms_cat_level'];
                $catMapArray[$key] = $videoCatDataRow['category_id'];
            }
            $this->categoryMapping = $catMapArray;

            while ($masterVideoRow = $masterVideoResults->fetch_assoc()) {
                //print_r($masterVideoRow); exit;
                $masterVideoId = $masterVideoRow['id'];
                $checkmasterVideoExistResultSet = $this->conn2->query("select video_id, video_title,video_summary, video_name,video_thumb_name,tags,created_at,updated_at from video_master where video_id=$masterVideoId");
                if ($checkmasterVideoExistResultSet->num_rows > 0) { //echo 'going to update';exit;  
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    if($masterVideoRow['video_status']=='1'){//echo 'sumit'; exit();
                    $masterVideoUpdateStmt = $this->conn2->prepare("update video_master set video_title=?,video_summary=?,video_name=?,video_thumb_name=?,tags=?,created_at=?,updated_at=?,campaign_id=?,video_by=? where video_id=?");
                    //echo $this->conn2->error; exit;
                    $masterVideoUpdateStmt->bind_param('sssssssisi', $masterVideoRow['video_title'], $masterVideoRow['video_summary'], $masterVideoRow['video_name'], $masterVideoRow['video_thumb_name'], $masterVideoRow['tags'], $masterVideoRow['created_at'], $masterVideoRow['updated_at'], $masterVideoRow['campaign_id'], $masterVideoRow['video_by'], $masterVideoId);
                    $masterVideoUpdateStmt->execute() or die($this->conn2->error);
                    //print_r($masterVideoUpdateStmt);exit;

                    $iid = $masterVideoRow['id'];
                    //echo $iid ; exit;
                    $this->callVideoRelatedContent($iid);
                    $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    } else {
                        $delStmt = $this->conn2->prepare("delete from video_master where video_id=?");
                        $delStmt->bind_param('i', $masterVideoId);
                        $delStmt->execute();
                        
                    }
                    // echo  $_SESSION['noofupd'];
                } else {//echo 'going to insert';exit;
                    $masterVideoInsertStmt = $this->conn2->prepare("insert into video_master set video_id=?,video_title=?,video_summary=?,video_name=?,video_thumb_name=?,tags=?,created_at=?,updated_at=?,campaign_id=?,video_by=?");
                    //echo $this->conn2->error; exit;
                    $masterVideoInsertStmt->bind_param('isssssssis', $masterVideoRow['id'], $masterVideoRow['video_title'], $masterVideoRow['video_summary'], $masterVideoRow['video_name'], $masterVideoRow['video_thumb_name'], $masterVideoRow['tags'], $masterVideoRow['created_at'], $masterVideoRow['updated_at'], $masterVideoRow['campaign_id'], $masterVideoRow['video_by']);
                    $masterVideoInsertStmt->execute() or die($this->conn2->error);
                    //print_r($masterVideoInsertStmt);exit;
                    // echo $articleInsertStmt->insert_id;exit;    
                    if ($masterVideoInsertStmt->insert_id) {
                        $iid = $masterVideoInsertStmt->insert_id;
                        $masterVideoInsertStmt->close();
                        $this->callVideoRelatedContent($iid);
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwedmastervideo',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' mastervideo(s) inserted and ' . $_SESSION['noofupd'] . ' mastervideo(s) updated.</h5>';
    }

    function callVideoRelatedContent($id) {
        //echo $id ; exit;
        $delStmt = $this->conn2->prepare("delete from video_category where video_id=?") or die($this->conn2->error);
        $delStmt->bind_param('i', $id) or die($this->conn2->error);
        $delStmt->execute();
        $delStmt->close();
        //echo 'test'; exit;
        $videoCatRst = $this->conn->query("select * from video_category where video_id=$id");
        while (($videoCatRow = $videoCatRst->fetch_assoc())) {

            $catInsertStmt = $this->conn2->prepare("insert into video_category  set v_category_id=?,video_id=?,category_id=?,level=?") or die($this->conn2->error);
            $catInsertStmt->bind_param('iiii', $videoCatRow['v_category_id'], $id, $this->categoryMapping[$videoCatRow['category_id'] . '_' . $videoCatRow['level']], $videoCatRow['level']) or die($this->conn2->error);
            $catInsertStmt->execute() or die($this->conn2->error);
            $catInsertStmt->close();
        }

        // Deleting tag if already exist
        $delStmt = $this->conn2->prepare("delete from video_tags where video_id=?") or die($this->conn2->error);
        $delStmt->bind_param('i', $id) or die($this->conn2->error);
        $delStmt->execute();
        $delStmt->close();
        // Inserting tags
        $videoTagRst = $this->conn->query("select * from video_tags where video_id=$id");
        while (($videoTagRow = $videoTagRst->fetch_assoc())) {
            $tagInsertStmt = $this->conn2->prepare("insert into video_tags  set v_tags_id=?,video_id=?,tags_id=?") or die($this->conn2->error);
            $tagInsertStmt->bind_param('iii', $videoTagRow['v_tags_id'], $id, $videoTagRow['tags_id']) or die($this->conn2->error);
            $tagInsertStmt->execute() or die($this->conn2->error);
            $tagInsertStmt->close();
        }
    }
function migrateMasterNewsLetter() {
    
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwedmasternewsletter' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
             $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        //echo 'sumit' ;exit;
        $masterNewsLetterResults = $this->conn->query("SELECT * FROM master_newsletter where channel_id= $this->channelId  $condition");
       // echo $masterNewsLetterResults->num_rows; exit;
        if ($masterNewsLetterResults->num_rows > 0) {

            

            while ($masterNewsLetterRow = $masterNewsLetterResults->fetch_assoc()) {
                //print_r($masterNewsLetterRow); exit;
                $masterNewsLetterId = $masterNewsLetterRow['id'];
                $checkmasterNewsLettertResultSet = $this->conn2->query("select * from master_newsletter where id=$masterNewsLetterId");
                if ($checkmasterNewsLettertResultSet->num_rows > 0) { //echo 'going to update';exit;  
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    if($masterNewsLetterRow['is_deleted']=='0'){//echo 'sumit'; exit();
                    $masterNewsLetterUpdateStmt = $this->conn2->prepare("update master_newsletter set title=?,created_at=?,updated_at=? where id=?");
                    //echo $this->conn2->error; exit;
                    $masterNewsLetterUpdateStmt->bind_param('sssi', $masterNewsLetterRow['title'], $masterNewsLetterRow['created_at'], $masterNewsLetterRow['updated_at'], $masterNewsLetterId);
                    $masterNewsLetterUpdateStmt->execute() or die($this->conn2->error);
                    //print_r($masterNewsLetterUpdateStmt);exit;

                    $iid = $masterNewsLetterRow['id'];
                    //echo $iid ; exit;
                    $this->callNewsLetterRelatedContent($iid);
                    $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    } else {
                        $delStmt = $this->conn2->prepare("delete from master_newsletter where id=?");
                        $delStmt->bind_param('i', $masterNewsLetterId);
                        $delStmt->execute();
                        
                    }
                    // echo  $_SESSION['noofupd'];
                } else {//echo 'going to insert';exit;
                    if($masterNewsLetterRow['is_deleted']=='0'){
                    $masterNewsLetterInsertStmt = $this->conn2->prepare("insert into master_newsletter set id=?,title=?,created_at=?,updated_at=?");
                    //echo $this->conn2->error; exit;
                    $masterNewsLetterInsertStmt->bind_param('isss', $masterNewsLetterRow['id'], $masterNewsLetterRow['title'],$masterNewsLetterRow['created_at'], $masterNewsLetterRow['updated_at']);
                    $masterNewsLetterInsertStmt->execute() or die($this->conn2->error);
                    //print_r($masterNewsLetterInsertStmt);exit;
                    // echo $masterNewsLetterInsertStmt->insert_id;exit;    
                    if ($masterNewsLetterInsertStmt->insert_id) {
                        $iid = $masterNewsLetterInsertStmt->insert_id;
                        $masterNewsLetterInsertStmt->close();
                        $this->callNewsLetterRelatedContent($iid);
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                  } else {
                        $delStmt = $this->conn2->prepare("delete from master_newsletter where id=?");
                        $delStmt->bind_param('i', $masterNewsLetterId);
                        $delStmt->execute();
                        
                    }  
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwedmasternewsletter',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' masternewsletter(s) inserted and ' . $_SESSION['noofupd'] . ' masternewsletter(s) updated.</h5>';
    }

     function callNewsLetterRelatedContent($id) {
        //echo $id ; exit;
        $delStmt = $this->conn2->prepare("delete from master_newsletter_articles where master_newsletter_id=?") or die($this->conn2->error);
        $delStmt->bind_param('i', $id) or die($this->conn2->error);
        $delStmt->execute();
        $delStmt->close();
        //echo 'test'; exit;
        $masterNewsLetterArticleRst = $this->conn->query("select * from master_newsletter_articles where master_newsletter_id=$id");
        while (($masterNewsLetterArticleRow = $masterNewsLetterArticleRst->fetch_assoc())) {
	if ($masterNewsLetterArticleRow['is_deleted']=='0'){
            $masterNewsLetterArticleInsertStmt = $this->conn2->prepare("insert into master_newsletter_articles  set master_newsletter_id=?,article_id=?,sequence=?,is_deleted=?,updated_at=?") or die($this->conn2->error);
            $masterNewsLetterArticleInsertStmt->bind_param('iiiis', $masterNewsLetterArticleRow['master_newsletter_id'],  $masterNewsLetterArticleRow['article_id'],$masterNewsLetterArticleRow['sequence'], $masterNewsLetterArticleRow['is_deleted'], $masterNewsLetterArticleRow['updated_at']) or die($this->conn2->error);
            $masterNewsLetterArticleInsertStmt->execute() or die($this->conn2->error);
            $masterNewsLetterArticleInsertStmt->close();
	} else {
           $delStmt = $this->conn2->prepare("delete from master_newsletter_articles where article_id=?");
           $delStmt->bind_param('i', $masterNewsLetterArticleRow['article_id']);
           $delStmt->execute();
                        
          } 
        }
 
    }
    
}



?>
