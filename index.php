<?php
ob_start();
error_reporting(E_ALL);
date_Default_timezone_set('Asia/Tashkent');

const AlijonovUz = "BOT TOKEN";
$AlijonovUz = "ADMIN ID";

$bot = bot('getMe', ['bot'])->result->username;
$botname = bot('getMe', ['bot'])->result->first_name;
$bot_id = bot('getMe', ['bot'])->result->id;
$soat = date('H:i');
$soat2 = date('H:i:s');
$sana = date("d.m.Y");

require('sql.php');

/*
$a = file_get_contents("https://api.telegram.org/bot".AlijonovUz."/setWebhook?url=".$_SERVER['SERVER_NAME']."".$_SERVER['SCRIPT_NAME']);
echo $a;
*/

function deleteFolder($path)
{
	if (is_dir($path) === true) {
		$files = array_diff(scandir($path), array('.', '..'));
		foreach ($files as $file)
			deleteFolder(realpath($path) . '/' . $file);
		return rmdir($path);
	} else if (is_file($path) === true)
		return unlink($path);
	return false;
}

function ping($chat_id, $text)
{
	$startTime = microtime(true);

	$url = "http://localhost:8081/bot" . AlijonovUz . "/sendMessage";
	$postData = [
		'chat_id' => $chat_id,
		'text' => $text
	];

	$options = [
		'http' => [
			'method' => 'POST',
			'header' => 'Content-type: application/x-www-form-urlencoded',
			'content' => http_build_query($postData),
		],
	];

	$context = stream_context_create($options);
	$response = file_get_contents($url, false, $context);
	$endTime = microtime(true);
	$responseTime = ($endTime - $startTime) * 1000;

	return number_format($responseTime, 2, '.', ',');
	;
}

function soat($date, $lang)
{

	$ex = explode(":", $date);
	$hour = $ex[0];
	$minute = $ex[1];
	$second = $ex[2];

	$a = 23 - $hour;
	$b = 59 - $minute;
	$c = 59 - $second;

	if ($lang == "uz") {
		$tx = "$a soat $b daqiqa $c soniya";
	} elseif ($lang == "ru") {
		$tx = "$a час $b минута $c секунды";
	} elseif ($lang == "en") {
		$tx = "$a hours $b minutes $c seconds";
	}

	return $tx;
}

function kun($kun, $oxirgi)
{
	$boshlangich = new DateTime($kun);
	$oxirgiDateTime = new DateTime($oxirgi);
	$farq = $boshlangich->diff($oxirgiDateTime);
	return $farq->days;
}

function getAdmin($chat)
{
	$url = "https://api.telegram.org/bot" . AlijonovUz . "/getChatAdministrators?chat_id=@" . $chat;
	$result = file_get_contents($url);
	$result = json_decode($result);
	return $result->ok;
}

function formatJson($jsonString)
{
	$formattedString = stripslashes($jsonString);
	$formattedString = str_replace('\n', "\n", $formattedString);
	$formattedString = str_replace('\\', '', $formattedString);
	return $formattedString;
}

function joinchat($id, $lang, $connect)
{
	$texts = array(
		'uz' => [
			'text' => [
				'join' => "⚠️ <b>Botdan to'liq foydalanish uchun</b> quyidagi kanallarga obuna bo'ling:",
			],
			'button' => [
				'check' => "🔄 Tekshirish",
			],
		],

		'ru' => [
			'text' => [
				'join' => "⚠️ <b>Чтобы полноценно использовать бота</b>, подпишитесь на следующие каналы:",
			],
			'button' => [
				'check' => "🔄 Проверить",
			],
		],

		'en' => [
			'text' => [
				'join' => "⚠️ <b>To fully use the bot</b> subscribe to the following channels:",
			],
			'button' => [
				'check' => "🔄 Check",
			],
		],
	);

	$select = mysqli_query($connect, "SELECT * FROM channels");
	$num = mysqli_num_rows($select);

	if ($num == 0) {
		return true;
	} elseif ($num > 0) {
		while ($fetch = mysqli_fetch_assoc($select)) {
			$kanal = $fetch['url'];

			$a = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getchat?chat_id=$kanal"));
			$title = $a->result->title;

			$get = bot('getChatMember', [
				'chat_id' => $kanal,
				'user_id' => $id,
			]);

			$status = $get->result->status;
			if ($status == "member" or $status == "administrator" or $status == "creator") {
				$key[] = ["text" => "✅ $title", "url" => "https://t.me/" . str_replace('@', '', $kanal)];
			} else {
				$key[] = ["text" => "❌ $title", "url" => "https://t.me/" . str_replace('@', '', $kanal)];
				$uns = true;
			}

			$keys = array_chunk($key, 1);
			$keys[] = [['text' => $texts[$lang]['button']['check'], 'callback_data' => "result"]];
			$keyboard = json_encode([
				'inline_keyboard' => $keys,
			]);
		}
		if ($uns == true) {
			bot('sendMessage', [
				'chat_id' => $id,
				'text' => $texts[$lang]['text']['join'],
				'parse_mode' => 'html',
				'reply_markup' => $keyboard,
			]);
			exit();
		} else {
			return true;
		}
	}
}

function uploadFile($filePath)
{
	$url = 'https://chatgpt4online.org/wp-json/mwai-ui/v1/files/upload';
	$nonce = json_decode(file_get_contents("https://byopenai.uz/bots/include/nonce.php"), true)['restNonce'];
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'sec-ch-ua: "Not)A;Brand";v="99", "Google Chrome";v="127", "Chromium";v="127"',
		'sec-ch-ua-platform: "Windows"',
		'Referer: https://chatgpt4online.org/',
		'sec-ch-ua-mobile: ?0',
		'X-WP-Nonce: ' . $nonce,
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36'
	]);

	$data = [
		'file' => new CURLFile($filePath),
		'type' => 'N/A',
		'purpose' => 'N/A'
	];

	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$response = curl_exec($ch);
	$error = curl_error($ch);
	curl_close($ch);

	return json_decode($response);
}

