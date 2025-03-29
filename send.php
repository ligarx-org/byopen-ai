<?php
date_Default_timezone_set('Asia/Tashkent');
$soat = date('H:i');

$token = "BOT TOKEN";

require('sql.php');

$result = mysqli_query($connect, "SELECT * FROM `send`");
$row = mysqli_fetch_assoc($result);
$sendstep = $row['step'];

if ($_GET['update'] == "send") {
    $row1 = $row['time1'];
    $row2 = $row['time2'];
    $row3 = $row['time3'];
    $row4 = $row['time4'];
    $row5 = $row['time5'];
    $start_id = $row['start_id'];
    $stop_id = $row['stop_id'];
    $admin_id = $row['admin_id'];
    $mied = $row['message_id'];
    $tugma = $row['reply_markup'];

    if ($tugma == "bnVsbA==") {
        $reply_markup = "";
    } else {
        $reply_markup = urlencode(base64_decode($tugma));
    }

    $time1 = date('H:i', strtotime('+1 minutes'));
    $time2 = date('H:i', strtotime('+2 minutes'));
    $time3 = date('H:i', strtotime('+3 minutes'));
    $time4 = date('H:i', strtotime('+4 minutes'));
    $time5 = date('H:i', strtotime('+5 minutes'));
    $limit = 150;

    if ($soat == $row1 || $soat == $row2 || $soat == $row3 || $soat == $row4 || $soat == $row5) {
        $sql = "SELECT * FROM `user_id` LIMIT $start_id,$limit";
        $res = mysqli_query($connect, $sql);

        while ($a = mysqli_fetch_assoc($res)) {
            $id = $a['user_id'];

            if ($id == $stop_id) {
                file_get_contents("https://api.telegram.org/bot" . $token . "/copyMessage?chat_id=" . $id . "&from_chat_id=" . $admin_id . "&message_id=" . $mied . "&disable_web_page_preview=true&reply_markup=" . $reply_markup);
                $tx = urlencode("✅ <b>Xabar barcha bot foydalanuvchilariga muvaffaqiyatli yuborildi!</b>");
                file_get_contents("https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=$admin_id&text=$tx&parse_mode=html");
                mysqli_query($connect, "DELETE FROM `send`");
                exit();
            } else {
                file_get_contents("https://api.telegram.org/bot" . $token . "/copyMessage?chat_id=" . $id . "&from_chat_id=" . $admin_id . "&message_id=" . $mied . "&disable_web_page_preview=true&reply_markup=" . $reply_markup);
            }
        }

        mysqli_query($connect, "UPDATE `send` SET `time1` = '$time1', `time2` = '$time2', `time3` = '$time3', `time4` = '$time4', `time5` = '$time5'");
        $get_id = $start_id + $limit;
        mysqli_query($connect, "UPDATE `send` SET `start_id` = '$get_id'");
        $tx = urlencode("✅ <b>Yuborildi:</b> $get_id ta");
        file_get_contents("https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=$admin_id&text=$tx&parse_mode=html");
    }
    echo json_encode(["status" => true, "cron" => "Sending message"]);
}

?>