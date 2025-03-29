<?php

$back = json_encode([
	'inline_keyboard' => [
		[['text' => "◀️ Orqaga", 'callback_data' => "panel"]],
	]
]);

$panel = json_encode([
	'inline_keyboard' => [
		[['text' => "📢 Kanallarni sozlash", 'callback_data' => "channels"]],
		[['text' => "🤖 Bot holati", 'callback_data' => "holati"], ['text' => "📋 Adminlar", 'callback_data' => "admins"]],
		[['text' => "🔎 Foydalanuvchini boshqarish", 'callback_data' => "search"]],
		[['text' => "📊 Statistika", 'callback_data' => "stat"], ['text' => "✉ Xabar yuborish", 'callback_data' => "xabar"]],
		[['text' => "⏳ Limitni o'zgartirish", 'callback_data' => "editlimit"]],
		[['text' => "Yopish", 'callback_data' => "yopish"]]
	]
]);

$channel = json_encode([
	'inline_keyboard' => [
		[['text' => "📝 Ro'yxat", 'callback_data' => "royxat"]],
		[['text' => "➕ Qo'shish", 'callback_data' => "addchan"], ['text' => "🗑 O'chirish", 'callback_data' => "delchan"]],
		[['text' => "◀️ Orqaga", 'callback_data' => "panel"]],
	]
]);

//<---- admin panel ---->

if ($text == "/panel" or $text == "/admin") {
	if ($cid == $admin) {
		bot('sendMessage', [
			'chat_id' => $cid,
			'text' => "⚙ <b>Boshqaruv panelidasiz!</b>

<i>Quyidagi bo'limlardan birini tanlang!</i>",
			'parse_mode' => 'html',
			'reply_markup' => $panel
		]);
		mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
		exit();
	}
}

if ($data == "panel") {
	if ($cid2 == $admin) {
		bot('deleteMessage', [
			'chat_id' => $cid2,
			'message_id' => $mid2,
		]);
		bot('sendMessage', [
			'chat_id' => $cid2,
			'text' => "<b>Quyidagi bo'limlardan birini tanlang:</b>",
			'parse_mode' => 'html',
			'reply_markup' => $panel
		]);
		mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid2");
		exit();
	}
}

if ($data == "yopish") {
	bot('deleteMessage', [
		'chat_id' => $cid2,
		'message_id' => $mid2,
	]);
	bot('answerCallbackQuery', [
		'callback_query_id' => $qid,
		'text' => "Boshqaruv paneli yopildi!",
		'show_alert' => false,
	]);
}

if ($data == "channels") {
	if ($cid2 == $admin) {
		if ($admin_channel == "true") {
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "👇 <b>Quyidagilardan birini tanlang!</b>",
				'parse_mode' => 'html',
				'reply_markup' => $channel
			]);
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = '$cid2'");
			exit();
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}



if ($data == "addchan") {
	if ($cid2 == $admin) {
		if ($admin_channel == "true") {
			bot('deleteMessage', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
			]);
			bot('SendMessage', [
				'chat_id' => $cid2,
				'text' => "<i>Kanalingiz manzilini yuborishdan avval botni kanalingizga admin qilib olishingiz kerak!</i>

📢 <b>Kerakli kanalni manzilini yuboring:

Namuna:</b> <code>@By_Alik</code>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "channels"]],
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'addchan' WHERE user_id = $cid2");
			exit();
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($step == "addchan") {
	if ($cid == $admin) {
		if (isset($text)) {
			if (mb_stripos($text, "@") !== false) {
				$get = bot('getChat', [
					'chat_id' => $text
				]);
				$types = $get->result->type;
				$ch_name = $get->result->title;
				$ch_user = $get->result->username;
				if (getAdmin($ch_user) == true) {
					$result = mysqli_query($connect, "SELECT * FROM `channels` WHERE `url` = '$text'");
					$row = mysqli_fetch_assoc($result);
					if ($row) {
						bot('SendMessage', [
							'chat_id' => $cid,
							'text' => "⛔ <b>$text qabul qilinmadi.</b>

<i>Ushbu kanal avvaldan ro'yxatda mavjud!</i>",
							'parse_mode' => 'html',
							'reply_markup' => json_encode([
								'inline_keyboard' => [
									[['text' => "◀️ Orqaga", 'callback_data' => "channels"]],
								]
							])
						]);
						exit();
					} else {
						bot('SendMessage', [
							'chat_id' => $cid,
							'text' => "✅ <b>$text qabul qilindi.</b>

<i>Quyidagilardan birini tanlang:</i>",
							'parse_mode' => 'html',
							'reply_markup' => json_encode([
								'inline_keyboard' => [
									[['text' => "📝 Limit kiritish", 'callback_data' => "miqdori=$text"], ['text' => "⛔ Tashlab ketish", 'callback_data' => "tashlash=$text"]],
									[['text' => "◀️ Orqaga", 'callback_data' => "channels"]],
								]
							])
						]);
						mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
						exit();
					}
				} else {
					bot('sendMessage', [
						'chat_id' => $cid,
						'text' => "<b>Bot ushbu kanalda admin emas!</b>

Qayta urinib ko'ring:",
						'parse_mode' => 'html',
					]);
					exit();
				}
			} else {
				bot('SendMessage', [
					'chat_id' => $cid,
					'text' => "<b>Kanal manzilini to'g'ri yuboring!</b>

Namuna: <code>@By_Alik</code>",
					'parse_mode' => 'html',
				]);
				exit();
			}
		}
	}
}

if (mb_stripos($data, "tashlash=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_channel == "true") {
			$user = explode("=", $data)[1];
			mysqli_query($connect, "INSERT INTO `channels` (`url`,`limit`) VALUES ('$user','∞')");
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "⏱ <b>Yuklanmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "⏱ <b>Yuklanmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('deleteMessage', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
			]);
			bot('sendMessage', [
				'chat_id' => $cid2,
				'text' => "✅ $user <b>kanali muvaffaqiyatli ro'yxatga olindi!</b>

<i>Ushbu kanal avtomatik tarzda ro'yxatdan olib tashlanmaydi!</i>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "channels"]],
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid2");
			exit();
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($data, "miqdori=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_channel == "true") {
			$user = explode("=", $data)[1];
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "📝 <b>$user kanaliga nechta obunachi qo'shilishi kerak? Kerakli miqdorni kiriting:</b>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "channels"]],
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'miqdori=$user' WHERE user_id = '$cid2'");
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($step, "miqdori=") !== false) {
	$user = explode("=", $step)[1];
	if (is_numeric($text) == true) {
		if ($text >= 1) {
			$time = date("H:i", strtotime("+1 minute"));
			file_put_contents("other/time.txt", $time);
			$json = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getChatMembersCount?chat_id=" . $user), true);
			$getCount = $json['result'] + $text;
			mysqli_query($connect, "INSERT INTO `channels` (`url`,`limit`) VALUES ('$user','$getCount')");
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "⏱ <b>Yuklanmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid,
				'message_id' => $mid + 1,
				'text' => "⏱ <b>Yuklanmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid,
				'message_id' => $mid + 1,
				'text' => "⏱ <b>Yuklanmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('deleteMessage', [
				'chat_id' => $cid,
				'message_id' => $mid + 1,
			]);
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "✅ $user <b>kanali muvaffaqiyatli ro'yxatga olindi!</b>

<i>Ushbu kanal obunachilari soni <b>$getCount ta</b>ga yetganda avtomatik tarzda olib tashlanadi!</i>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "channels"]],
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
			exit();
		} else {
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "📝 <b>$user kanaliga nechta obunachi qo'shilishi kerak? Kerakli miqdorni kiriting:</b>",
				'parse_mode' => 'html',
			]);
			exit();
		}
	} else {
		bot('sendMessage', [
			'chat_id' => $cid,
			'text' => "🔢 <b>Faqat raqamlardan foydalaning!</b>",
			'parse_mode' => 'html',
		]);
		exit();
	}
}