function gpt($text, $photo_id, $chat_id)
{
	$historyFile = "data/$chat_id.json";
	$api = file_get_contents("other/gpt4.txt");

	$chatHistory = file_exists($historyFile) ? json_decode(file_get_contents($historyFile), true) : [];
	$max_length = 4096;
	$current_length = 0;

	foreach ($chatHistory as $message) {
		$current_length += strlen($message["content"]);
	}

	while (count($chatHistory) > 0 && $current_length > $max_length) {
		$removed_message = array_shift($chatHistory);
		$current_length -= strlen($removed_message["content"]);
	}

	$url = 'https://chatgpt4online.org/wp-json/mwai-ui/v1/chats/submit';
	$nonce = json_decode(file_get_contents("https://byopenai.uz/bots/include/nonce.php"), true)['restNonce'];
	$data = [
		"botId" => "default",
		"customId" => null,
		"session" => "N/A",
		"chatId" => $chat_id,
		"contextId" => 5410,
		"messages" => $chatHistory,
		"newMessage" => $text,
		"newFileId" => $photo_id,
		"stream" => true
	];
	$jsonData = json_encode($data);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Accept: text/event-stream',
		'Referer: https://chatgpt4online.org/',
		'X-WP-Nonce: ' . $nonce,
		'sec-ch-ua: "Not)A;Brand";v="99", "Google Chrome";v="127", "Chromium";v="127"',
		'sec-ch-ua-mobile: ?0',
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36',
		'sec-ch-ua-platform: "Windows"'
	]);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

	$response = curl_exec($ch);
	$error = curl_error($ch);
	curl_close($ch);

	$pattern = '/data: {"type":"live","data":"(.*?)"}/';
	preg_match_all($pattern, $response, $matches);
	$fullText = implode('', $matches[1]);

	$endPattern = '/data: {"type":"end","data":"(.*?)"}/';
	preg_match($endPattern, $response, $endMatch);
	$finalJson = isset($endMatch[1]) ? stripslashes($endMatch[1]) : null;

	if ($photo_id != null) {
		$result = "Rasm yuklandi!\n$text";
	} else {
		$result = $text;
	}

	if ($finalJson == null) {
		$return = json_decode($response);
		$error = $return->message;
	} else {
		$return = json_decode($finalJson);
		$reply = $return->reply;
	}

	if ($error != null and $reply == null) {
		$fullText2 = $error;
	} elseif ($error == null and $reply != null) {
		$fullText2 = $reply;
	} elseif ($error == null and $reply == null) {
		if (!$text) {
			$fullText2 = "From OpenAI: Error, invalid image!";
		} elseif (!$photo_id) {
			$fullText2 = "From OpenAI: Internal server error!";
		}
	}

	$chatHistory[] = [
		"id" => uniqid(),
		"role" => "user",
		"content" => $result,
		"who" => "User: ",
		"timestamp" => time() * 1000,
	];

	$chatHistory[] = [
		"id" => uniqid(),
		"role" => "assistant",
		"content" => $fullText2,
		"who" => "AI: ",
		"timestamp" => time() * 1000,
	];

	$encodedHistory = json_encode($chatHistory, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	file_put_contents($historyFile, $encodedHistory);

	return $return;
}

function bot($method, $datas = [])
{
	$url = "https://api.telegram.org/bot" . AlijonovUz . "/" . $method;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
	$res = curl_exec($ch);
	if (curl_error($ch)) {
		var_dump(curl_error($ch));
	} else {
		return json_decode($res);
	}
}

$alijonov = json_decode(file_get_contents('php://input'));
$message = $alijonov->message;
$cid = $message->chat->id;
$name = $message->chat->first_name;
$mid = $message->message_id;
$type = $message->chat->type;
$text = $message->text;
$from_id = $message->from->id;
$caption = $message->caption;
$premium = $message->from->is_premium;
$language = $message->from->language_code;
$name = $message->from->first_name;
$familya = $message->from->last_name;
$bio = $message->from->about;
$username = $message->from->username;
$chat_id = $message->chat->id;
$message_id = $message->message_id;
$reply = $message->reply_to_message->text;
$nameru = "<a href='tg://user?id=$uid'>$name $familya</a>";

$edit = $alijonov->edited_message;
$e_text = $edit->text;
$e_cid = $edit->chat->id;
$e_mid = $edit->message_id;

$delete = $alijonov->my_chat_member->new_chat_member;
$delete_id = $alijonov->my_chat_member->from->id;
$status = $delete->status;

$photo = $message->photo;
$video = $message->video;
$audio = $message->audio;
$document = $message->document;

$successful_payment = $alijonov->message->successful_payment;
$currency = $successful_payment->currency;
$amount = $successful_payment->total_amount;
$invoice_payload = $successful_payment->invoice_payload;

$data = $alijonov->callback_query->data;
$qid = $alijonov->callback_query->id;
$id = $alijonov->inline_query->id;
$query = $alijonov->inline_query->query;
$query_id = $alijonov->inline_query->from->id;
$cid2 = $alijonov->callback_query->message->chat->id;
$mid2 = $alijonov->callback_query->message->message_id;
$premium2 = $alijonov->callback_query->from->is_premium;
$callfrid = $alijonov->callback_query->from->id;
$callname = $alijonov->callback_query->from->first_name;
$calluser = $alijonov->callback_query->from->username;
$surname = $alijonov->callback_query->from->last_name;
$about = $alijonov->callback_query->from->about;
$nameuz = "<a href='tg://user?id=$callfrid'>$callname $surname</a>";

