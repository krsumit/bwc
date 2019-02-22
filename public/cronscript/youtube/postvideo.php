<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '/var/www/html/cms/public/cronscript/const.php'; // live
include '/var/www/html/cms/public/cronscript/youtube/vendor/autoload.php'; // local
//require_once 'vendor/autoload.php';
$conn = new mysqli(HOST, USER, PASS, DATABASE) or die($conn->connect_errno);
$conn->set_charset('utf8');
$_SESSION['noofins'] = 0;
$conStartTime = date('Y-m-d H:i:s');
$cronresult = $conn->query("select start_time from cron_log where section_name='youtube'  order by  start_time desc limit 0,1") or die($conn->error);
$condition = '';
if ($cronresult->num_rows > 0) {
    $cronLastExecutionTime = $cronresult->fetch_assoc()['start_time'];
    $condition = " and  (created_at>='$cronLastExecutionTime')";
}
//$masterVideoResults = $conn->query("SELECT * FROM video_master where channel_id=1  $condition order by id ");
//echo "SELECT * FROM video_master where 1  $condition order by id "; exit;
//echo "SELECT * FROM video_master where 1 and youtube_id=''  $condition order by id ";exit;
$masterVideoResults = $conn->query("SELECT * FROM video_master where 1 and youtube_id=''  $condition order by id ");
$OAUTH2_CLIENT_ID = '80935570278-ktl78l8eraaf02tk3ena8eac09g1tocs.apps.googleusercontent.com';
$OAUTH2_CLIENT_SECRET = 'I3DhEImJNL3KmTK5oZTMMkuM';
$REFRESH_TOKEN = '1/DmilbRJBH8Klh6C_d9xo6g8y5g1w55he5qPzoADys98';
$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$client->setScopes('https://www.googleapis.com/auth/youtube');
//  $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
//$client->setRedirectUri($redirect);
$client->setAccessType('offline');
// Define an object that will be used to make all API requests.
$youtube = new Google_Service_YouTube($client);
if (isset($_GET['code'])) {
    if (strval($_SESSION['state']) !== strval($_GET['state'])) {
        die('The session state did not match.');
    }
    $client->authenticate($_GET['code']);
    $_SESSION['token'] = $client->getAccessToken();
    header('Location: ' . $redirect);
}
if ((isset($_SESSION['token']))) {
    //$_SESSION['token']['created']+$_SESSION['token']['expires_in'].'----'.time();exit;
    if ($_SESSION['token']['created'] + $_SESSION['token']['expires_in'] > time())
        $client->setAccessToken($_SESSION['token']);
    else {
        $_SESSION['token'] = $client->refreshToken($REFRESH_TOKEN);
        $client->setAccessToken($_SESSION['token']);
    }
} else {
    $_SESSION['token'] = $client->refreshToken($REFRESH_TOKEN);
    $client->setAccessToken($_SESSION['token']);
}
// Check to ensure that the access token was successfully acquired.
if ($client->getAccessToken()) {
    while ($masterVideoRow = $masterVideoResults->fetch_assoc()) {
        $videoPath = '/var/www/html/cms/public/files/videomaster/'.$masterVideoRow['video_name'];
        //$videourl = 'http://d1s8mqgwixvb29.cloudfront.net/videomaster/'.$masterVideoRow['video_name']; 
        $namename = '';
        //copy($videourl, $videoPath);
        if (file_exists($videoPath)) {
            try {
                $snippet = new Google_Service_YouTube_VideoSnippet();
                $snippet->setTitle(addslashes($masterVideoRow['video_title']));
                $snippet->setDescription(addslashes($masterVideoRow['video_summary']));
                //$snippet->setTags(array("tag1", "tag21"));
                //$snippet->setCategoryId("22");
                $status = new Google_Service_YouTube_VideoStatus();
                $status->privacyStatus = "public";
                // Associate the snippet and status objects with a new video resource.
                $video = new Google_Service_YouTube_Video();
                $video->setSnippet($snippet);
                $video->setStatus($status);
                // Specify the size of each chunk of data, in bytes. Set a higher value for
                // reliable connection as fewer chunks lead to faster uploads. Set a lower
                // value for better recovery on less reliable connections.
                $chunkSizeBytes = 1 * 1024 * 1024;
                // Setting the defer flag to true tells the client to return a request which can be called
                // with ->execute(); instead of making the API call immediately.
                $client->setDefer(true);
                // Create a request for the API's videos.insert method to create and upload the video.
                $insertRequest = $youtube->videos->insert("status,snippet", $video);
                // Create a MediaFileUpload object for resumable uploads.
                $media = new Google_Http_MediaFileUpload(
                        $client, $insertRequest, 'video/*', null, true, $chunkSizeBytes
                );
                $media->setFileSize(filesize($videoPath));
                // Read the media file and upload it chunk by chunk.
                $status = false;
                $handle = fopen($videoPath, "rb");
                // print_r($handle); exit;
                while (!$status && !feof($handle)) {
                    $chunk = fread($handle, $chunkSizeBytes);
                    //echo $chunkSizeBytes;exit;
                    // echo $chunk; exit;
                    $status = $media->nextChunk($chunk);
                }
                fclose($handle);
                // If you want to make other calls after the file upload, set setDefer back to false
                $client->setDefer(false);
                $htmlBody .= "<h3>Video Uploaded</h3><ul>";
                $htmlBody .= sprintf('<li>%s (%s)</li>', $status['snippet']['title'], $status['id']);
                unlink($videoPath);
                $conn->query("update video_master set youtube_id='" . $status['id'] . "' where id=" . $masterVideoRow['id']);
                $htmlBody .= '</ul>';
            } catch (Google_Service_Exception $e) {
                 $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
            } catch (Google_Exception $e) {
                $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
            }
        }
    }
     $cronEndTime = date('Y-m-d H:i:s');
        $updatecronstmt = $conn->prepare("insert into cron_log set section_name='youtube',start_time=?,end_time=?");
        $updatecronstmt->bind_param('ss', $conStartTime, $cronEndTime);
        $updatecronstmt->execute();
        $updatecronstmt->close();
    // End of while 
    $_SESSION['token'] = $client->getAccessToken();
} else {
    // If the user hasn't authorized the app, initiate the OAuth flow
    $state = mt_rand();
    $client->setState($state);
    $_SESSION['state'] = $state;
    $authUrl = $client->createAuthUrl();
    $htmlBody = <<<END
  <h3>Authorization Required</h3>
  <p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
}
  echo '<h5 style="color:#009933;">' . $_SESSION['noofins'] . ' youtube video(s) inserted.</h5>';
?>