if ($data == "delchan") {
	if ($cid2 == $admin) {
		if ($admin_channel == "true") {
			$result = mysqli_query($connect, "SELECT * FROM `channels`");
			$row = mysqli_fetch_assoc($result);
			if ($row) {
				$key = [];
				$i = 0;
				$res = mysqli_query($connect, "SELECT * FROM `channels`");
				while ($a = mysqli_fetch_assoc($res)) {
					$url = $a['url'];
					$i++;
					$json = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getChat?chat_id=" . $url), true);
					$title = $json['result']['title'];
					$key[] = ["text" => "🗑 $i. $title", "callback_data" => "delchannel=$url"];
					$keyboard2 = array_chunk($key, 1);
					$keyboard2[] = [['text' => "◀️ Orqaga", 'callback_data' => "channels"]];
					$keys = json_encode([
						'inline_keyboard' => $keyboard2
					]);
				}
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "🗑 <b>O'chirilishi kerak bo'lgan kanalni ustiga bosing!</b>

⚠️ <i><b>Diqqat!</b> Kerakli kanalni ustiga bosganingizdan so'ng, o'sha kanal ro'yxatdan o'chiriladi!</i>",
					'parse_mode' => 'html',
					'reply_markup' => $keys
				]);
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "😔 <b>Hech qanday kanallar ulanmagan!</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "channels"]],
						]
					])
				]);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($data, "delchannel=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_channel == "true") {
			$ex = explode("=", $data)[1];
			$result = mysqli_query($connect, "SELECT * FROM `channels` WHERE `url` = '$ex'");
			$row = mysqli_fetch_assoc($result);
			if ($row) {
				mysqli_query($connect, "DELETE FROM `channels` WHERE `url` = '$ex'");
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>O'chirilmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>O'chirilmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('deleteMessage', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
				]);
				bot('SendMessage', [
					'chat_id' => $cid2,
					'text' => "✅ $ex <b>ro'yxatdan olib tashlandi!</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "channels"]],
						]
					])
				]);
				exit();
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>O'chirilmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>O'chirilmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('deleteMessage', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
				]);
				bot('SendMessage', [
					'chat_id' => $cid2,
					'text' => "⚠️ <b>Xatolik!</b>

<i>Ushbu kanal ma'lumotlar bazasidan topilmadi!</i>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "channels"]],
						]
					])
				]);
				exit();
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($data == "royxat") {
	if ($cid2 == $admin) {
		if ($admin_channel == "true") {
			$result = mysqli_query($connect, "SELECT * FROM `channels`");
			$row = mysqli_fetch_assoc($result);
			if ($row) {
				$key = [];
				$i = 0;
				$res = mysqli_query($connect, "SELECT * FROM `channels`");
				while ($a = mysqli_fetch_assoc($res)) {
					$url = $a['url'];
					$limit = $a['limit'];
					$i++;
					$json = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getChat?chat_id=" . $url), true);
					$title = $json['result']['title'];
					$key[] = ["text" => "⚙ $i. $title", "callback_data" => "royxat=$url=$limit"];
					$keyboard2 = array_chunk($key, 1);
					$keyboard2[] = [['text' => "◀️ Orqaga", 'callback_data' => "channels"]];
					$keys = json_encode([
						'inline_keyboard' => $keyboard2
					]);
				}
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "👇 <b>Quyidagilardan birini tanlang:</b>",
					'disable_web_page_preview' => true,
					'parse_mode' => 'html',
					'reply_markup' => $keys,
				]);
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "😔 <b>Hech qanday kanallar ulanmagan!</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "channels"]],
						]
					])
				]);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($data, "royxat=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_channel == "true") {
			$url = explode("=", $data)[1];
			$limit = explode("=", $data)[2];
			$json = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getChat?chat_id=" . $url), true);
			$json2 = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getChatMembersCount?chat_id=" . $url), true);
			$title = $json['result']['title'];
			$getCount = $json2['result'];
			$havola = str_replace("@", "", $url);

			if ($limit == "∞") {
				$status = "O'chirilmaydi!";
				$qoldi = "∞";
			} else {
				$status = "O'chiriladi!";
				$qoldi = $limit - $getCount . " ta";
			}

			$result = mysqli_query($connect, "SELECT * FROM `channels` WHERE `url` = '$url'");
			$row = mysqli_fetch_assoc($result);
			if ($row) {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "📝 <i>Nomi | Obunachilari | Limit</i>

<i><a href='https://t.me/$havola'>$title</a> | $getCount | $limit

<b>Holati:</b> $status
<b>Qoldi:</b> $qoldi</i>

⚠️ <i><b>Diqqat!</b> Botga ulangan ushbu kanal obunachilari soni limitga yetganida avtomatik tarzda kanal ro'yxatdan olib tashlanadi!</i>",
					'disable_web_page_preview' => true,
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "⏳ Limitni o'zgartirish", 'callback_data' => "chlimit=$url=$limit"]],
							[['text' => "◀️ Orqaga", 'callback_data' => "royxat"]],
						]
					])
				]);
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⚠️ <b>Xatolik!</b>

<i>Ushbu kanal ma'lumotlar bazasidan topilmadi!</i>",
					'disable_web_page_preview' => true,
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "royxat"]],
						]
					])
				]);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($data, "chlimit=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_channel == "true") {
			$url = explode("=", $data)[1];
			$lim = explode("=", $data)[2];
			bot('deleteMessage', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
			]);
			bot('SendMessage', [
				'chat_id' => $cid2,
				'text' => "📝 <b>$url kanaliga nechta obunachi qo'shilishi kerak? Kerakli miqdorni kiriting:</b>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "royxat=$url=$lim"]],
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'chlimit=$url=$lim' WHERE user_id = $cid2");
			exit();
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($step, "chlimit=") !== false) {
	$url = explode("=", $step)[1];
	$lim = explode("=", $step)[2];
	if (is_numeric($text) == true) {
		if ($text >= 1) {
			$time = date("H:i", strtotime("+1 minute"));
			file_put_contents("other/time.txt", $time);
			$json = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getChatMembersCount?chat_id=" . $url), true);
			$getCount = $json['result'] + $text;
			mysqli_query($connect, "UPDATE `channels` SET `limit` = '$getCount' WHERE `url` = '$url'");
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "⏱ <b>Yuklanmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid,
				'message_id' => $mid + 1,
				'text' => "⏱ <b>Yuklanmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid,
				'message_id' => $mid + 1,
				'text' => "⏱ <b>Yuklanmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid,
				'message_id' => $mid + 1,
				'text' => "✅ $text <b>qabul qilindi.</b>

<i>Endi <b>$url</b> kanal obunachilari soni <b>$getCount ta</b>ga yetganda avtomatik tarzda olib tashlanadi!</i>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "royxat=$url=$getCount"]],
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
			exit();
		} else {
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "📝 <b>$url kanaliga nechta obunachi qo'shilishi kerak? Kerakli miqdorni kiriting:</b>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "royxat=$url=$lim"]],
					]
				])
			]);
			exit();
		}
	} else {
		bot('sendMessage', [
			'chat_id' => $cid,
			'text' => "🔢 <b>Faqat raqamlardan foydalaning!</b>",
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => "◀️ Orqaga", 'callback_data' => "royxat=$url=$lim"]],
				]
			])
		]);
		exit();
	}
}