if (file_get_contents("other/bots.txt")) {
} else {
	if (file_put_contents("other/bots.txt", "on"))
		;
}
if (file_get_contents("other/limit.txt")) {
} else {
	if (file_put_contents("other/limit.txt", "10"))
		;
}

$bots = file_get_contents("other/bots.txt");
$limits = file_get_contents("other/limit.txt");
mkdir('data');
mkdir('other');
mkdir('image');
mkdir('include');

$res = mysqli_query($connect, "SELECT*FROM user_id WHERE user_id = '$cid$cid2$e_cid'");
while ($a = mysqli_fetch_assoc($res)) {
	$user_id = $a['user_id'];
	$token = $a['token'];
	$ban = $a['ban'];
	$holat = $a['holat'];
	$rejim = $a['rejim'];
	$limit = $a['limit'];
	$pul = $a['pul'];
	$lang = $a['lang'];
	$step = $a['step'];
}

$res = mysqli_query($connect, "SELECT * FROM admins WHERE user_id = '$cid$cid2'");
while ($a = mysqli_fetch_assoc($res)) {
	$admin = $a['user_id'];
	$appointed = $a['appointed'];
	$admin_channel = $a['channel'];
	$admin_bot = $a['bot'];
	$admin_search = $a['search'];
	$admin_stat = $a['stat'];
	$admin_send = $a['send'];
	$admin_limit = $a['limit'];
	$admin_admin = $a['admin'];
}

include "include/texts.php";
include "include/panel.php";

$admin_commands = json_encode([
	['command' => "/start", 'description' => $texts[$lang]['commands']['start']],
	['command' => "/settings", 'description' => $texts[$lang]['commands']['settings']],
	['command' => "/ai", 'description' => $texts[$lang]['commands']['ai']],
	['command' => "/panel", 'description' => $texts[$lang]['commands']['panel']],
]);

$user_commands = json_encode([
	['command' => "/start", 'description' => $texts[$lang]['commands']['start']],
	['command' => "/settings", 'description' => $texts[$lang]['commands']['settings']],
	['command' => "/ai", 'description' => $texts[$lang]['commands']['ai']],
]);

$result = mysqli_query($connect, "SELECT * FROM admins WHERE user_id = '$from_id'");
$rew = mysqli_fetch_assoc($result);

$commands = $rew ? $admin_commands : $user_commands;

bot('setMyCommands', [
	'commands' => $commands,
	'scope' => json_encode([
		'type' => 'chat',
		'chat_id' => $from_id,
	]),
]);

if (isset($delete)) {
	if ($status == "kicked") {
		mysqli_query($connect, "UPDATE user_id SET holat = 'nofaol' WHERE user_id = '$delete_id'");
		unlink("data/$delete_id.json");
	}
}

if (isset($message)) {
	if ($ban == "ban") {
		exit();
	}
}

if (isset($data)) {
	if ($ban == "ban") {
		exit();
	}
}

$pre_checkout_query = $alijonov->pre_checkout_query ?? null;
if ($pre_checkout_query) {
	$query_id = $pre_checkout_query->id;
	$amount = $pre_checkout_query->total_amount;
	$currency = $pre_checkout_query->currency;
	if (($amount >= 1 and $amount <= 2500) && $currency == "XTR") {
		bot('answerPreCheckoutQuery', [
			'pre_checkout_query_id' => $query_id,
			'ok' => true
		]);
	} else {
		bot('answerPreCheckoutQuery', [
			'pre_checkout_query_id' => $query_id,
			'ok' => false,
			'error_message' => "Invalid payment amount or currency."
		]);
	}
}

if (isset($successful_payment)) {
	if ($currency == "XTR" && ($amount >= 1 and $amount <= 2500)) {
		$result = mysqli_query($connect, "SELECT * FROM `payments` WHERE `id` = '$invoice_payload'");
		$rew = mysqli_fetch_assoc($result);
		if ($rew['status'] == "active") {

			$json = json_decode(file_get_contents("https://cbu.uz/uz/arkhiv-kursov-valyut/json/"), true);
			foreach ($json as $json2) {
				if ($json2['Ccy'] == "USD") {
					$usd = $json2['Rate'];
					break;
				}
			}

			$miqdor = $amount * ($usd / 50);
			$miqdor2 = number_format($miqdor, 2, ',', '.');
			$user_id = $rew['user_id'];
			$a = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `user_id` WHERE `user_id` = '$user_id'"));
			$pul = $a['pul'];
			$amounts = $pul + $miqdor;
			mysqli_query($connect, "UPDATE `user_id` SET `pul` = '$amounts' WHERE `user_id` = '$user_id'");
			mysqli_query($connect, "UPDATE `payments` SET `status` = 'passive' WHERE `id` = '$invoice_payload'");
			$admin_lang = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM user_id WHERE user_id = $AlijonovUz"))['lang'];
			bot('sendMessage', [
				'chat_id' => $user_id,
				'text' => str_replace('%amount%', $miqdor2, $texts[$lang]['text']['payment_complete']),
				'parse_mode' => 'html',
			]);
			bot('SendMessage', [
				'chat_id' => $AlijonovUz,
				'text' => str_replace(['%id%', '%amount%'], [$user_id, $miqdor2], $texts[$admin_lang]['text']['check_admin']),
				'disable_web_page_preview' => true,
				'parse_mode' => 'html',
			]);
			exit();
		}
	}
}

