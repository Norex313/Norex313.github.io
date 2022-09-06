<?php 
error_reporting(0); 
date_default_timezone_set('Asia/Tehran');
include "config.php";
include "jdf.php"; 
function bot($method,$datas=[]){
$url = "https://api.telegram.org/bot".API_KEY."/".$method;
 $ch = curl_init();
 curl_setopt($ch,CURLOPT_URL,$url);
 curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
 curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
 $res = curl_exec($ch);
 if(curl_error($ch)){
  var_dump(curl_error($ch));
 }else{ 
  return json_decode($res);
 }
} 
$update = json_decode(file_get_contents('php://input'));
$data = $update->callback_query->data;
$message = $update->message;
$text = $message->text;
$tc = $update->message->chat->type;
$first_name = $message->from->first_name;
$username = $message->from->username;
//----------------------------------------------------------------------
if(isset($data)){
$chat_id = $update->callback_query->message->chat->id;
$from_id = $update->callback_query->from->id;
$message_id = $update->callback_query->message->message_id;
} 
if(isset($message->from)){
$chat_id = $message->chat->id;
$from_id  = $message->from->id;
$message_id  = $message->message_id;
} 
$user = json_decode(file_get_contents("melat/$from_id.json"),true);
//=============================================================
$settings = json_decode(file_get_contents("settings.json"),true);
$bot_mode = $settings['bot_mode'];
$chupl = $settings['chupl'];
$timedel = $settings['timedel']; 
$time = date('H:i:s'); 
$ToDay = jdate('l'); 
$date = gregorian_to_jalali(date('Y'), date('m'), date('d'), '/');
//===============================================================
function saveJson($file,$data){
$new_data = json_encode($data,true);
file_put_contents($file,$new_data);
}
function Takhmin($fil){
if($fil <= 200 ){
return "2";
}else{
$besanie = $fil/200;
return ceil($besanie)+1;
}}
function convert($size){
return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2).' '.['', 'K', 'M', 'G', 'T', 'P'][$i].'B';
}
function doc($name) {
if ($name == "document") {
return "فایل";
}
if ($name == "video") {
return "ویدیو";
}
if ($name == "photo") {
return "عکس";
}
if ($name == "voice") {
return "ویس";
}
if ($name == "audio") {
return "موزیک";
}
if ($name == "sticker") {
return "استیکر";
}}
function getChatstats($chat_id,$token) {
$url = 'https://api.telegram.org/bot'.$token.'/getChatAdministrators?chat_id='.$chat_id;
$result = file_get_contents($url);
$result = json_decode ($result);
$result = $result->ok;
return $result;
}
function check_join($id){
$settings = json_decode(file_get_contents("settings.json"),true);
foreach ($settings['channel'] as $ch_id){
$okk = explode('https://t.me/',$ch_id)[1];
$type = bot("getChatMember" , ["chat_id" => "@$okk" , "user_id" => $id]);
$type = (is_object($type)) ? $type->result->status : $type['result']['status'];
if($type == 'creator' || $type ==  'administrator' || $type ==  'member'){
$in_ch[$ch_id] = $type;
}else{
return false;
break;
}}
return true;
}
function check_joins($id){
$settings = json_decode(file_get_contents("settings.json"),true);
foreach ($settings['channels'] as $links){
$ok0 = explode('^',$links)[0];
$type = bot("getChatMember" , ["chat_id" => "$ok0" , "user_id" => $id]);
$type = (is_object($type)) ? $type->result->status : $type['result']['status'];
if($type == 'creator' || $type ==  'administrator' || $type ==  'member'){
$in_ch[$links] = $type;
}else{
return false;
break;
}}
return true;
}
function faNum($n){
$r = "";
$n=$n+0;
if($n<0){
$n = 0-$n;
$r = "منفی ";
}
$l1 = ["صفر","یک","دو","سه","چهار","پنج","شش","هفت","هشت","نه"];
if($n<10){
$r.= $l1[$n];
}else{
$l2_ = ["ده","یازده","دوازده","سیزده","چهارده","پونزده","شونزده","هفده","هجده","نوزده"];
if($n<20){
$r .= $l2_[$n-10];
}else{
$l2 = ["","ده","بیست","سی","چهل","پنجاه","شصت","هفتاد","هشتاد","نود"];
if($n<100){
$b = $n % 10;
$a = ($n-$b)/10;
$r .= $l2[$a].($b==0?"":(" و ".fanum($b)));
}else{
$l3 = ["","صد","دویست","سیصد","چهارصد","پانصد","ششصد","هفتصد","هشتصد","نهصد"];
if($n<1000){
$b = $n % 100;
$a = ($n-$b)/100;
$r.=$l3[$a].($b==0?"":(" و ".fanum($b)));
}else{
if($n<2000){
$b = $n % 1000;
$a = ($n-$b)/1000;
$c = "";
if($a>1) $c = fanum($n)." ";
$r .= $c."هزار".($b==0?"":(" و ".fanum($b)));
}else{
$r .= "خطا";
}}}}}
return $r;
}
function is_join($from_id,$Channel){
$forchaneel = bot('getChatMember',[
'chat_id'=>"$Channel",
'user_id'=>$from_id]);
$tch = $forchaneel->result->status;
if($tch != 'member' and $tch != 'creator' and $tch != 'administrator' ){
return false;
}else{
return true; 
}}
if(file_exists("block")){
bot('sendMessage',[
'chat_id'=>$from_id,
'text'=>"این ربات توسط کارگروه تعیین مصادیق بلاک شده است",
 'parse_mode'=>"HTML",
 ]);
exit();   
}
date_default_timezone_set('Asia/Tehran'); 
$sharge = file_get_contents("Lite.txt");
$a = date('Y/m/d');
$b = "$sharge";
$sec = strtotime($b)-strtotime($a);
$days = $sec/86400;
$d0ays = explode('.',$days)[0];
if(1 > $d0ays){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"⚠️ زمان اشتراک ماهیانه ربات شما به پایان رسید.",
'parse_mode'=>"HTML",
]);
exit();
}
if($text == "/start" or $text == "🏠 برگشت به منو"){  
if(in_array($chat_id,$admins)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔥 | سلام کاربر عزیز ، به ربات آپلودر خوش آمدید.

❄️ | Welcom To Uploder
🔸 | id : $chat_id",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"👤پنل مدیریت👤"]],
],
'resize_keyboard'=>true
])
]);
if(!is_file("melat/$from_id.json")){
$user['step'] = 'none';
$user['step2'] = 'none';
$user['step3'] = 'none';
$user['step4'] = 'none';
$user['step5'] = '0';
saveJson("melat/$from_id.json",$user);
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔥 | سلام کاربر عزیز ، به ربات آپلودر خوش آمدید.

❄️ | Welcom To Uploder
🔸 | id : $chat_id",
'parse_mode'=>"HTML",
]);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}}
//#####################################################################
if(strpos($text,"/start dl_") !== false and $text != "/start" and $tc == "private"){
if(!is_file("melat/$from_id.json")){
$user['step'] = 'none';
$user['step2'] = 'none';
$user['step3'] = 'none';
$user['step4'] = 'none';
$user['step5'] = '0';
saveJson("melat/$from_id.json",$user);
}
$edit = str_replace("/start dl_","",$text);
$files = json_decode(file_get_contents("files/$edit.json"),true);
$fil = count($settings['channel']);
$fils = count($settings['channels']);
if($files['ghfl_ch'] == "on" && $fil != 0 or $fils != 0 and check_join("$from_id")!='true' or check_joins("$from_id")!='true'){
$by = 1;
$link = null;
foreach($settings['channel'] as $link){
$okk = explode('https://t.me/',$link)[1];
if(is_join($from_id,"@$okk") == false ){
$bys = faNum($by);
$d4[] = [['text'=>"ورود به چنل $bys",'url'=>$link]];
$by++;
}}
$links = null;
foreach($settings['channels'] as $links){
$ok0 = explode('^',$links)[0];
$ok1 = explode('^',$links)[1];
if(is_join($from_id,$ok0) == false ){
$bys = faNum($by);
$d4[] = [['text'=>"ورود به چنل $bys",'url'=>$ok1]];
$by++;
}}
$d4[] = [['text'=>"✅ عضو شدم",'callback_data'=>"taid_$edit"]];
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"به ربات آپلودر ما خوش آمدید🙏🏻🌹

جهت دریافت ، ابتدا وارد چنل های زیر شوید.

⭕️ بعد از عضویت در همه چنل ها روی 'تایید عضویت' کلیک کنید تا برای شما ارسال شود",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'inline_keyboard'=>$d4
])]);
}else{
$files = json_decode(file_get_contents("files/$edit.json"),true);
if($files['file_id'] != null ){
if($files['pass'] == 'none' ){
if($files['mahdodl'] == 'none' ){
$file_size = $files['file_size'];
$file_id = $files['file_id'];
$file_type = $files['file_type'];
$zaman = $files['zaman'];
$tozihat = $files['tozihat'];
$dl = $files['dl'];
$id = $files['id'];
$type = doc($file_type);
$bash = $dl + 1;
$id = bot('send'.$files['file_type'], [
'chat_id' => $chat_id,
"$file_type" => $file_id,
'caption' => "$tozihat

📥 تعداد دانلود : $bash",
'reply_to_message_id' => $message_id,
'parse_mode' => "HTML",
])->result;
$msg_iddd = $id->message_id;
if($files['zd_filter'] == "on"){
$timesdel = strtotime("+{$settings['timedel']} minutes");
$dlms = json_decode(file_get_contents("dbremove/$msg_iddd.json"),true);
$dlms['id'] = "$from_id";
$dlms['message_id'] = "$msg_iddd";
$dlms['time'] = "$timesdel";
saveJson("dbremove/$msg_iddd.json",$dlms);
$isdeltime = $settings['timedel'];
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"⭕️👆👆👆⭕️
پیام بالا را هر چه سریعتر به قسمت پیام های ذخیره شده خود( Saved Message ) فوروارد کنید !

این پیام تا $isdeltime دقیقه دیگر حذف خواهد شد .",
'parse_mode'=>"HTML",
]);}
$files['dl'] = "$bash";
saveJson("files/$edit.json",$files);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}else{
if($files['dl'] != $files['mahdodl'] and $files['dl'] + 0.1 < $files['mahdodl']){
$file_size = $files['file_size'];
$file_id = $files['file_id'];
$file_type = $files['file_type'];
$zaman = $files['zaman'];
$tozihat = $files['tozihat'];
$dl = $files['dl'];
$id = $files['id'];
$type = doc($file_type);
$bash = $dl + 1;
 $id = bot('send'.$files['file_type'], [
'chat_id' => $chat_id,
"$file_type" => $file_id,
'caption' => "$tozihat

📥 تعداد دانلود : $bash",
'reply_to_message_id' => $message_id,
'parse_mode' => "HTML",
  ])->result;  
  $msg_iddd = $id->message_id;
  if($files['zd_filter'] == "on"){
  $timesdel = strtotime("+{$settings['timedel']} minutes");
$dlms = json_decode(file_get_contents("dbremove/$msg_iddd.json"),true);
$dlms['id'] = "$from_id";
$dlms['message_id'] = "$msg_iddd";
$dlms['time'] = "$timesdel";
saveJson("dbremove/$msg_iddd.json",$dlms);
  $isdeltime = $settings['timedel'];
  bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"⭕️👆👆👆⭕️
پیام بالا را هر چه سریعتر به قسمت پیام های ذخیره شده خود( Saved Message ) فوروارد کنید !

این پیام تا $isdeltime دقیقه دیگر حذف خواهد شد .",
'parse_mode'=>"HTML",
]);
  }
 $files['dl'] = "$bash";
saveJson("files/$edit.json",$files);
 $user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
 }else{  
 bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ این فایل به حداکثر دانلود رسیده است .",
'parse_mode'=>"HTML",
]);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
 }
 } 
 }else{
 bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔐 این محتوا حاوی پسورد است !

- لطفا رمز دسترسی را وارد کنید:",
'parse_mode'=>"HTML",
]);
$user['step'] = "khiiipassz_$edit";
saveJson("melat/$from_id.json",$user);
 }
  }
  }
  }
