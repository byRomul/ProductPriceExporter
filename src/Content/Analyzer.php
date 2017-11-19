<?php

namespace App\Content;

use App\App;
use App\Example;
use App\Site\Site;

class Analyzer
{
    const FRAGMENT_LEN = 5;

    /**
     * @var Site
     */
    private $site;
    /**
     * @var Example
     */
    private $example;

    /**
     * @param Site $site
     * @param Example $example
     */
    public function __construct(Site $site, Example $example)
    {
        $this->site = $site;
        $this->example = $example;
    }

    public function research()
    {
        $loader = $this->getLoader();
        $source = $loader->getSource();

        $tasks = [
            [
                'find' => $this->example->getTitle(),
                'data' => [],
            ],

            [
                'find' => $this->example->getPrice(),
                'data' => [],
            ],
        ];
        foreach ($tasks as &$task) {
            $find = $task['find'];
            $findLen = mb_strlen($find);
            $pos = 0;
            while (($pos = mb_strpos($source, $find, $pos)) !== false) {
                if (($pos - self::FRAGMENT_LEN) < 0) {
                    $pos++;
                    continue;
                }
                $startBefore = $pos - self::FRAGMENT_LEN - 1;
                $startAfter = $pos + $findLen + 1;
                $task['data'][] = [
                    'pos' => $pos,
                    'contentBefore' => mb_substr($source, $startBefore, self::FRAGMENT_LEN),
                    'contentAfter' => mb_substr($source, $startAfter, self::FRAGMENT_LEN),
                ];
                $pos++;
            }
        }
    }

    /**
     * @return Cached\Loader|Loader
     */
    private function getLoader()
    {
        if (App::instance()->config('useContentLoaderCache')) {
            $loader = new Cached\Loader($this->example->getUrl());
        } else {
            $loader = new Loader($this->example->getUrl());
        }
        return $loader;
    }
}