if (isset($message)) {
	if (!$connect) {
		bot('sendMessage', [
			'chat_id' => $cid,
			'text' => "⚠️ <b>Baza bilan aloqa mavjud emas!</b>",
			'parse_mode' => 'html',
		]);
		exit();
	}
}

if (isset($message)) {
	if ($holat == "nofaol") {
		mysqli_query($connect, "UPDATE user_id SET holat = 'faol' WHERE user_id = '$delete_id'");
	}
}

if (isset($message)) {
	$result = mysqli_query($connect, "SELECT * FROM admins WHERE user_id = '$AlijonovUz'");
	$rew = mysqli_fetch_assoc($result);
	if (!$rew) {
		mysqli_query($connect, "INSERT INTO `admins` (`user_id`,`appointed`,`channel`,`bot`,`search`,`stat`,`send`,`limit`,`admin`) VALUES ('$AlijonovUz','$bot_id','true','true','true','true','true','true','true')");
	}
}

if (isset($message)) {
	$result = mysqli_query($connect, "SELECT * FROM user_id WHERE user_id = $cid");
	$rew = mysqli_fetch_assoc($result);
	if (!$rew) {
		$token = uniqid();
		mysqli_query($connect, "INSERT INTO user_id(`user_id`,`token`,`limit`,`holat`,`rejim`,`pul`,`ban`,`step`,`sana`) VALUES ('$cid','$token','10','faol','gpt','0','unban','0','$sana')");
	}
}

if (isset($message)) {
	if ($lang == null) {
		if ($language == "uz" or $language == "ru" or $language == "en") {
			mysqli_query($connect, "UPDATE `user_id` SET `lang` = '$language' WHERE `user_id` = '$cid'");
			$til = $language;
		} else {
			mysqli_query($connect, "UPDATE `user_id` SET `lang` = 'en' WHERE `user_id` = '$cid'");
			$til = "en";
		}
		bot('sendMessage', [
			'chat_id' => $cid,
			'text' => $texts[$til]['text']['loading'],
			'parse_mode' => 'html',
		]);
		bot('editMessageText', [
			'chat_id' => $cid,
			'message_id' => $mid + 1,
			'text' => $texts[$til]['text']['loading'],
			'parse_mode' => 'html',
		]);
		bot('editMessageText', [
			'chat_id' => $cid,
			'message_id' => $mid + 1,
			'text' => $texts[$til]['text']['interfeys'],
			'parse_mode' => 'html',
		]);
		exit();
	}
}

//<---- Boshlash ---->//

if ($text == "/start") {
	if (joinchat($cid, $lang, $connect) == true) {
		if ($rejim == "gpt") {
			$start = $texts[$lang]['text']['start_gpt'];
		} elseif ($rejim == "img") {
			$start = $texts[$lang]['text']['start_img'];
		}
		bot('sendChatAction', [
			'chat_id' => $cid,
			'action' => "typing"
		]);
		bot('sendMessage', [
			'chat_id' => $cid,
			'text' => $start,
			'parse_mode' => 'html',
		]);
		mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `user_id` = '$cid'");
		exit();
	}
}

if ($data == "result") {
	bot('deleteMessage', [
		'chat_id' => $cid2,
		'message_id' => $mid2
	]);
	if (joinchat($cid2, $lang, $connect) == true) {
		bot('SendMessage', [
			'chat_id' => $cid2,
			'text' => $texts[$lang]['text']['start'],
			'parse_mode' => 'html',
		]);
		mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = '$cid2'");
		exit();
	}
}

//<---- Sozlamalar ---->//

if ($text == "/settings") {
	if (joinchat($cid, $lang, $connect) == true) {
		bot('sendChatAction', [
			'chat_id' => $cid,
			'action' => "typing"
		]);
		bot('sendMessage', [
			'chat_id' => $cid,
			'text' => $texts[$lang]['text']['settings'],
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['interfeys'], 'callback_data' => "interfeys"]],
					[['text' => $texts[$lang]['button']['limits'], 'callback_data' => "limitlar"], ['text' => $texts[$lang]['button']['history'], 'callback_data' => "tarixlar"]],
					[['text' => $texts[$lang]['button']['account'], 'callback_data' => "account"]],
					[['text' => $texts[$lang]['button']['close'], 'callback_data' => "yopis"]],
				]
			])
		]);
		mysqli_query($connect, "UPDATE user_id SET step = '$mid - 1' WHERE user_id = '$cid'");
		exit();
	}
}

if ($data == "settings") {
	bot('deleteMessage', [
		'chat_id' => $cid2,
		'message_id' => $mid2
	]);
	if (joinchat($cid2, $lang, $connect) == true) {
		bot('SendMessage', [
			'chat_id' => $cid2,
			'text' => $texts[$lang]['text']['settings'],
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['interfeys'], 'callback_data' => "interfeys"]],
					[['text' => $texts[$lang]['button']['limits'], 'callback_data' => "limitlar"], ['text' => $texts[$lang]['button']['history'], 'callback_data' => "tarixlar"]],
					[['text' => $texts[$lang]['button']['account'], 'callback_data' => "account"]],
					[['text' => $texts[$lang]['button']['close'], 'callback_data' => "yopis"]],
				]
			])
		]);
		exit();
	}
}

if ($data == "yopis") {
	if (joinchat($cid2, $lang, $connect) == true) {
		bot('deleteMessage', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
		]);
		bot('deleteMessage', [
			'chat_id' => $cid2,
			'message_id' => $step,
		]);
		bot('answerCallbackQuery', [
			'callback_query_id' => $qid,
			'text' => $texts[$lang]['text']['department'],
			'show_alert' => false,
		]);
		mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = '$cid2'");
	}
}

