<?php
namespace Cvar1984\TelegramBot;
use Serps\SearchEngine\Google\GoogleClient as SearchEngineClient;
use Serps\HttpClient\CurlClient;
use Serps\SearchEngine\Google\GoogleUrl as SearchEngineUrl;
use Serps\Core\Browser\Browser;
use Serps\SearchEngine\Google\NaturalResultType;

class SearchEngine
{
    protected const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.93 Safari/537.36';
    protected const BROWSER_LANG = 'fr-FR';

    public static function getHtml(string $dork)
    {
        $browser = new Browser(
            new CurlClient(),
            SearchEngine::USER_AGENT,
            SearchEngine::BROWSER_LANG
        );
        $client = new SearchEngineClient($browser);
        $searchUrl = new SearchEngineUrl();
        $searchUrl->setSearchTerm($dork);
        $response = $client->query($searchUrl);
        $result = $response->getNaturalResults();

        ob_start();
        foreach ($result as $results) {
            if ($results->is(NaturalResultType::CLASSICAL)) {
                echo '<a href=\'' .
                    $results->url .
                    '\'>' .
                    $results->title .
                    '</a>' .
                    PHP_EOL;
            }
        }
        return ob_get_clean();
    }
}
