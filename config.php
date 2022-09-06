<?php
error_reporting(0);
//----------------------------------------//
define('API_KEY','5581256514:AAFVUCdmhESh4JqA7F4cgxj2r6eMXlIkXoI');
$boter_id = 'irivrisvrisv5isvisvvibot';
$tokens_bot = '5581256514:AAFVUCdmhESh4JqA7F4cgxj2r6eMXlIkXoI';
$API_KC = "5581256514:AAFVUCdmhESh4JqA7F4cgxj2r6eMXlIkXoI"; // توکن رو قرار بدید
$admins = array("5575642551");
//--------------------------------------------------------
$API_TEL = json_decode(file_get_contents('https://api.telegram.org/bot'.$API_KC.'/getme'));
$botid = $API_TEL->result->id;
$bottag = $API_TEL->result->username;
//--------------------------------------------------------
?>