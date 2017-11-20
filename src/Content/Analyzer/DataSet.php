<?php

namespace App\Content\Analyzer;

use App\Site\Site;
use App\Example;

class DataSet implements \Iterator
{
    /**
     * @var int
     */
    private $position = 0;
    /**
     * @var Site
     */
    private $site;
    /**
     * @var Example[]
     */
    private $examples = [];

    /**
     * @param Site $site
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * @return Site
     */
    public function getSite(): Site
    {
        return $this->site;
    }

    /**
     * @param Example $example
     */
    public function add(Example $example)
    {
        $this->examples[] = $example;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @inheritdoc
     */
    public function valid(): bool
    {
        return isset($this->examples[$this->position]);
    }

    /**
     * @inheritdoc
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @inheritdoc
     */
    public function current(): Example
    {
        return $this->examples[$this->position];
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->position++;
    }
}