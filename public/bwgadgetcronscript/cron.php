<?php
require_once "Mail.php";
require_once "Mail/mime.php";
class Cron {
    var $conn;
    var $conn2;
    var $message;
    var $keyarray;
    var $categoryMapping;
    var $channelId;
    var $url;

    function __construct(){
        $this->conn = new mysqli(HOST, USER, PASS, DATABASE) or die($this->conn->connect_errno);
        mysqli_set_charset($this->conn, "utf8");
        $this->conn2 = new mysqli(LHOST, LUSER, LPASS, LDATABASE) or die($this->conn2->connect_errro);
        mysqli_set_charset($this->conn2, "utf8");
        $this->channelId = 14;
        $this->url='http://bwgadget.businessworld.in/';
    }

    function migrateData($section) {
        //$this->migrateArticleAuthor('1','2');exit;
        //print_r($arr);exit;
        //echo $section; exit;
        
        switch ($section):
            case 'author':
                $this->migrateAuthor();
                break;
            case 'event':
                $this->migrateEvent();
                break;
            case 'articleviewcount':
                $this->Articleviewcount();
                break;
            case 'tips':
                $this->migrateTips();
                break;
            case 'tipcategory':
                $this->migratetipCategory();
                break;
            case 'quotes':
                $this->migrateQuotes();
                break;
            case 'quotestag':
                $this->migratequotesTage();
                break;
            case 'photoshoot':
                $this->migratePhotoshoot();
                break;
            case 'quotesauthor':
                $this->migratequotesCategory();
                break;

            case 'Sponsored':
                $this->migrateSponsored();
                break;
            case 'tipstagcombinations':
                $this->migratetipstagComb();
                break;

            case 'feature':
                $this->migrateFeature();
                break;
            case 'featureviewcount':
                $this->Featureviewcount();
                break;
            case 'sponsorviewcount':
                $this->Sponsorviewcount();
                break;
            case 'category':
                $this->migrateCategory();
                break;
            case 'tag':
                $this->migrateTag();
                break;
            case 'article':
                $this->migrateArticle();
                break;
            case 'newsletter':
                $this->migrateMasterNewsLetter();
                break;
            case 'topics' :
                $this->migrateTopics();
                break;
            case 'generatetopics' :
                $this->generateTopics();
                break;
            case 'newstype':
                $this->migrateNewsType();
                break;
            case 'quickbyte':
                $this->migrateQuickByte();
                break;
            case 'magazine':
                $this->migrateMagazineissue();
                break;
            case 'mastervideo':
                $this->migrateMasterVideo();
                break;
            case 'debate':
                $this->migrateDebate();
                break;
            case 'sendreport':
                //echo 'test'; exit;
                $this->generateReport();
                break;
            case 'eveningreport':
                $this->generateEveningReport();
                break;
            case 'dailyreport':
                $this->sendDailyReport();
                break;
            case 'campaing':
                //echo 'test'; exit;
                $this->migrateCampaing();
                break;
            case 'chkpti':
                $this->checkPtiServer();
                break;
            case 'subscriber':
                $this->migrateNewsletterSubscriber();
                break;
            case 'trending':
                $this->migrateNewsTrending();
                break;
            case 'Magazineissuearticlelist':
                $this->MagazineissueArticlelist();
                break;
            case 'podcast':
                $this->Podcast();
                break;
            case 'PodcastAudioList':
                $this->podcastAudioList();
                break;
            case 'izooto':
                $this->izootoContentPush();
                break;
            case 'removecsv':
                $this->removeCsv();
                break;
            case 'livestreaming':
                $this->Livestreaming();
                break;
            case 'quoteswtbd': // Quotest what's app broadcast
                $this->whatsAppBroadcast();
                break;
            case 'livefeed':
                $this->migrateLiveFeed();
            //whatsAppBroadcast
        endswitch;

        $_SESSION['message'] = $this->message;
    }

