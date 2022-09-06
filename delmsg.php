<?php
//---------------------- i n c l u d e --------------------------------//
include "config.php";
//------------------------------------------------------//
error_reporting(0);
date_default_timezone_set('Asia/Tehran');
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
}}
foreach (str_replace('.json',NULL,array_diff(scandir('dbremove'),['.','..'])) as $rand){
if (json_decode(file_get_contents('dbremove/'.$rand.'.json'),true)['time'] <= time()){
$row = json_decode(file_get_contents("dbremove/$rand.json"),true);
bot('deleteMessage',[
'chat_id' => $row['id'],
'message_id' => $row['message_id'],
]);
bot('editMessagetext',[
'chat_id'=>$row['id'],
'message_id'=>$row['message_id']+1,
'text'=>"پیام به علت فیلترینگ گسترده تلگرام حذف شد!",
 'parse_mode'=>"HTML",
]);
unlink("dbremove/$rand.json"); 
}}
//###################################################################
$sendall = json_decode(file_get_contents("sendall.json"),true);
if($sendall["step"] == "sendall"){
foreach(glob('melat/*.json') as $array){
$userID = str_replace(['melat/', '.json'], '', $array);
if(is_numeric($userID)){
bot('copyMessage', ['chat_id'=> $userID, 'from_chat_id'=> $sendall['userID'], 'message_id'=> $sendall['msgid']]);
}}
bot('sendmessage',[
'chat_id'=>$sendall['userID'],
'text'=>"✅ عملیات همگانی پایان یافت !",
]);
unlink('sendall.json');
}
//###################################################################
$forall = json_decode(file_get_contents("forall.json"),true);
if($forall["step"] == "forall"){
foreach(glob('melat/*.json') as $array){
$userID = str_replace(['melat/', '.json'], '', $array);
if(is_numeric($userID)){
bot('forwardMessage', ['chat_id'=> $userID, 'from_chat_id'=> $forall['userID'], 'message_id'=> $forall['msgid']]);
}}
bot('sendmessage',[
'chat_id'=>$forall['userID'],
'text'=>"✅ عملیات همگانی پایان یافت !",
]);
unlink('forall.json');
}
//###################################################################