if ($data == "account") {
	if (joinchat($cid2, $lang, $connect) == true) {
		$pul = number_format($pul, 2, ',', '.');
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => $texts[$lang]['text']['loading'],
			'parse_mode' => 'html',
		]);
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => $texts[$lang]['text']['loading'],
			'parse_mode' => 'html',
		]);
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => str_replace(['%id%', '%balance%'], [$cid2, $pul], $texts[$lang]['text']['account']),
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['input'], 'callback_data' => "input"]],
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "settings"]],
				]
			])
		]);
	}
}

if ($data == "input") {
	if (joinchat($cid2, $lang, $connect) == true) {
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => $texts[$lang]['text']['input'],
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['click'], 'callback_data' => "click"], ['text' => $texts[$lang]['button']['stars'], 'callback_data' => "stars"]],
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "account"]],
				]
			])
		]);
	}
}


if ($data == "click") {
	if (joinchat($cid2, $lang, $connect) == true) {
		$miqdor = "5.000,00";
		bot('deleteMessage', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
		]);
		bot('SendMessage', [
			'chat_id' => $cid2,
			'text' => str_replace('%amount%', $miqdor, $texts[$lang]['text']['amount']),
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "input"]],
				]
			])
		]);
		mysqli_query($connect, "UPDATE user_id SET step = 'click' WHERE user_id = '$cid2'");
		exit();
	}
}

if ($step == "click" and joinchat($cid, $lang, $connect) == true) {
	if ($text >= 5000) {
		$rand = rand(1, 999);
		$tolov = $text + $rand;
		$miqdor2 = number_format($tolov, 2, ',', '.');
		$wallet = "8801512234590813";
		bot('sendmessage', [
			'chat_id' => $cid,
			'text' => str_replace(['%id%', '%wallet%', '%tolov%', '%pul%'], [$cid, $wallet, $rand, $miqdor2], $texts[$lang]['text']['click']),
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['check'], 'callback_data' => "checkout=$tolov"]],
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "input"]],
				]
			])
		]);
		mysqli_query($connect, "UPDATE `user_id` SET `step`='0' WHERE `user_id`='$cid'");
		exit();
	} else {
		$miqdor = "5.000,00";
		bot('sendmessage', [
			'chat_id' => $cid,
			'text' => str_replace('%amount%', $miqdor, $texts[$lang]['text']['amount']),
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "input"]],
				]
			])
		]);
		exit();
	}
}



if (mb_stripos($data, "checkout=") !== false) {
	$summa = explode("=", $data)[1];
	$miqdor2 = number_format($summa, 2, ',', '.');
	$checkids = json_decode(file_get_contents("https://api.u12369.xvest1.ru/Sadiikovv/history.php?text=$summa"), true);
	if ($checkids == true) {
		bot('deleteMessage', [
			'chat_id' => $cid2,
			'message_id' => $mid2
		]);
		bot('sendMessage', [
			'chat_id' => $cid2,
			'text' => str_replace('%amount%', $miqdor2, $texts[$lang]['text']['payment_complete']),
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "input"]],
				]
			])
		]);
		$jami = $pul + $summa;
		mysqli_query($connect, "UPDATE `user_id` SET `pul`='$jami' WHERE `user_id`='$cid2'");
		exit();
	} else {
		bot('answerCallbackQuery', [
			'callback_query_id' => $qid,
			'text' => $texts[$lang]['text']['check_no_complete'],
			'show_alert' => true,
		]);
	}
}

if ($data == "stars") {
	if (joinchat($cid2, $lang, $connect) == true) {
		$miqdor = "10";
		bot('deleteMessage', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
		]);
		bot('sendMessage', [
			'chat_id' => $cid2,
			'text' => str_replace('%amount%', $miqdor, $texts[$lang]['text']['amount']),
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "input"]],
				]
			])
		]);
		mysqli_query($connect, "UPDATE user_id SET step = 'stars' WHERE user_id = '$cid2'");
		exit();
	}
}

if ($step == "stars") {
	if (joinchat($cid, $lang, $connect) == true) {
		if (is_numeric($text) == true) {
			if ($text >= 10) {

				$json = json_decode(file_get_contents("https://cbu.uz/uz/arkhiv-kursov-valyut/json/"), true);
				foreach ($json as $json2) {
					if ($json2['Ccy'] == "USD") {
						$usd = $json2['Rate'];
						break;
					}
				}
				$amounts = $text * ($usd / 50);
				$amount2 = number_format($amounts, 2, ',', '.');
				$uniqd = md5(uniqid());
				mysqli_query($connect, "INSERT INTO payments (`id`,`user_id`,`status`) VALUES ('$uniqd','$cid','active')");
				$res = bot('sendInvoice', [
					'chat_id' => $cid,
					'title' => "$botname",
					'description' => str_replace(['%bot%', '%amount%'], [$bot, $amount2], $texts[$lang]['text']['sendInvoice']),
					'payload' => $uniqd,
					'currency' => "XTR",
					'prices' => json_encode([['label' => 'star', 'amount' => $text]])
				]);
				mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = '$cid'");
				exit();
			} else {
				$miqdor = "10";
				bot('sendMessage', [
					'chat_id' => $cid,
					'text' => str_replace('%amount%', $miqdor, $texts[$lang]['text']['amount']),
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => $texts[$lang]['button']['back'], 'callback_data' => "input"]],
						]
					])
				]);
				exit();
			}
		} else {
			$miqdor = "10";
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => str_replace('%amount%', $miqdor, $texts[$lang]['text']['amount']),
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => $texts[$lang]['button']['back'], 'callback_data' => "input"]],
					]
				])
			]);
			exit();
		}
	}
}

