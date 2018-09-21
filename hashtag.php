<?php
set_time_limit(0);
error_reporting(0);
define('HASHTAG_NAMESPACE', '#nha_loc_phat');/* Sửa hashtag tại đây , chữ thường không viết hoa*/
$token = 'EAAAAUaZA8jlABADRDhvtyO7faxjxgH2pGcagGnUVwAAsf1TYEZCmK7Jv6EbFG9r1udqyz7twaHnTcGnhY5RR35QvAbMC3ZCZATId0JF1OwsewzPffGJUmXCZAZB287ku2i8dQVMvZAFHXnAzI7EqCvG2z0aWxMrM3O78aNyEAEbxafX1vzvNU6L4oPwAD88i0YZD'; /* Sửa token tại đây */
$idgroup = '246283756077148'; /* Id Group */
$post = json_decode(request('https://graph.facebook.com/v2.9/' .$idgroup. '/feed?fields=id,message,created_time,from&limit=100&access_token=' . $token), true); /* Get Data Post*/
$timelocpost = date('Y-m-d');
$logpost     = file_get_contents("log.txt");
for ($i = 0; $i < 100; $i++) {
    $idpost      = $post['data'][$i]['id'];
    $messagepost = $post['data'][$i]['message'];
    $time        = $post['data'][$i]['created_time'];
	/* Check time Post */
    if (strpos($time, $timelocpost) !== false) {
		/* Check hashtag */
        if (strpos(strtolower($messagepost), HASHTAG_NAMESPACE) === FALSE) {
			/* Check trùng  */
            if (strpos($logpost, $idpost) === FALSE) {
				/* Send Comment  */
                $comment = '[nhalocphat.com.vn]' . "\n" . 'nhalocphat.vn' . $post['data'][$i]['from']['id'] . ':0] !' . "\n\n" . 'xxx<3xxx';
				request('https://graph.facebook.com/' . urlencode($idpost) . '/comments?attachment_url='.$image.'&method=post&message=' . urlencode($comment) . '&access_token=' . $token);
                $luulog = fopen("log.txt", "a");
                fwrite($luulog, $idpost . "\n");
                fclose($luulog);
            } else {
                echo 'Nhà Lộc Phát. ';
            }
        }
        
    }
}
exec("php test.php"); /* Chạy lại file  */
function request($url)
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return FALSE;
    }
    
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HEADER => FALSE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_ENCODING => '',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36',
        CURLOPT_AUTOREFERER => TRUE,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 0
    );
    
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    unset($options);
    return $http_code === 200 ? $response : FALSE;
}
?>