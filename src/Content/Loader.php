<?php

namespace App\Content;

use App\App;
use GuzzleHttp\Client;

class Loader
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return bool|string
     */
    public function getSource()
    {
        $urlParts = parse_url($this->url);

        $client = new Client();
        $headers = [
            'Host' => $urlParts['host'],
            'Accept' => 'text/html,application/xhtml+xml,application/xml',
            'Accept-Language' => 'en-US;q=0.8,en;q=0.7',
            'Connection' => 'keep-alive',
            'Accept-Encoding' => 'deflate',
            'Cache-Control' => 'max-age=0',
            'Referer' => $urlParts['scheme'] . '://' . $urlParts['host'],
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.75 Safari/537.36',
        ];
        $result = $client->request('GET', $this->url, ['headers' => $headers, 'timeout' => App::instance()->config('contentLoaderTimeout')]);
        if ($result->getStatusCode() === 200) {
            return $result->getBody();
        }
        return false;
    }
}