elseif(strpos($data,"taid_") !== false ){ 
$ok = str_replace("taid_",null,$data);
$files = json_decode(file_get_contents("files/$ok.json"),true);
$fil = count($settings['channel']);
$fils = count($settings['channels']);
if($files['ghfl_ch'] == "on" && $fil != 0 or $fils != 0 and check_join("$from_id")!='true' or check_joins("$from_id")!='true'){
bot('answercallbackquery', [
  'callback_query_id' => $update->callback_query->id,
'text' => "هنوز در چنل جوین نشده اید !",
  'show_alert' => false
 ]);
}else{
bot('editMessagetext',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"✅ عضویت شما تایید شد .",
 'parse_mode'=>"HTML",
]);  
$files = json_decode(file_get_contents("files/$ok.json"),true);
 if($files['pass'] == 'none' ){
 if($files['mahdodl'] == 'none' ){
$files = json_decode(file_get_contents("files/$ok.json"),true);
$file_size = $files['file_size'];
$file_id = $files['file_id'];
$file_type = $files['file_type'];
$zaman = $files['zaman'];
$tozihat = $files['tozihat'];
$dl = $files['dl'];
$id = $files['id'];
$type = doc($file_type);
$bash = $dl + 1;
  $id = bot('send'.$files['file_type'], [
'chat_id' => $chat_id,
"$file_type" => $file_id,
'caption' => "$tozihat

📥 تعداد دانلود : $bash",
'reply_to_message_id' => $message_id,
'parse_mode' => "HTML",
  ])->result;
  $msg_iddd = $id->message_id;
  if($files['zd_filter'] == "on"){
  $timesdel = strtotime("+{$settings['timedel']} minutes");
$dlms = json_decode(file_get_contents("dbremove/$msg_iddd.json"),true);
$dlms['id'] = "$from_id";
$dlms['message_id'] = "$msg_iddd";
$dlms['time'] = "$timesdel";
saveJson("dbremove/$msg_iddd.json",$dlms);
  $isdeltime = $settings['timedel'];
  bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"⭕️👆👆👆⭕️
پیام بالا را هر چه سریعتر به قسمت پیام های ذخیره شده خود( Saved Message ) فوروارد کنید !

این پیام تا $isdeltime دقیقه دیگر حذف خواهد شد .",
'parse_mode'=>"HTML",
]);
}
$files['dl'] = "$bash";
saveJson("files/$ok.json",$files);
 $user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
 }else{
 if($files['dl'] != $files['mahdodl'] and $files['dl'] + 0.1 < $files['mahdodl']){
 $file_size = $files['file_size'];
$file_id = $files['file_id'];
$file_type = $files['file_type'];
$zaman = $files['zaman'];
$tozihat = $files['tozihat'];
$dl = $files['dl'];
$id = $files['id'];
$type = doc($file_type);
$bash = $dl + 1;
  $id =  bot('send'.$files['file_type'], [
'chat_id' => $chat_id,
"$file_type" => $file_id,
'caption' => "$tozihat

📥 تعداد دانلود : $bash",
'reply_to_message_id' => $message_id,
'parse_mode' => "HTML",
  ])->result;
  $msg_iddd = $id->message_id;
  if($files['zd_filter'] == "on"){
  $timesdel = strtotime("+{$settings['timedel']} minutes");
$dlms = json_decode(file_get_contents("dbremove/$msg_iddd.json"),true);
$dlms['id'] = "$from_id";
$dlms['message_id'] = "$msg_iddd";
$dlms['time'] = "$timesdel";
saveJson("dbremove/$msg_iddd.json",$dlms);
  $isdeltime = $settings['timedel'];
  bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"⭕️👆👆👆⭕️
پیام بالا را هر چه سریعتر به قسمت پیام های ذخیره شده خود( Saved Message ) فوروارد کنید !

این پیام تا $isdeltime دقیقه دیگر حذف خواهد شد .",
'parse_mode'=>"HTML",
]);
  }
 $files['dl'] = "$bash";
saveJson("files/$ok.json",$files);
 $user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
 }else{
 bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ این فایل به حداکثر دانلود رسیده است .",
'parse_mode'=>"HTML"
]);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
 }
 }
 }else{  
 bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔐 این محتوا حاوی پسورد است !

- لطفا رمز دسترسی را وارد کنید:",
'parse_mode'=>"HTML",
]);
$user['step'] = "khiiipassz_$ok";
saveJson("melat/$from_id.json",$user);
 }
} 
}
elseif(strpos($user['step'],"khiiipassz_") !== false and strpos($text,"start") === false ){
$ok = str_replace("khiiipassz_",null,$user['step']);
$files = json_decode(file_get_contents("files/$ok.json"),true);
if($files['pass'] != 'none'){
if($text == $files['pass']){
if($files['mahdodl'] == "none"){
$file_size = $files['file_size'];
$file_id = $files['file_id'];
$file_type = $files['file_type'];
$zaman = $files['zaman'];
$tozihat = $files['tozihat'];
$dl = $files['dl'];
$id = $files['id'];
$type = doc($file_type);
$bash = $dl + 1;
  $id = bot('send'.$files['file_type'], [
'chat_id' => $chat_id,
"$file_type" => $file_id,
'caption' => "$tozihat

📥 تعداد دانلود : $bash",
'reply_to_message_id' => $message_id,
'parse_mode' => "HTML",
  ])->result;
  $msg_iddd = $id->message_id;
  if($files['zd_filter'] == "on"){
  $timesdel = strtotime("+{$settings['timedel']} minutes");
$dlms = json_decode(file_get_contents("dbremove/$msg_iddd.json"),true);
$dlms['id'] = "$from_id";
$dlms['message_id'] = "$msg_iddd";
$dlms['time'] = "$timesdel";
saveJson("dbremove/$msg_iddd.json",$dlms);
  $isdeltime = $settings['timedel'];
  bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"⭕️👆👆👆⭕️
پیام بالا را هر چه سریعتر به قسمت پیام های ذخیره شده خود( Saved Message ) فوروارد کنید !

این پیام تا $isdeltime دقیقه دیگر حذف خواهد شد .",
'parse_mode'=>"HTML",
]);
  } 
 $files['dl'] = "$bash";
saveJson("files/$ok.json",$files);
 $user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
 }else{
 if($files['dl'] != $files['mahdodl'] and $files['dl'] + 0.1 < $files['mahdodl']){
 $file_size = $files['file_size'];
$file_id = $files['file_id'];
$file_type = $files['file_type'];
$zaman = $files['zaman'];
$tozihat = $files['tozihat'];
$dl = $files['dl'];
$id = $files['id'];
$type = doc($file_type);
$bash = $dl + 1;
  $id =  bot('send'.$files['file_type'], [
'chat_id' => $chat_id,
"$file_type" => $file_id,
'caption' => "$tozihat

📥 تعداد دانلود : $bash",
'reply_to_message_id' => $message_id,
'parse_mode' => "HTML",
  ])->result;
  $msg_iddd = $id->message_id;
  if($files['zd_filter'] == "on"){
  $timesdel = strtotime("+{$settings['timedel']} minutes");
$dlms = json_decode(file_get_contents("dbremove/$msg_iddd.json"),true);
$dlms['id'] = "$from_id";
$dlms['message_id'] = "$msg_iddd";
$dlms['time'] = "$timesdel";
saveJson("dbremove/$msg_iddd.json",$dlms);
  $isdeltime = $settings['timedel'];
  bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"⭕️👆👆👆⭕️
پیام بالا را هر چه سریعتر به قسمت پیام های ذخیره شده خود( Saved Message ) فوروارد کنید !

این پیام تا $isdeltime دقیقه دیگر حذف خواهد شد .",
'parse_mode'=>"HTML",
]);
  }
 $files['dl'] = "$bash";
saveJson("files/$ok.json",$files);
 $user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
 }else{
 bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ این فایل به حداکثر دانلود رسیده است .",
'parse_mode'=>"HTML",
]);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
 }
 }
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ پسورد نامعتبر است !

