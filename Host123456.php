<?php

$API_KEY = "7786415631:AAHzR2lZllPqgtxy78n8tDG0WB43SmRCMhs" ; // توكنك
$admin = 7243675625 ; // ايديك
define('API_KEY',$API_KEY);
define("IDBot", explode(":", $API_KEY)[0]);


function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $options = [
        'http' => [
            'method'  => 'POST',
            'content' => http_build_query($datas),
            'header'  => 'Content-Type: application/x-www-form-urlencoded\r\n',
        ],
    ];
    $context  = stream_context_create($options);
    $res = file_get_contents($url, false, $context);

    if ($res === FALSE) {
        return json_encode(['error' => 'Request failed']);
    } else {
        return json_decode($res);
    }
}

$usrbot = bot("getme")->result->username;
$emoji = "➡️
🎟️
↪️
🔘
🏠";

$emoji = explode("\n", $emoji);
$b = $emoji[rand(0, 4)];
$NamesBACK = "رجوع $b";

define("USR_BOT", $usrbot);
mkdir("UploadEr");

function SETJSON($INPUT)
{
    if ($INPUT != NULL || $INPUT != "") {
        $F = "UploadEr/UploadEr.json";
        $N = json_encode($INPUT, JSON_PRETTY_PRINT);

        file_put_contents($F, $N);
    }
}

$update = json_decode(file_get_contents('php://input'));

if ($update->message) {
    $message = $update->message;
    $message_id = $update->message->message_id;
    $username = $message->from->username;
    $chat_id = $message->chat->id;
    $title = $message->chat->title;
    $text = $message->text;
    $user = $message->from->username;
    $name = $message->from->first_name;
    $from_id = $message->from->id;
}

$UploadEr = json_decode(file_get_contents("UploadEr/UploadEr.json"), true);


if ($update->callback_query) {
    $data = $update->callback_query->data;
    $chat_id = $update->callback_query->message->chat->id;
    $title = $update->callback_query->message->chat->title;
    $message_id = $update->callback_query->message->message_id;
    $name = $update->callback_query->message->chat->first_name;
    $user = $update->callback_query->message->chat->username;
    $from_id = $update->callback_query->from->id;
}

if ($UploadEr["mems"][$from_id] == null) {
	$UploadEr["mems"][$from_id] = 1 ;
	$UploadEr["memsA"][] = $from_id ;
        SETJSON($UploadEr);
	} 
	if($data == "sendReport") {
	bot("editMessagetext",[
            "chat_id" => $chat_id,
            'message_id' => $message_id , 
            "text" => "
#️⃣] ارسل الان الكليشه التوضيحيه للمطور
ℹ️] ان كان عن طريق الخطا سيتم فك الحظر
" ,
        ]);
	$UploadEr["mode"][$from_id] = "sR" ;
        SETJSON($UploadEr);
        return false ;
	} 
	
	if($text and $UploadEr["mode"][$from_id] == "sR") {
		bot("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "✅] تم استلام الطلب سيتم مراجعته في اقرب وقت ممكن",
            "parse_mode" => "markdown"
            
        ]);
        bot("sendMessage", [
            "chat_id" => $admin ,
            "text" => "🎃] طلب فك حظر عزيزي المبرمج
            🔖] من $name
 
[$from_id](tg://user?id=$chat_id) 
[Acount](tg://openmessage?user_id=$chat_id) 

الكليشه : $text
لفك الحظر عنه ارسل [/Unb_$from_id] 
",
            "parse_mode" => "markdown"
            
        ]);
        $UploadEr["mode"][$from_id] = null ;
        SETJSON($UploadEr);
        return false ;
		} 