if ($data == "stat") {
	if ($cid2 == $admin) {
		if ($admin_stat == "true") {
			$ping = ping($cid2, "");
			$s_kecha = date('d.m.Y', strtotime('-1 day'));

			$us = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `user_id`"));
			$bugun = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `user_id` WHERE `sana` = '$sana'"));
			$kecha = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `user_id` WHERE `sana` = '$s_kecha'"));

			$faol = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `user_id` WHERE `holat` = 'faol'"));
			$nofaol = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `user_id` WHERE `holat` = 'nofaol'"));

			$block = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `user_id` WHERE `ban` = 'ban'"));
			$admins = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `admins`"));
			$admins = $admins - 1;

			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "⏱ <b>Yuklanmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "⏱ <b>Yuklanmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "💡 <b>O'rtacha yuklanish:</b> <code>$ping</code>

📊 <b>Bot statistikasi:</b>

• <b>Foydalanuvchilar:</b> $us ta
• <b>Faol foydalanuvchilar:</b> $faol ta
• <b>Faol bo'lmagan obunachilar:</b> $nofaol ta
• <b>Blocklangan foydalanuvchilar:</b> $block ta

📆 <b>Kunlik statistika:</b>

• <b>Kecha qo'shilganlar:</b> $kecha ta
• <b>Bugun qo'shilganlar:</b> $bugun ta

👮 <b>Yordamchi adminlar soni:</b> $admins ta",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "🔄 Yangilash", 'callback_data' => "stat"]],
						[['text' => "◀️ Orqaga", 'callback_data' => "panel"]],
					]
				])
			]);
			exit();
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($data == "xabar") {
	if ($cid2 == $admin) {
		if ($admin_send == "true") {
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "<b>Quyidagi xabar turlaridan birini tanlang:</b>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "Barchaga xabar", 'callback_data' => "send"]],
						[['text' => "Foydalanuvchiga xabar", 'callback_data' => "user"], ['text' => "Adminlarga xabar", 'callback_data' => "send-admin"]],
						[['text' => "Rejalashtirilgan xabarni to'xtatish", 'callback_data' => "stop-send"]],
						[['text' => "◀️ Orqaga", 'callback_data' => "panel"]],
					]
				])
			]);
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($data == "stop-send") {
	if ($cid2 == $admin) {
		if ($admin_send == "true") {
			$result = mysqli_query($connect, "SELECT * FROM `send`");
			$row = mysqli_fetch_assoc($result);
			if ($row) {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "✅ <b>Barcha foydalanuvchilarga xabar yuborish to'xtatildi!</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
						]
					])
				]);
				mysqli_query($connect, "DELETE FROM `send`");
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "🤷‍♂️ <b>Rejalashtirilgan xabar topilmadi!</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
						]
					])
				]);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}


if ($data == "user") {
	if ($cid2 == $admin) {
		if ($admin_send == "true") {
			bot('deleteMessage', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
			]);
			bot('SendMessage', [
				'chat_id' => $cid2,
				'text' => "<b>Foydalanuvchi ID raqamini kiriting:</b>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'user' WHERE user_id = $cid2");
			exit();
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($step == "user") {
	if ($cid == $admin) {
		if (is_numeric($text) == "true") {
			if ($text != $admin) {
				$result = mysqli_query($connect, "SELECT * FROM user_id WHERE user_id = '$text'");
				$row = mysqli_fetch_assoc($result);
				if (!$row) {
					bot('SendMessage', [
						'chat_id' => $cid,
						'text' => "<b>Foydalanuvchi topilmadi.</b>

Qayta urinib ko'ring:",
						'parse_mode' => 'html',
					]);
					exit();
				} else {
					bot('SendMessage', [
						'chat_id' => $cid,
						'text' => "<b>Qidirilmoqda...</b>",
						'parse_mode' => 'html',
					]);
					bot('editMessageText', [
						'chat_id' => $cid,
						'message_id' => $mid + 1,
						'text' => "<b>Qidirilmoqda...</b>",
						'parse_mode' => 'html',
					]);
					bot('editMessageText', [
						'chat_id' => $cid,
						'message_id' => $mid + 1,
						'text' => "<b>Foydalanuvchiga yubormoqchi bo'lgan xabaringizni kiriting:</b>",
						'parse_mode' => 'html',
					]);
					mysqli_query($connect, "UPDATE user_id SET step = 'xabar=$text' WHERE user_id = $cid");
					exit();
				}
			} else {
				bot('SendMessage', [
					'chat_id' => $cid,
					'text' => "<b>Hurmatli admin, siz o'zingizga xabar yubora olmaysiz!</b>",
					'parse_mode' => 'html',
				]);
				exit();
			}
		} else {
			bot('SendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Faqat raqamlardan foydalaning!</b>",
				'parse_mode' => 'html',
			]);
			exit();
		}
	}
}

if (mb_stripos($step, "xabar=") !== false) {
	if ($cid == $admin) {
		$id = explode("=", $step)[1];
		$get = bot('getChat', [
			'chat_id' => $id,
		]);
		$first = $get->result->first_name;
		$user = $get->result->username;
		$text = str_replace(["%first%", "%id%", "%user%", "%hour%", "%date%"], [$first, $id, $user, $soat, $sana], $text);
		bot('SendMessage', [
			'chat_id' => $id,
			'text' => "$text",
			'parse_mode' => 'html',
			'disable_web_page_preview' => true,
		]);
		bot('SendMessage', [
			'chat_id' => $cid,
			'text' => "✅ <b>Foydalanuvchiga xabaringiz yuborildi!</b>",
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
				]
			])
		]);
		mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
		exit();
	}
}


if ($data == "send-admin") {
	if ($cid2 == $admin) {
		if ($admin_send == "true") {
			$admins = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `admins`"));
			if ($admins != "1") {
				bot('deleteMessage', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
				]);
				bot('SendMessage', [
					'chat_id' => $cid2,
					'text' => "<b>Adminlarga yubormoqchi bo'lgan xabaringizni kiriting:</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
						]
					])
				]);
				mysqli_query($connect, "UPDATE user_id SET step = 'send-admins' WHERE user_id = '$cid2'");
				exit();
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "🤷‍♂️ <b>Yordamchi adminlar topilmadi!</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
						]
					])
				]);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}


