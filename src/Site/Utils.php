<?php

namespace App\Site;

use GuzzleHttp\Client;
use App\App;

class Utils
{
    /**
     * @param Site
     * @return string
     */
    public static function findSiteMap(Site $site): string
    {
        $url = $site->getScheme() . '://' . $site->getHost() . '/robots.txt';
        $client = new Client();
        $result = $client->request('GET', $url, ['timeout' => App::instance()->config('contentLoaderTimeout')]);
        if ($result->getStatusCode() === 200) {
            $source = $result->getBody();
        } else {
            return '';
        }
        if (preg_match('~^Sitemap: (.*)$~im', $source, $match) === false || !isset($match[1])) {
            return '';
        }
        return $match[1];
    }
    /**
     * @param Site
     * @return string
     */
    public static function findCharset(Site $site): string
    {
        $url = $site->getScheme() . '://' . $site->getHost() . '';
        $client = new Client();
        $result = $client->request('GET', $url, ['timeout' => App::instance()->config('contentLoaderTimeout')]);
        if ($result->getStatusCode() === 200) {
            $source = $result->getBody();
        } else {
            return '';
        }
        if (preg_match('~charset="?([a-z0-9-]+)"?[ />]~i', $source, $match) === false || !isset($match[1])) {
            return '';
        }
        return $match[1];
    }
}