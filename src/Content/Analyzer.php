<?php

namespace App\Content;

use App\App;
use App\Site\Site;
use App\Content\Analyzer\DataSet;

class Analyzer
{
    const PATTERN_MAX_LEN = 5;

    /**
     * @var DataSet
     */
    private $dataSet;

    /**
     * @param DataSet $dataSet
     */
    public function __construct($dataSet)
    {
        $this->dataSet = $dataSet;
    }

    public function research()
    {
        $site = $this->dataSet->getSite();
        foreach ($this->dataSet as $example) {
            $loader = $this->getLoader($example->getUrl());
            $source = $loader->getSource();

            $tasks = [
                [
                    'find' => $example->getTitle(),
                    'data' => [],
                ],

                [
                    'find' => $example->getPrice(),
                    'data' => [],
                ],
            ];
            foreach ($tasks as &$task) {
                $pos = 0;
                $find = $task['find'];
                $findLen = mb_strlen($find, $site->getCharset());
                $sourceLen = mb_strlen($source, $site->getCharset());
                while (($pos = mb_strpos($source, $find, $pos, $site->getCharset())) !== false) {
                    $leftPattern = $this->findPattern($site, $source, $sourceLen, $pos, true);
                    if ($leftPattern !== '') {
                        $rightPattern = $this->findPattern($site, $source, $sourceLen, $pos + $findLen, false);
                        if ($rightPattern !== '') {
                            $task['data'][] = [
                                'pos' => $pos,
                                'leftPattern' => $leftPattern,
                                'rightPattern' => $rightPattern,
                            ];
                            print_r($task);
                        }
                    }
                    $pos++;
                }
            }
        }
    }

    /**
     * @param Site $site
     * @param string $source
     * @param int $sourceLen
     * @param int $pos
     * @param bool $isLeft
     * @param int $len
     * @return string
     */
    private function findPattern(Site $site, string &$source, int $sourceLen, int $pos, bool $isLeft, int $len = 1): string
    {
        if ($isLeft) {
            $sidePos = $pos - $len;
        } else {
            $sidePos = $pos;
        }
        if ($sidePos < 0 || $sidePos > $sourceLen || $len > self::PATTERN_MAX_LEN) {
            return '';
        }
        $pattern = mb_substr($source, $sidePos, $len, $site->getCharset());
        if (mb_substr_count($source, $pattern, $site->getCharset()) === 1) {
            return $pattern;
        } else {
            return $this->findPattern($site, $source, $sourceLen, $pos, $isLeft, $len + 1);
        }
    }

    /**
     * @param string $url
     * @return Cached\Loader|Loader
     */
    private function getLoader(string $url)
    {
        if (App::instance()->config('useContentLoaderCache')) {
            $loader = new Cached\Loader($url);
        } else {
            $loader = new Loader($url);
        }
        return $loader;
    }
}