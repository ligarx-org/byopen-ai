<?php
date_Default_timezone_set('Asia/Tashkent');
$soat = date('H:i');

$token = "7174650963:AAF5draJEgdxzPXI9HiJJ0Oa8rUDtsuiE1M";
$admin = "6150504681";
$limits = file_get_contents("other/limit.txt");
$kanal = file_get_contents("other/kanal/kanal.txt");
$time = file_get_contents("other/time.txt");
$batchSize = 100;

require('sql.php');

if ($soat == "00:00") {
    $offset = 0;
    do {
        $res = mysqli_query($connect, "SELECT * FROM `user_id` LIMIT $offset, $batchSize");
        $rowCount = mysqli_num_rows($res);
        while ($a = mysqli_fetch_assoc($res)) {
            $user_id = $a['user_id'];
            $limit = $a['limit'];
            if ($limits >= $limit) {
                $a = $limits - $limit;
                $all = $limit + $a;
            } else {
                $all = $limit + 0;
            }
            mysqli_query($connect, "UPDATE `user_id` SET `limit` = '$all' WHERE `user_id` = '$user_id'");
        }
        $offset += $batchSize;
    } while ($rowCount == $batchSize);
}

if ($soat >= $time) {
    $time = date("H:i", strtotime("+1 hour"));
    file_put_contents("other/time.txt", $time);
    $res = mysqli_query($connect, "SELECT * FROM `channels`");
    while ($a = mysqli_fetch_assoc($res)) {
        $url = $a['url'];
        $limit = $a['limit'];
        $json = json_decode(file_get_contents("https://api.telegram.org/bot" . $token . "/getChatMembersCount?chat_id=" . $url), true);
        $getCount = $json['result'];
        if ($limit != "∞") {
            if ($getCount >= $limit) {
                $tx = urlencode("✅ <i><b>$url</b> ushbu kanalning obunachilari soni <b>$limit ta</b>ga yetganligi sababli ro'yxatdan olib tashlandi!</i>");
                file_get_contents("https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=$admin&text=$tx&parse_mode=html");
                mysqli_query($connect, "DELETE FROM `channels` WHERE `url` = '$url'");
            }
        }
    }
}

?>