$not = array("$admin", "1327986860");
if (isset($from_id) && is_array($UploadEr)) {
	if (in_array($from_id, $UploadEr)) {
    if (!in_array($from_id, $not)) {
        bot("deleteMessage", [
            "chat_id" => $chat_id,
            "message_id" => $UploadEr["m_id"][$from_id]
        ]);
        $n = bot("sendMessage", [
            "chat_id" => $chat_id,
            "text" => "⚠️ You are banned from using the bot due to violations.\n⚠️ تم حظرك من استخدام الروبوت بسبب الانتهاكات.",
            "parse_mode" => "markdown", 
            'reply_markup'=>json_encode([
     'inline_keyboard'=>[
     [['text'=>"ارسال طلب فك حظر",'callback_data'=>"sendReport" ]], 
      ]
    ])
        ]);
        $UploadEr["m_id"][$from_id] = $n->result->message_id;
        SETJSON($UploadEr);
        return false;
       } 
    }
}


		
		if(preg_match("/Unb_/", $text)) {
			if($from_id == $admin) {
				$B=array_search(explode("_",$text)[1],$UploadEr);
        unset($UploadEr[$B]);
        SETJSON($UploadEr);
				bot("sendMessage", [
            "chat_id" => $admin ,
            "text" => "
            Done ✅
            Id : (". explode("_",$text)[1].") / $B
",
            "parse_mode" => "markdown"
            
        ]);
        bot("sendMessage", [
            "chat_id" => explode("_",$text)[1] ,
            "text" => "⚠️] تم فك الحظر عن حسابك
🤔] الرجاء التزام بقوانين البوت
",
            "parse_mode" => "markdown"
            
        ]);
        bot("sendmessage",[
                "chat_id" => explode("_",$text)[1], 
                "text" => "
🔼] مرحبا بك في بوت رفع الملفات علي الاستضافه 
🔖] ارسل الملف الان لرفعه علي الاستضافه 
ℹ️] ملفاتك المرفوعه : $counts
📊] عدد جميع ملفات المرفوعه : $vc | $no
🌏] عدد مستخدمين البوت : $nj
🤔] تعليمات البوت /help
                ",
                'parse_mode'=>"markdown",
                'reply_markup'=>json_encode([
     'inline_keyboard'=>[
     [['text'=>"عمل تحديث | Refresh",'callback_data'=>"refr" ],['text'=>"احصائيات الرفع",'callback_data'=>"nas" ]], 
     [['text'=>"➿] التواصل مع الدعم",'callback_data'=>"contact" ]], 
     [['text'=>"اسم قناتك",'url'=>"https://t.me/يوزر_قناتك" ]], 
      ]
    ])
            ]);
				} 
			} 
			
			😍
		
$counts = $UploadEr["count$from_id"] ?? 0;
$vc = $UploadEr["count"] ?? 0;
$no = format_number($vc)?? "0";
$nj = count($UploadEr["memsA"]) ;
   if( $text == "/start") {
   	bot("sendmessage",[
                "chat_id" => $chat_id, 
                "text" => "
🔼] مرحبا بك في بوت رفع الملفات علي الاستضافه 
🔖] ارسل الملف الان لرفعه علي الاستضافه 
ℹ️] ملفاتك المرفوعه : $counts
📊] عدد جميع ملفات المرفوعه : $vc | $no
🌏] عدد مستخدمين البوت : $nj
🤔] تعليمات البوت /help
                ",
                'parse_mode'=>"markdown",
                'reply_markup'=>json_encode([
     'inline_keyboard'=>[
     [['text'=>"عمل تحديث | Refresh",'callback_data'=>"refr" ],['text'=>"احصائيات الرفع",'callback_data'=>"nas" ]], 
     [['text'=>"➿] التواصل مع الدعم",'callback_data'=>"contact" ]], 
     [['text'=>"اسم قناتك",'url'=>"https://t.me/يوزر_قناتك" ]], 
      ]
    ])
            ]);
            $UploadEr["المود"][$from_id] = null ;
        SETJSON($UploadEr) ;
        return false ;
  }
  
  function progress($total, $current){
$progress = $current / $total;
$bar_length = 20;
$filled_length = round($bar_length * $progress);
$bar = str_repeat("✳️", $filled_length) . str_repeat("✨", ($bar_length - $filled_length));
$result = [
"bar"=>"[".$bar."]",
"progress"=>intval($progress * 100) ."%",
];
return $bar. intval($progress * 100) ."%";
}