if ($step == "send-admins") {
	if ($cid == $admin) {
		mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
		$res = mysqli_query($connect, "SELECT * FROM `admins`");
		bot('sendMessage', [
			'chat_id' => $cid,
			'text' => "✅ <b>Tayyor!</b>
	  
<i>Xabar barcha adminlarga yuborilmoqda...</i> ",
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
				]
			])
		]);
		$x = 0;
		$y = 0;
		while ($a = mysqli_fetch_assoc($res)) {
			$id = $a['user_id'];
			$key = $message->reply_markup;
			$keyboard = json_encode($key);
			$ok = bot('copyMessage', [
				'from_chat_id' => $chat_id,
				'chat_id' => $id,
				'message_id' => $mid,
			])->ok;
			if ($ok == true) {
			} else {
				$okk = bot('copyMessage', [
					'from_chat_id' => $chat_id,
					'chat_id' => $id,
					'message_id' => $mid,
				])->ok;
			}
			if ($okk == true or $ok == true) {
				$x = $x + 1;
				bot('editMessageText', [
					'chat_id' => $chat_id,
					'message_id' => $mid,
					'text' => "✅ <b>Yuborildi:</b> $x
	❌ <b>Yuborilmadi:</b> $y",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
						]
					])
				]);
			} elseif ($okk == false) {
				$y = $y + 1;
				bot('editmessagetext', [
					'chat_id' => $chat_id,
					'message_id' => $mid + 1,
					'text' => "✅ <b>Yuborildi:</b> $x
	❌ <b>Yuborilmadi:</b> $y",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
						]
					])
				]);
			}
		}
		bot('editmessagetext', [
			'chat_id' => $chat_id,
			'message_id' => $mid + 1,
			'text' => "✅ <b>Yuborildi:</b> $x
	❌ <b>Yuborilmadi:</b> $y",
			'parse_mode' => 'html',
			'reply_markup' => json_encode([
				'inline_keyboard' => [
					[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
				]
			])
		]);
		exit();
	}
}


if ($data == "send") {
	if ($cid2 == $admin) {
		if ($admin_send == "true") {
			$result = mysqli_query($connect, "SELECT * FROM `send`");
			$row = mysqli_fetch_assoc($result);
			if (!$row) {
				bot('deleteMessage', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
				]);
				bot('SendMessage', [
					'chat_id' => $cid2,
					'text' => "<b>Foydalanuvchilarga yubormoqchi bo'lgan xabaringizni kiriting:</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
						]
					])
				]);
				mysqli_query($connect, "UPDATE user_id SET step = 'send' WHERE user_id = '$cid2'");
				exit();
			} else {
				bot('answerCallbackQuery', [
					'callback_query_id' => $qid,
					'text' => "Hozirda xabar yuborish davom etmoqda. Keyinroq qayta urunib ko'ring!",
					'show_alert' => true,
				]);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($step == "send" and $cid == $admin) {
	$result = mysqli_query($connect, "SELECT * FROM user_id");
	$stat = mysqli_num_rows($result);
	$res = mysqli_query($connect, "SELECT * FROM user_id WHERE id = '$stat'");
	$row = mysqli_fetch_assoc($res);
	$user_id = $row['user_id'];
	$time1 = date('H:i', strtotime('+1 minutes'));
	$time2 = date('H:i', strtotime('+2 minutes'));
	$tugma = json_encode($update->message->reply_markup);
	$reply_markup = base64_encode($tugma);
	mysqli_query($connect, "INSERT INTO `send` (`time1`,`time2`,`start_id`,`stop_id`,`admin_id`,`message_id`,`reply_markup`,`step`) VALUES ('$time1','$time2','0','$user_id','$admin','$mid','$reply_markup','send')");
	bot('sendMessage', [
		'chat_id' => $cid,
		'text' => "✅ <b>Tayyor!</b>

<i>Xabar foydalanuvchilarga soat $time1 da yuborish boshlanadi!</i>",
		'parse_mode' => 'html',
		'reply_markup' => json_encode([
			'inline_keyboard' => [
				[['text' => "◀️ Orqaga", 'callback_data' => "xabar"]]
			]
		])
	]);
	mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = '$cid'");
	exit();
}

if ($data == "search") {
	if ($cid2 == $admin) {
		if ($admin_search == "true") {
			bot('deleteMessage', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
			]);
			bot('sendMessage', [
				'chat_id' => $cid2,
				'text' => "<b>Kerakli foydalanuvchining ID raqamini kiriting:</b>",
				'parse_mode' => 'html',
				'reply_markup' => $back,
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'searchID' WHERE user_id = '$cid2'");
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($step == "searchID") {
	if ($cid == $admin) {
		if (is_numeric($text) == true) {
			$result = mysqli_query($connect, "SELECT * FROM user_id WHERE user_id = '$text'");
			$row = mysqli_fetch_assoc($result);
			if (!$row) {
				bot('SendMessage', [
					'chat_id' => $cid,
					'text' => "<b>Qidirilmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid,
					'message_id' => $mid + 1,
					'text' => "<b>Qidirilmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid,
					'message_id' => $mid + 1,
					'text' => "<b>Foydalanuvchi topilmadi.</b>

Qayta urinib ko'ring:",
					'parse_mode' => 'html',
				]);
				exit();
			} else {
				$token = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM user_id WHERE user_id = $text"))['token'];
				$pul = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM user_id WHERE user_id = $text"))['pul'];
				$pul2 = number_format($pul, 2, ',', '.');
				$ban = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM user_id WHERE user_id = $text"))['ban'];
				$limit = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM user_id WHERE user_id = $text"))['limit'];
				if ($ban == "unban") {
					$bans = "🔔 Banlash";
				} else {
					$bans = "🔕 Bandan olish";
				}
				bot('SendMessage', [
					'chat_id' => $cid,
					'text' => "<b>Qidirilmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid,
					'message_id' => $mid + 1,
					'text' => "<b>Qidirilmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid,
					'message_id' => $mid + 1,
					'text' => "<b>Foydalanuvchi topildi!

ID:</b> <a href='tg://user?id=$text'>$text</a>
<b>Balans: $pul2 so'm
Limitlar: $limit ta</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "$bans", 'callback_data' => "bans=$text"]],
							[['text' => "➕ Pul qo'shish", 'callback_data' => "plus=$text"], ['text' => "➖ Pul ayirish", 'callback_data' => "minus=$text"]],
							[['text' => "💭 Tarixni ko'rish", 'web_app' => ['url' => "https://byopenai.uz/bots/tarix.php?id=$text&token=$token"]]],
							[['text' => "➕ Limit qo'shish", 'callback_data' => "PlusLimits=$text"], ['text' => "➖ Limit ayirish", 'callback_data' => "MinusLimits=$text"]],
							[['text' => "◀️ Orqaga", 'callback_data' => "panel"]],
						]
					])
				]);
				mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
				exit();
			}
		} else {
			bot('SendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Faqat raqamlardan foydalaning!</b>",
				'parse_mode' => 'html',
			]);
			exit();
		}
	}
}

