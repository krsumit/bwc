<?php
require_once "Mail.php";
require_once "Mail/mime.php";
class Cron {

    var $conn;
    var $message;
    var $keyarray;
    var $url;
    
    
    
    function __construct() {
        $this->conn = new mysqli('cmsdb.cfdluvagb8xv.ap-southeast-1.rds.amazonaws.com', 'bwcms', 'bw#digital#2017#cms', 'bwcms') or die($this->conn->connect_errno);
        //$this->conn = new mysqli('localhost', 'root', 'admin', '17novlivecms') or die($this->conn->connect_errno);
        $this->url='http://bwdisrupt.businessworld.in/';
        
    }


    function sendMailData($section) {
		//$this->migrateArticleAuthor('1','2');exit;
        // print_r($arr);exit;
       // echo $section;
        switch ($section):
            case 'sendmailauthor':
                $this->sendMailAuthor();
        endswitch;

        $_SESSION['message'] = $this->message;
    }

    function sendMailAuthor() {
            //send_mail_status='0' AND
        $articlesResults = $this->conn->query("SELECT articles.article_id,articles.title,articles.publish_date FROM articles where  status ='P' and send_mail_status='0' and channel_id ='5'");
        //echo $articlesResults->num_rows; exit;
        if ($articlesResults->num_rows > 0) {

            while ($authorRow = $articlesResults->fetch_assoc()) {
                 //print_r($authorRow); exit;
                $articleId = $authorRow['article_id'];
              $result = $this->sendMail($articleId);
              if($result){
                  
                  $send_mail_status='1';
                  $articlesUpdateStmt = $this->conn->prepare("update articles set send_mail_status=? where article_id=?");
                        $articlesUpdateStmt->bind_param('ii', $send_mail_status,$articleId);
                        $articlesUpdateStmt->execute();
                        
              }
             
              
            }
        }

     }