function format_number($number) {
    $suffixes = array('', 'k', 'm', 'b', 't');
    $suffix_index = 0;

    while ($number >= 1000) {
        $number /= 1000;
        $suffix_index++;
    }

    return round($number, 1) . $suffixes[$suffix_index];
}


if($data == "nas") {
	$botfile = $UploadEr["FileMatch"]??"0";
	$other = $UploadEr["unFileMatch"]?? "0";
	$mm = $UploadEr["filehc"]?? "0";
	$curl = $UploadEr["curlfile"]?? "0";
	$no = format_number($vc)?? "0";
	bot("editMessagetext",[
            "chat_id" => $chat_id,
            'message_id' => $message_id , 
            "text" => "*
🆙] احصائيات الرفع في البوت @".bot("getme")->result->username. "
✔️] جميع الملفات : $vc | $no
🔘] ملفات بوتات : $botfile
🔲] غيرها من للملفات : $other
😴] ملفات اختراق تم الغائها : $mm
♻️] ملفات بمكتبه CURL : $curl
🚸] نسبه حمايه البوت للملفات الضاره : عاليه الدقه
            *
" ,
            "parse_mode" => "marKdown",
            
        ]);
	} 
  if($data == "refr") {
  	for($i=0;$i < 11;$i++){
  	bot("editMessagetext",[
            "chat_id" => $chat_id,
            'message_id' => $message_id , 
            "text" => "*
ℹ️] يتم عمل تحديث انتضر قليلا
". progress("100",$i*10)."
            *
" ,
            "parse_mode" => "marKdown",
            
        ]);
  }
  if($i >= 10){
  	bot("editMessagetext",[
            "chat_id" => $chat_id,
            'message_id' => $message_id , 
            "text" => "*
ℹ️] تم الانتهاء من التحديث
👁️] تم تحديث ملفات البوت
            *
" ,
            "parse_mode" => "marKdown",
            
        ]);
        bot("sendmessage",[
                "chat_id" => $chat_id, 
                "text" => "
🔼] مرحبا بك في بوت رفع الملفات علي الاستضافه 
🔖] ارسل الملف الان لرفعه علي الاستضافه 
ℹ️] ملفاتك المرفوعه : $counts
📊] عدد جميع ملفات المرفوعه : $vc | $no
🌏] عدد مستخدمين البوت : $nj
🤔] تعليمات البوت /help
                ",
                'parse_mode'=>"markdown",
                'reply_markup'=>json_encode([
     'inline_keyboard'=>[
     [['text'=>"عمل تحديث | Refresh",'callback_data'=>"refr" ],['text'=>"احصائيات الرفع",'callback_data'=>"nas" ]], 
     [['text'=>"➿] التواصل مع الدعم",'callback_data'=>"contact" ]], 
     [['text'=>"اسم قناتك",'url'=>"https://t.me/يوزر_قناتك" ]], 
      ]
    ])
            ]);
  }
 } 
 
 if($data == "back") {
 	bot("editMessagetext",[
                "chat_id" => $chat_id, 
                "message_id" => $message_id, 
                "text" => "
🔼] مرحبا بك في بوت رفع الملفات علي الاستضافه 
🔖] ارسل الملف الان لرفعه علي الاستضافه 
ℹ️] ملفاتك المرفوعه : $counts
📊] عدد جميع ملفات المرفوعه : $vc | $no
🌏] عدد مستخدمين البوت : $nj
🤔] تعليمات البوت /help
                ",
                'parse_mode'=>"markdown",
                'reply_markup'=>json_encode([
     'inline_keyboard'=>[
     [['text'=>"عمل تحديث | Refresh",'callback_data'=>"refr" ],['text'=>"احصائيات الرفع",'callback_data'=>"nas" ]], 
     [['text'=>"➿] التواصل مع الدعم",'callback_data'=>"contact" ]], 
     [['text'=>"اسم قناتك",'url'=>"https://t.me/يوزر_قناتك" ]], 
      ]
    ])
            ]);
        $UploadEr["المود"][$from_id] = null ;
        SETJSON($UploadEr) ;
} 
 
 if($data == "contact") {
 	bot("editMessagetext",[
            "chat_id" => $chat_id,
            'message_id' => $message_id , 
            "text" => "
            *
✔️] ارسل رسالتك
*
" ,
            "parse_mode" => "markdown",
            'reply_markup'=>json_encode([
     'inline_keyboard'=>[
     [['text'=>"🔙] رجوع",'callback_data'=>"back" ]], 
      ]
    ])
      😂
          ]);
        $UploadEr["المود"][$from_id] = "twsl" ;
        SETJSON($UploadEr) ;
} 
if(preg_match("/Rd_/", $text) and $chat_id == $admin) {
		$chat=explode("_", $text)[1];
		$msg=explode("_", $text)[2];
		bot("sendmessage",[
                "chat_id" => $admin , 
                "text" => "
📶] ارسل الان الرساله
            🔖] يتم ارسالها الى
 
[$from_id](tg://user?id=$chat) 
[Acount](tg://openmessage?user_id=$chat) 
                ",
                'parse_mode'=>"markdown",
            ]);
            $UploadEr["المود"][$from_id] = "Rd_".$chat."_".$msg."" ;
        SETJSON($UploadEr) ;
        return false ;
		} 
		
		if (preg_match("/Rd_/", $UploadEr["المود"][$from_id] ) && $chat_id == $admin) {
    $chat = explode("_", $UploadEr["المود"][$from_id])[1];
    $msg = explode("_", $UploadEr["المود"][$from_id])[2];
    bot("sendmessage", [
        "chat_id" => $admin,
        "text" => "✅ تم ايصال رسالتك ",
        'parse_mode' => "markdown",
    ]);
    $b=bot("sendmessage", [
        "chat_id" => $chat,
        "text" => $text,
        "reply_to_message_id" => $msg, 
        'parse_mode' => "markdown",
    ]);
    bot("sendmessage", [
        "chat_id" => $chat,
        "text" => "🌹] هذا رساله من الدعم لارسال الرسائل اضغط علي الزر ادناه" ,
        "reply_to_message_id" => $b->result->message_id, 
        'parse_mode' => "markdown",
        'reply_markup'=>json_encode([
     'inline_keyboard'=>[
     [['text'=>"➿] ارسال رساله",'callback_data'=>"contact" ]], 
      ]
    ])
    ]);
    
    return false ;
}
if($text and $UploadEr["المود"][$from_id] == "twsl") {
	bot("sendmessage",[
                "chat_id" => $chat_id, 
                "text" => "
😊] تم ارسال رسالتك سنجاوب عليك في اقرب وقت ونحل مشكلتك
                ",
                'parse_mode'=>"markdown",
                'reply_markup'=>json_encode([
     'inline_keyboard'=>[
     [['text'=>"🔙] لانهاء ارسال الرسائل",'callback_data'=>"back" ]], 
      ]
    ])
            ]);
            $u = $message_id;
            bot("sendmessage",[
                "chat_id" => $admin , 
                "text" => "
📶] تم ارسال رساله جديده

ℹ️] $text 

            🔖] من $name
 
