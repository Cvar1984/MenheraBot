<?php
require __DIR__.'/vendor/autoload.php';
use Cvar1984\TelegramBot\Telegram;
use Cvar1984\TelegramBot\Google;
while (true) {
    Telegram::$lastUpdate = Telegram::getUpdates(Telegram::$lastUpdate['update_id'] + 1);
    $msg = Telegram::$lastUpdate['message'];
    if (empty($msg['text'])) {
        continue;
    }
    if (empty($msg['chat']['title'])) {
        $msg['chat']['title'] = null;
    }
    if (empty($msg['from']['username'])) {
        $msg['from']['username'] = null;
    }

    echo 'Chat Title : ' . $msg['chat']['title'] . PHP_EOL;
    echo 'Chat ID : ' . $msg['chat']['id'] . PHP_EOL;
    echo 'Name : ' . $msg['from']['first_name'] . PHP_EOL;
    echo 'Username: ' . $msg['from']['username'] . PHP_EOL;
    echo 'Text : ' . $msg['text'] . PHP_EOL;
    echo 'Date : ' . date('d/m/Y H:i:s', $msg['date']) . PHP_EOL . PHP_EOL;

    $data = array(
        'username' => $msg['from']['username'],
        'chat_title' => isset($msg['chat']['title']),
        'chat_id' => $msg['chat']['id'],
        'name' => $msg['from']['first_name'],
        'username' => $msg['from']['username'],
        'text' => $msg['text'],
        'date' => date('d/m/Y H:i:s', $msg['date'])
    );

    $data = json_encode($data, JSON_PRETTY_PRINT);
    Telegram::writeFiles($data, 'chat_logs.json');

    $data = json_encode($msg, JSON_PRETTY_PRINT);
    $status = null;

    Google::search($msg['text']);
    ob_start();
    $x=0;
    $count=count(Google::$title);
    while($count > $x) {
        echo '['.Google::$title[$x].']('.Google::$link[$x].')'.PHP_EOL;
        $x++;
    }
    $result=ob_get_clean();
    $status[]=Telegram::bot(
        'sendMessage',
        [
            'chat_id' => $msg['chat']['id'],
            'parse_mode'=>'markdown',
            'text'=>"{$result}"
        ]
    );

    Telegram::writeFiles(json_encode($status, JSON_PRETTY_PRINT), 'status.json');
    unset($status, $result);
    $result=null;
    sleep(5);
}