    function migrateAuthor() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetauthor' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $authorResults = $this->conn->query("SELECT * FROM authors where  1 $condition");
        //echo $authorResults->num_rows; exit;
        if ($authorResults->num_rows > 0) {

            while ($authorRow = $authorResults->fetch_assoc()) {
                // print_r($authorRow); exit;
                $authorId = $authorRow['author_id'];
                $checkAthorExistResultSet = $this->conn2->query("select author_id,author_name from author where author_id=$authorId");
                if ($checkAthorExistResultSet->num_rows > 0) { //echo 'going to update';exit;  
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $authorUpdateStmt = $this->conn2->prepare("update author set author_name=?,author_photo=?,author_bio=?,author_type=?,column_id=?,valid=? where author_id=?");
                    $authorUpdateStmt->bind_param('sssiiii', $authorRow['name'], $authorRow['photo'], $authorRow['bio'], $authorRow['author_type_id'], $authorRow['column_id'], $authorRow['valid'], $authorId);
                    $authorUpdateStmt->execute();
                    if ($authorUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    // echo  $_SESSION['noofupd'];
                }else {
                    $authorInsertStmt = $this->conn2->prepare("insert into author set author_id=?,author_name=?,author_photo=?,author_bio=?,author_type=?,column_id=?,valid=?");
                    //echo $this->conn2->error; exit;
                    $authorInsertStmt->bind_param('isssiii', $authorRow['author_id'], $authorRow['name'], $authorRow['photo'], $authorRow['bio'], $authorRow['author_type_id'], $authorRow['column_id'], $authorRow['valid']);
                    $authorInsertStmt->execute();
                    if ($authorInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetauthor',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' author(s) inserted and ' . $_SESSION['noofupd'] . ' author(s) updated.</h5>';
    }


    function migratePhotoshoot() {
        //echo 'test';exit;
        //$this->migrateTag();
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetPhotoshoot' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $photoshootResults = $this->conn->query("SELECT *  FROM album where channel_id='$this->channelId' $condition");
        //echo $photoshootResults->num_rows;exit;
        if ($photoshootResults->num_rows > 0) {
            while ($photoshootRow = $photoshootResults->fetch_assoc()) {
                $id = $photoshootRow['id'];
                //echo  $id=$photoshootBytesRow['id']; exit;
                $checkResult = $this->conn2->query("select photo_shoot_title from photo_shoot where photo_shoot_id=$id");
                if ($checkResult->num_rows > 0) {  //echo 'test';exit;
                    if ($photoshootRow['valid'] == '1') {//echo 'sumit'; exit();
                        $photoshootUpdateStmt = $this->conn2->prepare("update photo_shoot set photo_shoot_author_type=?,photo_shoot_title=?,photo_shoot_description=?,photo_shoot_sponsered=?,photo_shoot_featured=?,photo_shoot_published_date=?,photo_shoot_updated_at=?,campaign_id=? where photo_shoot_id=?");
                        //echo 'sumit';
                        $photoshootUpdateStmt->bind_param('issiissii',$photoshootRow['author_type'],$photoshootRow['title'], $photoshootRow['description'], $photoshootRow['sponsored'], $photoshootRow['featured'], $photoshootRow['created_at'], $photoshootRow['updated_at'], $photoshootRow['campaign_id'], $id) or die($this->conn2->error);
                        //echo 'abcd'.$this->conn2->error;exit;
                        $photoshootUpdateStmt->execute();
                        //print_r($photoshootUpdateStmt);exit;
                        if ($photoshootUpdateStmt->affected_rows)
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                        
                        $this->conn2->query("delete from photo_shoot_tags where photo_shoot_id=$id");
                        
                        $tags = explode(',', $photoshootRow['tags']);
                        foreach ($tags as $tag) {
                            $this->conn2->query("insert into photo_shoot_tags set photo_shoot_id=$id,tag_id=$tag");
                        }
                        $this->migratePhotoshootPhoto($id, 0, $condition);
                        $this->migratePhotoshootAuthor($id, 0, $condition);
                    }else {
                        $delStmt = $this->conn2->prepare("delete from photo_shoot where photo_shoot_id=?");
                        $delStmt->bind_param('i', $id);
                        $delStmt->execute();
                        if ($delStmt->affected_rows) {
                            $this->conn2->query("delete from photo_shoot_tags where photo_shoot_id=$id");
                            $this->conn2->query("delete from photo_shoot_authors where photo_shoot_id=$id");
                            $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                            $this->deletePhotoshootRelatedRelated($id);
                        }
                        $delStmt->close();
                    }
                } else {//echo 'test4';exit;
                    $insertStmt = $this->conn2->prepare("insert into photo_shoot set photo_shoot_id=?,photo_shoot_author_type=?"
                            . ",photo_shoot_title=?,photo_shoot_description=?,photo_shoot_sponsered=?,photo_shoot_featured=?,photo_shoot_published_date=?,photo_shoot_updated_at=?,campaign_id=?");
                    $insertStmt->bind_param('iissiissi', $photoshootRow['id'],$photoshootRow['author_type']
                            , $photoshootRow['title'], $photoshootRow['description'], $photoshootRow['sponsored'], $photoshootRow['featured'], $photoshootRow['created_at'], $photoshootRow['updated_at'], $photoshootRow['campaign_id']);
                    $insertStmt->execute();
                    //print_r($insertStmt);exit;
                    // echo $articleInsertStmt->insert_id;exit;    
                    if ($insertStmt->insert_id) {
                        $iid = $insertStmt->insert_id;
                        $insertStmt->close();
                        $tags = explode(',', $photoshootRow['tags']);
                        foreach ($tags as $tag) {
                            $this->conn2->query("insert into photo_shoot_tags set photo_shoot_id=$iid,tag_id=$tag");
                        }
                        $this->migratePhotoshootPhoto($iid, 1, $condition);
                        $this->migratePhotoshootAuthor($iid, 1, $condition);
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetPhotoshoot',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' Photoshoot(s) inserted, ' . $_SESSION['noofupd'] . ' Photoshoot(s) updated and ' . $_SESSION['noofdel'] . ' Photoshoot(s) deleted.</h5>';
    }

    function migratePhotoshootPhoto($id, $is_new = 0, $condition) {
        if ($is_new) {
            $photos = $this->conn->query("select * from photos where owned_by='album' and owner_id=$id");
            while ($photo = $photos->fetch_object()) {
                //print_r($photo);exit;
                $photoInsStmt = $this->conn2->prepare("insert into photo_shoot_photos set photo_shoot_id=?,photo_shoot_photo_name=?,photo_shoot_photo_url=?"
                        . ",photo_shoot_photo_title=?,photo_by=?,photo_shoot_photo_description=?,sequence=?");
                $photoInsStmt->bind_param('isssssi', $id, $photo->photopath, $photo->imagefullPath, $photo->title, $photo->photo_by, $photo->description, $photo->sequence);
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
                            . ",photo_shoot_photo_title=?,photo_by=?,photo_shoot_photo_description=?,sequence=?") or die($this->conn2->error);
                    ;
                    $photoInsStmt->bind_param('isssssi', $id, $photo->photopath, $photo->imagefullPath, $photo->title, $photo->photo_by, $photo->description, $photo->sequence) or die($this->conn2->error);
                    $photoInsStmt->execute() or die($this->conn2->error);
                }
            }
        }
    }

    
    function migratePhotoshootAuthor($id, $isNew = 0, $condition = '') {

        if ($isNew == '1') {
            $albumAuthorResultset = $this->conn->query("select * from album_author where album_id=$id");
            while ($authorRow = $albumAuthorResultset->fetch_assoc()) {
                $tauthId = $authorRow['author_id'];
                $auInsertStmt = $this->conn2->prepare("insert into photo_shoot_authors set photo_shoot_id=?,author_id=?");
                $auInsertStmt->bind_param('ii', $authorRow['album_id'],$authorRow['author_id']);
                $auInsertStmt->execute();
                $auInsertStmt->close();
            }
            
        } else {
            
            $this->conn2->query("delete from photo_shoot_authors where photo_shoot_id=$id");
            
            $albumAuthorResultset = $this->conn->query("select * from album_author where album_id=$id");
            
            while ($authorRow = $albumAuthorResultset->fetch_assoc()) {
                $tauthId = $authorRow['author_id'];
                $auInsertStmt = $this->conn2->prepare("insert into photo_shoot_authors set photo_shoot_id=?,author_id=?");
                $auInsertStmt->bind_param('ii', $authorRow['album_id'], $authorRow['author_id']);
                $auInsertStmt->execute();
                $auInsertStmt->close();
            }            
        }
    }
    
    function deletePhotoshootRelatedRelated($id) {
        $delStmt = $this->conn2->prepare("delete from photo_shoot_photos where photo_shoot_id=?");
        $delStmt->bind_param('i', $id);
        $delStmt->execute();
    }



    function migrateEvent() {
        ///echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetevent' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $eventrResults = $this->conn->query("SELECT * FROM event  WHERE channel_id= '$this->channelId'  $condition");
        if ($eventrResults->num_rows > 0) {
            while ($eventRow = $eventrResults->fetch_assoc()) {
                $eventId = $eventRow['event_id'];
                $checkEventExistResultSet = $this->conn2->query("select event_id,title from event where event_id=$eventId");
                if ($checkEventExistResultSet->num_rows > 0) { //echo 'here'; exit;
                    $eventUpdateStmt = $this->conn2->prepare("update event set title=?,description=?,imagepath=?,image_url=?,start_date=?,end_date=?,start_time=?,end_time=?,country=?,state=?,valid=?,created_at=?,updated_at=? where event_id=?") or die($this->conn->error);
                    $eventUpdateStmt->bind_param('ssssssssiiissi', $eventRow['title'], $eventRow['description'], $eventRow['imagepath'], $eventRow['image_url'], $eventRow['start_date'], $eventRow['end_date'], $eventRow['start_time'], $eventRow['end_time'], $eventRow['country'],$eventRow['state'], $eventRow['valid'], $eventRow['created_at'], $eventRow['updated_at'], $eventRow['event_id']) or die($this->conn->error);
                    $eventUpdateStmt->execute()or die($this->conn->error);
                    if ($eventUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {
                    $eventInsertStmt = $this->conn2->prepare("insert into event set event_id=?,title=?,description=?,imagepath=?,image_url=?,start_date=?,end_date=?,start_time=?,end_time=?,country=?,state=?,valid=?,created_at=?,updated_at=?");
                    $eventInsertStmt->bind_param('issssssssiiiss', $eventRow['event_id'], $eventRow['title'], $eventRow['description'], $eventRow['imagepath'], $eventRow['image_url'], $eventRow['start_date'], $eventRow['end_date'], $eventRow['start_time'], $eventRow['end_time'], $eventRow['country'],$eventRow['state'], $eventRow['valid'], $eventRow['created_at'], $eventRow['updated_at']);
                    $eventInsertStmt->execute();
                    if ($eventInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }
        $this->migrateSpeakerTag();
        $this->migrateSpeaker();
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetevent',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' event(s) inserted and ' . $_SESSION['noofupd'] . ' event(s) updated.</h5>';
    }


    function migrateTopicCategory() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgettopiccategory' order by start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';

        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $topicCategoryResults = $this->conn->query("SELECT *  FROM topic_category where 1 $condition");
        //echo $tagResults->num_rows;exit;
        if ($topicCategoryResults->num_rows > 0) {
            while ($catrow = $topicCategoryResults->fetch_assoc()) {
                $cid = $catrow['id'];
                $checkCatExistResultSet = $this->conn2->query("select * from topic_category where id=$cid");
                if ($checkCatExistResultSet->num_rows > 0) {
                    $existingCatRow = $checkCatExistResultSet->fetch_assoc();
                    $checkCatExistResultSet->close();
                    if ($catrow['is_deleted'] == 0) {
                        $catUpdateStmt = $this->conn2->prepare("update topic_category set name=?,parent_id=? where id=?");
                        //echo $this->conn2->error;exit;
                        $catUpdateStmt->bind_param('sii', $catrow['name'], $catrow['parent_id'], $cid);
                        $catUpdateStmt->execute();
                        if ($catUpdateStmt->affected_rows)
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    }else {
                        $delStmt = $this->conn2->prepare("delete from topic_category where id=?");
                        $delStmt->bind_param('i', $catrow['id']);
                        $delStmt->execute();
                        $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                    }
                } else {
                    $catInsertStmt = $this->conn2->prepare("insert into topic_category set id=?,name=?,parent_id=?") or die($this->conn2->error);

                    $catInsertStmt->bind_param('isi', $catrow['id'], $catrow['name'], $catrow['parent_id']);
                    $catInsertStmt->execute();
                    if ($catInsertStmt->insert_id) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgettopiccategory',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();

        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' topic category(ies) inserted,   ' . $_SESSION['noofupd'] . ' topic category(ies) updated and ' . $_SESSION['noofdel'] . ' topic category(ies) deleted.</h5>';
    }

     
    function migrateCategory() {
        //echo 'test'; exit;
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetcategory' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        $cronLastExecutionTime = 0;
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

//        $catresults = $this->conn->query("SELECT category_id as id,name,channel_id as parent_id,valid,'1' as level  FROM category where channel_id='1' $condition
//union
//select category_two_id as id,name,category_id as parent,valid,'2' as level from category_two where 1=1 $condition
//union
//select category_three_id as id,name,category_two_id as parent,valid,'3' as level from category_three where 1=1 $condition
//union
//select category_four_id as id,name,category_three_id as parent,valid,'4' as level from category_four where 1=1 $condition");
//        
//        echo $catresults->num_rows;exit;
        $catresults = $this->conn->query("SELECT category_id as id,name,channel_id as parent_id,valid,'1' as level  FROM category where channel_id='$this->channelId' $condition");
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
        //echo $condition; exit;
        $this->migrateSubCategoryDirect($cronLastExecutionTime);
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetcategory',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' category/categories inserted and ' . $_SESSION['noofupd'] . ' category/categories updated.</h5>';
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

    function migrateSubCategory($frontparentId, $cmsParentId, $cmsLevel, $cronLastExecutionTime) {
        $condition = '';
        if ($cronLastExecutionTime) {
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        if ($cmsLevel == '2') {
            $query = "select category_two_id as id,name,category_id as parent,valid from category_two where category_id='$cmsParentId' $condition";
        } elseif ($cmsLevel == '3') {
            $query = "select category_three_id as id,name,category_two_id as parent,valid from category_three where category_two_id='$cmsParentId' $condition";
        } elseif ($cmsLevel == '4') {
            $query = "select category_four_id as id,name,category_three_id as parent,valid from category_four where category_three_id='$cmsParentId' $condition";
        }
        //echo $query; exit;
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

    function migrateTag() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgettag' order by  start_time desc limit 0,1") or die($this->conn->error);
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
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgettag',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();

        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' tag(s) inserted and ' . $_SESSION['noofupd'] . ' tag(s) updated.</h5>';
    }

    function migrateSpeakerTag() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetspeakertag' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $tagResults = $this->conn->query("SELECT tags_id as id,tag,valid  FROM speaker_tags where 1 $condition");
        //echo $tagResults->num_rows;exit;
        if ($tagResults->num_rows > 0) {
            while ($tagrow = $tagResults->fetch_assoc()) {
                $tid = $tagrow['id'];
                $checkTagExistResultSet = $this->conn2->query("select tags_id,tag from speaker_tags where tags_id=$tid");
                if ($checkTagExistResultSet->num_rows > 0) {
                    $checkTagExistResultSet->close();
                    $tagUpdateStmt = $this->conn2->prepare("update speaker_tags set tag=?,valid=? where tags_id=?");
                    $tagUpdateStmt->bind_param('sii', $tagrow['tag'], $tagrow['valid'], $tid);
                    $tagUpdateStmt->execute();
                    if ($tagUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {
                    if ($tagrow['valid']) {
                        $tagInsertStmt = $this->conn2->prepare("insert into speaker_tags set tags_id=?,tag=?,valid=?");
                        $tagInsertStmt->bind_param('isi', $tagrow['id'], $tagrow['tag'], $tagrow['valid']);
                        $tagInsertStmt->execute();
                        if ($tagInsertStmt->insert_id) {
                            $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                        }
                    }
                }
            }
        }


        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetspeakertag',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();

        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' speaker tag(s) inserted and ' . $_SESSION['noofupd'] . ' speaker tag(s) updated.</h5>';
    }

    function migrateSpeaker() {

        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetspeaker' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            // $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $authorResults = $this->conn->query("SELECT * FROM event_speaker where  1 $condition");
        //echo $authorResults->num_rows; exit;
        if ($authorResults->num_rows > 0) {
            while ($authorRow = $authorResults->fetch_assoc()) {
                // print_r($authorRow); exit;
                $authorId = $authorRow['id'];
                $checkAthorExistResultSet = $this->conn2->query("select id from event_speaker where id=$authorId");
                if ($checkAthorExistResultSet->num_rows > 0) {
                    if ($authorRow['status']) {
                        $authorUpdateStmt = $this->conn2->prepare("update event_speaker set event_id=?,name=?,email=?,photo=?,twitter=?,description=?,tag=?,sequence=? where id=?");
                        $authorUpdateStmt->bind_param('issssssii', $authorRow['event_id'], $authorRow['name'], $authorRow['email'], $authorRow['photo'], $authorRow['twitter'], $authorRow['description'], $authorRow['tag'], $authorRow['sequence'], $authorRow['id']);
                        $authorUpdateStmt->execute();
                        if ($authorUpdateStmt->affected_rows)
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    }else {
                        $authorDelStmt = $this->conn2->prepare("delete from event_speaker where id=?");
                        $authorDelStmt->bind_param('i', $authorRow['id']);
                        $authorDelStmt->execute();
                        $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                    }
                } else {
                    $authorInsertStmt = $this->conn2->prepare("insert into event_speaker set id=?,event_id=?,name=?,email=?,photo=?,twitter=?,description=?,tag=?,sequence=?");
                    $authorInsertStmt->bind_param('iissssssi', $authorRow['id'], $authorRow['event_id'], $authorRow['name'], $authorRow['email'], $authorRow['photo'], $authorRow['twitter'], $authorRow['description'], $authorRow['tag'], $authorRow['sequence']);
                    $authorInsertStmt->execute();
                    if ($authorInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }


        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetspeaker',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' speaker(s) inserted, ' . $_SESSION['noofupd'] . ' speaker(s) updated and ' . $_SESSION['noofdel'] . ' speaker(s) deleted.</h5>';
    }

    function migrateNewsType() {

        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetnewstype' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $newTypeResults = $this->conn->query("SELECT * FROM news_type where 1 $condition");
        if ($newTypeResults->num_rows > 0) {
            while ($newsTypeRow = $newTypeResults->fetch_assoc()) {
                //print_r($newsTypeRow);exit;
                $id = $newsTypeRow['news_type_id'];
                $checkNewsTypeResultSet = $this->conn2->query("select news_type_id from news_type where news_type_id=$id");
                //echo $checkNewsTypeResultSet->num_rows;exit;
                if ($checkNewsTypeResultSet->num_rows > 0) {
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $newTypeUpdateStmt = $this->conn2->prepare("update news_type set name=?,valid=? where news_type_id=?");
                    $newTypeUpdateStmt->bind_param('sii', $newsTypeRow['name'], $newsTypeRow['valid'], $id);
                    $newTypeUpdateStmt->execute();
                    if ($newTypeUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {
                    $newTypeInsertStmt = $this->conn2->prepare("insert into news_type set name=?,valid=?,news_type_id=?");
                    $newTypeInsertStmt->bind_param('sii', $newsTypeRow['name'], $newsTypeRow['valid'], $id);
                    $newTypeInsertStmt->execute();
                    if ($newTypeInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetnewstype',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' newstype(s) inserted and ' . $_SESSION['noofupd'] . ' newstype(s) updated.</h5>';
    }

  
      function generateTopics(){
          $_SESSION['noofins'] = 0;
          $_SESSION['noofupd'] = 0;
          $articles_topic=array();
          $conStartTime = date('Y-m-d H:i:s');
          $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetgeneratetopics' order by  start_time desc limit 0,1") or die($this->conn->error);
           if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
          }
        
           $results = $this->conn->query("SELECT id,topic FROM topics where valid='1'");
           $articleResults = $this->conn->query("SELECT article_id,description  FROM articles where  status='P'  $condition");
           $articles=array();
           
           if ($articleResults->num_rows > 0) {
               while($article=$articleResults->fetch_assoc()){
                    $articles[$article['article_id']]=$article['description'];
                }
               while ($row = $results->fetch_assoc()) {
                   $allArticles=$articles;
                   $pattern = '/\b('.$row['topic'].')\b/';
                   foreach($allArticles as $key => $value){
                       $ret = preg_match($pattern.'i', strip_tags($value), $matches, PREG_OFFSET_CAPTURE);
                       if($ret == 1){
                           $articles_topic[$key][]=$row['id'];
                       }
                   }
                   
               }
           }
           
           foreach($allArticles as $key => $value){
               
               if(isset($articles_topic[$key])){
                   $current_topics=$articles_topic[$key];                   
                   $existingTopicsResults = $this->conn->query("select group_concat(topic_id) as topic_ids from article_topics where article_id=$key");
                   $existingTopicsRow=$existingTopicsResults->fetch_assoc();
                   $existing_topics=$existingTopicsRow['topic_ids'];
                   if($existing_topics){
                       $existing_topics=explode(',', $existingTopicsRow['topic_ids']);
                   }
                   else {
                       $existing_topics=array();
                   }
                   
                   $newTopics=array_diff($current_topics,$existing_topics);
                   $topics=array_unique($newTopics);
                   //print_r($topics); exit;
                   foreach($topics as $topic_id){
                        $articleTopicStmt = $this->conn->prepare("insert into  article_topics set article_id=?,topic_id=?,created_at=?,updated_at=?");
                        $cdate=date('Y-m-d H:i:s');
                        $articleTopicStmt->bind_param('iiss',$key,$topic_id,$cdate,$cdate);
                        $articleTopicStmt->execute();
                   }
                   
                   if(count($topics)){
                       $updateArticle=$this->conn->query("update articles set updated_at='".date('Y-m-d H:i:s')."' where article_id=$key"); 
                      $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                   }
                   $alltopicsIds=implode(',',$current_topics);
                   $this->conn->query("delete from article_topics where article_id=$key and topic_id not in($alltopicsIds)");
                   
                   
               }else{
                   $delStmt = $this->conn->prepare("delete from article_topics where article_id=?");
                   $delStmt->bind_param('i', $key);
                   $delStmt->execute();
               }
               
           }
           
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetgeneratetopics',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' topic(s) inserted and ' . $_SESSION['noofupd'] . ' topic(s) updated.</h5>';
           
    }
    function migrateTopics() {
        $this->migrateTopicCategory();
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgettopics' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $results = $this->conn->query("SELECT * FROM topics where 1 $condition");
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                $id = $row['id'];
                $checkExistanceRst = $this->conn2->query("select * from channel_topics where topic_id=$id");
                if ($checkExistanceRst->num_rows > 0) {
                    $existingRow = $checkExistanceRst->fetch_assoc();
                    $checkExistanceRst->close();
                    if ($row['valid'] == 1) {
                        $catUpdateStmt = $this->conn2->prepare("update channel_topics set topic_name=?,category_id=? where topic_id=?");
                        //echo $this->conn2->error;exit;
                        $catUpdateStmt->bind_param('sii', $row['topic'], $row['category_id'], $id);
                        $catUpdateStmt->execute();
                        if ($catUpdateStmt->affected_rows)
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    }else {
                        $delStmt = $this->conn2->prepare("delete from channel_topics where topic_id=?");
                        $delStmt->bind_param('i', $id);
                        $delStmt->execute();
                        $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                    }
                } else {
                    $insertStmt = $this->conn2->prepare("insert into channel_topics set topic_id=?,topic_name=?,category_id=?") or die($this->conn2->error);
                    $insertStmt->bind_param('isi', $row['id'], $row['topic'], $row['category_id']);
                    $insertStmt->execute();
                    if ($insertStmt->insert_id) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgettopics',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();

        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' topic(s) inserted,   ' . $_SESSION['noofupd'] . ' topic(s) updated and ' . $_SESSION['noofdel'] . ' topic(s) deleted.</h5>';
    }

    function migrateQuickByte() {
        //$this->migrateAuthor();
        //$this->migrateTag();
        //$this->migrateTopics();
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetquickbyte' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $quickBytesResults = $this->conn->query("SELECT *  FROM quickbyte where channel_id='$this->channelId' $condition");
        //echo $quickBytesResults->num_rows;exit;
        if ($quickBytesResults->num_rows > 0) {
            
            $catMapArray = array();
            $articleCatDataRst = $this->conn2->query("select * from channel_category");
            while ($articleCatDataRow = $articleCatDataRst->fetch_assoc()) {
                $key = $articleCatDataRow['cms_cat_id'] . '_' . $articleCatDataRow['cms_cat_level'];
                $catMapArray[$key] = $articleCatDataRow['category_id'];
            }
            $this->categoryMapping = $catMapArray;
            
            while ($quickBytesRow = $quickBytesResults->fetch_assoc()) {
                $id = $quickBytesRow['id'];
                $checkResult = $this->conn2->query("select quick_byte_title from quick_bytes where quick_byte_id=$id") or die($this->conn2->error);
                if ($checkResult->num_rows > 0) {
                    if ($quickBytesRow['status'] == 'P') {
                        $updateStmt = $this->conn2->prepare("update quick_bytes set quick_byte_author_type=?,"
                                . "quick_byte_author_id=?,quick_byte_title=?,quick_byte_description=?,quick_byte_sponsered=?,quick_byte_published_date=?,campaign_id=? where quick_byte_id=?") or die($this->conn2->error);
                        $updateStmt->bind_param('iissisii', $quickBytesRow['author_type'], $quickBytesRow['author_id']
                                        , $quickBytesRow['title'], $quickBytesRow['description'], $quickBytesRow['sponsored'], $quickBytesRow['publish_date'], $quickBytesRow['campaign_id'], $quickBytesRow['id']) or die($this->conn2->error);
                        $updateStmt->execute() or die($this->conn2->error);
                        //print_r($articleInsertStmt);exit;
                        // echo $articleInsertStmt->insert_id;exit;    
                        //  if ($insertStmt->insert_id) {
                        $iid = $quickBytesRow['id'];
                        $updateStmt->close();
                        $topics = explode(',', $quickBytesRow['topics']);
                        $tags = explode(',', $quickBytesRow['tags']);

                        $this->conn2->query("delete from quick_bytes_topic where quick_byte_id=$iid");

                        foreach ($topics as $topic) {
                            $this->conn2->query("insert into quick_bytes_topic set quick_byte_id=$iid,topic_id=$topic");
                        }

                        $this->conn2->query("delete from quick_bytes_tags where quick_byte_id=$iid");

                        foreach ($tags as $tag) {
                            $this->conn2->query("insert into quick_bytes_tags set quick_byte_id=$iid,tag_id=$tag");
                        }

                        $this->migrateQuickBytePhoto($iid, 0);
                        $this->migrateQuickByteCategory($iid,0);
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;

                        //   }
                        // updating quickbyte
                    } else {
                        // deleting quickbyte
                        $delStmt = $this->conn2->prepare("delete from quick_bytes where quick_byte_id=?") or die($this->conn2->error);
                        $delStmt->bind_param('i', $id) or die($this->conn2->error);
                        $delStmt->execute();
                        if ($delStmt->affected_rows) {
                            $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                            $this->deleteQuickByteRelatedRelated($id);
                        }
                        $delStmt->close();
                    }
                } else {
                    if ($quickBytesRow['status'] == 'P') {
                        // Inserting quickbyte
                        //echo '<pre>';
                        //print_r($quickBytesRow);exit;
                        $insertStmt = $this->conn2->prepare("insert into quick_bytes set quick_byte_id=?,quick_byte_author_type=?,	"
                                . "quick_byte_author_id=?,quick_byte_title=?,quick_byte_description=?,quick_byte_sponsered=?,quick_byte_published_date=?,campaign_id=?") or die($this->conn2->error);
                        $insertStmt->bind_param('iiissisi', $quickBytesRow['id'], $quickBytesRow['author_type'], $quickBytesRow['author_id']
                                        , $quickBytesRow['title'], $quickBytesRow['description'], $quickBytesRow['sponsored'], $quickBytesRow['publish_date'], $quickBytesRow['campaign_id']) or die($this->conn2->error);
                        $insertStmt->execute() or die($this->conn2->error);
                        //print_r($articleInsertStmt);exit;
                        // echo $articleInsertStmt->insert_id;exit;    
                        if ($insertStmt->insert_id) {
                            $iid = $insertStmt->insert_id;
                            $insertStmt->close();
                            $topics = explode(',', $quickBytesRow['topics']);
                            $tags = explode(',', $quickBytesRow['tags']);
                            foreach ($topics as $topic) {
                                $this->conn2->query("insert into quick_bytes_topic set quick_byte_id=$iid,topic_id=$topic");
                            }
                            foreach ($tags as $tag) {
                                $this->conn2->query("insert into quick_bytes_tags set quick_byte_id=$iid,tag_id=$tag");
                            }
                            $this->migrateQuickBytePhoto($iid, 1);
                             $this->migrateQuickByteCategory($iid,1);
                            $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                        }
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetquickbyte',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' quickbyte(s) inserted, ' . $_SESSION['noofupd'] . ' quickbyte(s) updated and ' . $_SESSION['noofdel'] . ' quickbyte(s) deleted.</h5>';
    }
    
    function migrateQuickByteCategory($quickbyteId, $isNew = 0) {
        // $this->categoryMapping
        $catResultSet = '';
        if ($isNew == '1') {
            $quickbyteCatRst = $this->conn->query("SELECT concat(`category_id`,'_',`category_level`) as catlevel,category_level FROM `quickbyte_category` WHERE  `quickbyte_id`='$quickbyteId'");
            while ($catRow = $quickbyteCatRst->fetch_assoc()) {
                $insertQickbyteCategoryStmt = $this->conn2->prepare("insert into quickbyte_category set quickbyte_id=?,category_id=?,category_level=?");
                $insertQickbyteCategoryStmt->bind_param('iii', $quickbyteId, $this->categoryMapping[$catRow['catlevel']], $catRow['category_level']);
                $insertQickbyteCategoryStmt->execute();
                $insertQickbyteCategoryStmt->close();
            }
        } else {
            $this->conn2->query("delete from quickbyte_category where quickbyte_id=$quickbyteId");

            $quickbyteCatRst = $this->conn->query("SELECT concat(`category_id`,'_',`category_level`) as catlevel,category_level FROM `quickbyte_category` WHERE  `quickbyte_id`='$quickbyteId'");

            while ($catRow = $quickbyteCatRst->fetch_assoc()) {//print_r($catRow);exit;
                $insertQickbyteCategoryStmt = $this->conn2->prepare("insert into quickbyte_category set quickbyte_id=?,category_id=?,category_level=?");
                $insertQickbyteCategoryStmt->bind_param('iii', $quickbyteId, $this->categoryMapping[$catRow['catlevel']], $catRow['category_level']);
                $insertQickbyteCategoryStmt->execute();
                $insertQickbyteCategoryStmt->close();
            }
            // }
        }
    }

    function migrateQuickBytePhoto($id, $is_new = 0) {
        if ($is_new) {
            $photos = $this->conn->query("select * from photos where owned_by='quickbyte' and owner_id=$id");
            while ($photo = $photos->fetch_object()) {
                //print_r($photo);exit;
                $photoInsStmt = $this->conn2->prepare("insert into quick_bytes_photos set quick_byte_id=?,quick_byte_photo_name=?"
                        . ",quick_byte_photo_title=?,quick_byte_photo_description=?,photo_by=?,sequence=?");
                $photoInsStmt->bind_param('issssi', $id, $photo->photopath, $photo->title, $photo->description, $photo->photo_by, $photo->sequence);
                $photoInsStmt->execute();

                // Photo Tags 
                $photoId=$photoInsStmt->insert_id;
                $photoTagRst = $this->conn->query("SELECT tag_id FROM `photo_tags` WHERE  `photo_id`='$photo->photo_id'");
                while ($photoTagRow = $photoTagRst->fetch_assoc()) {//print_r($catRow);exit;
                    //print_r($photoTagRow);exit;
                    $insertPhotoTagStmt = $this->conn2->prepare("insert into photo_tags set photo_id=?,tag_id=?");
                    $insertPhotoTagStmt->bind_param('ii',$photoId,$photoTagRow['tag_id']);
                    $insertPhotoTagStmt->execute();
                    $insertPhotoTagStmt->close();
                }
            }
        } else {
           
            $this->conn2->query("delete from photo_tags where photo_id in (select quick_byte_image_id from quick_bytes_photos where quick_byte_id=$id)");
            $this->conn2->query("delete from quick_bytes_photos where quick_byte_id=$id");
                        
            $photos = $this->conn->query("select * from photos where owned_by='quickbyte' and owner_id=$id");
            while ($photo = $photos->fetch_object()) {
                //print_r($photo);exit;
                $photoInsStmt = $this->conn2->prepare("insert into quick_bytes_photos set quick_byte_id=?,quick_byte_photo_name=?"
                        . ",quick_byte_photo_title=?,quick_byte_photo_description=?,photo_by=?,sequence=?");
                $photoInsStmt->bind_param('issssi', $id, $photo->photopath, $photo->title, $photo->description, $photo->photo_by, $photo->sequence);
                $photoInsStmt->execute();
                // Photo Tags 
                $photoId=$photoInsStmt->insert_id;
                $photoTagRst = $this->conn->query("SELECT tag_id FROM `photo_tags` WHERE  `photo_id`='$photo->photo_id'");
                while ($photoTagRow = $photoTagRst->fetch_assoc()) {//print_r($catRow);exit;
                    //print_r($photoTagRow);exit;
                    $insertPhotoTagStmt = $this->conn2->prepare("insert into photo_tags set photo_id=?,tag_id=?");
                    $insertPhotoTagStmt->bind_param('ii',$photoId,$photoTagRow['tag_id']);
                    $insertPhotoTagStmt->execute();
                    $insertPhotoTagStmt->close();
                }
            }
        }
    }

    function deleteQuickByteRelatedRelated($id) {
        
    }

    function migrateArticle() {
        //echo 'test'; exit;
        // updating scheduled articles
	//Pti server url to insert article in cms: http://35.194.177.143/news/importfeed.php
	//file_get_contents('http://35.194.177.143/news/importfeed.php');
        $this->conn->query("update articles set status='P',updated_at='".date('Y-m-d H:i:s')."' where status='SD' and channel_id = $this->channelId and concat(publish_date,' ',publish_time) <= '" . date('Y-m-d H:i:s') . "'") or die($this->conn->error);
        //echo date('Y-m-d h:i:s'); exit;
        //exit;
        $this->migrateCampaing();
        $this->migrateAuthor();
        $this->migrateCategory();
        //$this->migrateTopicCategory();
        $this->migrateTag();
        $this->migrateTopics();
        //$this->migrateMagazine();
        // if(){
        // }
        //echo 'test'; exit;
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetarticle' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $articleResults = $this->conn->query("SELECT *  FROM articles where  channel_id='$this->channelId'  $condition");
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
                                . "article_type=?,article_published_date=?,article_slug=?,article_status=?,important_article=?,video_Id=?,display_to_homepage=?,is_exclusive=?,exclusive_non_featured=?,featured_in_print=?,"
                                . "magzine_issue_name=?,canonical_options=?,video_type=?,canonical_url=?,social_title=?,social_summary=?,social_image=?,bitly_url=?,article_location_country=?,article_location_state=?,event_id=?,hide_image=?,campaign_id=?,updated_at=? where article_id=?");
                        $articleUpdateStmt->bind_param('sssisssiiiiiiiissssssiiiiisi', $articleRow['title'], $articleRow['description'], $articleRow['summary'], $articleRow['news_type']
                                , $pubDate, $articleRow['slug'], $status, $articleRow['important'], $articleRow['video_Id'], $articleRow['for_homepage'], $articleRow['web_exclusive'],$articleRow['exclusive_non_featured'],$articleRow['featured_in_print'], $articleRow['magazine_id'], $articleRow['canonical_options'], $articleRow['video_type'], $articleRow['canonical_url'], $articleRow['social_title'], $articleRow['social_summary'], $articleRow['social_image'], $articleRow['bitly_url'], $articleRow['country'], $articleRow['state'], $articleRow['event_id'], $articleRow['hide_image'], $articleRow['campaign_id'], $articleRow['updated_at'], $articleRow['article_id']);
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
                } else {
                    if ($articleRow['status'] == 'P') {
                        $pubDate = $articleRow['publish_date'] . ' ' . $articleRow['publish_time'];
                        $status = 'published';
                        $articleInsertStmt = $this->conn2->prepare("insert articles set article_id=?,article_title=?,article_description=?,article_summary=?,"
                                . "article_type=?,article_published_date=?,article_slug=?,article_status=?,video_Id=?,important_article=?,display_to_homepage=?,is_exclusive=?,exclusive_non_featured=?,featured_in_print=?,"
                                . "magzine_issue_name=?,canonical_options=?,video_type=?,canonical_url=?,social_title=?,social_summary=?,social_image=?,bitly_url=?,article_location_country=?,article_location_state=?,event_id=?,is_old=?,hide_image=?,campaign_id=?,updated_at=?,partner_id=?");
                        $articleInsertStmt->bind_param('isssisssiiiiiiiissssssiiiiiisi', $articleRow['article_id'], $articleRow['title'], $articleRow['description'], $articleRow['summary'], $articleRow['news_type']
                                , $pubDate, $articleRow['slug'], $status, $articleRow['video_Id'], $articleRow['important'], $articleRow['for_homepage'], $articleRow['web_exclusive'],$articleRow['exclusive_non_featured'],$articleRow['featured_in_print'], $articleRow['magazine_id'], $articleRow['canonical_options'], $articleRow['video_type'], $articleRow['canonical_url'], $articleRow['social_title'], $articleRow['social_summary'], $articleRow['social_image'],$articleRow['bitly_url'], $articleRow['country'], $articleRow['state'], $articleRow['event_id'], $articleRow['is_old'], $articleRow['hide_image'], $articleRow['campaign_id'], $articleRow['updated_at'],$articleRow['partner_id']);
                        $articleInsertStmt->execute();
                        //print_r($articleInsertStmt);exit;
                        // echo $articleInsertStmt->insert_id;exit;    
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
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetarticle',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' article(s) inserted, ' . $_SESSION['noofupd'] . ' article(s) updated and ' . $_SESSION['noofdel'] . ' article(s) deleted.</h5>';
        //$key = md5(date('dmY') . 'businessworld');
        //file_get_contents('http://businessworld.in/create-json/' . $key);
	//file_get_contents('http://businessworld.in/create-article-json/'.$key);
    }

    function migrateFeature() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');

        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetfeatur' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $featurResults = $this->conn->query("SELECT *  FROM featuredarticle where channel_id='$this->channelId' $condition");
        // echo $featurResults->num_rows ;exit;
        //print_r($featurResults);exit;
        if ($featurResults->num_rows > 0) {
            // exit;
            while ($featurRow = $featurResults->fetch_assoc()) {
                $id = $featurRow['id'];

                //                continue; 
                //echo $id;exit;
                $checkFeaturResult = $this->conn2->query("select feature_box_title from feature_box where id=$id");
                if ($checkFeaturResult->num_rows > 0) { //echo 'test'; exit;
                    //$checkFeaturResult->close();
                    if ($featurRow['valid'] == '1') {
                        $featurUpdateStmt = $this->conn2->prepare("update feature_box set feature_box_title=?,feature_box_description=?,position2_type=?,position2_title=?,position2_photo=?,position2_url=?,position3_type=?,position3_title=?,position3_photo=?,position3_url=?,feature_box_url=?,feature_box_create_at=?,feature_box_updated_at=?,currently_feature=? where id=?");
                        $featurUpdateStmt->bind_param('sssssssssssssii', $featurRow['title'], $featurRow['description'], $featurRow['position2_type'], $featurRow['position2_title'], $featurRow['position2_photo'], $featurRow['position2_url'], $featurRow['position3_type'], $featurRow['position3_title'], $featurRow['position3_photo'], $featurRow['position3_url'], $featurRow['url'], $featurRow['created_at'], $featurRow['updated_at'], $featurRow['featured'], $id);
                        $featurUpdateStmt->execute();
                        if ($featurUpdateStmt->affected_rows) {
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                        }
                        $this->callFeaturRelatedContent($featurRow['id'], $condition);
                    } else {
                        $delStmt = $this->conn2->prepare("delete from feature_box where id=?");
                        $delStmt->bind_param('i', $id);
                        $delStmt->execute();
                        if ($delStmt->affected_rows) {
                            $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                            //$this->deleteFeaturRelated($id);
                        }
                        $delStmt->close();
                    }
                } else {//echo 'sumit insert';exit;
                    if ($featurRow['valid'] == '1') {
                        //echo 'sumit2 insert';exit;
                        //echo '<pre>';
                        // print_r($featurRow);
                        $featurInsertStmt = $this->conn2->prepare("insert feature_box set id=?,feature_box_title=?,feature_box_description=?,position2_type=?,position2_title=?,position2_photo=?,position2_url=?,position3_type=?,position3_title=?,position3_photo=?,position3_url=?,feature_box_url=?,feature_box_create_at=?,feature_box_updated_at=?,currently_feature=?") or die($this->conn2->error);
                        ;
                        $featurInsertStmt->bind_param('isssssssssssssi', $featurRow['id'], $featurRow['title'], $featurRow['description'], $featurRow['position2_type'], $featurRow['position2_title'], $featurRow['position2_photo'], $featurRow['position2_url'], $featurRow['position3_type'], $featurRow['position3_title'], $featurRow['position3_photo'], $featurRow['position3_url'], $featurRow['url'], $featurRow['created_at'], $featurRow['updated_at'], $featurRow['featured']) or die($this->conn2->error);
                        ;
                        $featurInsertStmt->execute() or die($this->conn2->error);
                        //print_r($featurInsertStmt); //exit;
                        //echo $featurInsertStmt->insert_id.'---'.$featurRow['id'].'<br>';    
                        if ($featurInsertStmt->insert_id) {
                            $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                            $this->callFeaturRelatedContent($featurInsertStmt->insert_id, $condition);
                        }
                    }
                }
                //echo '1 done ';exit;
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetfeatur',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' featur(s) inserted, ' . $_SESSION['noofupd'] . ' featur(s) updated and ' . $_SESSION['noofdel'] . ' featur(s) deleted.</h5>';
    }

    function callFeaturRelatedContent($featurId, $condition) {

        $this->migrateFeaturImage($featurId, $condition);
        $this->migrateFeaturVideo($featurId, $condition);
    }

    function migrateFeaturImage($featurId, $condition) {
        //echo "select * from photos where owned_by='featurebox' and owner_id=$featurId ";
        // exit;
        $featurImageResultset = $this->conn->query("select * from photos where owned_by='featurebox' and owner_id=$featurId and valid='1'");
        while ($imageRow = $featurImageResultset->fetch_assoc()) {
            $imInsertStmt = $this->conn2->prepare("update feature_box  set feature_box_photo_uploder_url=? where id=?");
            $imInsertStmt->bind_param('si', $imageRow['photopath'], $featurId);
            $imInsertStmt->execute();
            $imInsertStmt->close();
        }
    }

    function migrateFeaturVideo($featurId, $condition) {
        //$articleId= 71; 
        //$isNew = 1
        //echo "select * from videos where owned_by='featurebox' and owner_id=$featurId";
        //exit;
        $articleVideoResultset = $this->conn->query("select * from videos where owned_by='featurebox' and owner_id=$featurId ");
        while ($videoRow = $articleVideoResultset->fetch_assoc()) {
            $vdInsertStmt = $this->conn2->prepare("update feature_box set feature_box_video_uploder_url=? where id=?");
            $vdInsertStmt->bind_param('si', $videoRow['url'], $featurId);
            $vdInsertStmt->execute();
            $vdInsertStmt->close();
        }
    }

    function callArticleRelatedContent($articleId, $isNew = 0, $condition) {
        $this->migrateArticleTag($articleId, $isNew, $condition);
        $this->migrateArticleCategory($articleId, $isNew, $condition);
        $this->migrateArticleAuthor($articleId, $isNew, $condition);
        $this->migrateArticleTopic($articleId, $isNew, $condition);
        $this->migrateAtricleImage($articleId, $isNew, $condition);
        $this->migrateAtricleVideo($articleId, $isNew, $condition);
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
            /* $checkTagResult = $this->conn->query("select * from article_tags where article_id=$articleId $condition");
              if ($checkTagResult->num_rows > 0) {
              $checkTagResult->close(); */
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
              $checkCatResult->close(); */
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

    function migrateArticleAuthor($articleId, $isNew = 0, $condition = '') {

        $articleRst=$this->conn->query("select author_type from articles where article_id=".$articleId);
        $articleRow=$articleRst->fetch_object();
        
        if ($isNew == '1') {
            $articleAuthorResultset = $this->conn->query("select * from article_author where article_id=$articleId and valid='1'");
            while ($authorRow = $articleAuthorResultset->fetch_assoc()) {
                $tauthId = $authorRow['author_id'];
                $auInsertStmt = $this->conn2->prepare("insert into article_author set article_id=?,author_type=?,author_id=?");
                $auInsertStmt->bind_param('iii', $authorRow['article_id'], $articleRow->author_type, $authorRow['author_id']);
                $auInsertStmt->execute();
                $auInsertStmt->close();
            }
        } else {
            /* $checkAuthorResult = $this->conn->query("select * from article_author where article_id=$articleId $condition");
              if ($checkAuthorResult->num_rows > 0) {
              $checkAuthorResult->close(); */
            $this->conn2->query("delete from article_author where article_id=$articleId");
            $articleAuthorResultset = $this->conn->query("select * from article_author where article_id=$articleId and valid='1'");
            while ($authorRow = $articleAuthorResultset->fetch_assoc()) {
                $tauthId = $authorRow['author_id'];
                $auInsertStmt = $this->conn2->prepare("insert into article_author set article_id=?,author_type=?,author_id=?");
                $auInsertStmt->bind_param('iii', $authorRow['article_id'], $articleRow->author_type, $authorRow['author_id']);
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
              $checkTopicResult->close(); */
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

    function migrateAtricleImage($articleId, $isNew = 0, $condition) {
        if ($isNew == '1') {
            $articleImageResultset = $this->conn->query("select * from photos where owned_by='article' and owner_id=$articleId and valid='1'");
            while ($imageRow = $articleImageResultset->fetch_assoc()) {
                $imInsertStmt = $this->conn2->prepare("insert into article_images set article_id=?,photopath=?,image_url=?,image_title=?"
                        . ",image_source_name=?,image_source_url=?,photo_by=?,image_status=?,sequence=?");
                $status = ($imageRow['active'] == '1') ? 'enabled' : 'disabled';
                $imInsertStmt->bind_param('isssssssi', $imageRow['owner_id'], $imageRow['photopath'], $imageRow['imagefullPath'], $imageRow['title'], $imageRow['source'], $imageRow['source_url'], $imageRow['photo_by'], $status, $imageRow['sequence']);
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
                $imInsertStmt->bind_param('isssssssi', $imageRow['owner_id'], $imageRow['photopath'], $imageRow['imagefullPath'], $imageRow['title'], $imageRow['source'], $imageRow['source_url'], $imageRow['photo_by'], $status, $imageRow['sequence']);
                $imInsertStmt->execute();
                $imInsertStmt->close();
            }
            // }
        }
    }

    function migrateAtricleVideo($articleId, $isNew = 0, $condition) {
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
        } else {
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



    //video module

    function migrateMasterVideo() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetmastervideo' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
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
                    if ($masterVideoRow['video_status'] == '1') {//echo 'sumit'; exit();
                        $masterVideoUpdateStmt = $this->conn2->prepare("update video_master set video_title=?,video_summary=?,video_name=?,video_thumb_name=?,tags=?,created_at=?,updated_at=?,campaign_id=?,video_by=?,video_type=?,for_automated_news_video=? where video_id=?");
                        //echo $this->conn2->error; exit;
                        $masterVideoUpdateStmt->bind_param('sssssssisiii', $masterVideoRow['video_title'], $masterVideoRow['video_summary'], $masterVideoRow['video_name'], $masterVideoRow['video_thumb_name'], $masterVideoRow['tags'], $masterVideoRow['created_at'], $masterVideoRow['updated_at'], $masterVideoRow['campaign_id'], $masterVideoRow['video_by'],$masterVideoRow['video_type'],$masterVideoRow['for_automated_news_video'], $masterVideoId);
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
                    if ($masterVideoRow['video_status'] == '1') {
                        $masterVideoInsertStmt = $this->conn2->prepare("insert into video_master set video_id=?,video_title=?,video_summary=?,video_name=?,video_thumb_name=?,tags=?,created_at=?,updated_at=?,campaign_id=?,video_by=?,video_type=?,for_automated_news_video=?");
                        //echo $this->conn2->error; exit;
                        $masterVideoInsertStmt->bind_param('isssssssisii', $masterVideoRow['id'], $masterVideoRow['video_title'], $masterVideoRow['video_summary'], $masterVideoRow['video_name'], $masterVideoRow['video_thumb_name'], $masterVideoRow['tags'], $masterVideoRow['created_at'], $masterVideoRow['updated_at'], $masterVideoRow['campaign_id'], $masterVideoRow['video_by'],$masterVideoRow['video_type'],$masterVideoRow['for_automated_news_video']);
                        $masterVideoInsertStmt->execute() or die($this->conn2->error);
                        //print_r($masterVideoInsertStmt);exit;
                        // echo $articleInsertStmt->insert_id;exit;    
                        if ($masterVideoInsertStmt->insert_id) {
                            $iid = $masterVideoInsertStmt->insert_id;
                            $masterVideoInsertStmt->close();
                            $this->callVideoRelatedContent($iid);
                            $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                        }
                    } else {
                        $delStmt = $this->conn2->prepare("delete from video_master where video_id=?");
                        $delStmt->bind_param('i', $masterVideoRow['id']);
                        $delStmt->execute();
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetmastervideo',start_time=?,end_time=?");
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

    //video module end here 
    //
   //campain module start here 
    function migrateCampaing() {
        //echo 'sumit';exit;
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetcampaing' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            // $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $campaingResults = $this->conn->query("SELECT * FROM campaign where channel_id=$this->channelId $condition");
        //echo $authorResults->num_rows; exit;
        if ($campaingResults->num_rows > 0) {

            while ($campaignRow = $campaingResults->fetch_assoc()) {
                //print_r($campaingRow); exit;
                $campaingId = $campaignRow['campaign_id'];  
                $checkmasterVideoExistResultSet = $this->conn2->query("select campaing_id from campaign where campaing_id=$campaingId");
                if ($checkmasterVideoExistResultSet->num_rows > 0) { //echo 'going to update';exit;  
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $masterVideoUpdateStmt = $this->conn2->prepare("update campaign set campaing_title=?,description=?,url=?,campaing_status=?,campaing_pdate=? where campaing_id=?");
                    $masterVideoUpdateStmt->bind_param('sssisi', $campaignRow['title'],$campaignRow['description'],$campaignRow['url'],$campaignRow['valid'], $campaignRow['updated_at'], $campaingId);
                    $masterVideoUpdateStmt->execute();
                    if ($masterVideoUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    // echo  $_SESSION['noofupd'];
                }else {
                    //echo 'sumit'; exit;
                    $campaignInsertStmt = $this->conn2->prepare("insert into campaign set campaing_id=?, campaing_title=?,description=?,url=?,campaing_status=?,campaing_pdate=?");
                    //echo $this->conn2->error; exit;
                    $campaignInsertStmt->bind_param('isssis', $campaignRow['campaign_id'], $campaignRow['title'],$campaignRow['description'],$campaignRow['url'],$campaignRow['valid'], $campaignRow['updated_at']);
                    $campaignInsertStmt->execute();
                    if ($campaignInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetcampaing',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' campaing(s) inserted and ' . $_SESSION['noofupd'] . ' campaing(s) updated.</h5>';
    }





    function migrateMasterNewsLetter() {

        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetmasternewsletter' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            //$condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
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
                    if ($masterNewsLetterRow['is_deleted'] == '0') {//echo 'sumit'; exit();
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
                    if ($masterNewsLetterRow['is_deleted'] == '0') {
                        $masterNewsLetterInsertStmt = $this->conn2->prepare("insert into master_newsletter set id=?,title=?,created_at=?,updated_at=?");
                        //echo $this->conn2->error; exit;
                        $masterNewsLetterInsertStmt->bind_param('isss', $masterNewsLetterRow['id'], $masterNewsLetterRow['title'], $masterNewsLetterRow['created_at'], $masterNewsLetterRow['updated_at']);
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
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetmasternewsletter',start_time=?,end_time=?");
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
        $masterNewsLetterArticleRst = $this->conn->query("select * from master_newsletter_articles where is_deleted=0 and master_newsletter_id=$id");
        while (($masterNewsLetterArticleRow = $masterNewsLetterArticleRst->fetch_assoc())) {
                $masterNewsLetterArticleInsertStmt = $this->conn2->prepare("insert into master_newsletter_articles  set master_newsletter_id=?,article_id=?,sequence=?,is_deleted=?,updated_at=?") or die($this->conn2->error);
                $masterNewsLetterArticleInsertStmt->bind_param('iiiis', $masterNewsLetterArticleRow['master_newsletter_id'], $masterNewsLetterArticleRow['article_id'], $masterNewsLetterArticleRow['sequence'], $masterNewsLetterArticleRow['is_deleted'], $masterNewsLetterArticleRow['updated_at']) or die($this->conn2->error);
                $masterNewsLetterArticleInsertStmt->execute() or die($this->conn2->error);
                $masterNewsLetterArticleInsertStmt->close();
        }
    }

    function migrateNewsletterSubscriber() {    
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwgadgetsubscriber' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (subscription_date >='$cronLastExecutionTime')";
        }
        $subscriberResults = $this->conn2->query("SELECT * FROM newsletter_Subscriber where  1 $condition");
        
        if ($subscriberResults->num_rows > 0) {

            while ($subscriberRow = $subscriberResults->fetch_assoc()) {
                //print_r($subscriberRow); exit;
                $email = $subscriberRow['subscriber_email_id'];
                $checkSubscriberExistResultSet = $this->conn->query("select id from subscribers where email='$email'");
                
                if ($checkSubscriberExistResultSet->num_rows > 0) {

                    $checkSubscriberRow = $checkSubscriberExistResultSet->fetch_assoc();
                    $id = $checkSubscriberRow['id'];

                    $checkSubscriptionRst = $this->conn->query("select id from subscriber_newsletter where subscriber_id=$id and channel_id=$this->channelId");
                   
                    if ($checkSubscriptionRst->num_rows == 0) {
                        //echo 'test';
                        $updated_at = date('Y-m-d H:i:s');
                        $subsciptionInsertStmt = $this->conn->prepare("insert into subscriber_newsletter set subscriber_id=?,channel_id=?,sub_date=?,updated_at=?");
                        $subsciptionInsertStmt->bind_param('iiss', $id, $this->channelId, $subscriberRow['subscription_date'], $updated_at);
                        $subsciptionInsertStmt->execute();
                        if ($subsciptionInsertStmt->affected_rows)
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    }
                   
                }else {
                    $updated_at = date('Y-m-d H:i:s');
                    $suscriberInsertStmt = $this->conn->prepare("insert into subscribers set email=?,created_at=?,updated_at=?");
                    $suscriberInsertStmt->bind_param('sss', $email,$updated_at,$updated_at);
                    $suscriberInsertStmt->execute();
                    $id = $suscriberInsertStmt->insert_id;
                    $subsciptionInsertStmt = $this->conn->prepare("insert into subscriber_newsletter set subscriber_id=?,channel_id=?,sub_date=?,updated_at=?");
                    $subsciptionInsertStmt->bind_param('iiss', $id, $this->channelId, $subscriberRow['subscription_date'], $updated_at);
                    $subsciptionInsertStmt->execute();
                    if ($suscriberInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
          
        }
        
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwgadgetsubscriber',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' subscriber(s) inserted and ' . $_SESSION['noofupd'] . ' subscriber(s) updated.</h5>';
    }

        
    //Live streaming module start here 
    function Livestreaming() {
        //echo 'sumit';exit;
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='livestreaming' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            // $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $livestreamingResults = $this->conn->query("SELECT * FROM event_streaming where channel_id=$this->channelId $condition");
        //echo $trendingResults->num_rows; exit;
        if ($livestreamingResults->num_rows > 0) {

            while ($livestreamingRow = $livestreamingResults->fetch_assoc()) {
                //print_r($trendingRow); exit;
                $livestreamingId = $livestreamingRow['id'];
                $checklivestreamingExistResultSet = $this->conn2->query("select * from event_streaming where id=$livestreamingId");
                if ($checklivestreamingExistResultSet->num_rows > 0) { //echo 'going to update';exit;  
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $livestreamingUpdateStmt = $this->conn2->prepare("update event_streaming set event_name=?,banner_image=?,embed_code=?,is_live=? where id=?");
                    $livestreamingUpdateStmt->bind_param('ssssi', $livestreamingRow['event_name'], $livestreamingRow['banner_image'], $livestreamingRow['embed_code'], $livestreamingRow['is_live'],$livestreamingId);
                    $livestreamingUpdateStmt->execute();
                    if ($livestreamingUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    // echo  $_SESSION['noofupd'];
                }else {
                    //echo 'sumit'; exit;
                    $livestreamingInsertStmt = $this->conn2->prepare("insert into event_streaming set id=?, event_name=?,banner_image=?,embed_code=?,is_live=?");
                    //echo $this->conn2->error; exit;
                    $livestreamingInsertStmt->bind_param('issss', $livestreamingId,$livestreamingRow['event_name'], $livestreamingRow['banner_image'], $livestreamingRow['embed_code'], $livestreamingRow['is_live']);
                    $livestreamingInsertStmt->execute();
                    if ($livestreamingInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='livestreaming',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' livestreaming(s) inserted and ' . $_SESSION['noofupd'] . ' livestreaming(s) updated.</h5>';
    }
    
   
}

?>