if ($data == "limitlar") {
	if (joinchat($cid2, $lang, $connect) == true) {
		$date = soat($soat2, $lang);
		if ($limit > 0) {
			$str = $texts[$lang]['text']['limits_there_is'];
			$tx = str_replace("%limits%", $limit, $str);
		} else {
			$tx = $texts[$lang]['text']['limits_finish'];
		}
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => $texts[$lang]['text']['loading'],
			'parse_mode' => 'html',
		]);
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => $texts[$lang]['text']['loading'],
			'parse_mode' => 'html',
		]);
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => str_replace("%limits%", $tx, $texts[$lang]['text']['limits_text']),
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => "⏳ $date", 'callback_data' => "0"]],
					[['text' => $texts[$lang]['button']['api_shop'], 'callback_data' => "limit_shop"], ['text' => $texts[$lang]['button']['update'], 'callback_data' => "yangila"]],
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "settings"]]
				]
			])
		]);
		exit();
	}
}

if ($data == "yangila") {
	if (joinchat($cid2, $lang, $connect) == true) {
		$date = soat($soat2, $lang);
		if ($limit > 0) {
			$str = $texts[$lang]['text']['limits_there_is'];
			$tx = str_replace("%limits%", $limit, $str);
		} else {
			$tx = $texts[$lang]['text']['limits_finish'];
		}
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => str_replace("%limits%", $tx, $texts[$lang]['text']['limits_text']),
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => "⏳ $date", 'callback_data' => "0"]],
					[['text' => $texts[$lang]['button']['api_shop'], 'callback_data' => "limit_shop"], ['text' => $texts[$lang]['button']['update'], 'callback_data' => "yangila"]],
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "settings"]]
				]
			])
		]);
	}
}

if ($data == "limit_shop") {
	if (joinchat($cid2, $lang, $connect) == true) {
		bot('deleteMessage', [
			'chat_id' => $cid2,
			'message_id' => $mid2
		]);
		$sum = "100";
		bot('SendMessage', [
			'chat_id' => $cid2,
			'text' => str_replace("%sum%", $sum, $texts[$lang]['text']['api_shop']),
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "yangila"]],
				]
			])
		]);
		mysqli_query($connect, "UPDATE user_id SET step = 'limit_shop' WHERE user_id = '$cid2'");
		exit();
	}
}

if ($step == "limit_shop") {
	if (joinchat($cid, $lang, $connect) == true) {
		if (is_numeric($text) == true) {
			if ($text >= 50) {
				$sum = 100 * $text;
				$sum2 = number_format($sum, 2, ',', '.');
				if ($pul >= $sum) {
					bot('sendMessage', [
						'chat_id' => $cid,
						'text' => str_replace(['%sum%', '%limit%'], [$sum2, $text], $texts[$lang]['text']['shop_complete']),
						'parse_mode' => 'html',
						'reply_markup' => json_encode([
							'inline_keyboard' => [
								[['text' => $texts[$lang]['button']['back'], 'callback_data' => "yangila"]],
							]
						])
					]);
					$miqdor = $pul - $sum;
					$miqdor2 = $limit + $text;
					mysqli_query($connect, "UPDATE `user_id` SET `pul` = '$miqdor' WHERE `user_id` = '$cid'");
					mysqli_query($connect, "UPDATE `user_id` SET `limit` = '$miqdor2' WHERE `user_id` = '$cid'");
					mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `user_id` = '$cid'");
					exit();
				} else {
					bot('sendMessage', [
						'chat_id' => $cid,
						'text' => $texts[$lang]['text']['shop_hisob'],
						'parse_mode' => 'html',
						'reply_markup' => json_encode([
							'inline_keyboard' => [
								[['text' => $texts[$lang]['button']['input'], 'callback_data' => "input"]],
								[['text' => $texts[$lang]['button']['back'], 'callback_data' => "yangila"]],
							]
						])
					]);
					exit();
				}
			} else {
				bot('sendMessage', [
					'chat_id' => $cid,
					'text' => $texts[$lang]['text']['acceptance'],
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => $texts[$lang]['button']['back'], 'callback_data' => "yangila"]],
						]
					])
				]);
				exit();
			}
		} else {
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => $texts[$lang]['text']['is_numeric'],
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => $texts[$lang]['button']['back'], 'callback_data' => "yangila"]],
					]
				])
			]);
			exit();
		}
	}
}

if ($data == "tarixlar") {
	if (joinchat($cid2, $lang, $connect) == true) {
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => $texts[$lang]['text']['loading'],
			'parse_mode' => 'html',
		]);
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => $texts[$lang]['text']['loading'],
			'parse_mode' => 'html',
		]);
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => $texts[$lang]['text']['history'],
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['history_seen'], 'web_app' => ['url' => "https://byopenai.uz/bots/tarix.php?id=$cid2&token=$token"]]],
					//[['text'=>$texts[$lang]['button']['history_cleaning'],'callback_data'=>"tozalash"]],
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "settings"]]
				]
			])
		]);
		exit();
	}
}

if ($data == "orqaga") {
	if (joinchat($cid2, $lang, $connect) == true) {
		bot('deleteMessage', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
		]);
		bot('sendMessage', [
			'chat_id' => $cid2,
			'text' => $texts[$lang]['text']['history'],
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['history_seen'], 'web_app' => ['url' => "https://byopenai.uz/bots/tarix.php?id=$cid2&token=$token"]]],
					//[['text'=>$texts[$lang]['button']['history_cleaning'],'callback_data'=>"tozalash"]],
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "settings"]]
				]
			])
		]);
		exit();
	}
}