if (mb_stripos($data, "searchID=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_search == "true") {
			$id = explode("=", $data)[1];
			$token = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM user_id WHERE user_id = $id"))['token'];
			$pul = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM user_id WHERE user_id = $id"))['pul'];
			$pul2 = number_format($pul, 2, ',', '.');
			$ban = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM user_id WHERE user_id = $id"))['ban'];
			$limit = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM user_id WHERE user_id = $id"))['limit'];
			if ($ban == "unban") {
				$bans = "🔔 Banlash";
			} else {
				$bans = "🔕 Bandan olish";
			}
			bot('deleteMessage', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
			]);
			bot('SendMessage', [
				'chat_id' => $cid2,
				'text' => "<b>Foydalanuvchi topildi!

ID:</b> <a href='tg://user?id=$id'>$id</a>
<b>Balans: $pul2 so'm
Limitlar: $limit ta</b>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "$bans", 'callback_data' => "bans=$id"]],
						[['text' => "➕ Pul qo'shish", 'callback_data' => "plus=$id"], ['text' => "➖ Pul ayirish", 'callback_data' => "minus=$id"]],
						[['text' => "💭 Tarixni ko'rish", 'web_app' => ['url' => "https://byopenai.uz/bots/tarix.php?id=$id&token=$token"]]],
						[['text' => "➕ Limit qo'shish", 'callback_data' => "PlusLimits=$id"], ['text' => "➖ Limit ayirish", 'callback_data' => "MinusLimits=$id"]],
						[['text' => "◀️ Orqaga", 'callback_data' => "panel"]],
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid2");
			exit();
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($data, "plus=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_search == "true") {
			$id = explode("=", $data)[1];
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "<a href='tg://user?id=$id'>$id</a> <b>ning hisobiga qancha pul qo'shmoqchisiz?</b>",
				'parse_mode' => "html",
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "searchID=$id"]]
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'plus=$id' WHERE user_id = '$cid2'");
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($step, "plus=") !== false) {
	$id = explode("=", $step)[1];
	if ($cid == $admin) {
		if (is_numeric($text) == "true") {
			$pul2 = number_format($text, 2, ',', '.');
			bot('sendMessage', [
				'chat_id' => $id,
				'text' => "<b>Adminlar tomonidan hisobingizga $pul2 so'm qo'shildi!</b>",
				'parse_mode' => "html",
			]);
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Foydalanuvchi hisobiga $pul2 so'm qo'shildi!</b>",
				'parse_mode' => "html",
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "searchID=$id"]]
					]
				])
			]);
			$pul = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM `user_id` WHERE `user_id` = '$id'"))['pul'];
			$a = $pul + $text;
			mysqli_query($connect, "UPDATE `user_id` SET `pul` = '$a' WHERE `user_id` = '$id'");
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
			exit();
		} else {
			bot('SendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Faqat raqamlardan foydalaning!</b>",
				'parse_mode' => 'html',
			]);
			exit();
		}
	}
}

if (mb_stripos($data, "minus=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_search == "true") {
			$id = explode("=", $data)[1];
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "<a href='tg://user?id=$id'>$id</a> <b>ning hisobidan qancha pul ayirmoqchisiz?</b>",
				'parse_mode' => "html",
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "searchID=$id"]]
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'minus=$id' WHERE user_id = '$cid2'");
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($step, "minus=") !== false) {
	$id = explode("=", $step)[1];
	if ($cid == $admin) {
		if (is_numeric($text) == "true") {
			$pul2 = number_format($text, 2, ',', '.');
			bot('sendMessage', [
				'chat_id' => $id,
				'text' => "<b>Adminlar tomonidan hisobingizdan $pul2 so'm olib tashlandi!</b>",
				'parse_mode' => "html",
			]);
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Foydalanuvchi hisobidan $pul2 so'm olib tashlandi!</b>",
				'parse_mode' => "html",
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "searchID=$id"]]
					]
				])
			]);
			$pul = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM `user_id` WHERE `user_id` = '$id'"))['pul'];
			$a = $pul - $text;
			mysqli_query($connect, "UPDATE `user_id` SET `pul` = '$a' WHERE `user_id` = '$id'");
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
			exit();
		} else {
			bot('SendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Faqat raqamlardan foydalaning!</b>",
				'parse_mode' => 'html',
			]);
			exit();
		}
	}
}

if (mb_stripos($data, "PlusLimits=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_search == "true") {
			$id = explode("=", $data)[1];
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "<a href='tg://user?id=$id'>$id</a> <b>ning hisobiga qancha limit qo'shmoqchisiz?</b>",
				'parse_mode' => "html",
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "searchID=$id"]]
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'PlusLimits=$id' WHERE user_id = '$cid2'");
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($step, "PlusLimits=") !== false) {
	$id = explode("=", $step)[1];
	if ($cid == $admin) {
		if (is_numeric($text) == "true") {
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Foydalanuvchi hisobiga $text ta limit qo'shildi!</b>",
				'parse_mode' => "html",
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "searchID=$id"]]
					]
				])
			]);
			$limit = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM `user_id` WHERE `user_id` = '$id'"))['limit'];
			$a = $limit + $text;
			mysqli_query($connect, "UPDATE `user_id` SET `limit` = '$a' WHERE `user_id` = '$id'");
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
			exit();
		} else {
			bot('SendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Faqat raqamlardan foydalaning!</b>",
				'parse_mode' => 'html',
			]);
			exit();
		}
	}
}

if (mb_stripos($data, "MinusLimits=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_search == "true") {
			$id = explode("=", $data)[1];
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "<a href='tg://user?id=$id'>$id</a> <b>ning hisobidan qancha limit ayirmoqchisiz?</b>",
				'parse_mode' => "html",
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "searchID=$id"]]
					]
				])
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'MinusLimits=$id' WHERE user_id = '$cid2'");
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($step, "MinusLimits=") !== false) {
	$id = explode("=", $step)[1];
	if ($cid == $admin) {
		if (is_numeric($text) == "true") {
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Foydalanuvchi hisobidan $text ta limit olib tashlandi!</b>",
				'parse_mode' => "html",
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "◀️ Orqaga", 'callback_data' => "searchID=$id"]]
					]
				])
			]);
			$limit = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM `user_id` WHERE `user_id` = '$id'"))['limit'];
			$a = $limit - $text;
			mysqli_query($connect, "UPDATE `user_id` SET `limit` = '$a' WHERE `user_id` = '$id'");
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = $cid");
			exit();
		} else {
			bot('SendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Faqat raqamlardan foydalaning!</b>",
				'parse_mode' => 'html',
			]);
			exit();
		}
	}
}

