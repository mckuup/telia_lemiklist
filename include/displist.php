<?php 
#curliga boxi autoriseerimine
$authurl = "http://papi.mw.elion.ee/dtv-api/3.0/et/authentication/connection";
$post = 'mac='.$_POST["mac"].'&serial='.$_POST["serial"].'&model='.$_POST["model"].'&hw_version=&sw_version=&ui=dtv-frontend';
$_SESSION["kypsis"] = explode(";",cURL_headers($authurl,$post)["Set-Cookie"][0])[0];
echo ("<br>Küpsis:".$_SESSION["kypsis"].'<br>');
#listi tirimine küpsisega
$listurl = "http://papi.mw.elion.ee/dtv-api/2.0/et/channel_list";
$listq ="list_class=tv,radio&include_related_data=channels";
$json = json_decode(cURL_data($listurl.'?'.$listq, $q, $_SESSION["kypsis"]),false)[0];
if ($json->version != "2.0") die("Vale versioon:".$json->version);
function get_user_lists_ids($json){
	foreach ($json->data->context_objects->channel_lists as $list_id => $data) {
		if ($data->user_list){
			if ($data->list_class == "tv") 
			$ret .= '<a href="?p=editlist&id='.$data->id.'"> TV list: '.$data->title.' ID: '.$data->id.'</a><br>';
			elseif ($data->list_class == "radio")
			$ret .= 'Radio list: '.$data->title.' ID: '.$data->id.'<br>';
		}
	}
	return $ret;
}
echo(get_user_lists_ids($json));
?>