if ($data == "tozalash") {
	if (joinchat($cid2, $lang, $connect) == true) {
		$get = file_get_contents("data/$cid2.json");
		if ($get != null) {
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => $texts[$lang]['text']['loading'],
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => $texts[$lang]['text']['loading'],
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => $texts[$lang]['text']['history_cleaning'],
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => $texts[$lang]['button']['back'], 'callback_data' => "orqaga"]],
					]
				])
			]);
			unlink("data/$cid2.json");
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => $texts[$lang]['text']['not_found'],
				'show_alert' => true,
			]);
		}
	}
}

if ($data == "interfeys") {
	if (joinchat($cid2, $lang, $connect) == true) {
		bot('editMessageText', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
			'text' => $texts[$lang]['text']['interfeys_choose'],
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $texts[$lang]['button']['uz'], 'callback_data' => "til_uz"]],
					[['text' => $texts[$lang]['button']['en'], 'callback_data' => "til_en"], ['text' => $texts[$lang]['button']['ru'], 'callback_data' => "til_ru"]],
					[['text' => $texts[$lang]['button']['back'], 'callback_data' => "settings"]]
				]
			])
		]);
	}
}

if (mb_stripos($data, "til_") !== false) {
	if (joinchat($cid2, $lang, $connect) == true) {
		$til = explode("_", $data)[1];
		if ($lang == $til) {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => $texts[$til]['text']['interfeys_use'],
				'show_alert' => true,
			]);
		} else {
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => $texts[$til]['text']['loading'],
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => $texts[$til]['text']['loading'],
				'parse_mode' => 'html',
			]);
			bot('deleteMessage', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
			]);
			bot('sendMessage', [
				'chat_id' => $cid2,
				'text' => $texts[$til]['text']['interfeys'],
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => $texts[$til]['button']['back'], 'callback_data' => "interfeys"]]
					]
				])
			]);
			mysqli_query($connect, "UPDATE `user_id` SET `lang` = '$til' WHERE `user_id` = '$cid2'");
			unlink("data/$cid2.json");
			exit();
		}
	}
}

//<---- AI bo'lim ---->//

if ($text == "/ai") {
	if (joinchat($cid, $lang, $connect) == true) {
		if ($rejim == "gpt") {
			$gpt = $texts[$lang]['button']['gpt'] . " - ✅";
			$img = $texts[$lang]['button']['img'];
		} elseif ($rejim == "img") {
			$gpt = $texts[$lang]['button']['gpt'];
			$img = $texts[$lang]['button']['img'] . " - ✅";
		} elseif ($rejim == null) {
			$gpt = $texts[$lang]['button']['gpt'];
			$img = $texts[$lang]['button']['img'];
		}
		bot('sendChatAction', [
			'chat_id' => $cid,
			'action' => "typing"
		]);
		bot('sendMessage', [
			'chat_id' => $cid,
			'text' => $texts[$lang]['text']['ai'],
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => $gpt, 'callback_data' => "select=gpt"]],
					[['text' => $img, 'callback_data' => "select=img"]],
					[['text' => $texts[$lang]['button']['close'], 'callback_data' => "yopis"]],
				]
			])
		]);
		mysqli_query($connect, "UPDATE user_id SET step = '$mid - 1' WHERE user_id = '$cid'");
		exit();
	}
}

if (mb_stripos($data, "select=") !== false) {
	if (joinchat($cid2, $lang, $connect) == true) {
		$ex = explode("=", $data)[1];
		if ($rejim != $ex) {
			if ($ex == "gpt") {
				$gpt = $texts[$lang]['button']['gpt'] . " - ✅";
				$img = $texts[$lang]['button']['img'];
			} elseif ($ex == "img") {
				$gpt = $texts[$lang]['button']['gpt'];
				$img = $texts[$lang]['button']['img'] . " - ✅";
			}
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => $texts[$lang]['text']['ai'],
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => $gpt, 'callback_data' => "select=gpt"]],
						[['text' => $img, 'callback_data' => "select=img"]],
						[['text' => $texts[$lang]['button']['close'], 'callback_data' => "yopis"]],
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET rejim = '$ex' WHERE user_id = '$cid2'");
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => $texts[$lang]['text']['ai_use'],
				'show_alert' => true,
			]);
		}
	}
}

//<---- ChatGPT ---->//

if (isset($e_text)) {
	if (joinchat($e_cid, $lang, $connect) == true) {
		bot('sendChatAction', [
			'chat_id' => $e_cid,
			'action' => "typing"
		]);
		bot('editMessageText', [
			'chat_id' => $e_cid,
			'message_id' => $e_mid + 1,
			'text' => gpt($e_text, $e_cid)['reply'],
			'parse_mode' => 'MarkDown',
		]);
		$a = $limit - 1;
		mysqli_query($connect, "UPDATE `user_id` SET `limit` = '$a' WHERE `user_id` = '$e_cid'");
	}
}

