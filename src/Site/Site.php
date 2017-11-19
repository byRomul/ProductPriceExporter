<?php

namespace App\Site;

use App\App;
use GuzzleHttp\Client;

class Site
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $scheme;
    /**
     * @var string
     */
    private $host;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     */
    public function setScheme(string $scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return bool|string
     */
    public function getSiteMapUrl()
    {
        $url = $this->getScheme() . '://' . $this->getHost() . '/robots.txt';
        $client = new Client();
        $result = $client->request('GET', $url, ['timeout' => App::instance()->config('contentLoaderTimeout')]);
        if ($result->getStatusCode() === 200) {
            $robots = $result->getBody();
        } else {
            return false;
        }
        if (preg_match('~^Sitemap: (.*)$~im', $robots, $match) === false || !isset($match[1])) {
            return false;
        }
        return $match[1];
    }
}