if (mb_stripos($data, "bans=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_search == "true") {
			$id = explode("=", $data)[1];
			$ban = mysqli_fetch_assoc(mysqli_query($connect, "SELECT*FROM user_id WHERE user_id = $id"))['ban'];
			$result = mysqli_query($connect, "SELECT * FROM admins WHERE user_id = '$id'");
			$row = mysqli_fetch_assoc($result);
			if (!$row) {
				if ($ban == "ban") {
					$text = "<b>Foydalanuvchi ($id) bandan olindi!</b>";
					mysqli_query($connect, "UPDATE user_id SET ban = 'unban' WHERE user_id = $id");
				} else {
					$text = "<b>Foydalanuvchi ($id) banlandi!</b>";
					mysqli_query($connect, "UPDATE user_id SET ban = 'ban' WHERE user_id = $id");
				}
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => $text,
					'parse_mode' => "html",
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "searchID=$id"]]
						]
					])
				]);
				unlink("data/$id.json");
			} else {
				bot('answerCallbackQuery', [
					'callback_query_id' => $qid,
					'text' => "Adminlarni blocklash mumkin emas!",
					'show_alert' => true,
				]);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($data == "holati") {
	if ($cid2 == $admin) {
		if ($admin_bot == "true") {
			if ($bots == "on") {
				$on = "« ✅ »";
				$off = "☑";
				$f_name = "Faollashtirilgan!";
			} elseif ($bots == "off") {
				$on = "✅";
				$off = "« ☑ »";
				$f_name = "Faolsizlantirilgan!";
			}
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "<b>Quyidagilardan birini tanlang!</b>
	
<i>— Hozirgi holat: $f_name</i>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => $on, 'callback_data' => "bots_on"], ['text' => $off, 'callback_data' => "bots_off"]],
						[['text' => "◀️ Orqaga", 'callback_data' => "panel"]]
					]
				])
			]);
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($data, "bots_") !== false) {
	if ($cid2 == $admin) {
		if ($admin_bot == "true") {
			$ex = explode("_", $data)[1];
			if ($ex == "on") {
				$on = "« ✅ »";
				$off = "☑";
				$f_name = "Faollashtirilgan!";
			} elseif ($ex == "off") {
				$on = "✅";
				$off = "« ☑ »";
				$f_name = "Faolsizlantirilgan!";
			}
			if ($bots == $ex) {
				bot('answerCallbackQuery', [
					'callback_query_id' => $qid,
					'text' => "⚠️ $f_name",
					'show_alert' => true,
				]);
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "<b>Quyidagilardan birini tanlang!</b>
	
<i>— Hozirgi holat: $f_name</i>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => $on, 'callback_data' => "bots_on"], ['text' => $off, 'callback_data' => "bots_off"]],
							[['text' => "◀️ Orqaga", 'callback_data' => "panel"]]
						]
					])
				]);
				file_put_contents("other/bots.txt", $ex);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}


if ($data == "editlimit") {
	if ($cid2 == $admin) {
		if ($admin_limit == "true") {
			bot('deleteMessage', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
			]);
			bot('sendMessage', [
				'chat_id' => $cid2,
				'text' => "<b>Kerakli limit miqdorini kiriting:</b>

<i>Hozirgi beriladigan limit miqdori: $limits ta</i>",
				'parse_mode' => 'html',
				'reply_markup' => $back,
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'limit' WHERE user_id = '$cid2'");
			exit();
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}


if ($step == "limit") {
	if ($cid == $admin) {
		if (is_numeric($text) == true) {
			file_put_contents("other/limit.txt", $text);
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "✅ <code>$text</code> <b>qabul qilindi!</b>

<i>Muvaffaqiyatli o'zgartirildi!</i>",
				'parse_mode' => 'html',
				'reply_markup' => $back,
			]);
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = '$cid'");
			exit();
		} else {
			bot('sendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Faqat raqamlardan foydalaning!</b>",
				'parse_mode' => 'html',
				'reply_markup' => $back,
			]);
			exit();
		}
	}
}

if ($data == "admins") {
	if ($cid2 == $admin) {
		if ($admin_admin == "true") {
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "<b>Quyidagilardan birini tanlang:</b>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "➕ Yangi admin qo'shish", 'callback_data' => "new_admin"]],
						[['text' => "📑 Ro'yxat", 'callback_data' => "list"], ['text' => "🗑 O'chirish", 'callback_data' => "remove_admin"]],
						[['text' => "Orqaga", 'callback_data' => "panel"]]
					]
				])
			]);
			exit();
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($data == "list") {
	if ($cid2 == $admin) {
		if ($admin_admin == "true") {
			$admins = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `admins`"));
			if ($admins != "1") {
				$key = [];
				$i = 0;
				$res = mysqli_query($connect, "SELECT * FROM `admins`");
				while ($a = mysqli_fetch_assoc($res)) {
					$user_id = $a['user_id'];
					$appointed = $a['appointed'];
					$i++;
					$json = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getChat?chat_id=" . $user_id), true);
					$title = $json['result']['first_name'];
					if ($user_id == $AlijonovUz) {
						$first = "$title - Asosiy admin";
					} else {
						$first = "$title - Yordamchi admin";
					}
					$key[] = ["text" => "$i. $first", "callback_data" => "list=$user_id=$appointed"];
					$keyboard2 = array_chunk($key, 1);
					$keyboard2[] = [['text' => "◀️ Orqaga", 'callback_data' => "admins"]];
					$keys = json_encode([
						'inline_keyboard' => $keyboard2
					]);
				}
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "<b>👮 Botda mavjud adminlar ro'yxati!</b>",
					'parse_mode' => 'html',
					'reply_markup' => $keys
				]);
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "🤷‍♂️ <b>Yordamchi adminlar topilmadi!</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "Orqaga", 'callback_data' => "admins"]]
						]
					])
				]);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($data, "list=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_admin == "true") {
			$user_id = explode("=", $data)[1];
			$appointed = explode("=", $data)[2];
			$result = mysqli_query($connect, "SELECT * FROM `admins` WHERE `user_id` = '$user_id'");
			$row = mysqli_fetch_assoc($result);
			if ($row) {
				$json = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getChat?chat_id=" . $user_id), true);
				$first = $json['result']['first_name'];
				$user = $json['result']['username'];
				$json2 = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getChat?chat_id=" . $appointed), true);
				$first2 = $json2['result']['first_name'];
				$user2 = $json2['result']['username'];
				if ($user_id == $AlijonovUz) {
					$turi = "Asosiy";
				} else {
					$turi = "Yordamchi";
				}
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "<b>Admin:</b> <a href='https://t.me/$user'>$first</a>
<b>Admin turi:</b> $turi

<a href='https://t.me/$user2'>$first2</a> <b>tomonidan tayinlangan!</b>",
					'disable_web_page_preview' => true,
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "📋 Hozirgi holat", 'callback_data' => "holat=$user_id=$appointed"]],
							[['text' => "✏️ Ruxsatlarni o'zgartirish", 'callback_data' => "ruxsatlar=$user_id=$appointed"]],
							[['text' => "Orqaga", 'callback_data' => "list"]],
						]
					])
				]);
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⚠️ <b>Xatolik!</b>

<i>Ushbu admin ma'lumotlar bazasidan topilmadi!</i>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "admins"]],
						]
					])
				]);
				exit();
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}