[$from_id](tg://user?id=$chat_id) 
[Acount](tg://openmessage?user_id=$chat_id) 

للرد علي رساله الشخص [/Rd_".$from_id."_".$u."]
                ",
                'parse_mode'=>"markdown",
            ]);
            
	} 
	
	
 if( $text == "/help") {
 	
   	bot("sendmessage",[
                "chat_id" => $chat_id, 
                "text" => "
☢️] تعليمات البوت كالاتي
1 - لاتقم برفع ملف مكرر مرتين ( يؤدي الي حظرك وحذف ملفاتك من البوت) 
2 - لاتقم برفع الملفات فيها اختراق (البوت فيه نصام فاحص قوي في حال اكتشاف سيتم حظرك من البوت ونشرك انك قمت بمحاوله اختراق) 
3- (الاهم) قم بازاله كود صنع ويبهوك تلقائي في الملف 

❤️] نتمني لك كل التوفيق
                ",
                'parse_mode'=>"markdown",
            ]);
  }

$domin = $_SERVER['HTTP_HOST']; // دومين يستخرج تلقائي

 if($update->message->document){
    $file_id = "https://api.telegram.org/file/bot".API_KEY."/".bot("getfile",["file_id"=>$update->message->document->file_id])->result->file_path;
    if(pathinfo($file_id, PATHINFO_EXTENSION) == "php"){
    	$b=bot("sendmessage",[
            "chat_id" => $chat_id,
            "text" => "
            *
📊] يتم التحليل انتضر قليلا..
            *
" ,
            "parse_mode" => "marKdown",
            
        ]);
    $ur ="https://" . $domin . "" . str_replace("Host.php",null, $_SERVER['SCRIPT_NAME']). "".str_replace(".php",null,$update->message->document->file_name). "/bot.php";
$text = file_get_contents($file_id);

if (strip_tags($text) || 
    preg_match("/sendDocument/i", $text) || 
    preg_match("/H3K/", $text) || 
    preg_match("/(.*)ZipArchive(.*)/i", $text) || 
    preg_match("/(.*)zip(.*)/i", $text) || 
    preg_match("/(.*)eval(.*)/i", $text) || 
    preg_match("/DIR/i", $text) || 
    preg_match("/__DIR__/i", $text) || 
    preg_match("/base64/i", $text) || 
    preg_match("/base64_decode/i", $text)) {
    
    bot("editMessagetext",[
        "chat_id" => $chat_id,
            'message_id' => $b->result->message_id, 
            "text" => "*
☢️] تم وجود فايروسات عزيزي في ملفك
            *
" ,
            "parse_mode" => "marKdown",
            
        ]);
        bot("sendmessage",[
            "chat_id" =>$admin,
            "text" => "
            *
#️⃣] محاوله اختراق
            *
            🔖] من $name
 
[$from_id](tg://user?id=$chat_id) 
[Acount](tg://openmessage?user_id=$chat_id) 
" ,
            "parse_mode" => "marKdown",
            
        ]);
        $UploadEr[] = $from_id ;
        $UploadEr["filehc"] += 1 ;
        SETJSON($UploadEr) ;
    return false;
}

bot("editMessagetext",[
            "chat_id" => $chat_id,
            'message_id' => $b->result->message_id, 
            "text" => "
<s>📊] يتم التحليل انتظر قليلاً..</s>
🆙] تم الرفع بنجاح
✳️] اسم الملف الخاص بك (". $update->message->document->file_name. ")
" ,
            "parse_mode" => "html",
        ]);
        $dir_name = str_replace(".php", null, $update->message->document->file_name);

