<?php
date_default_timezone_set('Asia/Calcutta');
define('HOST','130.211.250.132');
define('USER','root');
define('PASS','bw#digital#2017#cms');
define('DATABASE','bw_cms_db');


define('LHOST','104.199.166.236');
define('LUSER','root');
define('LPASS','bwchannelsuser2016');
define('LDATABASE','bw_po_db');
/*
date_default_timezone_set('Asia/Calcutta');
define('HOST','localhost');
define('USER','root');
define('PASS','admin');
define('DATABASE','10feb2016cms');


define('LHOST','localhost');
define('LUSER','root');
define('LPASS','admin');
define('LDATABASE','17novlivewebsite');
*/


mysql -h localhost -u root -p bw_po_db  < /home/sumit/dumps/Dump20171025.sql