if (mb_stripos($data, "ruxsatlar=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_admin == "true") {
			$user_id = explode("=", $data)[1];
			$appointed = explode("=", $data)[2];
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "✏️ <b>Ruxsatlarni o'zgartirish bo'limidasiz!</b>

⚠️ <i><b>Diqqat!</b> Admin ruxsatlarini o'zgartirmoqchi bo'lsangiz, quyida ko'rsatilgan bo'limlarga kirib, keyin o'zgartirishingiz mumkin!</i>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "📢 Kanallarni sozlash", 'callback_data' => "ruxsat=$user_id=$appointed=channel"]],
						[['text' => "🤖 Bot holati", 'callback_data' => "ruxsat=$user_id=$appointed=bot"], ['text' => "📋 Adminlar", 'callback_data' => "ruxsat=$user_id=$appointed=admin"]],
						[['text' => "🔎 Foydalanuvchini boshqarish", 'callback_data' => "ruxsat=$user_id=$appointed=search"]],
						[['text' => "📊 Statistika", 'callback_data' => "ruxsat=$user_id=$appointed=stat"], ['text' => "✉ Xabar yuborish", 'callback_data' => "ruxsat=$user_id=$appointed=send"]],
						[['text' => "⏳ Limitni o'zgartirish", 'callback_data' => "ruxsat=$user_id=$appointed=limit"]],
						[['text' => "Orqaga", 'callback_data' => "list=$user_id=$appointed"]],
					]
				])
			]);
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($data, "holat=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_admin == "true") {
			$user_id = explode("=", $data)[1];
			$appointed = explode("=", $data)[2];
			$res = mysqli_query($connect, "SELECT * FROM admins WHERE user_id = '$user_id'");
			while ($a = mysqli_fetch_assoc($res)) {
				$admin_channel = $a['channel'];
				$admin_bot = $a['bot'];
				$admin_search = $a['search'];
				$admin_stat = $a['stat'];
				$admin_send = $a['send'];
				$admin_limit = $a['limit'];
				$admin_api = $a['api'];
				$admin_admin = $a['admin'];
			}
			if ($admin_channel == "true") {
				$channel = "Ruxsat berilgan!";
			} elseif ($admin_channel == "false") {
				$channel = "Ruxsat berilmagan!";
			}
			if ($admin_bot == "true") {
				$bot = "Ruxsat berilgan!";
			} elseif ($admin_bot == "false") {
				$bot = "Ruxsat berilmagan!";
			}
			if ($admin_search == "true") {
				$search = "Ruxsat berilgan!";
			} elseif ($admin_search == "false") {
				$search = "Ruxsat berilmagan!";
			}
			if ($admin_stat == "true") {
				$stat = "Ruxsat berilgan!";
			} elseif ($admin_stat == "false") {
				$stat = "Ruxsat berilmagan!";
			}
			if ($admin_send == "true") {
				$send = "Ruxsat berilgan!";
			} elseif ($admin_send == "false") {
				$send = "Ruxsat berilmagan!";
			}
			if ($admin_limit == "true") {
				$limit = "Ruxsat berilgan!";
			} elseif ($admin_limit == "false") {
				$limit = "Ruxsat berilmagan!";
			}
			if ($admin_admin == "true") {
				$admin = "Ruxsat berilgan!";
			} elseif ($admin_admin == "false") {
				$admin = "Ruxsat berilmagan!";
			}

			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "👇 <b>Hozirgi holat!</b>
		
<i>→ Kanallarni sozlash: $channel
→ Bot holati: $bot
→ Foydalanuvchini boshqarish: $search
→ Statistika: $stat
→ Xabar yuborish: $send
→ Limitni o'zgartirish: $limit
→ Adminlar: $admin</i>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "Orqaga", 'callback_data' => "list=$user_id=$appointed"]],
					]
				])
			]);
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}