❗️ لطفا پسورد را بدرستی ارسال کنید:",
'parse_mode'=>"HTML",
]);
} 
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"⭕️ این فایل دیگر پسورد ندارد.

لطفا یکبار دیگر با لینک وارد شوید:

https://telegram.me/$bottag?start=dl_$ok",
'parse_mode'=>"HTML",
]);
}}
//##############################################################
elseif($text == "👤پنل مدیریت👤" and in_array($from_id,$admins)){
 if(!is_file("melat/$from_id.json")){
$user['step'] = 'none';
$user['step2'] = 'none';
$user['step3'] = 'none';
$user['step4'] = 'none';
$user['step5'] = '0';
saveJson("melat/$from_id.json",$user);
}else{
$user['step'] = 'none';
$user['step2'] = 'none';
$user['step3'] = 'none';
$user['step4'] = 'none';
$user['step5'] = '0';
saveJson("melat/$from_id.json",$user);
  }
  bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"◾️ ادمین گرامی ، به پنل مدیریتی خوش آمدید.",
'parse_mode'=>"HTML",
 'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"👥 | آمار ربات"],['text'=>"📣 | تغییر قفل چنل"]],
[['text'=>"📨 | فوروارد همگانی"],['text'=>"📨 | پیام همگانی"]],
[['text'=>"ℹ️ | اطلاعات آپلود"],['text'=>"📥 | آپلود رسانه"]],
[['text'=>"⏱ | تاریخچه اپلود"],['text'=>"❎ | حذف رسانه"]],
[['text'=>"🔒 | تنظیم پسورد"],['text'=>"🚷 | محدودیت دانلود"]],
[['text'=>"💫 | تنظیم قفل آپلود"],['text'=>"🔥 | تنظیم ضد فیلتر"]],
[['text'=>"📛 | تنظیم تایم حذف"]],
[['text'=>"♻️| بروزرسانی"],['text'=>"⏳| اشتراک باقی مانده"]],
[['text'=>"🏠 برگشت به منو"]],
],
'resize_keyboard'=>true
])
]);
}
//##############################################################
elseif($text == "🔙 منوی پنل" and in_array($from_id,$admins)){  
 if(!is_file("melat/$from_id.json")){
$user['step'] = 'none';
$user['step2'] = 'none';
$user['step3'] = 'none';
$user['step4'] = 'none';
$user['step5'] = '0';
saveJson("melat/$from_id.json",$user);
}else{
$user['step'] = 'none';
$user['step2'] = 'none';
$user['step3'] = 'none';
$user['step4'] = 'none';
$user['step5'] = '0';
saveJson("melat/$from_id.json",$user);
  } 
  bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"◾️ ادمین گرامی ، به پنل مدیریتی خوش آمدید.",
'parse_mode'=>"HTML",
 'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"👥 | آمار ربات"],['text'=>"📣 | تغییر قفل چنل"]],
[['text'=>"📨 | فوروارد همگانی"],['text'=>"📨 | پیام همگانی"]],
[['text'=>"ℹ️ | اطلاعات آپلود"],['text'=>"📥 | آپلود رسانه"]],
[['text'=>"⏱ | تاریخچه اپلود"],['text'=>"❎ | حذف رسانه"]],
[['text'=>"🔒 | تنظیم پسورد"],['text'=>"🚷 | محدودیت دانلود"]],
[['text'=>"💫 | تنظیم قفل آپلود"],['text'=>"🔥 | تنظیم ضد فیلتر"]],
[['text'=>"📛 | تنظیم تایم حذف"]],
[['text'=>"♻️| بروزرسانی"],['text'=>"⏳| اشتراک باقی مانده"]],
[['text'=>"🏠 برگشت به منو"]],
],
'resize_keyboard'=>true
])
]);
  }
  //##############################################################
  elseif(strpos($data,"pnlzdfilter_") !== false ){
 if(in_array($chat_id,$admins)){
$ok = str_replace("pnlzdfilter_",null,$data);
$files = json_decode(file_get_contents("files/$ok.json"),true);
if($files['code'] != null ){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"💎 لطفا انتخاب کنید 

️ ℹ️ فایل شماره : <code>$ok</code>
👇🏻 ضد فیلتر برای کد آپلود بالا روشن/خاموش شود",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"❌ خاموش"],['text'=>"✅ روشن"]],
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = "setzdfilpn_$ok";
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ | این کد آپلود وجود ندارد و یا حذف شده.

🔄 | لطفا دوباره امتحان کنید :",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif(strpos($user['step'],"setzdfilpn_") !== false and $text != '🔙 منوی پنل'){
 if(in_array($chat_id,$admins)){
$ok = str_replace("setzdfilpn_",null,$user['step']);
if($text == "❌ خاموش" or $text == "✅ روشن" ){
if($text == "✅ روشن"){
$oonobbin = "on";
$textttt = "روشن";
}
if($text == "❌ خاموش"){
$oonobbin = "off";
$textttt = "خاموش";
} 
$files = json_decode(file_get_contents("files/$ok.json"),true);
if($files['zd_filter'] != $oonobbin ){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"💥 ضد فیلتر برای کد آپلود ( $ok ) با موفقیت $textttt شد !",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$files['zd_filter'] = "$oonobbin";
saveJson("files/$ok.json",$files);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔹 ضد فیلتر برای کد آپلود ( $ok ) قبلا $textttt بود!",
'parse_mode'=>"HTML",
]);
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ لطفا فقط یکی از گزینه های کیبورد را انتخاب کنید :",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif(strpos($data,"ghdpnl_") !== false ){ 
 if(in_array($chat_id,$admins)){
$ok = str_replace("ghdpnl_",null,$data);
$files = json_decode(file_get_contents("files/$ok.json"),true);
if($files['code'] != null ){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"💎 لطفا انتخاب کنید 

️ ℹ️ فایل شماره : <code>$ok</code>
👇🏻 قفل چنل برای کد آپلود بالا روشن/خاموش شود",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"❌ خاموش"],['text'=>"✅ روشن"]],
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]); 
$user['step'] = "setghfpnl_$ok";
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ | این کد آپلود وجود ندارد و یا حذف شده.

🔄 | لطفا دوباره امتحان کنید :",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif(strpos($user['step'],"setghfpnl_") !== false and $text != '🔙 منوی پنل'){
 if(in_array($chat_id,$admins)){
$ok = str_replace("setghfpnl_",null,$user['step']);
if($text == "❌ خاموش" or $text == "✅ روشن" ){
if($text == "✅ روشن"){
$oonobbin = "on";
$textttt = "روشن";
}
if($text == "❌ خاموش"){
$oonobbin = "off";
$textttt = "خاموش";
}
$files = json_decode(file_get_contents("files/$ok.json"),true);
if($files['ghfl_ch'] != $oonobbin ){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"💥 قفل چنل برای کد آپلود ( $ok ) با موفقیت $textttt شد !",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$files['ghfl_ch'] = "$oonobbin";
saveJson("files/$ok.json",$files);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔹 ضد فیلتر برای کد آپلود ( $ok ) قبلا $textttt بود!",
'parse_mode'=>"HTML",
]);
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ لطفا فقط یکی از گزینه های کیبورد را انتخاب کنید :",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif($text=="🔥 | تنظیم ضد فیلتر" ){
 if(in_array($chat_id,$admins)){
 bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔹 | لطفا کد آپلود را ارسال کنید :",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])]);
$user['step'] = 'setzdfilll';
saveJson("melat/$from_id.json",$user);
}}
elseif($user['step'] == "setzdfilll" and $text != '🔙 منوی پنل'){
if(in_array($chat_id,$admins)){
$files = json_decode(file_get_contents("files/$text.json"),true);
if($files['code'] != null ){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"💎 لطفا انتخاب کنید 

️ ℹ️ فایل شماره : <code>$text</code>
👇🏻 ضد فیلتر برای کد آپلود بالا روشن/خاموش شود",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"❌ خاموش"],['text'=>"✅ روشن"]],
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])]);
$user['step'] = "setzdfilpn_$text";
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ | این کد آپلود وجود ندارد و یا حذف شده.

🔄 | لطفا دوباره امتحان کنید :",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif($text=="💫 | تنظیم قفل آپلود" ){
if(in_array($chat_id,$admins)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔹 | لطفا کد آپلود را ارسال کنید :",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])]);
$user['step'] = 'setgfup';
saveJson("melat/$from_id.json",$user);
}}
elseif($user['step'] == "setgfup" and $text != '🔙 منوی پنل'){
if(in_array($chat_id,$admins)){
$files = json_decode(file_get_contents("files/$text.json"),true);
if($files['code'] != null ){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"💎 لطفا انتخاب کنید 

️ ℹ️ فایل شماره : <code>$text</code>
👇🏻 قفل چنل برای کد آپلود بالا روشن/خاموش شود",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"❌ خاموش"],['text'=>"✅ روشن"]],
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])]); 
$user['step'] = "setghfpnl_$text";
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ | این کد آپلود وجود ندارد و یا حذف شده.

🔄 | لطفا دوباره امتحان کنید :",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif($text=="📛 | تنظیم تایم حذف" ){ 
if(in_array($chat_id,$admins)){
 bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"✅ لطفا تعداد دقیقه حذف فایل را از کیبورد انتخاب کنید ( در صورتی که بعد آپلود گزینه ضد فیلتر را بزنید ، بعد دقیقه مشخص از پی وی کاربر حذف میشود )

🔹 مقدار پیشفرض : 1 دقیقه
🔸 مقدار فعلی : $timedel دقیقه

👇 لطفا از کیبورد انتخاب کنید :",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"1"],['text'=>"2"],['text'=>"3"],['text'=>"4"],['text'=>"5"]],
[['text'=>"10"],['text'=>"15"],['text'=>"30"]],
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])]);
$user['step'] = 'setdeltime';
saveJson("melat/$from_id.json",$user);
}}
elseif($user['step'] == "setdeltime" and $text != "🔙 منوی پنل" ){
$array5 = [1,2,3,4,5,10,15,30];
if(in_array($text,$array5)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"✅ با موفقیت تنظیم شد .

مقدار جدید : $text دقیقه",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])]);
$settings['timedel'] = "$text";
saveJson("settings.json",$settings);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ لطفا فقط از کیبورد انتخاب کنید 👇🏻",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"1"],['text'=>"2"],['text'=>"3"],['text'=>"4"],['text'=>"5"]],
[['text'=>"10"],['text'=>"15"],['text'=>"30"]],
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])]);
}}
//##############################################################
elseif($text=="📣 | تغییر قفل چنل" ){
if(in_array($chat_id,$admins)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ به بخش تنظیم چنل های قفل خوش آمدید.

💯 برای حذف چنل، از بخش لیست چنل چنل مورد نظر را حذف کنید .",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"➕ افزودن چنل"]],
[['text'=>"🔙 منوی پنل"],['text'=>"📚 لیست چنل ها"]],
],
'resize_keyboard'=>true
])]);
}}
//##############################################################
elseif($text=="➕ افزودن چنل" ){
if(in_array($chat_id,$admins)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"لطفا نوع چنلی که میخواهید اضافه کنید را از کیبورد انتخاب کنید :",
'parse_mode'=>"HTML",
 'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"عمومی"],['text'=>"خصوصی"]],
[['text'=>"🔙 منوی پنل"]],
 ],
'resize_keyboard'=>true
])
]);
}}

