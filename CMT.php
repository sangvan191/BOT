<?php
$like = "off"; //on hoặc off
$comments = "on";//on hoặc off
$log_post = @file_get_contents('log_id.txt');
$token= @file_get_contents('token.txt');
$limit = '20'; // số bài cần lái
get_UID($token);
$list_friend = @file_get_contents('friend.txt');
$all_type    = ["LOVE", "HAHA", "LIKE", "WOW"];//có 5 trạng thái
$stickerid =  ["1747089445602307","1747085962269322","1747082948936290","1747087128935872", "1747081105603141","416664085413098","1659201710991729","184002922217363","1601168630115055","792363667547441","131715684089706","193082804543990","364384103724895","416956522050521","387545828037968","488541347925977","178518209293200","178519069293114","178520939292927","1601168506781734","789355131153401","830546363633252","193082861210651","193083001210637","100004263211862","100001518861027","100014908207774","1302632663199038"]; // id của sticker
$array_comment = [" Cần tuyển nhân viên:
                  Sale Admin, Nhân viên kinh doanh BĐS, Quản lí nhân sự và Digital Marketing
                  Làm việc trong môi trường trẻ trung, năng động, chuyên nghiệp và hoà đồng
                  https://www.facebook.com/100028570185119/posts/113965526232453/ "];
$type  = $all_type[rand(0,4)]; //bạn có thể để random từ 0 -> 4 hoặc để số thay rand()
$idsticker = $stickerid[rand(0,count($stickerid)-1)];
$comment = $array_comment[rand(0,count($array_comment)-1)];
$id_nhom = ["123","456","789"]; //id nhóm không muốn bot


$link = "https://graph.facebook.com/me/home?order=chronological&limit=$limit&fields=id,from&access_token=$token";
$apilink = curl($link);
$datas = $apilink["data"];
foreach($datas as $each){
    $id_person = isset($each["from"]["id"]) ? $each["from"]["id"] : "";
    if (strpos($list_friend, $id_person)) {
        $id_post  = $each["id"];
        $log_id = fopen('log_id.txt', "a");
        fwrite($log_id, $id_post . "\n");
        fclose($log_id);
		$split   = explode("_", $id_post);
		$id_lay = $split[0];
		if (strpos($log_post, $id_post) === FALSE) {
		    if ($like == "on"){
        curl("https://graph.facebook.com/$id_post/reactions?type=$type&method=post&access_token=$token");
        }else
        {
            null;
        }
        if ($comments == "on"){
        curl("https://graph.facebook.com/$id_post/comments?attachment_id=$idsticker&message=$comment&method=post&access_token=$token");
        }else
        {
            null;
        }
			
		}
	}
}
function curl($url)
{
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 0,
	    CURLOPT_SSL_VERIFYPEER => false,
	    CURLOPT_SSL_VERIFYHOST => false
	));
	$response = curl_exec($curl);
	curl_close($curl);
	$response = json_decode($response,JSON_UNESCAPED_UNICODE);
	return $response;
}
function get_UID($acc_token)
{
    $get_friend = curl('https://graph.facebook.com/me?fields=friends&limit=5000&access_token=' . $acc_token);
    $list_idfriend = fopen('friend.txt', 'w+');
    $get_idfriend = $get_friend['friends']['data'];
    for ($i=0; $i < count($get_idfriend); $i++) { 
      fwrite($list_idfriend, $get_idfriend[$i]['id'] . "\n");
  }
  fclose($list_idfriend);
}