if (mb_stripos($data, "ruxsat=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_admin == "true") {
			$user_id = explode("=", $data)[1];
			$appointed = explode("=", $data)[2];
			$nomi = explode("=", $data)[3];
			$res = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM admins WHERE user_id = $user_id"))[$nomi];
			if ($res == "true") {
				$on = "« ✅ »";
				$off = "☑";
				$ruxsat = "👮 <b>Foydalanuvchiga ushbu bo'limga kirishga ruxsat berilgan!</b>";
			} elseif ($res == "false") {
				$on = "✅";
				$off = "« ☑ »";
				$ruxsat = "👮 <b>Foydalanuvchiga ushbu bo'limga kirishga ruxsat berilmagan!</b>";
			}
			bot('editMessageText', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
				'text' => "$ruxsat
		
<i>Quyidagilardan birini tanlang!</i>",
				'parse_mode' => 'html',
				'reply_markup' => json_encode([
					'inline_keyboard' => [
						[['text' => "$on", 'callback_data' => "rux=true=$user_id=$appointed=$nomi"], ['text' => "$off", 'callback_data' => "rux=false=$user_id=$appointed=$nomi"]],
						[['text' => "Orqaga", 'callback_data' => "ruxsatlar=$user_id=$appointed"]],
					]
				])
			]);
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($data, "rux=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_admin == "true") {
			$rux = explode("=", $data)[1];
			$user_id = explode("=", $data)[2];
			$appointed = explode("=", $data)[3];
			$nomi = explode("=", $data)[4];
			if ($user_id != $cid2) {
				if ($user_id != $AlijonovUz) {
					if ($cid2 == $AlijonovUz or $cid2 == $appointed) {
						if ($rux == "true") {
							$on = "« ✅ »";
							$off = "☑";
							$ruxsat = "👮 <b>Foydalanuvchiga ushbu bo'limga kirishga ruxsat berilgan!</b>";
						} elseif ($rux == "false") {
							$on = "✅";
							$off = "« ☑ »";
							$ruxsat = "👮 <b>Foydalanuvchiga ushbu bo'limga kirishga ruxsat berilmagan!</b>";
						}
						mysqli_query($connect, "UPDATE `admins` SET `$nomi` = '$rux' WHERE `user_id` = '$user_id'");
						bot('editMessageText', [
							'chat_id' => $cid2,
							'message_id' => $mid2,
							'text' => "$ruxsat
	
<i>Quyidagilardan birini tanlang!</i>",
							'parse_mode' => 'html',
							'reply_markup' => json_encode([
								'inline_keyboard' => [
									[['text' => "$on", 'callback_data' => "rux=true=$user_id=$appointed=$nomi"], ['text' => "$off", 'callback_data' => "rux=false=$user_id=$appointed=$nomi"]],
									[['text' => "Orqaga", 'callback_data' => "ruxsatlar=$user_id=$appointed"]],
								]
							])
						]);
					} else {
						bot('answerCallbackQuery', [
							'callback_query_id' => $qid,
							'text' => "Afsuski, ushbu foydalanuvchi ruxsatlarini o'zgartira olmaysiz. Siz faqat o'zingiz tayinlagan foydalanuvchi ruxsatlarini o'zgartirishingiz mumkin!",
							'show_alert' => true,
						]);
					}
				} else {
					bot('answerCallbackQuery', [
						'callback_query_id' => $qid,
						'text' => "Asosiy adminni ruxsatlarini o'zgartirish imkonsiz!",
						'show_alert' => true,
					]);
				}
			} else {
				bot('answerCallbackQuery', [
					'callback_query_id' => $qid,
					'text' => "O'zingizni ruxsatlaringizni o'zgartira olmaysiz!",
					'show_alert' => true,
				]);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($data == "new_admin") {
	if ($cid2 == $admin) {
		if ($admin_admin == "true") {
			bot('deleteMessage', [
				'chat_id' => $cid2,
				'message_id' => $mid2,
			]);
			bot('SendMessage', [
				'chat_id' => $admin,
				'text' => "<b>Kerakli foydalanuvchi ID raqamini yuboring:</b>",
				'parse_mode' => 'html',
				'reply_markup' => $back
			]);
			mysqli_query($connect, "UPDATE user_id SET step = 'new_admin' WHERE user_id = '$cid2'");
			exit();
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if ($step == "new_admin" and $cid == $admin) {
	$result = mysqli_query($connect, "SELECT * FROM user_id WHERE user_id = '$text'");
	$row = mysqli_fetch_assoc($result);
	if (!$row) {
		bot('SendMessage', [
			'chat_id' => $cid,
			'text' => "<b>Qidirilmoqda...</b>",
			'parse_mode' => 'html',
		]);
		bot('editMessageText', [
			'chat_id' => $cid,
			'message_id' => $mid + 1,
			'text' => "<b>Qidirilmoqda...</b>",
			'parse_mode' => 'html',
		]);
		bot('editMessageText', [
			'chat_id' => $cid,
			'message_id' => $mid + 1,
			'text' => "<b>Ushbu foydalanuvchi botdan foydalanmaydi!</b>
		
Boshqa ID raqamni kiriting:",
			'parse_mode' => 'html',
			'reply_markup' => $back
		]);
		exit();
	} else {
		$result = mysqli_query($connect, "SELECT * FROM admins WHERE user_id = '$text'");
		$row = mysqli_fetch_assoc($result);
		if (!$row) {
			bot('SendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Qidirilmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid,
				'message_id' => $mid + 1,
				'text' => "<b>Qidirilmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid,
				'message_id' => $mid + 1,
				'text' => "✅ <code>$text</code> <b>adminlar ro'yxatiga qo'shildi!</b>",
				'parse_mode' => 'html',
				'reply_markup' => $back
			]);
			mysqli_query($connect, "INSERT INTO `admins` (`user_id`,`appointed`,`channel`,`bot`,`search`,`stat`,`send`,`limit`,`admin`) VALUES ('$text','$cid','true','true','true','true','true','true','false')");
			mysqli_query($connect, "UPDATE user_id SET step = '0' WHERE user_id = '$cid'");
			exit();
		} else {
			bot('SendMessage', [
				'chat_id' => $cid,
				'text' => "<b>Qidirilmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid,
				'message_id' => $mid + 1,
				'text' => "<b>Qidirilmoqda...</b>",
				'parse_mode' => 'html',
			]);
			bot('editMessageText', [
				'chat_id' => $cid,
				'message_id' => $mid + 1,
				'text' => "<b>Ushbu foydalanuvchi adminlari ro'yxatida mavjud!</b>
		
Boshqa ID raqamni kiriting:",
				'parse_mode' => 'html',
				'reply_markup' => $back
			]);
			exit();
		}
	}
}

if ($data == "remove_admin") {
	if ($cid2 == $admin) {
		if ($admin_admin == "true") {
			$admins = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `admins`"));
			if ($admins != "1") {
				$key = [];
				$i = 0;
				$res = mysqli_query($connect, "SELECT * FROM `admins`");
				while ($a = mysqli_fetch_assoc($res)) {
					$user_id = $a['user_id'];
					$appointed = $a['appointed'];
					$i++;
					$json = json_decode(file_get_contents("https://api.telegram.org/bot" . AlijonovUz . "/getChat?chat_id=" . $user_id), true);
					$title = $json['result']['first_name'];
					if ($user_id == $AlijonovUz) {
						$first = "$title - Asosiy admin";
					} else {
						$first = "$title - Yordamchi admin";
					}
					$key[] = ["text" => "$i. $first", "callback_data" => "remove=$user_id=$appointed"];
					$keyboard2 = array_chunk($key, 1);
					$keyboard2[] = [['text' => "◀️ Orqaga", 'callback_data' => "admins"]];
					$keys = json_encode([
						'inline_keyboard' => $keyboard2
					]);
				}
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "🗑 <b>O'chirilishi kerak bo'lgan adminni ustiga bosing!</b>
			
⚠️ <i><b>Diqqat!</b> Kerakli adminni ustiga bosganingizdan so'ng, o'sha admin ro'yxatdan o'chiriladi!</i>",
					'parse_mode' => 'html',
					'reply_markup' => $keys
				]);
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>Yuklanmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "🤷‍♂️ <b>Yordamchi adminlar topilmadi!</b>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "admins"]],
						]
					])
				]);
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

if (mb_stripos($data, "remove=") !== false) {
	if ($cid2 == $admin) {
		if ($admin_admin == "true") {
			$user_id = explode("=", $data)[1];
			$appointed = explode("=", $data)[2];
			$result = mysqli_query($connect, "SELECT * FROM `admins` WHERE `user_id` = '$user_id'");
			$row = mysqli_fetch_assoc($result);
			if ($row) {
				if ($user_id != $cid2) {
					if ($user_id != $AlijonovUz) {
						if ($cid2 == $apptointed or $cid2 == $AlijonovUz) {
							mysqli_query($connect, "DELETE FROM `admins` WHERE `user_id` = '$user_id'");
							bot('editMessageText', [
								'chat_id' => $cid2,
								'message_id' => $mid2,
								'text' => "⏱ <b>O'chirilmoqda...</b>",
								'parse_mode' => 'html',
							]);
							bot('editMessageText', [
								'chat_id' => $cid2,
								'message_id' => $mid2,
								'text' => "⏱ <b>O'chirilmoqda...</b>",
								'parse_mode' => 'html',
							]);
							bot('deleteMessage', [
								'chat_id' => $cid2,
								'message_id' => $mid2,
							]);
							bot('SendMessage', [
								'chat_id' => $cid2,
								'text' => "✅ <code>$user_id</code> <b>adminlar ro'yxatidan olib tashlandi!</b>",
								'parse_mode' => 'html',
								'reply_markup' => json_encode([
									'inline_keyboard' => [
										[['text' => "◀️ Orqaga", 'callback_data' => "admins"]],
									]
								])
							]);
							exit();
						} else {
							bot('answerCallbackQuery', [
								'callback_query_id' => $qid,
								'text' => "Afsuski, ushbu foydalanuvchini adminlikdan olib tashlash huquqiga ega emassiz. Siz faqat o'zingiz tayinlagan adminni olib tashlashingiz mumkin!",
								'show_alert' => true,
							]);
						}
					} else {
						bot('answerCallbackQuery', [
							'callback_query_id' => $qid,
							'text' => "Asosiy adminni ro'yxatdan olib tashlash imkonsiz!",
							'show_alert' => true,
						]);
					}
				} else {
					bot('answerCallbackQuery', [
						'callback_query_id' => $qid,
						'text' => "O'zingizni ro'yxatdan olib tashlay olmaysiz!",
						'show_alert' => true,
					]);
				}
			} else {
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>O'chirilmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('editMessageText', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
					'text' => "⏱ <b>O'chirilmoqda...</b>",
					'parse_mode' => 'html',
				]);
				bot('deleteMessage', [
					'chat_id' => $cid2,
					'message_id' => $mid2,
				]);
				bot('SendMessage', [
					'chat_id' => $cid2,
					'text' => "⚠️ <b>Xatolik!</b>
			
<i>Ushbu admin ma'lumotlar bazasidan topilmadi!</i>",
					'parse_mode' => 'html',
					'reply_markup' => json_encode([
						'inline_keyboard' => [
							[['text' => "◀️ Orqaga", 'callback_data' => "admins"]],
						]
					])
				]);
				exit();
			}
		} else {
			bot('answerCallbackQuery', [
				'callback_query_id' => $qid,
				'text' => "Ushbu bo'limga kirish uchun sizga ruxsat berilmagan!",
				'show_alert' => true,
			]);
		}
	}
}

?>