elseif($text=="خصوصی" ){
if(in_array($chat_id,$admins)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"در خط اول ایدی عددی چنل و خط دوم لینک خصوصی چنل
نمونه ارسالی : 
-1009876262727
https://t.me/+lbw9Mfrmqvc1NTg0

ربات را قبل ارسال حتما ادمین کرده باشید.",
'parse_mode'=>"HTML",
 'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
 ],
'resize_keyboard'=>true
])
]);
$user['step'] = 'addchp0ub';
saveJson("melat/$from_id.json",$user);
}}
elseif($user['step'] == "addchp0ub" and $text != "🔙 منوی پنل" and !$data ){ 
if(in_array($chat_id,$admins)){
$ok0 = explode("\n",$text)[0];
$ok1 = explode("\n",$text)[1];
if(!in_array($text,$settings['channels'])){
$admini = getChatstats("$ok0",API_KEY);
if($admini == true ){
$linkk = "$ok0^$ok1";
$settings['channels'][] = "$linkk";
saveJson("settings.json",$settings);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"چنل $ok0 با موفقیت افزوده شد .",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"➕ افزودن چنل"]],
[['text'=>"🔙 منوی پنل"],['text'=>"📚 لیست چنل ها"]],
],
'resize_keyboard'=>true
])]); 
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"خطا ! ربات بر چنل $ok0 آدمین نیست !

ابتدا ربات را ادمین و سپس ارسال کنید تا افزوده شود.",
'parse_mode'=>"HTML",
]);
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"خطا ! قبلا چنلی با این ایدی ثبت شده !

لطفا دوباره ارسال فرمایید :",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif($text=="عمومی" ){
if(in_array($chat_id,$admins)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"لطفا یوزرنیم چنل عمومی را بدون @ ارسال کنید ( ربات را قبل ارسال بر ان چنل آدمین کرده باشید )",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])]);
$user['step'] = 'addchpub';
saveJson("melat/$from_id.json",$user);
}}
elseif($user['step'] == "addchpub" and $text != "🔙 منوی پنل" and !$data ){ 
if(in_array($chat_id,$admins)){
$textt = str_replace("@",null,$text);
$texttt = "https://t.me/$textt";
if(!in_array($texttt,$settings['channel'])){
$admini = getChatstats("@$textt",API_KEY);
if($admini == true ){
$linkk = "https://t.me/$textt";
$settings['channel'][] = "$linkk";
saveJson("settings.json",$settings);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"چنل @$textt با موفقیت افزوده شد .",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"➕ افزودن چنل"]],
[['text'=>"🔙 منوی پنل"],['text'=>"📚 لیست چنل ها"]],
],
'resize_keyboard'=>true
])]); 
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"خطا ! ربات بر چنل @$textt آدمین نیست !

ابتدا ربات را ادمین و سپس ارسال کنید تا افزوده شود.",
'parse_mode'=>"HTML",
]);
}}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"خطا ! قبلا چنلی با این ایدی ثبت شده !

