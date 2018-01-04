<?php
# In The Name Of GOD #
# Writer : @Mahdi_Elvis #
ob_start();
define('API_KEY','توکن');
define('ADMIN','آیدی عددی ادمین');
// === Functions === //
// Bot
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
// Send Action
function SendAction($chat_id,$action='typing'){
return bot('SendChatAction',[
'chat_id'=>$chat_id,
'action'=>$action
]);
}
// Send Message
function SendMessage($chat_id,$text,$parse_mode='HTML',$keyboard,$reply,$disable='true'){
return bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>$text,
'parse_mode'=>$parse_mode,
'reply_to_message_id'=>$reply,
'reply_markup'=>$keyboard
]);
}
// Edit Message 
function EditMessage($chat_id,$message_id,$text,$parse_mode='HTML',$keyboard){
return bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>$text,
'parse_mode'=>$parse_mode,
'reply_markup'=>$keyboard
]);
}
// Send Document
function SendDocument($chatid,$document,$reply,$keyboard,$caption){
return bot('SendDocument',[
'chat_id'=>$chatid,
'document'=>$document,
'caption'=>$caption,
'reply_to_message_id'=>$reply,
'reply_markup'=>$keyboard
]);
}
// Answer Callback Query
function AnswerCallbackQuery($callback_id,$text,$show_alert){
return bot('AnswerCallbackQuery',[
'callback_query_id'=>$callback_id,
'text'=>$text,
'show_alert'=>$show_alert
]);    
}
// === Variables === //
// Keyboards
$keyboard = json_encode([
'inline_keyyboard'=>[[['text'=>"ساخت گیف",'callback_data'=>"gif"]]]
]);
$back = json_encode([
'inline_keyyboard'=>[[['text'=>"برگشت",'callback_data'=>"back"]]]
])
// Input
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$callback_query = $update->callback_query;
$message_id = $message->message_id;
$messageid = $callback_query->message->message_id;
$from_id = $message->from->id;
$fromid = $callback_query->from->id;
$chat_id = $message->chat->id;
$chatid = $callback_query->message->chat->id;
$text = $message->text;
$data = $callback_query->data;
$callback_id = $callback_query->id;
$first_name = $message->from->first_name;
$username = $message->from->userame;
$command = file_get_contents("data/$from_id/command.txt");
// == Start Source === //
flush();
if(!file_exists("data/$from_id")){
if(!file_exists("data")){
mkdir("data");
}
mkdir("data/$from_id");
}
// === Start Command === //
if(preg_match('/^\/([Ss][Tt][Aa][Rr][Tt])/',$text) && mb_strlen($text) == 6){
SendAction($chat_id);
$user = file_get_contents('Member.txt');
$members = explode("\n",$user);
if(!in_array($chat_id,$members)){
file_put_contents('Member.txt',$members."$from_id\n");
}
SendMessage($chat_id,"سلام <a href='tg://user?id=$from_id'>$first_name</a> ...

به ربات ساخت گیف خوش اومدی !!",'HTML',$keyboard,$message_id);
}
elseif($data == "gif"){
AnswerCallbackQuery($callback_id,"لطفا کمی صبر کنید ...");
file_put_contents("data/$fromid/command.txt","gif");
EditMessage($chatid,$messageid,"لطفا متن خود را ارسال کنید :",'HTML',$back);		
}
elseif($command == "gif"){
if($text){
SendAction($chat_id,'upload_documnet');
file_put_contents("data/$from_id/command.txt",'');
$gif = json_decode(file_get_contents("http://www.flamingtext.com/net-fu/image_output.cgi?_comBuyRedirect=false&script=blue-fire&text=".urlencode($text)."&symbol_tagname=popular&fontsize=70&fontname=futura_poster&fontname_tagname=cool&textBorder=15&growSize=0&antialias=on&hinting=on&justify=2&letterSpacing=0&lineSpacing=0&textSlant=0&textVerticalSlant=0&textAngle=0&textOutline=off&textOutline=false&textOutlineSize=2&textColor=%230000CC&angle=0&blueFlame=on&blueFlame=false&framerate=75&frames=5&pframes=5&oframes=4&distance=2&transparent=off&transparent=false&extAnim=gif&animLoop=on&animLoop=false&defaultFrameRate=75&doScale=off&scaleWidth=240&scaleHeight=120&&_=1469943010141"))->src;
SendDocument($chat_id,$gif,"گیف شما با موفقیت ساخته شد !!",$back_,$message_id);
}else{
SendAction($chat_id);
SendMessage($chat_id,"لطفا فقط بصورت متن ارسال کنید !!",'HTML',$back,$message_id);
}}
//----- ADMIN -----//
elseif(preg_match('/^\/([Pp][Aa][Nn][Ee][Ll])/',$text) && $from_id == "$ADMIN"){
save("data/$from_id/step.txt""none");
    bot('SendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"`به منوی مدیریت خوش اومدی ادمین گرامی ...`",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [
                    ['text'=>"آمار کل",'callback_data'=>"stats"],['text'=>"پیام همگانی",'callback_data'=>"public_message"]
                    ]
                ]
            ])
        ]);
}
elseif($data == "panel" && $chatid == "$ADMIN"){
    save("data/$chatid/step.txt","none");
    bot('EditMessageText',[
        'chat_id'=>$chatid,
		'message_id'=>$message_id,
        'text'=>"`به منوی مدیریت خوش اومدی ادمین گرامی ...`",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [
                    ['text'=>"آمار کل",'callback_data'=>"stats"],['text'=>"پیام همگانی",'callback_data'=>"public_message"]
                    ]
                ]
            ])
        ]);
		}
elseif($data == "stats" && $chatid == "$ADMIN"){
    $user = file_get_contents("Member.txt");
    $member_id = explode("\n",$user);
    $member_count = count($member_id) -1;
   bot('EditMessageText',[
        'chat_id'=>$chatid,
		'message_id'=>$message_id,
        'text'=>"`- تعداد کل اعضا ربات شما $member_count ممبر است`",
        'parse_mode'=>'MarkDown',
		'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [
                    ['text'=>"برگشت",'callback_data'=>"panel"]
                    ]
                ]
            ])
        ]);
}
elseif($data == "public_message" && $from_id == "$ADMIN"){
    save("data/$chatid/step.txt","Send to All");
    bot('EditMessageText',[
        'chat_id'=>$chatid,
		'message_id'=>$message_id,
        'text'=>"`- لطفا پیام خود را فقط در قالب متن ارسال کنید :`",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [
                    ['text'=>"لغو",'callback_data'=>"panel"]
                    ]
                ]
            ])
        ]);
}
elseif($step == "Send to All"){
    save("data/$from_id/step.txt","none");
    $all_member = fopen( "Member.txt", "r");
  while( !feof( $all_member)) {
    $user = fgets( $all_member);
    SendMessage($user, $text, "html");
  }
  bot('SendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"`- پیام شما با موفقیت به تمام کاربران ربات ارسال شد !!`",
      'parse_mode'=>'MarkDown',
      'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [
                    ['text'=>"آمار کل",'callback_data'=>"stats"],['text'=>"پیام همگانی",'callback_data'=>"public_message"]
                    ]
                ]
            ])
      ]);
}
?>
