<?php
require __DIR__ . '/vendor/autoload.php';
use Cvar1984\TelegramBot\Telegram;
use Cvar1984\TelegramBot\SearchEngine;
$lastUpdate = 0;
while (true) {
    $lastUpdate = Telegram::getUpdates($lastUpdate['update_id'] + 1);
    $msg = $lastUpdate['message'];
    if (empty($msg['text']) || !isset($msg['text'])) {
        continue;
    }
    if (empty($msg['chat']['title'])) {
        $msg['chat']['title'] = null;
    }
    if (empty($msg['from']['username'])) {
        $msg['from']['username'] = null;
    }

    $data = array(
        'username' => $msg['from']['username'],
        'chat_title' => $msg['chat']['title'],
        'chat_id' => $msg['chat']['id'],
        'name' => $msg['from']['first_name'],
        'username' => $msg['from']['username'],
        'text' => $msg['text'],
        'date' => date('d/m/Y H:i:s', $msg['date'])
    );

    print_r($data);
    $dataJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    Telegram::writeFiles($dataJson, 'chat_logs.json');
    $htmlResult = SearchEngine::getHtml($msg['text']);

    $status[] = Telegram::bot(
        'sendMessage',
        [
            'chat_id' => $data['chat_id'],
            'parse_mode' => 'html',
            'text' => $htmlResult
        ]
    );

    if (substr($data['text'], 0, 6) == '/debug'
        && in_array($data['username'], Telegram::BOT_ADMIN)) {
        $document = new CURLFile('status.json');
        $status[] = Telegram::bot(
            'sendDocument',
            [
                'chat_id' => $data['chat_id'],
                'document' => $document
            ]
        );
    }

    Telegram::writeFiles(
        json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        'status.json'
    );
    unset($status);
}