لطفا دوباره ارسال فرمایید :",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif($text=="📚 لیست چنل ها" ){  
 if(in_array($chat_id,$admins)){
$by = 1;
$link = null;
foreach($settings['channel'] as $link){
$okk = explode('https://t.me/',$link)[1];
$bys = faNum($by);
$d4[] = [['text'=>"چنل شماره $bys",'url'=>"$link"],['text'=>"❌ حذف",'callback_data'=>"delc_$okk"]];
$by++;
}
$links = null;
foreach($settings['channels'] as $links){
$ok0 = explode('^',$links)[0];
$ok1 = explode('^',$links)[1];
$bys = faNum($by);
$d4[] = [['text'=>"چنل شماره $bys",'url'=>"$ok1"],['text'=>"❌ حذف",'callback_data'=>"delc_$ok0"]];
$by++;
}}
if($link != null or $links != null){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"👇🏻 لیست تمام چنل های قفل",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'inline_keyboard'=>$d4
])
]);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ هیچ چنل قفلی تنظیم نشده.",
'parse_mode'=>"HTML",
]); 
}}
//##############################################################
elseif(strpos($data,"delc_") !== false ){
if(in_array($chat_id,$admins)){
$ok = str_replace("delc_",null,$data);
$links = null;
foreach($settings['channels'] as $links){
$ok0 = explode('^',$links)[0];
$ok1 = explode('^',$links)[1];
}
if($ok == "$ok0"){
$linkk = "$ok0^$ok1";
$search = array_search($linkk,$settings['channels']);
unset($settings['channels'][$search]);
$settings['channels'] = array_values($settings['channels']);
saveJson("settings.json",$settings);
}
if(in_array($ok,$settings['channel'])){
$linkk = "https://t.me/$ok";
$search = array_search($linkk,$settings['channel']);
unset($settings['channel'][$search]);
$settings['channel'] = array_values($settings['channel']);
saveJson("settings.json",$settings);
}
$settings = json_decode(file_get_contents("settings.json"),true);
$fil = count($settings['channel']);
$fils = count($settings['channels']);
if($fil == 0 and $fils == 0){
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "✅ چنل حذف شد .",
'show_alert' => false
]);
bot('editMessagetext',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"👇🏻 لیست تمام چنل های قفل

❌ تمام چنل ها حذف شده است.",
'parse_mode'=>"HTML",
]); 
}else{
$by = 1;
foreach($settings['channel'] as $link){
$okk = explode('https://t.me/',$link)[1];
$d4[] = [['text'=>"چنل شماره $by",'url'=>$link],['text'=>"❌ حذف",'callback_data'=>"delc_$okk"]];
$by++;
}
$links = null;
foreach($settings['channels'] as $links){
$ok0 = explode('^',$links)[0];
$ok1 = explode('^',$links)[1];
$bys = faNum($by);
$d4[] = [['text'=>"چنل شماره $bys",'url'=>$ok1],['text'=>"❌ حذف",'callback_data'=>"delc_$ok0"]];
$by++;
}
bot('editMessagetext',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"👇🏻 لیست تمام چنل های قفل",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'inline_keyboard'=>$d4
  ])
]); 
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "✅ چنل حذف شد .",
'show_alert' => false
]);
}}}
//##############################################################
elseif($text == "🚷 | محدودیت دانلود"){
 if(in_array($chat_id,$admins)){
 bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"💯 لطفا شماره آپلود را ارسال کنید:",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = 'getcodeuu';
saveJson("melat/$from_id.json",$user);
}
}
elseif($user['step'] == "getcodeuu" and $text != "🔙 منوی پنل"){
 if(in_array($chat_id,$admins)){
$files = json_decode(file_get_contents("files/$text.json"),true);
 if($files['code'] != null and is_numeric($text) == true ){
 if($files['mahdodl'] != 'none'){
 $khi = '❌ برداشتن محدودیت';
 }else{
 $khi = null;
 }
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"✅ لطفا حداکثر تعداد دانلود فایل شماره $text را بصورت عدد لاتین (123) وارد فرمایید:",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"],['text'=>"$khi"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = "newpmah_$text";
saveJson("melat/$from_id.json",$user);
 }else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ این کد آپلود یافت نشد و یا بصورت لاتین(123) ارسال نکردید .

💯 لطفا دوباره امتحان کنید .",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif(strpos($data,"mahdl_") !== false ){
 if(in_array($chat_id,$admins)){
$ok = str_replace("mahdl_",null,$data);
$files = json_decode(file_get_contents("files/$ok.json"),true);
if($files['mahdodl'] != 'none'){
 $khi = '❌ برداشتن محدودیت';
  }else{
 $khi = null;
 }
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"✅ لطفا حداکثر تعداد دانلود فایل شماره $ok را بصورت عدد لاتین (123) وارد فرمایید:",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"],['text'=>"$khi"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = "newpmah_$ok";
saveJson("melat/$from_id.json",$user);
}} 
//##############################################################
elseif(strpos($user['step'],"newpmah_") !== false and $text != '🔙 منوی پنل' and $text != '❌ برداشتن محدودیت'){
 if(in_array($chat_id,$admins)){
$ok = str_replace("newpmah_",null,$user['step']);
$files = json_decode(file_get_contents("files/$ok.json"),true);
if(is_numeric($text) == true){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔘 محدودیت دانلود تنظیم شد .

ℹ️ فایل شماره : <code>$ok</code>
🚷 محدودیت دانلود : <code>$text نفر</code>",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$files['mahdodl'] = "$text";
saveJson("files/$ok.json",$files);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ لطفا فقط یک عدد لاتین(123) ارسال کنید.",
'parse_mode'=>"HTML",
]);
}
}
}
//##############################################################
elseif(strpos($user['step'],"newpmah_") !== false and $text == "❌ برداشتن محدودیت"){
if(in_array($chat_id,$admins)){
$ok = str_replace("newpmah_",null,$user['step']);
$files = json_decode(file_get_contents("files/$ok.json"),true);
if($files['code'] != null ){
$files['mahdodl'] = 'none';
saveJson("files/$ok.json",$files);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ محدودیت دانلود برداشته شد !

ℹ️ فایل شماره : <code>$ok</code>",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}
}
}
//##############################################################
elseif(strpos($user['step'],"newpass_") !== false and $text == "❌ برداشتن پسورد"){
if(in_array($chat_id,$admins)){
$ok = str_replace("newpass_",null,$user['step']);
$files = json_decode(file_get_contents("files/$ok.json"),true);
if($files['code'] != null ){
$files['pass'] = 'none';
saveJson("files/$ok.json",$files);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ پسورد برداشته شد !

ℹ️ فایل شماره : <code>$ok</code>",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}
}
}
//##############################################################
elseif($text == "🔒 | تنظیم پسورد"){
 if(in_array($chat_id,$admins)){
 bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"💯 لطفا شماره آپلود را ارسال کنید:",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = 'getcodeu';
saveJson("melat/$from_id.json",$user);
}
}  
elseif($user['step'] == "getcodeu" and $text != "🔙 منوی پنل"){
 if(in_array($chat_id,$admins)){
 $files = json_decode(file_get_contents("files/$text.json"),true);
 if($files['code'] != null and is_numeric($text) == true ){
 if($files['pass'] != 'none'){
 $khi = '❌ برداشتن پسورد';
  }else{
 $khi = null;
 }
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"✅ لطفا پسورد جدید را وارد کنید:

ℹ️ فایل شماره : <code>$text</code>",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"],['text'=>"$khi"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = "newpass_$text";
saveJson("melat/$from_id.json",$user);
 }else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ این کد آپلود یافت نشد و یا بصورت لاتین(123) ارسال نکردید .

💯 لطفا دوباره امتحان کنید .",
'parse_mode'=>"HTML",
]);
}
 } 
}
elseif(strpos($data,"Setpas_") !== false ){
 if(in_array($chat_id,$admins)){
$ok = str_replace("Setpas_",null,$data);
 $files = json_decode(file_get_contents("files/$ok.json"),true);
 if($files['pass'] != 'none'){
 $khi = '❌ برداشتن پسورد';
  }else{
 $khi = null;
 }
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"✅ لطفا پسورد جدید را وارد کنید:

ℹ️ فایل شماره : <code>$ok</code>",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"],['text'=>"$khi"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = "newpass_$ok";
saveJson("melat/$from_id.json",$user);
}
}
elseif(strpos($user['step'],"newpass_") !== false and $text != '🔙 منوی پنل' and $text != '❌ برداشتن پسورد'){
 if(in_array($chat_id,$admins)){
$ok = str_replace("newpass_",null,$user['step']);
if($text != null ){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔐 پسورد تنظیم گردید.

ℹ️ فایل شماره : <code>$ok</code>
🔑 پسورد جدید : <code>$text</code>",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
]) 
]);
$files = json_decode(file_get_contents("files/$ok.json"),true);
$files['pass'] = "$text";
saveJson("files/$ok.json",$files);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ لطفا فقط یک متن ارسال کنید:",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif($text=="⏱ | تاریخچه اپلود" ){
 if(in_array($chat_id,$admins)){
$allup = 0;
foreach(glob('files/*.json') as $array){
$userID = str_replace(['files/', '.json'], '', $array);
if(is_numeric($userID)){
$allup++;
}}
if($allup > 0){
$files = json_decode(file_get_contents("files/1.json"),true);
$file_size = $files['file_size'];
$zaman = $files['zaman'];
$tozihat = $files['tozihat'];
$dl = $files['dl'];
$id = $files['id'];
if($files['pass'] == 'none'){
$ispass = '❌ بدون پسورد';
$namepass = 'تنظیم پسورد';
$datapass = "Setpas_1";
}else{
$ispass = $files['pass'];
$namepass = '🔐 تغییر پسورد';
$datapass = "Setpas_1";
}
if($files['mahdodl'] == 'none'){
$ismahd = '❌ بدون محدودیت دانلود';
$namemahd = 'تنظیم محدودیت';
$datamahd = "mahdl_1";
}else{
$ismahd = $files['mahdodl'];
$namemahd = '🚷 تغییر محدودیت';
$datamahd = "mahdl_1";
}
if($files['ghfl_ch'] == 'on'){
$hesofff2 = '✅';
}else{
$hesofff2 = '❌';
}
if($files['zd_filter'] == 'on'){
$hesofff = '✅';
}else{
$hesofff = '❌';
}
if($files['msg_id'] == 'delete'){
$hours = '⛔️ این رسانه از دسترس خارج شده است.';
}else{
$hours = '✅این رسانه فعال میباشد.';
}
$file_type = doc($files['file_type']);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"ℹ️ اطلاعات کامل این رسانه یافت شد :

▪️ کد رسانه : <code>1</code>

🔹 نوع : $file_type
🔸 اندازه : $file_size
🔹 زمان آپلود : $zaman
🔸 تعداد دانلود : $dl 
🔹 وضعیت دانلود : $hours

🔹 توضیحات : $tozihat

🔓 پسورد : <code>$ispass</code>
🖇 محدودیت دانلود : $ismahd
📌 ضد فیلتر : $hesofff
🔐 قفل چنل : $hesofff2
🔗 لینک دریافت : https://telegram.me/$bottag?start=dl_1

🔸 توسط ادمین <a href='tg://user?id=$id'>$id</a> آپلود شده است .",
'parse_mode'=>"HTML",
'reply_markup'=> json_encode([
'inline_keyboard'=>[
[['text'=>"🔢 کد : 1 با اندازه $file_size",'callback_data'=>"in_1"]],
 [['text'=>"➡️ صفحه بعدی",'callback_data'=>"saf_2"]],
  ]
  ])
]);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ هیچ رسانه ای آپلود نشده است .",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif(strpos($data,"saf_") !== false ){
 if(in_array($chat_id,$admins)){
$ok = str_replace("saf_",null,$data);
if(!is_file("files/$ok.json")){
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "❗️صفحه ای وجود ندارد.",
'show_alert' => false
]);
}else{
$files = json_decode(file_get_contents("files/$ok.json"),true);
$file_size = $files['file_size'];
$zaman = $files['zaman'];
$tozihat = $files['tozihat'];
$dl = $files['dl'];
$id = $files['id'];
if($files['pass'] == 'none'){
$ispass = '❌ بدون پسورد';
$namepass = 'تنظیم پسورد';
$datapass = "Setpas_$ok";
}else{
$ispass = $files['pass'];
$namepass = '🔐 تغییر پسورد';
$datapass = "Setpas_$ok";
}
if($files['mahdodl'] == 'none'){
$ismahd = '❌ بدون محدودیت دانلود';
$namemahd = 'تنظیم محدودیت';
$datamahd = "mahdl_$ok";
}else{
$ismahd = $files['mahdodl'];
$namemahd = '🚷 تغییر محدودیت';
$datamahd = "mahdl_$ok";
}
if($files['msg_id'] == 'delete'){
$hours = '⛔️ این رسانه از دسترس خارج شده است.';
}else{
$hours = '✅این رسانه فعال میباشد.';
}
if($files['ghfl_ch'] == 'on'){
$hesofff2 = '✅';
}else{
$hesofff2 = '❌';
}
if($files['zd_filter'] == 'on'){
$hesofff = '✅';
}else{
$hesofff = '❌';
}
//=============
$ors = $ok + 1;
$ore = $ok - 1;
//=============
$file_type = doc($files['file_type']);
//#####################################
$d4 = json_encode(['inline_keyboard'=>[
[['text'=>"🔢 کد : $ok با اندازه $file_size",'callback_data'=>"in_$ok"]],
 [['text'=>"⬅️ صفحه قبلی",'callback_data'=>"saf_$ore"],['text'=>"➡️ صفحه بعدی",'callback_data'=>"saf_$ors"]],
]]);
//=============
if(!is_file("files/$ors.json")){
$d4 = json_encode(['inline_keyboard'=>[
[['text'=>"🔢 کد : $ok با اندازه $file_size",'callback_data'=>"in_$ok"]],
 [['text'=>"⬅️ صفحه قبلی",'callback_data'=>"saf_$ore"]],
 ]]);
}
//=============
if($ok == 1 ){
$d4 = json_encode(['inline_keyboard'=>[
[['text'=>"🔢 کد : $ok با اندازه $file_size",'callback_data'=>"in_$ok"]],
[['text'=>"➡️ صفحه بعدی",'callback_data'=>"saf_$ors"]],
]]);
}
//=============
bot('editMessagetext',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"ℹ️ اطلاعات کامل این رسانه یافت شد :

▪️ کد رسانه : <code>$ok</code>

🔹 نوع : $file_type
🔸 اندازه : $file_size
🔹 زمان آپلود : $zaman
🔸 تعداد دانلود : $dl 
🔹 وضعیت دانلود : $hours

🔸 توضیحات : $tozihat

🔓 پسورد : <code>$ispass</code>
🖇 محدودیت دانلود : $ismahd
📌 ضد فیلتر : $hesofff
🔐 قفل چنل : $hesofff2
🔗 لینک دریافت : https://telegram.me/$bottag?start=dl_$ok

🔸 توسط ادمین <a href='tg://user?id=$id'>$id</a> آپلود شده است .",
'parse_mode'=>"HTML",
  'reply_markup'=>$d4
]);
}}}
//##############################################################
elseif(strpos($data,"in_") !== false ){
$ok = str_replace("in_",null,$data);
$files = json_decode(file_get_contents("files/$ok.json"),true);
$file_size = $files['file_size'];
$zaman = $files['zaman'];
$tozihat = $files['tozihat'];
$dl = $files['dl'];
$id = $files['id'];
if($files['pass'] == 'none'){
$ispass = '❌ بدون پسورد';
$namepass = 'تنظیم پسورد';
$datapass = "Setpas_$ok";
}else{
$ispass = $files['pass'];
$namepass = '🔐 تغییر پسورد';
$datapass = "Setpas_$ok";
}
if($files['mahdodl'] == 'none'){
$ismahd = '❌ بدون محدودیت دانلود';
$namemahd = 'تنظیم محدودیت';
$datamahd = "mahdl_$ok";
}else{
$ismahd = $files['mahdodl'];
$namemahd = '🚷 تغییر محدودیت';
$datamahd = "mahdl_$ok";
}
if($files['ghfl_ch'] == 'on'){
$hesofff2 = '✅';
}else{
$hesofff2 = '❌';
}
if($files['zd_filter'] == 'on'){
$hesofff = '✅';
}else{
$hesofff = '❌';
}
if($files['msg_id'] == 'delete'){
$hours = '⛔️ این رسانه از دسترس خارج شده است.';
}else{
$hours = '✅این رسانه فعال میباشد.';
}
$file_type = doc($files['file_type']);
$ore = $ok;
bot('editMessagetext',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"ℹ️ اطلاعات کامل این رسانه یافت شد :

▪️ کد رسانه : <code>$ok</code>

🔹 نوع : $file_type
🔸 اندازه : $file_size
🔹 زمان آپلود : $zaman
🔸 تعداد دانلود : $dl 
🔹 وضعیت دانلود : $hours

🔹 توضیحات : $tozihat

🔓 پسورد : <code>$ispass</code>
🖇 محدودیت دانلود : $ismahd
📌 ضد فیلتر : $hesofff
🔐 قفل چنل : $hesofff2
🔗 لینک دریافت : https://telegram.me/$bottag?start=dl_$ok

🔸 توسط ادمین <a href='tg://user?id=$id'>$id</a> آپلود شده است .",
'parse_mode'=>"HTML",
'reply_markup'=> json_encode([
'inline_keyboard'=>[
 [['text'=>"$namemahd",'callback_data'=>"$datamahd"],['text'=>"$namepass",'callback_data'=>"$datapass"]],
  [['text'=>"ضدفیلتر : $hesofff",'callback_data'=>"pnlzdfilter_$ok"],['text'=>"قفل چنل : $hesofff2",'callback_data'=>"ghdpnl_$ok"]],
  [['text'=>"🔙 برگشت به صفحات",'callback_data'=>"saf_$ore"]],
  ]
  ])
]);
}
//##############################################################
  elseif($text=="❎ | حذف رسانه" ){
 if(in_array($chat_id,$admins)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❗️ لطفا کد رسانه را برای حذف وارد کنید :",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = 'delres';
saveJson("melat/$from_id.json",$user);
}
} 
elseif($user['step'] =="delres" and $text != "🔙 منوی پنل"){
 if(in_array($chat_id,$admins)){
 $files = json_decode(file_get_contents("files/$text.json"),true);
 if($files['code'] != null and is_numeric($text) == true ){
$files['msg_id'] = 'delete';
$files['file_id'] = null;
saveJson("files/$text.json",$files);
  bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"✅ با موفقیت حذف گردید .",
'parse_mode'=>"HTML",
]);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
 }else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ این کد آپلود یافت نشد و یا بصورت لاتین(123) ارسال نکردید .