   function sendMail($id){
       $authorResults = $this->conn->query("SELECT articles.article_id,articles.title,articles.publish_date,article_author.*,authors.* FROM articles  JOIN article_author ON  article_author.article_id = articles.article_id JOIN  authors ON  authors.author_id = article_author.author_id where articles.article_id = $id ");

          while ($authorRow = $authorResults->fetch_assoc()) {
              if($authorRow['author_type_id']==1)continue;
              $email=$authorRow['email'];
              //echo $email;exit;
              $name=  $authorRow['name'];
              $articletitle =  $authorRow['title'];
              $title=  str_replace(' ', '-', $authorRow['title']);
	      $publish_date=date('d-m-Y',strtotime($authorRow['publish_date']));
              $article_id=  str_replace(' ', '-', $authorRow['article_id']);
              $url= $this->url.'article/'.preg_replace('/([^a-zA-Z0-9_.])+/', '-',$title).'/'.$publish_date.'-'.$article_id;
              $user_email= 'BW Edit Team <noreply@businessworld.in>';
             //$user_email= 'noreply@businessworld.com';
             $urlcontact =$this->url.'contact-us/';
             
              $this->sendSms($url,$authorRow['mobile'],$authorRow['name'],$authorRow['title']);
             
         // exit;
          $headers  = 'MIME-Version: 1.0' . "\r\n";

          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
          $headers .= 'From: '.$user_email."\r\n".'Reply-To: '.$user_email."\r\n" .'X-Mailer: PHP/' . phpversion();
        $return_html=''; 
        $return_html .= '<!doctype html>';
        $return_html .= '<html xmlns="http://www.w3.org/1999/xhtml">';
        $return_html .= '<head>';
        $return_html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $return_html .= '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">';
        $return_html .= '<title>BW Disrupt</title>';
        $return_html .= '<style type="text/css">

        /* iPad Text Smoother */
        div, p, a, li, td { -webkit-text-size-adjust:none; }

        /* This is the color to change the links #f17366; */

        .ReadMsgBody
        {width: 100%; background-color: #ffffff;}
        .ExternalClass
        {width: 100%; background-color: #ffffff;}
        body
        {width: 100%; background-color: #ffffff; margin:0; padding:0; -webkit-font-smoothing: antialiased;}
        html
        {width: 100%; }

        table[class=fullWidth]			{width: 100%!important;}


        @media only screen and (max-width: 640px) 
                           {
                        body{width:auto!important;}


                        table[class=scaleForMobile]		{width: 100%!important; padding-left: 30px!important; padding-right: 30px!important;}
                        table[class=scaleForMobile2]	{width: 100%!important; padding-left: 15px!important; padding-right: 0px!important;}
                        table[class=fullWidth]			{width: 100%!important;}
                        table[class=mobileCenter]		{width: 100%!important; text-align: center!important; }
                        td[class=mobileCenter]			{width: 440px !important; text-align: center!important; }
                        span[class=eraseForMobile]		{width: 0; display:none !important;}
                        td[class=eraseForMobile]		{width: 0; display:none !important;}
                        table[class=eraseForMobile]		{width: 0; display:none !important;}
                        img[class=headerScale]			{width: 100%!important;}
                        td[class=headerBG]				{width: 100%!important; height: 130px!important; background-image: url(images/header_bg.jpg); background-size: 100% 95%; background-repeat: repeat-x;}
                        img[class=shadowScale]			{width: 100%!important;}
                        table[class=imageScale1]		{width: 46%!important; margin-right: 15px;}
                        table[class=imageScale2]		{width: 46%!important; margin-left: 15px;}
                        img[class=imageScale]			{width: 100%!important;}
                        table[class=smallImageScale]	{width: 30%!important; margin-right: 20px;}
                        table[class=smallImageScale2]	{width: 30%!important; margin-right: 0px;}
                        table[class=columnTableScale]	{width: 175px!important;}
                        img[class=columnImageScale]		{width: 175px!important; margin-left: 0px!important;}
                        table[class=columnScale]		{width: 58%!important; padding-left: 20px!important; padding-right: 10px!important;}
                        td[class=columnScale]			{width: 58%!important; padding-left: 10px!important; padding-right: 10px!important;}

                        table[class=columnScale2]		{width: 56%!important; padding-left: 0px!important; padding-right: 10px!important; text-align: left!important;}	
                        table[class=numberScale]		{width: 79%!important;}	
                        table[class=FooterScale1]		{width: 30%!important; text-align: left!important; margin-bottom: 40px!important; padding-left: 0px!important; margin-right: 0px!important;}
                        table[class=FooterScale2]		{width: 30%!important; text-align: left!important; margin-bottom: 40px!important; margin-left: 20px; margin-right: 20px!important;}
                        table[class=FooterScale3]		{width: 30%!important; text-align: left!important; margin-bottom: 40px!important;}

                        a[class=navPadLeft]				{margin-right: 40px!important; margin-left: 0px; }
                        a[class=navPadMiddle]			{margin-right: 40px!important; margin-left: 40px; }
                        a[class=navPadRight]			{margin-right: 0px!important; margin-left: 40px; }

                        }		


        @media only screen and (max-width: 479px) 
                           {
                        body{width:auto!important;}

                        table[class=scaleForMobile]		{width: 100%!important; padding-left: 30px!important; padding-right: 30px!important; }
                        table[class=scaleForMobile2]	{width: 100%!important; padding-left: 15px!important; padding-right: 15px!important; }
                        table[class=fullWidth]			{width: 100%!important;}
                        table[class=mobileCenter]		{width: 100%!important; text-align: center!important; }
                        td[class=mobileCenter]			{width: 280px!important; text-align: center!important; }
                        span[class=eraseForMobile]		{width: 0; display:none !important;}
                        td[class=eraseForMobile]		{width: 0; display:none !important;}
                        table[class=eraseForMobile]		{width: 0; display:none !important;}
                        td[class=headerBG] 				{width: 100%!important; height: 110px!important; background-color: #ffffff; background-image: url(images/header_bg.jpg); background-size: 100% 95%; background-repeat: repeat-x;}
                        table[class=imageScale1]		{width: 100%!important; margin-right: 0px!important;}
                        table[class=imageScale2]		{width: 100%!important; margin-left: 0px!important;}
                        img[class=imageScale]			{width: 100%!important;}
                        table[class=smallImageScale]	{width: 100%!important; margin-right: 0px!important;}
                        table[class=smallImageScale2]	{width: 100%!important; margin-right: 0px!important;}

                        img[class=columnImageScale]		{width: 100%!important; height: auto!important; margin-left: 0px!important; margin-right: 0px!important; margin-bottom: 0px!important; text-align: center!important;}
                        table[class=columnScale]		{width: 100%!important; text-align: center!important; padding: 0px!important; padding-top: 10px!important;}
                        table[class=columnTableScale]	{width: 100%!important;}
                        td[class=columnScale]			{width: 100%!important; padding: 0px!important;}
                        table[class=columnScale2]		{width: 100%!important; padding: 0px!important; margin-top: 10px!important;}
                        table[class=numberScale]		{width: 100%!important; margin-top: 15px!important;}
                        table[class=FooterScale1]		{width: 46%!important; text-align: left!important; margin-bottom: 40px!important; padding-left: 0px!important; margin-right: 0px!important;}
                        table[class=FooterScale2]		{width: 46%!important; text-align: right!important; margin-bottom: 40px!important; margin-left: 0px;}
                        table[class=FooterScale3]		{width: 100%!important; text-align: center!important; margin-bottom: 40px!important;}
                        td[class=FooterScale3]			{width: 100%!important; text-align: center!important; margin-bottom: 40px!important;}

                        a[class=navPadLeft]				{margin-right: 25px!important; margin-left: 0px; font-size: 12px!important; }
                        a[class=navPadMiddle]			{margin-right: 25px!important; margin-left: 25px; font-size: 12px!important; }
                        a[class=navPadRight]			{margin-right: 0px!important; margin-left: 25px; font-size: 12px!important; }

                        }


        </style>';
        $return_html .= '</head>';
        $return_html .= '<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
        $return_html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">';
        $return_html .= '<tr>';
        $return_html .= '<td width="100%" valign="top" bgcolor="#ffffff">';
        $return_html .= '<table width="590" border="0" cellpadding="0" cellspacing="0" align="center" class="scaleForMobile">';
        $return_html .= '<td bgcolor="ffffff" width="100%">';
        $return_html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">';
        $return_html .= '<tr><td height="10">&nbsp;</td></tr>';
        $return_html .= '</table><table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="mobileCenter">';
        $return_html .= '<tr><td>';
        $return_html .= '<table border="0" cellpadding="0" cellspacing="0" align="left" class="mobileCenter">';
        $return_html .= '<tr><td height="12"></td></tr>';
        $return_html .= '<tr><td height="50"><a href="#"><img editable="true" width="250" src="http://d1s8mqgwixvb29.cloudfront.net/static_bwdisrupt/images/full-Disrupt-logo.jpg" alt="" border="0"></a></td></tr>';
        $return_html .= '</table>';
        $return_html .= '<table border="0" cellpadding="0" cellspacing="0" align="right" class="mobileCenter">';
        $return_html .= '<tr><td height="12" class="eraseForMobile"></td></tr>';
        $return_html .= '</table>';
        $return_html .= '</td>';
        $return_html .= '</tr>';
        $return_html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">';
        $return_html .= '<tr><td height="20">&nbsp;</td></tr>';
        $return_html .= '</table>';
        $return_html .= '</td></tr>';
        $return_html .= '</table>';
        $return_html .= '<table width="590" border="0" cellpadding="0" cellspacing="0" align="center" class="scaleForMobile">';
        $return_html .= '<tr><td width="590" height="12"></td></tr>';
        $return_html .= '<tr><td width="590" style="font-size: 22px; color: #2f2f36; text-align: left; font-weight: bold; font-family: Helvetica, Arial, sans-serif; line-height: 30px;"><singleline>Dear&nbsp;'.$name .',</singleline></td></tr>';
        $return_html .= '<tr><td width="590" height="10"></td></tr>';
        $return_html .= '<tr><td width="590" style="font-size: 14px; color: #696a78; text-align: left; font-weight: normal; font-family: Helvetica, Arial, sans-serif; line-height: 26px;"><singleline>Your article titled <a href="'.$url.'" target="_blank">'.$articletitle.'&nbsp;(click here!)</a> has been published on BW Disrupt website.</singleline></td></tr>';
        $return_html .= '<tr><td width="590" height="30"></td></tr>';
        $return_html .= '<tr><td width="590" style="font-size: 14px; color: #696a78; text-align: left; font-weight: normal; font-family: Helvetica, Arial, sans-serif; line-height: 26px;"><b>What can you do now?</b></td></tr>'
                . '<tr><td width="590" style="font-size: 14px; color: #696a78; text-align: left; font-weight: normal; font-family: Helvetica, Arial, sans-serif; line-height: 26px;"><a style="text-decoration: none; color: inherit; display: block; width: 180px;" href="'.$url.'" target="_blank"><img src="http://d1s8mqgwixvb29.cloudfront.net/static/images/fb.png" alt="Facebook"/>&nbsp;&nbsp; <span style="float:right">Share it on Facebook</span></a></td></tr>'
                . '<tr><td width="590" style="font-size: 14px; color: #696a78; text-align: left; font-weight: normal; font-family: Helvetica, Arial, sans-serif; line-height: 26px;"><a style="text-decoration: none; color: inherit; display: block; width: 161px;" href="'.$url.'" target="_blank"><img src="http://d1s8mqgwixvb29.cloudfront.net/static/images/tw.png" alt="Twitter"/>&nbsp;&nbsp; <span style="float:right">Share it on Twitter</span></a></td></tr>'
                . '<tr><td width="590" style="font-size: 14px; color: #696a78; text-align: left; font-weight: normal; font-family: Helvetica, Arial, sans-serif; line-height: 26px;"><a style="text-decoration: none; color: inherit; display: block; width: 217px;" href="'.$url.'" target="_blank"><img src="http://d1s8mqgwixvb29.cloudfront.net/static/images/wht.png" alt="Whatsapp"/>&nbsp;&nbsp; <span style="float:right">Share it through Whatsapp</span></a></td></tr>';
        $return_html .= '<tr><td width="590" height="30"></td></tr>';
        $return_html .= '<tr><td width="590" style="font-size: 14px; color: #696a78; text-align: left; font-weight: normal; font-family: Helvetica, Arial, sans-serif; line-height: 26px;"><singleline>Looking forward to many such enriching contributions from you.</singleline></td></tr>';
        $return_html .= '<tr><td width="590" height="30"></td></tr>';
        $return_html .= '<tr><td style="font-family:Segoe,Segoe UI,DejaVu Sans,Trebuchet MS,Verdana,sans-serif !important;"><strong >Regards</strong></td> </tr>';
        $return_html .= '<tr><td style="font-family:Segoe,Segoe UI,DejaVu Sans,Trebuchet MS,Verdana,sans-serif !important;"><strong >BW Disrupt Editorial</strong></td> </tr>';
        $return_html .= '<tr><td width="590" height="30"></td></tr>';
        $return_html .= '<tr><td width="590" style="font-size: 14px; color: #696a78; text-align: left; font-weight: normal; font-family: Helvetica, Arial, sans-serif; line-height: 26px;"><singleline><i>This is a system generated email. Please do not reply to this mail. For any feedback about the article/process or otherwise, <a href="'.$urlcontact.'" target="_blank">(click here)</a> &nbsp;to contact us.</i></singleline></td></tr>';
        $return_html .= '<tr><td width="590" height="30"></td></tr>';
        $return_html .= '<tr><td style="font-family:Segoe,Segoe UI,DejaVu Sans,Trebuchet MS,Verdana,sans-serif !important;"><strong >Think <span style="color:#d92e35;">Business.</span> Think <span style="color:#d92e35;">BW Businessworld.</span></strong></td> </tr>';
        $return_html .= '<tr><td width="590" style="font-size: 12.5px; color: #696a78; text-align: left; border-top:1px solid #ccc; font-weight: normal; font-family: Helvetica, Arial, sans-serif; line-height: 26px;"><singleline>BW Businessworld Media Pvt. Ltd., Express Building 9-10 BSZ Marg, ITO , New Delhi, 110092</singleline></td></tr>';
        $return_html .= '</table>';
        $return_html .= '</td>';
        $return_html .= '</tr>';
        $return_html .= '</table>';
        $return_html .= '</body>';
        $return_html .= '</html>';
        //echo $return_html; exit;
       // mail("$email","Your article on BW has been published",$return_html,$headers);
        $this->sendSmtpMail($user_email,$email,'Your article on BW has been published',$return_html);
    }
    return true;
}
 function sendSmtpMail($from, $to, $subject, $message) {
        
        $smtp = Mail::factory('smtp', array('host' => 'smtp.sendgrid.net', 'port' => '2525', 'auth' => true, 'username' => 'godigital@businessworld.in', 'password' => 'bwdigital@1234'));
        $mime = new Mail_mime();
        $headers = array(
            'To' => $to,
            'From' => $from,
            'Subject' => $subject
        );
        $mime->setTXTBody($message);
        $mime->setHTMLBody($message);
        $mimeparams['text_encoding'] = "8bit";
        $mimeparams['text_charset'] = "UTF-8";
        $mimeparams['html_charset'] = "UTF-8";
        $mimeparams['head_charset'] = "UTF-8";
        $mimeparams["debug"] = "True";
        $body = $mime->get($mimeparams);
        $headers = $mime->headers($headers);
        $mail = $smtp->send($to, $headers, $body);
    }
// funtion to send sms to authors
function sendSms($url,$mob,$authorName,$articleTitle){
    //$mob='9899415606';
    $bitly_acesss_token='54faae0489a41d4c932f27cd7d5a060563bace93';
    $nimbus_username='businessworld';
    $nimbus_password='del12345';
    $url=  urlencode($url);
    $data=file_get_contents("https://api-ssl.bitly.com//v3/shorten?access_token=$bitly_acesss_token&longUrl=$url");
    $arr=json_decode($data);
    //echo $arr->data->url;exit;
    $message=urlencode("Hi $authorName, Your article : $articleTitle has been published. Url :".$arr->data->url); // $url";
    file_get_contents("http://203.212.70.200/smpp/sendsms?username=$nimbus_username&password=$nimbus_password&to=$mob&from=Bworld&text=$message&udh=&dlr-mask=19&dlr-url=http://www.mywebsite.com/myurl?myid=123456%26status=%25d%26updated_on=%25t%26res=%252");
    }
}

?>
