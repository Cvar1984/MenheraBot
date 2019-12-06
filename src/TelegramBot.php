<?php
namespace Cvar1984\TelegramBot;
class Telegram
{
    protected const BOT_TOKEN = '1061711210:AAEjnJT2GN6fDpZg9eJVLYFSL7RlHKxo7M4';
    public const USER_NAME = ['Cvar1984', 'E13371984'];

    function __construct()
    {
        set_time_limit(0);
        ignore_user_abort(false);
        ini_set('max_execution_time', 0); //exec time
        ini_set('memory_limit', -1); //memmory limit
    }
    public static function bot(string $method, array $datas = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . self::BOT_TOKEN . '/' . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }
    public static function getUpdates($updateId)
    {
        $get = Telegram::bot('getupdates', ['offset' => $updateId]);
        return @end($get['result']);
    }
    public static function writeFiles(string $data, string $name)
    {
        $write = @fopen($name, 'a');
        if ($write) {
            fprintf($write, '%s%s', $data, PHP_EOL);
            fclose($write);
        } else {
            fprintf(STDERR, 'Error : can\'t write %s%s', $name, PHP_EOL);
        }
    }
}