💯 لطفا دوباره امتحان کنید .",
'parse_mode'=>"HTML",
]);
}
 }
}
//##############################################################
 elseif($text=="📥 | آپلود رسانه" ){
if(in_array($chat_id,$admins)){
  bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"🔹 لطفا فایل خود را برای آپلود ارسال فرمایید:

شما می توانید پرونده(سند) ، ویدیو ، عکس ، ویس ، استیکر ، موزیک را ارسال کنید تا در ربات آپلود شود .",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = 'upload';
saveJson("melat/$from_id.json",$user);
}
} 
elseif($text != "/start" and $user['step'] =="upload" and $text != "🔙 منوی پنل" and !$data ){
if(in_array($chat_id,$admins)){
if(isset($message->video)) {
 $file_id = $message->video->file_id;
 $file_size = $message->video->file_size;
$size = convert($file_size);
$type = 'video';
}
if(isset($message->document)) {
 $file_id = $message->document->file_id;
 $file_size = $message->document->file_size;
$size = convert($file_size);
$type = 'document';
 }
 if(isset($message->photo)) {
 $photo = $message->photo;
 $file_id = $photo[count($photo)-1]->file_id;
 $file_size = $photo[count($photo)-1]->file_size;
$size = convert($file_size);
$type = 'photo';
 } 
 if(isset($message->voice)) {
 $file_id = $message->voice->file_id;
 $file_size = $message->voice->file_size;
$size = convert($file_size);
$type = 'voice';
 }
 if(isset($message->audio)) {
 $file_id = $message->audio->file_id;
 $file_size = $message->audio->file_size;
$size = convert($file_size);
$type = 'audio';
 }
 if(isset($message->sticker)) {
 $file_id = $message->sticker->file_id;
 $file_size = $message->sticker->file_size;
$size = convert($file_size);
$type = 'sticker';
 }
 if($file_id != null ){ 
  $type_farsi = doc($type);
 bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"➕ بسیار خب ! اکنون توضیحات را ارسال کنید :

🔹 نوع فایل شما : $type_farsi

توضیحات حداکثر 500 کاراکتر میتواند باشد.",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$user['step4'] = "$type";
saveJson("melat/$from_id.json",$user);
$user['step3'] = "$size";
saveJson("melat/$from_id.json",$user);
$user['step2'] = "$file_id";
saveJson("melat/$from_id.json",$user);
$user['step'] = 'tozihat';
saveJson("melat/$from_id.json",$user);
}}}
elseif($text != "/start" and $user['step'] =="tozihat" and $text != "🔙 منوی پنل" and !$data ){
if(in_array($chat_id,$admins)){
if(mb_strlen($text) < 501 ){
$type = $user['step4'];
$size = $user['step3'];
$file_id = $user['step2'];
$Scn0s = scandir('files');
$Scn0s = array_diff($Scn0s, ['.','..']);
$allu0p = 0;
foreach(glob('files/*.json') as $array){
$userID = str_replace(['files/', '.json'], '', $array);
if(is_numeric($userID)){
$allu0p++;
}}
if($allu0p == 0){
$code = 1;
}else{
$code = $allu0p+1;
}
$type_farsi = doc($type);
$zaman = "$ToDay $time $date";
$order = ['code'=> $code, 'msg_id'=> 'none', 'ghfl_ch'=>'on', 'zd_filter'=>'off', 'file_id'=>"$file_id",'file_size'=>"$size",'file_type'=>"$type", 'id'=>"$from_id",'dl'=>'0', 'tozihat'=> "$text",'zaman'=> "$zaman", 'mahdodl'=>'none','pass'=>'none'];
 file_put_contents("files/$code.json", json_encode($order, 448));
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"درحال آپلود فایل...",
'parse_mode'=>"HTML",
 'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"👥 | آمار ربات"],['text'=>"📣 | تغییر قفل چنل"]],
[['text'=>"📨 | فوروارد همگانی"],['text'=>"📨 | پیام همگانی"]],
[['text'=>"ℹ️ | اطلاعات آپلود"],['text'=>"📥 | آپلود رسانه"]],
[['text'=>"⏱ | تاریخچه اپلود"],['text'=>"❎ | حذف رسانه"]],
[['text'=>"🔒 | تنظیم پسورد"],['text'=>"🚷 | محدودیت دانلود"]],
[['text'=>"💫 | تنظیم قفل آپلود"],['text'=>"🔥 | تنظیم ضد فیلتر"]],
[['text'=>"📛 | تنظیم تایم حذف"]],
[['text'=>"♻️| بروزرسانی"],['text'=>"⏳| اشتراک باقی مانده"]],
[['text'=>"🏠 برگشت به منو"]],
],
'resize_keyboard'=>true
])
]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"$type_farsi شما با موفقیت آپلود شد .✅

