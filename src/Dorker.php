<?php
namespace Cvar1984\TelegramBot;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\HttpClient\CurlClient;
use Serps\SearchEngine\Google\GoogleUrl;
use Serps\Core\Browser\Browser;
use Serps\SearchEngine\Google\NaturalResultType;

class Google
{
    protected static $userAgent = "Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.93 Safari/537.36";
    protected static $browserLanguage = "fr-FR";
    public static $link;
    public static $title;

    public static function search(string $dork)
    {
        $browser = new Browser(new CurlClient(), self::$userAgent, self::$browserLanguage);
        $googleClient = new GoogleClient($browser);
        $googleUrl = new GoogleUrl();
        $googleUrl->setSearchTerm($dork);
        $response = $googleClient->query($googleUrl);
        $results = $response->getNaturalResults();

        foreach ($results as $results) {
            if ($results->is(NaturalResultType::CLASSICAL)) {
                self::$title[]=$results->title;
                self::$link[]=$results->url;
            }
        }
    }
}
