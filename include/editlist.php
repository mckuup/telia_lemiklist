<script>
	function get_fav_list(){
	var tlist = "";
	$("#sortable li").each(function() {
		if ($(this).attr('channel') === "0") {
			return false; //break
		}
		else{
		tlist += $(this).attr('channel') + ',';
		}
	});
	tlist = tlist.slice(0, -1);
	$("#chan_ids").val(tlist);
	}
	
  $( function() {
    $( "#sortable" ).sortable({
	update: function( event, ui ) { get_fav_list(); }})
    $( "#sortable" ).disableSelection();
  });
</script>
<?php
#salvestamine kui vaja
if ($_GET["act"] == "save"){
	$saveurl = "http://papi.mw.elion.ee/dtv-api/2.0/et/channel_list/".$_GET["id"]."/save";
	$saveq ="channel_ids=".$_POST["chan_ids"];
	$json = json_decode(cURL_data($saveurl, $saveq, $_SESSION["kypsis"]), false)[0];
	if ($json->success == True)
		echo '<span style="color:green;">Salvestatud!</span>';
	else
		echo '<span style="color:red;">Salvestamisel tekkis tõrge: '.$json->error_code.'</span>';
}
#listi tirimine küpsisega
$listurl = "http://papi.mw.elion.ee/dtv-api/2.0/et/channel_list";
$listq ="list_class=tv,radio&include_related_data=channels";
$json = json_decode(cURL_data($listurl.'?'.$listq, $q, $_SESSION["kypsis"]),false)[0];
if ($json->version != "2.0") die("Vale versioon: ".$json->version);
#Kõik kanalid
function get_all_chan($json){
	return $json->data->context_objects->channels;
}
#Tellitud kanalid
function get_ord_chan($json){
	$clist = $json->data->context_objects->channel_lists;
	foreach ($clist as $list){
		if ($list->title == "Tellitud kanalid" && !$list->user_list )
			return $list;
	}
	die('Ei leitud tellitud kanaleid. id 46 ei ole "Tellitud kanalid"');
}
#koostame listi
function parse_list($json, $allc, $ordc, $id){
	$favc = $json->data->context_objects->channel_lists->$id->channels;
	#esmalt lemmiklisti kanalid
	$inlist[0] = ""; #set array
	if (is_array($favc)){
		foreach ($favc as $kanal){ 
			$ret .= '<li channel="'.$kanal->id.'" class="chan"><img src="https://inet-static.mw.elion.ee/images/channels/90x40/'.$kanal->id.'.png"> '.$allc->{$kanal->id}->title.'</li>';
			$inlist[$kanal->id] = $kanal->id; #jätame meelde
			#https://inet-static.mw.elion.ee/images/channels/90x40/111.png
		}
	}
	$ret .= '<li channel="0" class="chan sep">-- Lemiklisti lõpp --</li>';
	#tellitud kanalid, mis pole lemmiknimekirjas
	foreach ($ordc->channels as $kanal) {
		if (!in_array($kanal->id, $inlist))
			$ret .= '<li channel="'.$kanal->id.'" class="chan"><img src="https://inet-static.mw.elion.ee/images/channels/90x40/'.$kanal->id.'.png"> '.$allc->{$kanal->id}->title.'</li>';
	}
	return $ret;
}
$allc = get_all_chan($json); #kõik kanalid
$ordc = get_ord_chan($json); #tellitud kanalid
?>
<script>
$('document').ready(function(){get_fav_list()});
</script>
<ul id="sortable">
<?php
echo parse_list($json, $allc, $ordc, $_GET["id"]);
?>
</ul>
<form method="post" action="?p=editlist&id=<?php echo $_GET["id"]; ?>&act=save">
<textarea id="chan_ids" name="chan_ids" style="height:150px;width:50%;"></textarea><br>
<input type="submit" value="Salvesta">
</form>