if (isset($message)) {
	if (joinchat($cid, $lang, $connect) == true) {
		if ($rejim == "gpt") {
			if (isset($text) || isset($photo)) {
				if ($bots == "on") {
					if ($limit > 0) {
						bot('sendChatAction', [
							'chat_id' => $cid,
							'action' => "typing"
						]);

						if (isset($text)) {
							$gpt = gpt($text, null, $chat_id);
							$reply = $gpt->reply;
							if ($reply == true) {
								bot('sendMessage', [
									'chat_id' => $cid,
									'text' => formatJson($reply),
									'disable_web_page_preview' => true,
									'parse_mode' => 'MarkDown',
								]);
								$a = $limit - 1;
								mysqli_query($connect, "UPDATE `user_id` SET `limit` = '$a' WHERE `user_id` = '$cid'");
							} else {
								if ($error == null) {
									$tx = "From OpenAI: Internal server error!";
								} else {
									$tx = $error;
								}
								bot('sendMessage', [
									'chat_id' => $cid,
									'text' => "⚠️ <b>$tx</b>",
									'parse_mode' => 'html',
								]);
							}
						} elseif (isset($photo)) {
							$photo_id = $photo[count($photo) - 1]->file_id;
							$file = bot('getFile', ['file_id' => $photo_id]);
							$path = $file->result->file_path;
							$img = "https://api.telegram.org/file/bot" . AlijonovUz . "/" . $path;
							file_put_contents("image/$cid.jpg", file_get_contents($img));
							$image_url = "https://byopenai.uz/bots/image/$cid.jpg";
							$uploadResponse = uploadFile($image_url);
							$photo_id = $uploadResponse->data->id;

							$gpt = gpt($caption, $photo_id, $chat_id);
							$reply = $gpt->reply;
							$error = $gpt->message;
							if ($reply == true) {
								bot('sendMessage', [
									'chat_id' => $cid,
									'text' => formatJson($reply),
									'disable_web_page_preview' => true,
									'parse_mode' => 'MarkDown',
								]);
								$a = $limit - 1;
								mysqli_query($connect, "UPDATE `user_id` SET `limit` = '$a' WHERE `user_id` = '$cid'");
								unlink("image/$cid.jpg");
							} else {
								if ($error == null) {
									$errors = "From OpenAI: Error, invalid image!";
								} else {
									$erros = $error;
								}
								bot('sendMessage', [
									'chat_id' => $cid,
									'text' => "⚠️ <b>$errors</b>",
									'parse_mode' => 'html',
								]);
								unlink("image/$cid.jpg");
							}
						}
					} else {
						$date = soat($soat2, $lang);
						bot('sendChatAction', [
							'chat_id' => $cid,
							'action' => "typing"
						]);
						bot('sendMessage', [
							'chat_id' => $cid,
							'text' => str_replace("%date%", $date, $texts[$lang]['text']['no_limits']),
							'parse_mode' => 'html',
							'reply_markup' => json_encode([
								'inline_keyboard' => [
									[['text' => $texts[$lang]['button']['api_shop'], 'callback_data' => "limit_shop"]],
									[['text' => $texts[$lang]['button']['close'], 'callback_data' => "yopis"]]
								]
							])
						]);
						unlink("image/$cid.jpg");
					}
				} else {
					bot('sendChatAction', [
						'chat_id' => $cid,
						'action' => "typing"
					]);
					bot('sendMessage', [
						'chat_id' => $cid,
						'text' => $texts[$lang]['text']['error_bots'],
						'parse_mode' => 'html',
					]);
				}
			} else {
				bot('sendChatAction', [
					'chat_id' => $cid,
					'action' => "typing"
				]);
				bot('sendMessage', [
					'chat_id' => $cid,
					'text' => $texts[$lang]['text']['isset_text'],
					'parse_mode' => 'html',
				]);
			}
		}
	}
}

if (isset($message)) {
	if (joinchat($cid, $lang, $connect) == true) {
		if ($rejim == "img") {
			if (isset($text)) {
				if ($bots == "on") {
					if ($limit > 0) {
						$img = urlencode($text);
						$file = "https://img.hazex.workers.dev/?prompt=$img&improve=true&format=square&random=Hj6Fq19j";
						$headers = get_headers($file, 1);
						if (mb_stripos($headers[0], '200') !== false) {
							bot('sendChatAction', [
								'chat_id' => $cid,
								'action' => "upload_photo"
							]);
							bot('sendPhoto', [
								'chat_id' => $cid,
								'photo' => $file,
								'caption' => "🎨 <i>$text</i>
					
🤖 <b><a href='https://t.me/$bot'>$botname</a></b>",
								'disable_web_page_preview' => true,
								'parse_mode' => 'html'
							]);
							$a = $limit - 1;
							mysqli_query($connect, "UPDATE `user_id` SET `limit` = '$a' WHERE `user_id` = '$cid'");
						} else {
							bot('sendChatAction', [
								'chat_id' => $cid,
								'action' => "typing"
							]);
							bot('sendMessage', [
								'chat_id' => $cid,
								'text' => $texts[$lang]['text']['error_api'],
								'parse_mode' => 'html',
							]);
						}
					} else {
						$date = soat($soat2, $lang);
						bot('sendChatAction', [
							'chat_id' => $cid,
							'action' => "typing"
						]);
						bot('sendMessage', [
							'chat_id' => $cid,
							'text' => str_replace("%date%", $date, $texts[$lang]['text']['no_limits']),
							'parse_mode' => 'html',
							'reply_markup' => json_encode([
								'inline_keyboard' => [
									[['text' => $texts[$lang]['button']['api_shop'], 'callback_data' => "limit_shop"]],
									[['text' => $texts[$lang]['button']['close'], 'callback_data' => "yopis"]]
								]
							])
						]);
					}
				} else {
					bot('sendChatAction', [
						'chat_id' => $cid,
						'action' => "typing"
					]);
					bot('sendMessage', [
						'chat_id' => $cid,
						'text' => $texts[$lang]['text']['error_bots'],
						'parse_mode' => 'html',
					]);
				}
			} else {
				bot('sendChatAction', [
					'chat_id' => $cid,
					'action' => "typing"
				]);
				bot('sendMessage', [
					'chat_id' => $cid,
					'text' => $texts[$lang]['text']['isset_text'],
					'parse_mode' => 'html',
				]);
			}
		}
	}
}

?>