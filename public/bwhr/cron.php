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
        mysqli_set_charset($this->conn, "utf8");
        $this->conn2 = new mysqli(LHOST, LUSER, LPASS, LDATABASE) or die($this->conn2->connect_errro);
        mysqli_set_charset($this->conn2, "utf8");
        $this->channelId = 8;
    }

    function migrateData($section) {
        //$this->migrateArticleAuthor('1','2');exit;
        // print_r($arr);exit;
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
            case 'livestreaming':
                $this->Livestreaming();
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
            
	     case 'dailyreport':
                $this->sendDailyReport();
		break;
            case 'campaing':
                //echo 'test'; exit;
                $this->migrateCampaing();
                break;
        endswitch;
		
        $_SESSION['message'] = $this->message;
    }

    function migrateAuthor() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrauthor' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            // $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
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
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrauthor',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' author(s) inserted and ' . $_SESSION['noofupd'] . ' author(s) updated.</h5>';
    }

    function Featureviewcount() {
        ///echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn2->query("select start_time from cron_log where section_name='bwhrfeaturviewcount' order by  start_time desc limit 0,1") or die($this->conn2->error);

        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
        }
        //echo "SELECT * FROM event  WHERE 1 $condition";exit;
        $featurviewcountrResults = $this->conn2->query("SELECT * FROM feature_box_clicked_count WHERE 1 ");
        //echo $articleviewcountrResults->num_rows;exit;
        if ($featurviewcountrResults->num_rows > 0) {

            while ($viewsRow = $featurviewcountrResults->fetch_assoc()) {
                // print_r($viewsRow);exit;
                $viewId = $viewsRow['feature_box_id'];
                //echo $viewId;
                // exit;
                $checkFeaturviewcountExistResultSet = $this->conn->query("select article_id,title from articles where article_id=$viewId");
                if ($checkFeaturviewcountExistResultSet->num_rows > 0) { //echo 'test';exit;
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $featurviewcountUpdateStmt = $this->conn->prepare("update featuredarticle set views =?  where id=?") or die($this->conn->error);
                    $featurviewcountUpdateStmt->bind_param('ii', $viewsRow['no_of_clicker'], $viewId) or die($this->conn->error);
                    //echo $this->conn2->error;exit;
                    $featurviewcountUpdateStmt->execute()or die($this->conn->error);
                    //print_r($eventUpdateStmt);exit;
                    //echo $eventUpdateStmt->affected_rows;exit;
                    if ($featurviewcountUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {//echo 'goint to insert';exit;
                    $featurviewcountInsertStmt = $this->conn->prepare("insert into featuredarticle set id=?,views=?");
                    //echo $this->conn2->error; exit;
                    $featurviewcountInsertStmt->bind_param('ii', $viewsRow['feature_box_id'], $viewsRow['no_of_clicker']);
                    $featurviewcountInsertStmt->execute();
                    if ($featurviewcountInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn2->prepare("insert into cron_log set section_name='bwhrfeaturviewcount',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' featurviewcount(s) inserted and ' . $_SESSION['noofupd'] . ' featurviewcount(s) updated.</h5>';
    }

    function migratePhotoshoot() {
        //echo 'test';exit;
        //$this->migrateTag();
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrPhotoshoot' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $photoshootResults = $this->conn->query("SELECT *  FROM album where channel_id='8' $condition");
        //echo $photoshootResults->num_rows;exit;
        if ($photoshootResults->num_rows > 0) {
            while ($photoshootRow = $photoshootResults->fetch_assoc()) {
                $id = $photoshootRow['id'];
                //echo  $id=$photoshootBytesRow['id']; exit;
                $checkResult = $this->conn2->query("select photo_shoot_title from photo_shoot where photo_shoot_id=$id");
                if ($checkResult->num_rows > 0) {  //echo 'test';exit;
                    if ($photoshootRow['valid'] == '1') {//echo 'sumit'; exit();
                        $photoshootUpdateStmt = $this->conn2->prepare("update photo_shoot set photo_shoot_title=?,photo_shoot_description=?,photo_shoot_sponsered=?,photo_shoot_featured=?,photo_shoot_published_date=?,photo_shoot_updated_at=?,campaign_id=? where photo_shoot_id=?");
                        //echo 'sumit';
                        $photoshootUpdateStmt->bind_param('ssiissii', $photoshootRow['title'], $photoshootRow['description'], $photoshootRow['sponsored'], $photoshootRow['featured'], $photoshootRow['created_at'], $photoshootRow['updated_at'], $photoshootRow['campaign_id'], $id) or die($this->conn2->error);
                        //echo 'abcd'.$this->conn2->error;exit;
                        $photoshootUpdateStmt->execute();
                        //print_r($photoshootUpdateStmt);exit;
                        if ($photoshootUpdateStmt->affected_rows)
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                        $this->migratePhotoshootPhoto($id, 0, $condition);
                    }else {
                        $delStmt = $this->conn2->prepare("delete from photo_shoot where photo_shoot_id=?");
                        $delStmt->bind_param('i', $id);
                        $delStmt->execute();


                        if ($delStmt->affected_rows) {
                            $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                            $this->deletePhotoshootRelatedRelated($id);
                        }
                        $delStmt->close();
                    }
                } else {//echo 'test4';exit;
                    $insertStmt = $this->conn2->prepare("insert into photo_shoot set photo_shoot_id=?,"
                            . "photo_shoot_title=?,photo_shoot_description=?,photo_shoot_sponsered=?,photo_shoot_featured=?,photo_shoot_published_date=?,photo_shoot_updated_at=?,campaign_id=?");
                    $insertStmt->bind_param('issiissi', $photoshootRow['id']
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
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrPhotoshoot',start_time=?,end_time=?");
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
                $photoInsStmt->bind_param('isssssi', $id, $photo->photopath, $photo->imagefullPath, $photo->title, $photo->photo_by, $photo->description,$photo->sequence);
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
                    $photoInsStmt->bind_param('isssssi', $id, $photo->photopath, $photo->imagefullPath, $photo->title, $photo->photo_by, $photo->description,$photo->sequence) or die($this->conn2->error);
                    $photoInsStmt->execute() or die($this->conn2->error);
                }
            }
        }
    }

    function deletePhotoshootRelatedRelated($id) {
        $delStmt = $this->conn2->prepare("delete from photo_shoot_photos where photo_shoot_id=?");
        $delStmt->bind_param('i', $id);
        $delStmt->execute();
    }

    function Sponsorviewcount() {
        ///echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn2->query("select start_time from cron_log where section_name='bwhrsponsorviewcount' order by  start_time desc limit 0,1") or die($this->conn2->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
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
                    $sponsoredpostsviewcountUpdateStmt->bind_param('ii', $viewsRow['sponsoredpostscout'], $viewId) or die($this->conn->error);
                    //echo $this->conn2->error;exit;
                    $sponsoredpostsviewcountUpdateStmt->execute()or die($this->conn->error);
                    //print_r($eventUpdateStmt);exit;
                    //echo $eventUpdateStmt->affected_rows;exit;
                    if ($sponsoredpostsviewcountUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {//echo 'goint to insert';exit;
                    $sponsoredpostsviewcountInsertStmt = $this->conn->prepare("insert into sponsoredposts set id=?,view_count=?");
                    //echo $this->conn2->error; exit;
                    $sponsoredpostsviewcountInsertStmt->bind_param('is', $viewsRow['sponsoredposts_id'], $viewsRow['sponsoredpostscout']);
                    $sponsoredpostsviewcountInsertStmt->execute();
                    if ($sponsoredpostsviewcountInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn2->prepare("insert into cron_log set section_name='bwhrsponsorviewcount',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' sponsorviewcount(s) inserted and ' . $_SESSION['noofupd'] . ' sponsorviewcount   (s) updated.</h5>';
    }

    function Articleviewcount() {
        ///echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn2->query("select start_time from cron_log where section_name='bwhrarticleviewcount' order by  start_time desc limit 0,1") or die($this->conn2->error);

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
                    $articleviewcountUpdateStmt->bind_param('ii', $viewsRow['articleidcount'], $viewId) or die($this->conn->error);
                    //echo $this->conn2->error;exit;
                    $articleviewcountUpdateStmt->execute()or die($this->conn->error);
                    //print_r($eventUpdateStmt);exit;
                    //echo $eventUpdateStmt->affected_rows;exit;
                    if ($articleviewcountUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {//echo 'goint to insert';exit;
                    $articleviewcountInsertStmt = $this->conn->prepare("insert into articles set article_id=?,view_count=?");
                    //echo $this->conn2->error; exit;
                    $articleviewcountInsertStmt->bind_param('ii', $viewsRow['article_id'], $viewsRow['articleidcount']);
                    $articleviewcountInsertStmt->execute();
                    if ($articleviewcountInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn2->prepare("insert into cron_log set section_name='bwhrarticleviewcount',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' articleviewcount(s) inserted and ' . $_SESSION['noofupd'] . ' articleviewcount(s) updated.</h5>';
    }

    function migrateEvent() {
        ///echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrevent' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $eventrResults = $this->conn->query("SELECT * FROM event  WHERE channel_id= '8'  $condition");
        if ($eventrResults->num_rows > 0) {
            while ($eventRow = $eventrResults->fetch_assoc()) {
                $eventId = $eventRow['event_id'];
                $checkEventExistResultSet = $this->conn2->query("select event_id,title from event where event_id=$eventId");
                if ($checkEventExistResultSet->num_rows > 0) { //echo 'here'; exit;
                    $eventUpdateStmt = $this->conn2->prepare("update event set title=?,description=?,imagepath=?,image_url=?,banner_image=?,start_date=?,end_date=?,start_time=?,end_time=?,country=?,state=?,valid=?,created_at=?,updated_at=? where event_id=?") or die($this->conn->error);
                    $eventUpdateStmt->bind_param('sssssssssiiissi', $eventRow['title'], $eventRow['description'], $eventRow['imagepath'], $eventRow['image_url'], $eventRow['banner_image'], $eventRow['start_date'], $eventRow['end_date'], $eventRow['start_time'], $eventRow['end_time'], $eventRow['country'], $eventRow['state'], $eventRow['valid'], $eventRow['created_at'], $eventRow['updated_at'], $eventRow['event_id']) or die($this->conn->error);
                    $eventUpdateStmt->execute()or die($this->conn->error);
                    if ($eventUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {
                    $eventInsertStmt = $this->conn2->prepare("insert into event set event_id=?,title=?,description=?,imagepath=?,image_url=?,banner_image=?,start_date=?,end_date=?,start_time=?,end_time=?,country=?,state=?,valid=?,created_at=?,updated_at=?");
                    $eventInsertStmt->bind_param('isssssssssiiiss', $eventRow['event_id'], $eventRow['title'], $eventRow['description'], $eventRow['imagepath'], $eventRow['image_url'], $eventRow['banner_image'], $eventRow['start_date'], $eventRow['end_date'], $eventRow['start_time'], $eventRow['end_time'], $eventRow['country'], $eventRow['state'], $eventRow['valid'], $eventRow['created_at'], $eventRow['updated_at']);
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
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrevent',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' event(s) inserted and ' . $_SESSION['noofupd'] . ' event(s) updated.</h5>';
    }

    function migrateTips() {


        $this->migratetipstagComb();
        $this->migratetipCategory();
        //echo 'test'; exit;
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrtips' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        //echo "SELECT * FROM tips  WHERE 1 $condition";exit;
        $tipsrResults = $this->conn->query("SELECT * FROM tips  WHERE channel_id = '8'   $condition");
        //echo $tipsrResults->num_rows;exit; 
        if ($tipsrResults->num_rows > 0) {

            while ($tipsRow = $tipsrResults->fetch_assoc()) {
                //print_r($tipsRow);exit;
                $tipsId = $tipsRow['tip_id'];
                //exit;
                $checkTipsExistResultSet = $this->conn2->query("select tips_id from channel_tips where tips_id=$tipsId");
                if ($checkTipsExistResultSet->num_rows > 0) { //echo 'test';exit;
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $tipsUpdateStmt = $this->conn2->prepare("update channel_tips set tips_name=?,tips_discriptions=?,tips_category=?,"
                            . "tips_update_at=?,tips_created_at=?,tips_status=? where tips_id=?") or die($this->conn->error);
                    $tipsUpdateStmt->bind_param('sssssii', $tipsRow['tip'], $tipsRow['description'], $tipsRow['t_category_id'], $tipsRow['updated_at'], $tipsRow['created_at'], $tipsRow['valid'], $tipsId) or die($this->conn->error);
                    // echo $this->conn2->error;exit;
                    $tipsUpdateStmt->execute()or die($this->conn->error);
                    //print_r($tipsUpdateStmt);exit;
                    //echo $tipsUpdateStmt->affected_rows;exit;
                    if ($tipsUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {//echo 'goint to insert';exit;
                    $tipsInsertStmt = $this->conn2->prepare("insert into channel_tips set tips_id=?,"
                            . "tips_name=?,tips_discriptions=?,tips_category=?,"
                            . "tips_update_at=?,tips_created_at=?,tips_status=?");
                    //echo 'ddd'.$this->conn2->error; exit;
                    //print_r($tipsRow);
                    $tipsInsertStmt->bind_param('isssssi', $tipsRow['tip_id'], $tipsRow['tip'], $tipsRow['description'], $tipsRow['t_category_id'], $tipsRow['updated_at'], $tipsRow['created_at'], $tipsRow['valid']);
                    //print_r($tipsInsertStmt);exit;
                    //echo $tipsInsertStmt->affected_rows;exit;
                    $tipsInsertStmt->execute();
                    //print_r($tipsInsertStmt);exit;
                    // echo $_SESSION['noofins'];
                    //echo $tipsInsertStmt->insert_id;exit;
                    if ($tipsInsertStmt->affected_rows) {
                        //echo '--'.$tipsInsertStmt->insert_id.'--';
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                        // exit;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrtips',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' tips(s) inserted and ' . $_SESSION['noofupd'] . ' author(s) updated.</h5>';
    }

    function migratetipstagComb() {
        //echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrtipstagcomb' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        //echo "SELECT * FROM event  WHERE 1 $condition";exit;
        $tipstagcombrResults = $this->conn->query("SELECT * FROM tiptags  WHERE 1 $condition");
        //echo $tipstagcombrResults->num_rows;exit;
        if ($tipstagcombrResults->num_rows > 0) {

            while ($tipstagRow = $tipstagcombrResults->fetch_assoc()) {
                //print_r($tipstagRow);exit;
                $tipstagId = $tipstagRow['ttag_id'];
                // exit;
                $checkTipstagcombExistResultSet = $this->conn2->query("select tagp_id,tag_name from channel_tips_tag where tagp_id=$tipstagId");
                if ($checkTipstagcombExistResultSet->num_rows > 0) { //echo 'test';exit;
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $tipstagcombUpdateStmt = $this->conn2->prepare("update channel_tips_tag set tag_name=?,sponsered_by=?,tag_logo=?,url=?,valid=? where tagp_id=?") or die($this->conn->error);
                    $tipstagcombUpdateStmt->bind_param('ssssii', $tipstagRow['tag'], $tipstagRow['sponsered_by'], $tipstagRow['logopath'], $tipstagRow['url'], $tipstagRow['valid'], $tipstagId) or die($this->conn->error);
                    //echo $this->conn2->error;exit;
                    $tipstagcombUpdateStmt->execute()or die($this->conn->error);
                    //print_r($tipstagcombUpdateStmt);exit;
                    //echo $eventUpdateStmt->affected_rows;exit;
                    if ($tipstagcombUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {//echo 'goint to insert';exit;
                    $tipstagcombInsertStmt = $this->conn2->prepare("insert into channel_tips_tag set tagp_id=?,tag_name=?,sponsered_by=?,tag_logo=?,url=?,valid=?");
                    //echo $this->conn2->error; exit;
                    $tipstagcombInsertStmt->bind_param('issssi', $tipstagRow['ttag_id'], $tipstagRow['tag'], $tipstagRow['sponsored_by'], $tipstagRow['logopath'], $tipstagRow['url'], $tipstagRow['valid']);

                    //print_r($tipstagcombInsertStmt);exit;
                    //echo $tipstagcombInsertStmt->affected_rows;exit;
                    $tipstagcombInsertStmt->execute();
                    //print_r($tipstagcombInsertStmt);exit;
                    //echo $_SESSION['noofins'];
                    //echo $tipstagcombInsertStmt->insert_id;exit;
                    if ($tipstagcombInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrtipstagcomb',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' tipstagcomb(s) inserted and ' . $_SESSION['noofupd'] . ' tipstagcomb(s) updated.</h5>';
    }

    function migratetipCategory() {
        //echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrtipCategory' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        //echo "SELECT * FROM tipcategory  WHERE 1 $condition";exit;
        $tipscategoryrResults = $this->conn->query("SELECT * FROM tipcategory  WHERE 1 $condition");
        //echo $tipscategoryrResults->num_rows;exit;
        if ($tipscategoryrResults->num_rows > 0) {

            while ($tipscategoryRow = $tipscategoryrResults->fetch_assoc()) {
                //print_r($tipscategoryRow);exit;
                $tipscategoryId = $tipscategoryRow['tcate_id'];
                // exit;
                $checkCategoryExistResultSet = $this->conn2->query("select tcate_id,tcategory from tipcategory where tcate_id=$tipscategoryId");
                if ($checkCategoryExistResultSet->num_rows > 0) {
                    //echo 'test';
                    //exit;
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $tipscategoryUpdateStmt = $this->conn2->prepare("update tipcategory set tcategory=?,valid=? where tcate_id=?") or die($this->conn->error);
                    $tipscategoryUpdateStmt->bind_param('sii', $tipscategoryRow['tcategory'], $tipscategoryRow['valid'], $tipscategoryId) or die($this->conn->error);
                    //echo $this->conn2->error;exit;
                    $tipscategoryUpdateStmt->execute()or die($this->conn->error);
                    //print_r($tagUpdateStmt);exit;
                    //echo $eventUpdateStmt->affected_rows;exit;
                    if ($tipscategoryUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {//echo 'goint to insert';exit;
                    $tipscategoryInsertStmt = $this->conn2->prepare("insert into tipcategory set tcate_id=?,tcategory=?,valid=?");
                    //echo $this->conn2->error; exit;
                    $tipscategoryInsertStmt->bind_param('isi', $tipscategoryRow['tcate_id'], $tipscategoryRow['tcategory'], $tipscategoryRow['valid']);
                    //print_r($tipstagcombInsertStmt);exit;
                    //echo $tipstagcombInsertStmt->affected_rows;exit;
                    $tipscategoryInsertStmt->execute();
                    //print_r($tipstagcombInsertStmt);exit;
                    //echo $_SESSION['noofins'];
                    //echo $tipstagcombInsertStmt->insert_id;exit;
                    if ($tipscategoryInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrtipCategory',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' tipCategory(s) inserted and ' . $_SESSION['noofupd'] . ' tipCategory(s) updated.</h5>';
    }

    function migrateQuotes() {
        $this->migratequotesTage();
        $this->migratequotesCategory();
        //echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrquotes' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        //echo "SELECT * FROM event  WHERE 1 $condition";exit;
        $quotesrResults = $this->conn->query("SELECT * FROM quotes WHERE channel_id = '8'  $condition");
        //echo $quotesrResults->num_rows;exit;
        if ($quotesrResults->num_rows > 0) {

            while ($quotesRow = $quotesrResults->fetch_assoc()) {
                //print_r($quotesRow);exit;
                $quotesId = $quotesRow['quote_id'];
                // exit;
                $checkQuotesExistResultSet = $this->conn2->query("select quote_id,quote from channel_quote where quote_id=$quotesId");
                if ($checkQuotesExistResultSet->num_rows > 0) { //echo 'test';exit;
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
		if($quotesRow['valid']==1){

                    $quotesUpdateStmt = $this->conn2->prepare("update channel_quote set quote=?,quote_description=?,q_author_id=?,q_tags=?,quotes_image=?,valid=?,quote_update_at=?,quote_created_at=? where quote_id=?") or die($this->conn->error);
                    $quotesUpdateStmt->bind_param('ssississi', $quotesRow['quote'], $quotesRow['description'], $quotesRow['q_category_id'], $quotesRow['q_tags'],$quotesRow['quotes_image'], $quotesRow['valid'], $quotesRow['updated_at'], $quotesRow['created_at'], $quotesId) or die($this->conn->error);
                    //echo $this->conn2->error;exit;
                    $quotesUpdateStmt->execute()or die($this->conn->error);
                    //print_r($tagUpdateStmt);exit;
                    //echo $eventUpdateStmt->affected_rows;exit;
                    if ($quotesUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
		   }else{

			$delStmt = $this->conn2->prepare("delete from channel_quote where quote_id=?");
                        $delStmt->bind_param('i', $quotesId);
                        $delStmt->execute();
			}
                }else {//echo 'goint to insert';exit;
			
                    $quotesInsertStmt = $this->conn2->prepare("insert into channel_quote set quote_id=?,quote=?,quote_description=?,q_author_id=?,q_tags=?,quotes_image=?,valid=?,quote_update_at=?,quote_created_at=?");
                    //echo $this->conn2->error; exit;
                    $quotesInsertStmt->bind_param('issississ', $quotesRow['quote_id'], $quotesRow['quote'], $quotesRow['description'], $quotesRow['q_category_id'], $quotesRow['q_tags'],$quotesRow['quotes_image'],$quotesRow['valid'], $quotesRow['updated_at'], $quotesRow['created_at']);
                    //print_r($tipstagcombInsertStmt);exit;
                    //echo $tipstagcombInsertStmt->affected_rows;exit;
                    $quotesInsertStmt->execute();
                    //print_r($tipstagcombInsertStmt);exit;
                    //echo $_SESSION['noofins'];
                    //echo $tipstagcombInsertStmt->insert_id;exit;
                    if ($quotesInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrquotes',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' quotes(s) inserted and ' . $_SESSION['noofupd'] . ' quotes(s) updated.</h5>';
    }

    function migratequotesCategory() {
        ///echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrquotescategory' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        //echo "SELECT * FROM event  WHERE 1 $condition";exit;
        $quotescategoryrResults = $this->conn->query("SELECT * FROM quotescategory  WHERE 1 $condition");
        //echo $quotescategoryrResults->num_rows;exit;
        if ($quotescategoryrResults->num_rows > 0) {

            while ($quotescategoryRow = $quotescategoryrResults->fetch_assoc()) {
                //print_r($quotescategoryRow);exit;
                $quotescategoryId = $quotescategoryRow['cate_id'];
                // exit;
                $checkquotesCategoryExistResultSet = $this->conn2->query("select author_id,author_name from quotesauthor where author_id=$tipscategoryId");
                if ($checkquotesCategoryExistResultSet->num_rows > 0) { //echo 'test';exit;
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $quotescategoryUpdateStmt = $this->conn2->prepare("update quotesauthor set author_name=?,valid=? where author_id=?") or die($this->conn->error);
                    $quotescategoryUpdateStmt->bind_param('sii', $quotescategoryRow['category'], $quotescategoryRow['valid'], $tipscategoryId) or die($this->conn->error);
                    //echo $this->conn2->error;exit;
                    $quotescategoryUpdateStmt->execute()or die($this->conn->error);
                    //print_r($tagUpdateStmt);exit;
                    //echo $eventUpdateStmt->affected_rows;exit;
                    if ($quotescategoryUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {//echo 'goint to insert';exit;
                    $quotescategoryInsertStmt = $this->conn2->prepare("insert into quotesauthor set author_id=?,author_name=?,valid=?");
                    //echo $this->conn2->error; exit;
                    $quotescategoryInsertStmt->bind_param('isi', $quotescategoryRow['cate_id'], $quotescategoryRow['category'], $quotescategoryRow['valid']);
                    //print_r($tipstagcombInsertStmt);exit;
                    //echo $tipstagcombInsertStmt->affected_rows;exit;
                    $quotescategoryInsertStmt->execute();
                    //print_r($tipstagcombInsertStmt);exit;
                    //echo $_SESSION['noofins'];
                    //echo $tipstagcombInsertStmt->insert_id;exit;
                    if ($quotescategoryInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrquotescategory',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' quotescategory(s) inserted and ' . $_SESSION['noofupd'] . ' quotescategory(s) updated.</h5>';
    }

    function migratequotesTage() {
        ///echo 'test';
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrquotetags' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        //echo "SELECT * FROM quotetags  WHERE 1 $condition"; 
        $tagrResults = $this->conn->query("SELECT * FROM quotetags  WHERE 1 $condition");
        //echo $eventrResults->num_rows;exit;
        //echo '--'.$tagrResults->num_rows; exit;
        if ($tagrResults->num_rows > 0) {

            while ($tagRow = $tagrResults->fetch_assoc()) {
                //print_r($tagRow);
                $tagtId = $tagRow['tag_id']; 
               // echo "select tag_id,tag from quotetags where tag_id=$tagtId"; exit;
                $checkTagExistResultSet = $this->conn2->query("select tag_id,tag from quotetags where tag_id=$tagtId");
                if ($checkTagExistResultSet->num_rows > 0) { //echo 'test';exit;
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $tagUpdateStmt = $this->conn2->prepare("update quotetags set tag=?,valid=? where tag_id=?") or die($this->conn->error);
                    $tagUpdateStmt->bind_param('sii', $tagRow['tag'], $tagRow['valid'], $tagtId) or die($this->conn->error);
                    //echo $this->conn2->error;exit;
                    $tagUpdateStmt->execute()or die($this->conn->error);
                    //print_r($tagUpdateStmt);exit;
                    //echo $eventUpdateStmt->affected_rows;exit;
                    if ($tagUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                }else {//echo 'goint to insert';exit;
                    $tagInsertStmt = $this->conn2->prepare("insert into quotetags set tag_id=?,tag=?,valid=?");
                    //echo $this->conn2->error; exit;
                    $tagInsertStmt->bind_param('isi', $tagRow['tags_id'], $tagRow['tag'], $tagRow['valid']);
                    $tagInsertStmt->execute();
                    if ($tagInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }
        //        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' quotetags(s) inserted and ' . $_SESSION['noofupd'] . ' quotetags(s) updated.</h5>';

        //echo 'here'; exit;
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrquotetags',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' quotetags(s) inserted and ' . $_SESSION['noofupd'] . ' quotetags(s) updated.</h5>';
    }

    
    function migrateTopicCategory(){
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrtopiccategory' order by start_time desc limit 0,1") or die($this->conn->error);
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
                    $existingCatRow=$checkCatExistResultSet->fetch_assoc();
                    $checkCatExistResultSet->close();
                    if($catrow['is_deleted']==0){
                    $catUpdateStmt = $this->conn2->prepare("update topic_category set name=?,parent_id=? where id=?");
                    //echo $this->conn2->error;exit;
                    $catUpdateStmt->bind_param('sii', $catrow['name'], $catrow['parent_id'],$cid);
                    $catUpdateStmt->execute();
                    if ($catUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    
                    }else{
                       $delStmt = $this->conn2->prepare("delete from topic_category where id=?");
                       $delStmt->bind_param('i', $catrow['id']);
                       $delStmt->execute();
                       $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                    }
                }else {
                    $catInsertStmt = $this->conn2->prepare("insert into topic_category set id=?,name=?,parent_id=?") or die($this->conn2->error);
                    
                    $catInsertStmt->bind_param('isi', $catrow['id'], $catrow['name'],$catrow['parent_id']);
                    $catInsertStmt->execute();
                    if ($catInsertStmt->insert_id) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }
        
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrtopiccategory',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();

        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' topic category(ies) inserted,   ' . $_SESSION['noofupd'] . ' topic category(ies) updated and '.$_SESSION['noofdel'].' topic category(ies) deleted.</h5>';
        
            
    }
    
    function migrateCategory() {
        //echo 'test'; exit;
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrcategory' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        $cronLastExecutionTime = 0;
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

//        $catresults = $this->conn->query("SELECT category_id as id,name,channel_id as parent_id,valid,'1' as level  FROM category where channel_id='8' $condition
//union
//select category_two_id as id,name,category_id as parent,valid,'2' as level from category_two where 1=1 $condition
//union
//select category_three_id as id,name,category_two_id as parent,valid,'3' as level from category_three where 1=1 $condition
//union
//select category_four_id as id,name,category_three_id as parent,valid,'4' as level from category_four where 1=1 $condition");
//        
//        echo $catresults->num_rows;exit;
        $catresults = $this->conn->query("SELECT category_id as id,name,channel_id as parent_id,valid,'1' as level  FROM category where channel_id='8' $condition");
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
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrcategory',start_time=?,end_time=?");
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
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrtag' order by  start_time desc limit 0,1") or die($this->conn->error);
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
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrtag',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();

        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' tag(s) inserted and ' . $_SESSION['noofupd'] . ' tag(s) updated.</h5>';
    }
    
    function migrateSpeakerTag() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrspeakertag' order by  start_time desc limit 0,1") or die($this->conn->error);
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
                        $tagInsertStmt->bind_param('isi', $tagrow['id'], $tagrow['tag'],$tagrow['valid']);
                        $tagInsertStmt->execute();
                        if ($tagInsertStmt->insert_id) {
                            $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                        }
                    }
                }
            }
        }
        
        
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrspeakertag',start_time=?,end_time=?");
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
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrspeaker' order by  start_time desc limit 0,1") or die($this->conn->error);
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
                        $authorUpdateStmt->bind_param('issssssii', $authorRow['event_id'], $authorRow['name'], $authorRow['email'],$authorRow['photo'], $authorRow['twitter'], $authorRow['description'], $authorRow['tag'], $authorRow['sequence'],$authorRow['id']);
                        $authorUpdateStmt->execute();
                        if ($authorUpdateStmt->affected_rows)
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    }else {
                        $authorDelStmt = $this->conn2->prepare("delete from event_speaker where id=?");
                        $authorDelStmt->bind_param('i', $authorRow['id']);
                        $authorDelStmt->execute();
                        $_SESSION['noofdel']=$_SESSION['noofdel']+1;
                    }

                } else {
                    $authorInsertStmt = $this->conn2->prepare("insert into event_speaker set id=?,event_id=?,name=?,email=?,photo=?,twitter=?,description=?,tag=?,sequence=?");
                    $authorInsertStmt->bind_param('iissssssi', $authorRow['id'], $authorRow['event_id'], $authorRow['name'], $authorRow['email'],$authorRow['photo'], $authorRow['twitter'], $authorRow['description'], $authorRow['tag'], $authorRow['sequence']);
                    $authorInsertStmt->execute();
                    if ($authorInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }


        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrspeaker',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' speaker(s) inserted, ' . $_SESSION['noofupd'] . ' speaker(s) updated and '.$_SESSION['noofdel'].' speaker(s) deleted.</h5>';
    }

    function migrateNewsType() {

        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrnewstype' order by  start_time desc limit 0,1") or die($this->conn->error);
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
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrnewstype',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' newstype(s) inserted and ' . $_SESSION['noofupd'] . ' newstype(s) updated.</h5>';
    }

    /*
      function migrateMagazine() {

      $_SESSION['noofins'] = 0;
      $_SESSION['noofupd'] = 0;
      $conStartTime = date('Y-m-d H:i:s');

      $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrmagazine' order by  start_time desc limit 0,1") or die($this->conn->error);
      $condition = '';
      if ($cronresult->num_rows > 0) {
      $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
      $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
      }
      $magazineResults = $this->conn->query("SELECT * FROM magazine where 1 $condition");
      if ($magazineResults->num_rows > 0) {
      while ($magazineRow = $magazineResults->fetch_assoc()) {
      //print_r($magazineRow); exit;
      $magazineId = $magazineRow['magazine_id'];
      $checkMagazineExistResultSet = $this->conn2->query("select magazine_id,title from magazine where magazine_id=$magazineId");
      //echo $checkMagazineExistResultSet->num_rows;exit;
      if ($checkMagazineExistResultSet->num_rows > 0) {
      //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
      $magazineUpdateStmt = $this->conn2->prepare("update magazine set title=?,description=?,imagepath=?,start_date=?"
      . ",end_date=?,start_time=?,end_time=?,venue=?,valid=? where magazine_id=?");
      $magazineUpdateStmt->bind_param('ssssssssii',$magazineRow['title'],$magazineRow['description'],$magazineRow['imagepath']
      ,$magazineRow['start_date'],$magazineRow['end_date'],$magazineRow['start_time'],$magazineRow['end_time']
      ,$magazineRow['venue'],$magazineRow['valid'],$magazineId);
      $magazineUpdateStmt->execute();
      if ($magazineUpdateStmt->affected_rows)
      $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
      }else {

      $magazineInsertStmt = $this->conn2->prepare("insert into  magazine set magazine_id=?,title=?,description=?,imagepath=?,start_date=?"
      . ",end_date=?,start_time=?,end_time=?,venue=?,valid=?");
      $magazineInsertStmt->bind_param('issssssssi',$magazineId,$magazineRow['title'],$magazineRow['description'],$magazineRow['imagepath']
      ,$magazineRow['start_date'],$magazineRow['end_date'],$magazineRow['start_time'],$magazineRow['end_time']
      ,$magazineRow['venue'],$magazineRow['valid']);
      $magazineInsertStmt->execute();

      if ($magazineInsertStmt->affected_rows) {
      $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
      }
      }
      }
      }
      $cronEndTime = date('Y-m-d H:i:s');
      $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrmagazine',start_time=?,end_time=?");
      $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
      $updatecronstmt->execute();
      $updatecronstmt->close();
      echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' magazine(s) inserted and ' . $_SESSION['noofupd'] . ' magazine(s) updated.</h5>';

      }
     */

    function migrateTopics() {
        $this->migrateTopicCategory();
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrtopics' order by  start_time desc limit 0,1") or die($this->conn->error);
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
                    $existingRow=$checkExistanceRst->fetch_assoc();
                    $checkExistanceRst->close();
                    if($row['valid']==1){
                    $catUpdateStmt = $this->conn2->prepare("update channel_topics set topic_name=?,category_id=? where topic_id=?");
                    //echo $this->conn2->error;exit;
                    $catUpdateStmt->bind_param('sii', $row['topic'], $row['category_id'],$id);
                    $catUpdateStmt->execute();
                    if ($catUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    
                    }else{  
                       $delStmt = $this->conn2->prepare("delete from channel_topics where topic_id=?");
                       $delStmt->bind_param('i', $id);
                       $delStmt->execute();
                       $_SESSION['noofdel'] = $_SESSION['noofdel'] + 1;
                    }
                }else {
                    $insertStmt = $this->conn2->prepare("insert into channel_topics set topic_id=?,topic_name=?,category_id=?") or die($this->conn2->error);
                    $insertStmt->bind_param('isi', $row['id'], $row['topic'],$row['category_id']);
                    $insertStmt->execute();
                    if ($insertStmt->insert_id) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }
        
        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrtopics',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();

        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' topic(s) inserted,   ' . $_SESSION['noofupd'] . ' topic(s) updated and '.$_SESSION['noofdel'].' topic(s) deleted.</h5>';
       
        
    }

    function migrateQuickByte() {
        //$this->migrateAuthor();
        //$this->migrateTag();
        //$this->migrateTopics();
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrquickbyte' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $quickBytesResults = $this->conn->query("SELECT *  FROM quickbyte where channel_id='8' $condition");
        //echo $quickBytesResults->num_rows;exit;
        if ($quickBytesResults->num_rows > 0) {
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
                            $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                        }
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrquickbyte',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' quickbyte(s) inserted, ' . $_SESSION['noofupd'] . ' quickbyte(s) updated and ' . $_SESSION['noofdel'] . ' quickbyte(s) deleted.</h5>';
    }

    function migrateQuickBytePhoto($id, $is_new = 0) {
        if ($is_new) {
            $photos = $this->conn->query("select * from photos where owned_by='quickbyte' and owner_id=$id");
            while ($photo = $photos->fetch_object()) {
                //print_r($photo);exit;
                $photoInsStmt = $this->conn2->prepare("insert into quick_bytes_photos set quick_byte_id=?,quick_byte_photo_name=?"
                        . ",quick_byte_photo_title=?,quick_byte_photo_description=?,photo_by=?,sequence=?");
                $photoInsStmt->bind_param('issssi', $id, $photo->photopath, $photo->title, $photo->description, $photo->photo_by,$photo->sequence);
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
                $photoInsStmt->bind_param('issssi', $id, $photo->photopath, $photo->title, $photo->description, $photo->photo_by,$photo->sequence);
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
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrarticle' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $articleResults = $this->conn->query("SELECT *  FROM articles where  channel_id='8'  $condition");
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
                                . "magzine_issue_name=?,canonical_options=?,video_type=?,canonical_url=?,social_title=?,social_summary=?,social_image=?,article_location_country=?,article_location_state=?,hide_image=?,campaign_id=?,event_id=? where article_id=?");
                        $articleUpdateStmt->bind_param('sssisssiiiiiisssssiiiiii', $articleRow['title'], $articleRow['description'], $articleRow['summary'], $articleRow['news_type']
                                , $pubDate, $articleRow['slug'], $status, $articleRow['important'], $articleRow['video_Id'], $articleRow['for_homepage'], $articleRow['web_exclusive'], $articleRow['magazine_id'], $articleRow['canonical_options'], $articleRow['video_type'], $articleRow['canonical_url'],$articleRow['social_title'],$articleRow['social_summary'],$articleRow['social_image'], $articleRow['country'], $articleRow['state'], $articleRow['hide_image'], $articleRow['campaign_id'], $articleRow['event_id'], $articleRow['article_id']
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
                } else {
                    if ($articleRow['status'] == 'P') {
                        $pubDate = $articleRow['publish_date'] . ' ' . $articleRow['publish_time'];
                        $status = 'published';
                        $articleInsertStmt = $this->conn2->prepare("insert articles set article_id=?,article_title=?,article_description=?,article_summary=?,"
                                . "article_type=?,article_published_date=?,article_slug=?,article_status=?,video_Id=?,important_article=?,display_to_homepage=?,is_exclusive=?,"
                                . "magzine_issue_name=?,canonical_options=?,video_type=?,canonical_url=?,social_title=?,social_summary=?,social_image=?,article_location_country=?,article_location_state=?,is_old=?,hide_image=?,campaign_id=?,event_id=?");
                        $articleInsertStmt->bind_param('isssisssiiiiiisssssiiiiii', $articleRow['article_id'], $articleRow['title'], $articleRow['description'], $articleRow['summary'], $articleRow['news_type']
                                , $pubDate, $articleRow['slug'], $status, $articleRow['video_Id'], $articleRow['important'], $articleRow['for_homepage'], $articleRow['web_exclusive'], $articleRow['magazine_id'], $articleRow['canonical_options'], $articleRow['video_type'], $articleRow['canonical_url'],$articleRow['social_title'],$articleRow['social_summary'],$articleRow['social_image'],$articleRow['country'], $articleRow['state'], $articleRow['is_old'], $articleRow['hide_image'], $articleRow['campaign_id'], $articleRow['event_id']);
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
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrarticle',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' article(s) inserted, ' . $_SESSION['noofupd'] . ' article(s) updated and ' . $_SESSION['noofdel'] . ' article(s) deleted.</h5>';
        $key= md5(date('dmY').'businessworld');
	    file_get_contents('http://businessworld.in/create-json/'.$key);
    }

    function migrateFeature() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');

        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrfeatur' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $featurResults = $this->conn->query("SELECT *  FROM featuredarticle where channel_id='8' $condition");
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
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrfeatur',start_time=?,end_time=?");
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
                $imInsertStmt->bind_param('isssssssi', $imageRow['owner_id'], $imageRow['photopath'], $imageRow['imagefullPath'], $imageRow['title'], $imageRow['source'], $imageRow['source_url'], $imageRow['photo_by'], $status,$imageRow['sequence']);
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
                $imInsertStmt->bind_param('isssssssi', $imageRow['owner_id'], $imageRow['photopath'], $imageRow['imagefullPath'], $imageRow['title'], $imageRow['source'], $imageRow['source_url'], $imageRow['photo_by'], $status,$imageRow['sequence']);
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

    // Sponsored Post

    function migrateSponsored() {
        //echo 'sumit';exit; 
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrsponsoredposts' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $sponsoredResults = $this->conn->query("SELECT *  FROM sponsoredposts  where channel_id = 8");
        //echo $sponsoredResults->num_rows ;exit; 
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
                //echo $checkSponsoredResult->num_rows.'-' ;exit;
                if ($checkSponsoredResult->num_rows > 0) {
                    $checkSponsoredResult->close();
                    if ($sponsoredRow['status'] == 'P') {
                        $pubDate = $sponsoredRow['publish_date'] . ' ' . $sponsoredRow['publish_time'];
                        $status = 'published';
                        $sponsoredUpdateStmt = $this->conn2->prepare("update sponsoredposts set sponsoredposts_title=?,sponsoredposts_description=?,sponsoredposts_summary=?,"
                                . "sponsoredposts_type=?,sponsoredposts_published_date=?,sponsoredposts_status=?,important_sponsoredposts=?,"
                                . "where sponsoredposts_id=?");
                        $sponsoredUpdateStmt->bind_param('ssssssii', $sponsoredRow['title'], $sponsoredRow['description'], $sponsoredRow['summary'], $sponsoredRow['event_id']
                                , $pubDate, $status, $sponsoredRow['feature_this'], $sponsoredRow['id']
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
                        //echo 'sumit insert'; exit;
                        $sponsoredpostsInsertStmt = $this->conn2->prepare("insert sponsoredposts set sponsoredposts_id=?,sponsoredposts_title=?,sponsoredposts_description=?,sponsoredposts_summary=?,sponsoredposts_type=?,sponsoredposts_published_date=?,sponsoredposts_status=?,important_sponsoredposts=?");
                        $sponsoredpostsInsertStmt->bind_param('issssssi', $sponsoredRow['id'], $sponsoredRow['title'], $sponsoredRow['description'], $sponsoredRow['summary'], $sponsoredRow['event_id'],$pubDate, $status, $sponsoredRow['feature_this']);
                        $sponsoredpostsInsertStmt->execute();
                        //print_r($sponsoredpostsInsertStmt);exit;
                        // echo $sponsoredpostsInsertStmt->insert_id;exit;    
                        if ($sponsoredpostsInsertStmt->insert_id) {
                            $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                            $this->callSponsoredRelatedContent($sponsoredpostsInsertStmt->insert_id, 1);
                        }
                    }
                }
                //echo '1 done ';exit;
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecorstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrsponsoredposts',start_time=?,end_time=?");
        $updatecorstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecorstmt->execute();
        $updatecorstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' sponsoredposts(s) inserted, ' . $_SESSION['noofupd'] . ' sponsoredposts(s) updated and ' . $_SESSION['noofdel'] . ' sponsoredposts(s) deleted.</h5>';
        exit;
    }

    function callSponsoredRelatedContent($SponsoredId, $isNew = 0) {
        $this->migrateSponsoredImage($SponsoredId, $isNew);
        $this->migrateSponsoredVideo($SponsoredId, $isNew);
        $this->migrateSponsoredCategory($SponsoredId);
    }

    function migrateSponsoredImage($SponsoredId, $isNew = 0) {
        if ($isNew == '1') {
            //echo 'sumit'.$SponsoredId ; exit;
            $sponsoredpostImageResultset = $this->conn->query("select * from photos where owned_by='sponsoredpost' and owner_id=$SponsoredId and valid='1'");
            //echo $sponsoredpostImageResultset->num_rows ;exit;
            while ($imageRow = $sponsoredpostImageResultset->fetch_assoc()) {
                //print_r($imageRow);
                $imInsertStmt = $this->conn2->prepare("insert into sponsoredposts_images set sponsoredposts_id=?,image_url=?,image_title=?,image_source_name=?,image_source_url=?");
                $status = ($imageRow['active'] == '1') ? 'enabled' : 'disabled';
                $imInsertStmt->bind_param('issss', $imageRow['owner_id'], $imageRow['photopath'], $imageRow['title'], $imageRow['source'], $imageRow['source_url']);
                $imInsertStmt->execute();
                //print_r($imInsertStmt);exit;
                $imInsertStmt->close();
            }
        } else {

            $checkImResult = $this->conn->query("select * from photos where owned_by='sponsoredpost' and owner_id=$SponsoredId ");

            if ($checkImResult->num_rows > 0) {
                $checkImResult->close();
                $this->conn2->query("delete from sponsoredposts_images where sponsoredposts_id=$SponsoredId");


                $sponsoredImageResultset = $this->conn->query("select * from photos where owned_by='sponsoredpost' and owner_id=$SponsoredId and valid='1'");
                while ($imageRow = $sponsoredImageResultset->fetch_assoc()) {
                    $imInsertStmt = $this->conn2->prepare("insert into article_images set article_id=?,image_url=?,image_title=?,image_source_name=?,image_source_url=?,image_status=?");
                    $status = ($imageRow['active'] == '1') ? 'enabled' : 'disabled';
                    $imInsertStmt->bind_param('isssss', $imageRow['owner_id'], $imageRow['photopath'], $imageRow['title'], $imageRow['source_url'], $status);
                    $imInsertStmt->execute();
                    $imInsertStmt->close();
                }
            }
        }
    }

    function migrateSponsoredCategory($sponsId ) {
        $catResultSet = '';
        $this->conn2->query("delete from sponsoredposts_category where sponsoredposts_id=$sponsId");

        $sponCatRst = $this->conn->query("SELECT category1,category2,category3,category4 FROM `sponsoredposts` WHERE  `id`='$sponsId'");
        $catTempRow = $sponCatRst->fetch_assoc();
        $catRowArray = array();
        if ($catTempRow['category1'] != 0) {
            $catRowArray[] = array('catlevel' => $catTempRow['category1'], 'level' => '1');
            if ($catTempRow['category2'] != 0) {
                $catRowArray[] = array('catlevel' => $catTempRow['category2'], 'level' => '2');
                if ($catTempRow['category3'] != 0) {
                    $catRowArray[] = array('catlevel' => $catTempRow['category3'], 'level' => '3');
                    if ($catTempRow['category4'] != 0) {
                        $catRowArray[] = array('catlevel' => $catTempRow['category4'], 'level' => '4');
                    }
                }
            }
        }
        foreach ($catRowArray as $catRow) {
            $insertSponsCategoryStmt = $this->conn2->prepare("insert into sponsoredposts_category set sponsoredposts_id=?,category_id=?,category_level=?");
            $insertSponsCategoryStmt->bind_param('iii', $sponsId, $this->categoryMapping[$catRow['catlevel'] . '_' . $catRow['level']], $catRow['level']);
            $insertSponsCategoryStmt->execute();
            $insertSpnsCategoryStmt->close();
        }
    }

    function migrateSponsoredVideo($SponsoredId, $isNew = 0 ) {
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
        } else {
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

    function migrateMagazineissue() {
        //echo 'test';die;
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $_SESSION['noofdel'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrmagazineissue' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        $magazinerResults = $this->conn->query("SELECT * FROM magazine  WHERE channel_id ='8' $condition");
        if ($magazinerResults->num_rows > 0) {
            //echo $magazinerResults->num_rows; exit;
            //echo 'testsumit';
            //die;
            while ($magazineRow = $magazinerResults->fetch_assoc()) {
                //print_r($magazineRow); die;
                $magazinevalid = $magazineRow['valid'];
                //die;
                $magazineId = $magazineRow['magazine_id'];
                if ($magazinevalid == '1') {
                    //echo 'test'; exit;
                    //echo $magazineId;
                    //echo 'testsumitkumar';
                    //die;
                    $checkMagazineExistResultSet = $this->conn2->query("select magazine_id,title from magazine where magazine_id=$magazineId");
                    if ($checkMagazineExistResultSet->num_rows > 0) { //echo 'here'; exit;
                        $magazineUpdateStmt = $this->conn2->prepare("update magazine set title=?,imagepath=?,publish_date_m=?,story1_title=?,story1_url=?,story2_title=?,story2_url=?,story3_title=?,story3_url=?,story4_title=?, story4_url=?,story5_title=?,story5_url=?,flipbook_url=?,buy_digital=?,created_at=?,updated_at=? where magazine_id=?") or die($this->conn2->error);
                        $magazineUpdateStmt->bind_param('sssssssssssssssssi', $magazineRow['title'], $magazineRow['imagepath'], $magazineRow['publish_date_m'], $magazineRow['story1_title'], $magazineRow['story1_url'], $magazineRow['story2_title'], $magazineRow['story2_url'], $magazineRow['story3_title'], $magazineRow['story3_url'], $magazineRow['story4_title'], $magazineRow['story4_url'], $magazineRow['story5_title'], $magazineRow['story5_url'],$magazineRow['flipbook_url'],$magazineRow['buy_digital'], $magazineRow['created_at'], $magazineRow['updated_at'], $magazineRow['magazine_id']) or die($this->conn2->error);
                        $magazineUpdateStmt->execute() or die($this->conn2->error);
                        if ($magazineUpdateStmt->affected_rows)
                            $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    }else {//echo 'here sumit';echo $magazineRow['magazine_id']; exit;
                        $magazineInsertStmt = $this->conn2->prepare("insert into magazine set magazine_id=?,title=?,imagepath=?,publish_date_m=?,story1_title=?,story1_url=?,story2_title=?,story2_url=?,story3_title=?,story3_url=?,story4_title=?,story4_url=?,story5_title=?,story5_url=?,flipbook_url=?,buy_digital=?,created_at=?,updated_at=?") or die($this->conn2->error);
                        $magazineInsertStmt->bind_param('isssssssssssssssss', $magazineRow['magazine_id'], $magazineRow['title'], $magazineRow['imagepath'], $magazineRow['publish_date_m'], $magazineRow['story1_title'], $magazineRow['story1_url'], $magazineRow['story2_title'], $magazineRow['story2_url'], $magazineRow['story3_title'], $magazineRow['story3_url'], $magazineRow['story4_title'], $magazineRow['story4_url'], $magazineRow['story5_title'], $magazineRow['story5_url'],$magazineRow['flipbook_url'],$magazineRow['buy_digital'], $magazineRow['created_at'], $magazineRow['updated_at']) or die($this->conn2->error);
                        $magazineInsertStmt->execute() or die($this->conn2->error);
                        if ($magazineInsertStmt->affected_rows) {
                            $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                        }
                    }
                } else {
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
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrmagazineissue',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();

        //echo 'test1';die;
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' magazineissue(s) inserted,  ' . $_SESSION['noofupd'] . ' magazineissue(s) updated and ' . $_SESSION['noofdel'] . ' magazineissue(s) deleted.</h5>';
    }


    

    // Debate Video
    //video module

    function migrateMasterVideo() {
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrmastervideo' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $masterVideoResults = $this->conn->query("SELECT * FROM video_master where channel_id=8  $condition");
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
                    if($masterVideoRow['video_status']=='1'){
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
                  } else {
                        $delStmt = $this->conn2->prepare("delete from video_master where video_id=?");
                        $delStmt->bind_param('i', $masterVideoRow['id']);
                        $delStmt->execute();
                        
                    }  
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrmastervideo',start_time=?,end_time=?");
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
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrcampaing' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            // $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $campaingResults = $this->conn->query("SELECT * FROM campaign where channel_id=8 $condition");
        //echo $authorResults->num_rows; exit;
        if ($campaingResults->num_rows > 0) {

            while ($campaignRow = $campaingResults->fetch_assoc()) {
                //print_r($campaingRow); exit;
                $campaingId = $campaignRow['campaign_id'];
                $checkmasterVideoExistResultSet = $this->conn2->query("select campaing_id, campaing_title,campaing_status, campaing_pdate from campaign where campaing_id=$campaingId");
                if ($checkmasterVideoExistResultSet->num_rows > 0) { //echo 'going to update';exit;  
                    //Array ( [id] => 161 [tag] => anuradha parthasarathy [valid] => 1 )
                    $masterVideoUpdateStmt = $this->conn2->prepare("update campaign set campaing_title=?,campaing_status=?,campaing_pdate=? where campaing_id=?");
                    $masterVideoUpdateStmt->bind_param('sisi', $campaignRow['title'], $campaignRow['valid'], $campaignRow['updated_at'], $campaingId);
                    $masterVideoUpdateStmt->execute();
                    if ($masterVideoUpdateStmt->affected_rows)
                        $_SESSION['noofupd'] = $_SESSION['noofupd'] + 1;
                    // echo  $_SESSION['noofupd'];
                }else {
                    //echo 'sumit'; exit;
                    $campaignInsertStmt = $this->conn2->prepare("insert into campaign set campaing_id=?, campaing_title=?,campaing_status=?,campaing_pdate=?");
                    //echo $this->conn2->error; exit;
                    $campaignInsertStmt->bind_param('isis', $campaignRow['campaign_id'], $campaignRow['title'], $campaignRow['valid'], $campaignRow['updated_at']);
                    $campaignInsertStmt->execute();
                    if ($campaignInsertStmt->affected_rows) {
                        $_SESSION['noofins'] = $_SESSION['noofins'] + 1;
                    }
                }
            }
        }

        $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrcampaing',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' campaing(s) inserted and ' . $_SESSION['noofupd'] . ' campaing(s) updated.</h5>';
    }

//campain module end here 
function migrateMasterNewsLetter() {
    
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrmasternewsletter' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
             //$condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }
        //echo 'sumit' ;exit;
        $masterNewsLetterResults = $this->conn->query("SELECT * FROM master_newsletter where channel_id= 8  $condition");
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
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrmasternewsletter',start_time=?,end_time=?");
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
            $masterNewsLetterArticleInsertStmt->bind_param('iiiis', $masterNewsLetterArticleRow['master_newsletter_id'],  $masterNewsLetterArticleRow['article_id'],$masterNewsLetterArticleRow['sequence'],$masterNewsLetterArticleRow['is_deleted'], $masterNewsLetterArticleRow['updated_at']) or die($this->conn2->error);
            $masterNewsLetterArticleInsertStmt->execute() or die($this->conn2->error);
            $masterNewsLetterArticleInsertStmt->close();
	
        }

       
        
    }
     //Live streaming module start here 
    function Livestreaming() {
        //echo 'sumit';exit;
        $_SESSION['noofins'] = 0;
        $_SESSION['noofupd'] = 0;
        $conStartTime = date('Y-m-d H:i:s');
        $cronresult = $this->conn->query("select start_time from cron_log where section_name='bwhrlivestreaming' order by  start_time desc limit 0,1") or die($this->conn->error);
        $condition = '';
        if ($cronresult->num_rows > 0) {
            $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
            // $condition = " and  (created_at>='$cronLastExecutionTime' or updated_at>='$cronLastExecutionTime')";
        }

        $livestreamingResults = $this->conn->query("SELECT * FROM event_streaming where channel_id=8 $condition");
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
        $updatecronstmt = $this->conn->prepare("insert into cron_log set section_name='bwhrlivestreaming',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
        echo $this->message = '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' livestreaming(s) inserted and ' . $_SESSION['noofupd'] . ' livestreaming(s) updated.</h5>';
    }
}

?>
