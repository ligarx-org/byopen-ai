<?php

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://chatgpt4online.org/wp-json/mwai/v1/start_session');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'sec-ch-ua: "Not)A;Brand";v="99", "Google Chrome";v="127", "Chromium";v="127"',
    'Content-Type: application/json',
    'Referer: https://chatgpt4online.org/',
    'sec-ch-ua-mobile: ?0',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36',
    'sec-ch-ua-platform: "Windows"'
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;

?>