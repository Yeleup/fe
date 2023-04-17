<?php
session_start();
$cron_data = require(__DIR__ . '/../cron_data.php');

$base_url = $cron_data['base_url'];

$url = "$base_url/admin/index.php?route=common/login";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => $cron_data['username'],
    'password' => $cron_data['password'],
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
preg_match('/OCSESSID=(.*);/', $result, $matches, PREG_OFFSET_CAPTURE);
$ocsessid = $matches[1][0] ?? false;

$dashboard_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
preg_match('/user_token=(.*)/', $dashboard_url, $matches, PREG_OFFSET_CAPTURE);
$user_token = $matches[1][0] ?? false;

if (!($ocsessid && $user_token)) {
    echo "OCSESSID = $ocsessid\n";
    echo "user_token = $user_token\n";
    die("Error. ocsessid or user_token not set.");
}

curl_setopt($ch, CURLOPT_HTTPHEADER, ["Cookie: OCSESSID=$ocsessid"]);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_URL, "$base_url/admin/index.php?route=fe/pages/telegram/getToken&user_token={$user_token}");
$result = curl_exec($ch);
$result = json_decode($result, true);

$telegram_chat_ids = $result['fe_telegram_chat_ids'] ?? '';
$telegram_chat_ids = preg_split('/[\s]+/', $telegram_chat_ids);

$telegram_token = $result['telegram_token'] ?? '';

date_default_timezone_set("Asia/Almaty");
$current_datetime = date("Y-m-d H:i:s");

$apis = [
    'product_day',
    'category',
    'brand',
    'product',
    'priceAll',
    'balance',
    'crosscode',
    'deliveryPrice',
    'updateClientCategory'
];
foreach ($apis as $api) {
    $url = "$base_url/admin/index.php?route=fe/api/market_starter/{$api}&user_token={$user_token}";
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    $result = json_decode($result, true);

    if (($result['code'] ?? false) == 200) {

    }
    print_r($result);
    echo "\n\n\n";
}

if ($telegram_token) {
    echo "$telegram_token\n\n\n";
    foreach ($telegram_chat_ids as $id) {
        $ch_telegram = curl_init();
        curl_setopt_array(
            $ch_telegram,
            array(
                CURLOPT_URL => 'https://api.telegram.org/bot' . $telegram_token . '/sendMessage',
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POSTFIELDS => array(
                    'chat_id' => $id,
                    'text' => "Синхронизирован $current_datetime",
                ),
            )
        );
        $result = curl_exec($ch_telegram);
        echo "$result\n\n\n";
    }
}