▪️ کد رسانه : <code>$code</code>

🔸 اندازه : $size
🔹 زمان آپلود : $zaman

🔹 توضیحات : $text

و توسط شما $from_id در ربات آپلود شد  .

🔗 لینک دریافت : https://telegram.me/$bottag?start=dl_$code

💢 هر زمان خواستید از بخش اطلاعات آپلود میتوانید از آخرین وضعیت این رسانه با خبر شوید.",
'parse_mode'=>"HTML",
 'reply_markup'=> json_encode([
'inline_keyboard'=>[
 [['text'=>"تنظیم محدودیت",'callback_data'=>"mahdl_$code"],['text'=>"تنظیم پسورد",'callback_data'=>"Setpas_$code"]],
  [['text'=>"ضدفیلتر : ❌",'callback_data'=>"antifil_$code"],['text'=>"قفل چنل : ✅",'callback_data'=>"ghflch_$code"]],
  ]
  ])
]);
$user['step'] = 'none';
$user['step2'] = 'none';
$user['step3'] = 'none';
$user['step4'] = 'none';
$user['step5'] = '0';
saveJson("melat/$from_id.json",$user);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ خطا ! توضیحات طولانی است

لطفا متن توضیحات را دوباره و کوتاه ارسال کنید ( حداکثر 1000 کاراکتر )",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif(strpos($data,"antifil_") !== false ){
$ok = str_replace("antifil_",null,$data);
if(in_array($chat_id,$admins)){
$files2 = json_decode(file_get_contents("files/$ok.json"),true);
 if($files2['zd_filter'] == 'on'){
$nmddd1 = 'off';
}else{
$nmddd1 = 'on';
}
$files2['zd_filter'] = "$nmddd1";
saveJson("files/$ok.json",$files2);
 $files = json_decode(file_get_contents("files/$ok.json"),true);
  if($files['pass'] == 'none'){
$namepass = 'تنظیم پسورد';
$datapass = "Setpas_$ok";
}else{
$namepass = '🔐 تغییر پسورد';
$datapass = "Setpas_$ok";
} 
if($files['mahdodl'] == 'none'){
$namemahd = 'تنظیم محدودیت';
$datamahd = "mahdl_$ok";
}else{
$namemahd = '🚷 تغییر محدودیت';
$datamahd = "mahdl_$ok";
}
if($files['ghfl_ch'] == 'on'){
$hesofff2 = '✅';
}else{
$hesofff2 = '❌';
}
if($files['zd_filter'] == 'on'){
$hesofff = '✅';
}else{
$hesofff = '❌';
}
bot('editMessageReplyMarkup',[
 'chat_id'=>$chat_id,
 'message_id'=>$message_id,
 'reply_markup'=>json_encode([
'inline_keyboard'=>[

[['text'=>"$mtnsch",'callback_data'=>"$stepmsc"]],
 [['text'=>"$namemahd",'callback_data'=>"$datamahd"],['text'=>"$namepass",'callback_data'=>"$datapass"]],
 [['text'=>"ضدفیلتر : $hesofff",'callback_data'=>"antifil_$ok"],['text'=>"قفل چنل : $hesofff2",'callback_data'=>"ghflch_$ok"]],
  ]
  ])
]);
}
}
//##############################################################
elseif(strpos($data,"ghflch_") !== false ){
$ok = str_replace("ghflch_",null,$data);
if(in_array($chat_id,$admins)){
$files2 = json_decode(file_get_contents("files/$ok.json"),true);
 if($files2['ghfl_ch'] == 'on'){
$nmddd1 = 'off';
}else{
$nmddd1 = 'on';
}
$files2['ghfl_ch'] = "$nmddd1";
saveJson("files/$ok.json",$files2);
  if($files['pass'] == 'none'){
$namepass = 'تنظیم پسورد';
$datapass = "Setpas_$ok";
}else{
$namepass = '🔐 تغییر پسورد';
$datapass = "Setpas_$ok";
}
if($files['mahdodl'] == 'none'){
$namemahd = 'تنظیم محدودیت';
$datamahd = "mahdl_$ok";
}else{
$namemahd = '🚷 تغییر محدودیت';
$datamahd = "mahdl_$ok";
}
if($files['ghfl_ch'] == 'on'){
$hesofff2 = '✅'; 
}else{
$hesofff2 = '❌';
}
if($files['zd_filter'] == 'on'){
$hesofff = '✅';
}else{
$hesofff = '❌'; 
}
bot('editMessageReplyMarkup',[
 'chat_id'=>$chat_id,
 'message_id'=>$message_id,
 'reply_markup'=>json_encode([
'inline_keyboard'=>[
 [['text'=>"$namemahd",'callback_data'=>"$datamahd"],['text'=>"$namepass",'callback_data'=>"$datapass"]],
 [['text'=>"ضدفیلتر : $hesofff",'callback_data'=>"antifil_$ok"],['text'=>"قفل چنل : $hesofff2",'callback_data'=>"ghflch_$ok"]],
  ]
  ])
]);
}
}
//##############################################################
  elseif($text=="ℹ️ | اطلاعات آپلود" ){
if(in_array($chat_id,$admins)){
  bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❕ لطفا کد عددی رسانه آپلود شده را وارد کنید.",
'parse_mode'=>"HTML",
  'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
'resize_keyboard'=>true
])
]);
$user['step'] = 'infoupl';
saveJson("melat/$from_id.json",$user);
}
}
elseif($user['step'] =="infoupl" and $text != "🔙 منوی پنل" ){
if(in_array($chat_id,$admins)){
$files = json_decode(file_get_contents("files/$text.json"),true);
if(is_numeric($text) == true and $files['code'] != null ){
$file_size = $files['file_size'];
$zaman = $files['zaman'];
$tozihat = $files['tozihat'];
$dl = $files['dl'];
$id = $files['id'];
if($files['pass'] == 'none'){
$ispass = '❌ بدون پسورد';
$namepass = 'تنظیم پسورد';
$datapass = "Setpas_$text";
}else{
$ispass = $files['pass'];
$namepass = '🔐 تغییر پسورد';
$datapass = "Setpas_$text";
}
if($files['mahdodl'] == 'none'){
$ismahd = '❌ بدون محدودیت دانلود';
$namemahd = 'تنظیم محدودیت';
$datamahd = "mahdl_$text";
}else{
$ismahd = $files['mahdodl'];
$namemahd = '🚷 تغییر محدودیت';
$datamahd = "mahdl_$text";
}
if($files['ghfl_ch'] == 'on'){
$hesofff2 = '✅';
}else{
$hesofff2 = '❌';
}
if($files['zd_filter'] == 'on'){
$hesofff = '✅';
}else{
$hesofff = '❌';
}
if($files['msg_id'] == 'delete'){
$hours = '⛔️ این رسانه از دسترس خارج شده است.';
}else{
$hours = '✅این رسانه فعال میباشد.';
}
$file_type = doc($files['file_type']);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"ℹ️ اطلاعات کامل این رسانه یافت شد :

▪️ کد رسانه : <code>$text</code>

🔹 نوع : $file_type
🔸 اندازه : $file_size
🔹 زمان آپلود : $zaman
🔸 تعداد دانلود : $dl 
🔹 وضعیت دانلود : $hours

🔹 توضیحات : $tozihat

🔓 پسورد : <code>$ispass</code>
🖇 محدودیت دانلود : $ismahd
📌 ضد فیلتر : $hesofff
🔐 قفل چنل : $hesofff2
🔗 لینک دریافت : https://telegram.me/$bottag?start=dl_$text

🔸 توسط ادمین <a href='tg://user?id=$id'>$id</a> آپلود شده است .",
'parse_mode'=>"HTML",
'reply_markup'=> json_encode([
'inline_keyboard'=>[
 [['text'=>"$namemahd",'callback_data'=>"$datamahd"],['text'=>"$namepass",'callback_data'=>"$datapass"]],
  [['text'=>"ضدفیلتر : $hesofff",'callback_data'=>"pnlzdfilter_$text"],['text'=>"قفل چنل : $hesofff2",'callback_data'=>"ghdpnl_$text"]],
  ]
  ])
]);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"❌ این کد آپلود یافت نشد و یا بصورت لاتین(123) ارسال نکردید .

💯 لطفا دوباره امتحان کنید .",
'parse_mode'=>"HTML",
]);
}}}
//##############################################################
elseif($text=="📨 | فوروارد همگانی" and in_array($chat_id,$admins)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"📩 لطفا پیام را در اینجا فوروارد کنید :",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
  'keyboard'=>[
[['text'=>"🔙 منوی پنل"]], 
],
"resize_keyboard"=>true,
 ])
]);
$user['step'] = 'forall';
saveJson("melat/$from_id.json",$user);
}
//##############################################################
 elseif($user["step"] =="forall" and $text != "🔙 منوی پنل" and !$data ){
 if(in_array($chat_id,$admins)){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"📣 <i>پیام به صف فوروارد قرار گرفت !</i>

✅ <b>بعد از اتمام فوروارد، به شما اطلاع داده میشود.</b>",
'parse_mode'=>"HTML",
]);
$forall = json_decode(file_get_contents("forall.json"),true);
$forall['step'] = 'forall';
$forall['text'] = 'for';
$forall['chat'] = $chat_id;
$forall['msgid'] = "$message_id";
$forall['user'] = '0';
$forall['userID'] = $from_id;
saveJson("forall.json",$forall);
$user['step'] = 'none';
saveJson("melat/$from_id.json",$user);
}}
//##############################################################
elseif($text=="📨 | پیام همگانی" and in_array($chat_id,$admins) ){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"📍 پیام خود را در هر قالبی برام بفرستید.",
 'reply_markup'=>json_encode([
  'keyboard'=>[
[['text'=>"🔙 منوی پنل"]],
],
"resize_keyboard"=>true,
 ]) 
]); 
$user['step'] = 'sendall';
saveJson("melat/$from_id.json",$user);
}
//##############################################################
elseif($user["step"] =="sendall" and $text != "🔙 منوی پنل" and !$data ){
if(in_array($chat_id,$admins)){
$id = bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"📣 <i>پیام به صف ارسال قرار گرفت !</i>

✅ <b>بعد از اتمام ارسال، به شما اطلاع داده میشود.</b>
",
'parse_mode'=>"HTML",
]);
$sendall = json_decode(file_get_contents("sendall.json"),true);
$sendall['step'] = 'sendall';
$sendall['msgid'] = "$message_id";
$sendall['user'] = '0';
$sendall['userID'] = $from_id;
saveJson("sendall.json",$sendall);
}} 
//##############################################################
elseif($text=="👥 | آمار ربات"){
if(in_array($chat_id,$admins)){
$fil = 0;
foreach(glob('melat/*.json') as $array){
$userID = str_replace(['melat/', '.json'], '', $array);
if(is_numeric($userID)){
$fil++;
}}
$all_up = 0;
foreach(glob('files/*.json') as $array){
$userID = str_replace(['files/', '.json'], '', $array);
if(is_numeric($userID)){
$all_up++;
}}
bot('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"👥 <b>Member Count :</b> <code>$fil</code>

📥 <b>تعداد رسانه آپلود شده :</b> <code>$all_up</code>
",
'parse_mode'=>"HTML",
]);
}}
//##############################################################
elseif($text=="⏳| اشتراک باقی مانده"){
if(in_array($chat_id,$admins)){
date_default_timezone_set('Asia/Tehran'); 
$sharge = file_get_contents("Lite.txt");
$a = date('Y/m/d');
$b = "$sharge";
$sec = strtotime($b)-strtotime($a);
$days = $sec/86400;
$d0ays = explode('.',$days)[0];
bot('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"⏳از شارژ ربات شما <code>$d0ays</code> روز باقی مانده است",
'parse_mode'=>"HTML",
]);
}}
//##############################################################
elseif($text=="♻️| بروزرسانی"){
if(in_array($chat_id,$admins)){
$Button_upd = json_encode(['keyboard'=>[[['text'=>'♻️انجام بروز رسانی♻️']],[['text'=>'🔙 منوی پنل']],],'resize_keyboard'=>true]);
$user['step'] = 'updeta'; 
bot('sendmessage',['chat_id'=>$from_id,'text'=>"⁉️درصورتی که بروز رسانی جدید ربات در دسترس باشد با بروز کردن ربات به نسخه جدید میتوانید ربات خود را بهبود ببخشید👈بهتر است هر هفته این گزینه را امتحان کنید تا در صورت وجود باگ یا تغییرات ربات شما ارتقا یابد:",'reply_markup' => $Button_upd]);
saveJson("melat/$from_id.json",$user);}}
//----------------------------------------/////
else if($text == '♻️انجام بروز رسانی♻️' and $user['step'] == 'updeta' and $tc == 'private' and in_array($chat_id,$admins)){
$user['step'] = 'none';
bot('sendmessage',['chat_id'=>$from_id,'text'=>"اپدیت ربات شما در حال انجام می باشد..."]);
sleep(1.5);
bot('editMessageText',[ 'chat_id'=>$from_id, 'message_id'=>$message_id + 1, 'text'=>'⬛️⬜️⬜️⬜️⬜️ %20' ]); 
sleep(2.5);
bot('editMessageText',[ 'chat_id'=>$from_id, 'message_id'=>$message_id + 1, 'text'=>'⬛️⬛️⬜️⬜️⬜️ %40' ]); 
sleep(2.5); 
bot('editMessageText',[ 'chat_id'=>$from_id, 'message_id'=>$message_id + 1, 'text'=>'⬛️⬛️⬛️⬜️⬜️ %60' ]); 
sleep(2.5); 
bot('editMessageText',[ 'chat_id'=>$from_id, 'message_id'=>$message_id + 1, 'text'=>'⬛️⬛️⬛️⬛️⬜️ %80' ]); 
sleep(2.5);
bot('editMessageText',[ 'chat_id'=>$from_id, 'message_id'=>$message_id + 1, 'text'=>'⬛️⬛️⬛️⬛️⬛️ %100' ]); 
sleep(2.5);
bot('editMessageText',[ 'chat_id'=>$from_id, 'message_id'=>$message_id + 1, 'text'=>'%100درحال بارگزاری اطلاعات' ]);
copy('../../../Source/uploder/index.php',"bot.php");
copy('../../../Source/uploder/delmsg.php',"delmsg.php");
sleep(1); 
bot('editMessageText',[ 'chat_id'=>$from_id, 'message_id'=>$message_id + 1, 'text'=>'✅ربات شما با موفقیت به اخرین نسخه اپدیت شد
جهت شروع مجدد /start را بزنید' ]); 
saveJson("melat/$from_id.json",$user);}
?>