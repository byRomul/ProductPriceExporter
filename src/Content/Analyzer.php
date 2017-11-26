<?php

namespace App\Content;

use App\App;
use App\Site\Site;
use App\Content\Analyzer\DataSet;

class Analyzer
{
    /**
     * @var DataSet
     */
    private $dataSet;
    /**
     * @var int
     */
    private $maxPatternLen = 1000;

    /**
     * @param DataSet $dataSet
     */
    public function __construct(DataSet $dataSet)
    {
        $this->dataSet = $dataSet;
        $this->maxPatternLen = App::instance()->config('maxPatternLen');
    }

    /**
     * @return array
     */
    public function research(): array
    {
        $site = $this->dataSet->getSite();
        $patterns = [];
        $patternCounters = [];
        foreach ($this->dataSet as $example) {
            $loader = $this->getLoader($example->getUrl());
            $source = $loader->getSource();
            $tasks = [
                [
                    'find' => $example->getTitle(),
                    'name' => 'title',
                ],
                [
                    'find' => $example->getPrice(),
                    'name' => 'price',
                ],
            ];
            foreach ($tasks as &$task) {
                $pos = 0;
                $find = $task['find'];
                $findLen = mb_strlen($find, $site->getCharset());
                $sourceLen = mb_strlen($source, $site->getCharset());
                while (($pos = mb_strpos($source, $find, $pos, $site->getCharset())) !== false) {
                    $left = $this->findPattern($site, $source, $sourceLen, $pos, true);
                    if ($left !== '') {
                        $right = $this->findPattern($site, $source, $sourceLen, $pos + $findLen, false);
                        if ($right !== '') {
                            $index = md5($left . '§' . $right);
                            if (!isset($patternCounters[$task['name']][$index])) {
                                $patternCounters[$task['name']][$index] = 1;
                            } else {
                                $patternCounters[$task['name']][$index]++;
                            }
                            if (!isset($patterns[$task['name']][$index])) {
                                $patterns[$task['name']][$index] = [
                                    'pos' => $pos,
                                    'name' => $task['name'],
                                    'left' => $left,
                                    'right' => $right,
                                ];
                            }
                        }
                    }
                    $pos++;
                }
            }
        }
        $result = [];
        if (count($patterns)) {
            foreach ($patterns as $name => $data) {
                foreach ($data as $index => $pattern) {
                    if ($patternCounters[$name][$index] === count($this->dataSet)) {
                        $result[$name] = $pattern;
                    }
                }
            }
        }
        return $result;
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
        if ($sidePos < 0 || $sidePos > $sourceLen || $len > $this->maxPatternLen) {
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