<?php

ob_start();

define('API_KEY','توکن');

//----- Functions -----//
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
function sendmessage($chat_id, $text, $model){
 bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>$text,
 'parse_mode'=>$mode
 ]);
}
function sendphoto($chat_id, $photo, $captionl){
 bot('sendphoto',[
 'chat_id'=>$chat_id,
 'photo'=>$photo,
 'caption'=>$caption,
 ]);
 }
 function sendvideo($chat_id, $video, $caption){
 bot('sendvideo',[
 'chat_id'=>$chat_id,
 'video'=>$video,
 'caption'=>$caption
 ]);
 }
 function forwardmessage($chat_id, $from_chat_id, $message_id){
     bot('forwardMessage',[
        "chat_id"=>$chat_id,
        "from_chat_id"=>$chat_id,
        "message_id"=>$update->message->message_id,
    ]);
 }
function save($filename,$TXTdata)
  {
  $myfile = fopen($filename, "w") or die("Unable to open file!");
  fwrite($myfile, "$TXTdata");
  fclose($myfile);
  }

//----- Variables -----//
 
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$from_id = $message->from->id;
$chat_id = $message->chat->id;
$first_name = $message->from->first_name;
$last_name = $message->from->last_name;
$username = $message->from->userame;
$text = $message->text;
$chatid = $update->callback_query->message->chat->id;
$data = $update->callback_query->data;
$message_id = $update->callback_query->message->message_id;
$step = file_get_contents("data/$from_id/step.txt");
$ADMIN = "آیدی عددی ادمین";
//کد Api دریافت گیف
$gif_info = json_decode(file_get_contents("http://batsazfree.tk/gif.php?text=$text"));
$gif = $gif_info->src;

//----- Bot Codes -----//
flush();
mkdir("data");
mkdir("data/$from_id");
//تشخیص متن استارت
if(preg_match('/^\/([Ss][Tt][Aa][Rr][Tt])/',$text)){
$user = file_get_contents('Member.txt');
    $members = explode("\n",$user);
    if (!in_array($chat_id,$members)){
      $add_user = file_get_contents('Member.txt');
      $add_user .= $chat_id."\n";
     file_put_contents('Member.txt',$add_user);
    }
bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>"سلام <a href='https://t.me/'>$first_name</a> ...	
به ربات ساخت گیف خوش اومدی !!

جهت ساخت گیف دکمه زیر رو لمس کن ...",
	'parse_mode'=>'HTML',
	'reply_markup'=>json_encode([
	'inline_keyboard'=>[
	[
	['text'=>"ساخت گیف",'callback_data'=>"gif"]
	]
	]
])
]);
}
elseif($data == "gif"){
save("data/$chatid/step.txt","gif");
	bot('EditMessageText',[
	'chat_id'=>$chatid,
	'message_id'=>$message_id,
	'text'=>"لطفا پیام خود را فقط در قالب متن ارسال کنید ...",
	'reply_markup'=>json_encode([
	'inline_keyboard'=>[
	[
	['text'=>"برگشت",'callback_data'=>"menu"]
	]
	]
	])
	]);		
}
elseif($step == "gif"){
	if(!isset($text)){
             bot('SendMessage',[
                 'chat_id'=>$chat_id,
                 'text'=>"`- لطفا فقط بصورت متن ارسال کنید ...`",
                 'parse_mode'=>'MarkDown',
                  'reply_markup'=>json_encode([
             'inline_keyboard'=>[
                 [
                     ['text'=>"برگشت",'callback_data'=>"menu"]
                     ]
                 ]
             ])
                 ]);
         }else{
	save("data/$from_id/step.txt","none");
	bot('SendDocument',[
	'chat_id'=>$chat_id,
	'document'=>$gif,
	'caption'=>"گیف شما ساخته شد !!",
	'reply_markup'=>json_encode([
	'inline_keyboard'=>[
	[
	['text'=>"ساخت دوباره",'callback_data'=>"creategif"]
	],
	[
	['text'=>"برگشت",'callback_data'=>"menuu"]
	]
	]
	])
	]);
}
}
elseif($data == "menu"){
	save("data/$chatid/step.txt","none");
	bot('EditMessageText',[
	'chat_id'=>$chatid,
	'message_id'=>$message_id,
	'text'=>"به منوی اصلی برگشتید ...
	
انتخاب کنید ...",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[
['text'=>"ساخت گیف",'callback_data'=>"gif"]
]
]
])
	]);
}
elseif($data == "menuu"){
	save("data/$chatid/step.txt","none");
	bot('SendMessage',[
	'chat_id'=>$chatid,
	'message_id'=>$message_id,
	'text'=>"به منوی اصلی برگشتید ...
	
انتخاب کنید ...",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[
['text'=>"ساخت گیف",'callback_data'=>"gif"]
]
]
])
	]);
}
elseif($data == "creategif"){
	save("data/$chatid/step.txt","gif");
	bot('SendMessage',[
	'chat_id'=>$chatid,
	'text'=>"لطفا پیام خود را فقط در قالب متن ارسال کنید ...",
	'reply_markup'=>json_encode([
	'inline_keyboard'=>[
	[
	['text'=>"برگشت",'callback_data'=>"menu"]
	]
	]
	])
	]);		
}

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

//-----ADMIN-----//

//-----Bot Codes-----//

// نوشته شده توسط : @Mahdi_Elvis

?>