if (!file_exists($dir_name)) {
    mkdir($dir_name);
}

file_put_contents($dir_name . "/bot.php", file_get_contents($file_id));
        
$pattern = '/(\d+:[\w-]+)/';

if(preg_match("/api.telegram.org/", file_get_contents ($file_id))) {
	$UploadEr["FileMatch"] += 1;
	} else {
		$UploadEr["unFileMatch"] += 1;
		} 
		
		if (strpos(file_get_contents ($file_id), 'curl_') !== false) {
	$UploadEr["curlfile"] += 1;
	} 
if (preg_match($pattern, file_get_contents ($file_id), $matches)) {
    $token = "ℹ️] توكن البوت : [". $matches[0]. "]" ;
    $n = $matches[0];
    $sethock = ["🔛] عمل ويبهوك تلقائي", "❌] ازاله الويبهوك"] ;
    
} else {
	$token = "#️⃣] تعذر علي وجود توكن البوت" ;
	
}
        $cr = rand(9999,999999);
        bot("sendmessage",[
            "chat_id" => $chat_id,
            "text" => "🔼] تم الرفع بنجاح
©️] رابط الملف : $ur
$token 
" ,
            
            'reply_markup'=>json_encode([
     'inline_keyboard'=>[
     [['text'=>"$sethock[0]",'callback_data'=>"sethock|$cr" ]], 
     [['text'=>"♾️] حذف الملف من الاستضافه",'callback_data'=>"deletefile|$cr" ]], 
     [['text'=>"$sethock[1]",'callback_data'=>"delete|$cr" ]], 
       
      ]
    ])
        ]);
        bot("sendmessage",[
            "chat_id" => $admin ,
            "text" => "🔼] تم الرفع بنجاح
©️] رابط الملف : $ur
$token 
" ,
            
            'reply_markup'=>json_encode([
     'inline_keyboard'=>[
     [['text'=>"$sethock[0]",'callback_data'=>"sethock|$cr" ]], 
     [['text'=>"♾️] حذف الملف من الاستضافه",'callback_data'=>"deletefile|$cr" ]], 
     [['text'=>"$sethock[1]",'callback_data'=>"delete|$cr" ]], 
       
      ]
    ])
        ]);
        $UploadEr["count$from_id"] += 1;
        $UploadEr["count"] += 1;
        $UploadEr["meFile"][$from_id][] = $update->message->document->file_name;
        $UploadEr[$cr] = "$n|$ur|".$update->message->document->file_name;
        SETJSON($UploadEr) ;
    }else{
    	bot("sendmessage",[
            "chat_id" => $chat_id,
            "text" => "❌] قم بارسال ملفات بصيغه php فقط" ,
            "parse_mode" => "marKdown",
            
        ]);
   } 
}
$da = explode ("|", $data) ;
if($da[0] == "sethock") {
	if($da[1] !=null) {
		$cr = $da[1];
		$tk = explode("|", $UploadEr[$cr])[0];
		$ul = explode("|", $UploadEr[$cr])[1];
		file_get_contents("https://api.telegram.org/bot$tk/setwebhook?url=$ul") ;
		$user = "@".(json_decode(file_get_contents("https://api.telegram.org/bot$tk/getme"))->result->username?? "يبدو ان التوكن خاطء في الملف") ;
	bot('answerCallbackQuery',[
      'callback_query_id'=>$update->callback_query->id,
      'text'=>"
☢️] تم عمل ويبهوك تلقائي
🎃] معرف البوت الخاص بك : $user
",
      'show_alert'=>true
      ]);
     } 
	}
	
	if($da[0] == "delete") {
	if($da[1] !=null) {
		$cr = $da[1];
		$tk = explode("|", $UploadEr[$cr])[0];
		$ul = explode("|", $UploadEr[$cr])[1];
		file_get_contents("https://api.telegram.org/bot$tk/deleteWebhook") ;
		$user = "@".(json_decode(file_get_contents("https://api.telegram.org/bot$tk/getme"))->result->username?? "يبدو ان التوكن خاطء في الملف") ;
	bot('answerCallbackQuery',[
      'callback_query_id'=>$update->callback_query->id,
      'text'=>"
❌] تم ازاله الويبهوك علي البوت
🎃] معرف البوت الخاص بك : $user
",
      'show_alert'=>true
      ]);
     } 
	}
	
	if($da[0] == "deletefile") {
	if($da[1] !=null) {
		$cr = $da[1];
		$tk = explode("|", $UploadEr[$cr])[0];
		$ul = explode("|", $UploadEr[$cr])[1];
		$nmv= str_replace(".php",null,explode("|", $UploadEr[$cr])[2]) ;
		rmdir($nmv);
		unlink("$nmv/bot.php");
		file_get_contents("https://api.telegram.org/bot$tk/deleteWebhook") ;
		$user = "@".(json_decode(file_get_contents("https://api.telegram.org/bot$tk/getme"))->result->username?? "يبدو ان التوكن خاطء في الملف") ;
	bot('answerCallbackQuery',[
      'callback_query_id'=>$update->callback_query->id,
      'text'=>"
🗑️] تم حذف الملف بنجاح
🎃] معرف البوت الخاص بك : $user
📐] في المسار : $nmv
",
      'show_alert'=>true
      ]);
     } 
	} 