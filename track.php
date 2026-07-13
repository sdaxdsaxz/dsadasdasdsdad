<?php
// ==================== track.php ====================

$botToken = '8732389660:AAFuWqbvjCFSTI0Z79rBiO5AH46B8jQgGn8';
$chatId   = '-1004371583928';   // ← Замени на ID твоего канала/группы

function sendTelegram($text) {
    global $botToken, $chatId;
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

// Получаем данные
$action = $_POST['action'] ?? 'visit';
$ip     = $_SERVER['REMOTE_ADDR'];
$ua     = $_SERVER['HTTP_USER_AGENT'];
$ref    = $_SERVER['HTTP_REFERER'] ?? 'Direct';
$time   = date('Y-m-d H:i:s');

// Логирование в файл
$log = "[$time] IP: $ip | Action: $action | UA: $ua | Ref: $ref\n";
file_put_contents('logs.txt', $log, FILE_APPEND);

// Уведомления в Telegram
if ($action === 'install_click') {
    $msg = "🔴 <b>НАЖАЛИ УСТАНОВКУ!</b>\n\n".
           "🕒 Время: $time\n".
           "🌐 IP: <code>$ip</code>\n".
           "📱 Устройство: <code>" . substr($ua, 0, 80) . "</code>";
    sendTelegram($msg);
} 
elseif ($action === 'page_visit') {
    $msg = "👁 <b>Новый посетитель</b>\n\n".
           "🕒 $time\n".
           "IP: <code>$ip</code>";
    sendTelegram($msg);
}

echo json_encode(['status' => 'ok']);
?>