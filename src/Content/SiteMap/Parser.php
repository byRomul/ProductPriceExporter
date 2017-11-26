<?php

namespace App\Content\SiteMap;

class Parser
{
    const CONTENT_SITE_MAP = 'siteMap';
    const CONTENT_PRODUCT = 'product';

    /**
     * @var string
     */
    private $source;
    /**
     * @var bool
     */
    private $contentType;
    /**
     * @var array
     */
    private $items = [];

    /**
     * @param string $source
     */
    public function __construct(string $source)
    {
        $this->source = $source;
    }

    /**
     * @return bool
     */
    public function parse(): bool
    {
        $xml = simplexml_load_string($this->source);
        if ($xml === false) {
            return false;
        }
        foreach ($xml as $key => $value) {
            if ($this->contentType === null) {
                if ($key === 'sitemap') {
                    $this->contentType = self::CONTENT_SITE_MAP;
                } else {
                    $this->contentType = self::CONTENT_PRODUCT;
                }
            }
            $this->items[] = (string)$value->loc;
        }
        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function hasSiteMaps(): bool
    {
        if ($this->contentType === null) {
            throw new \Exception('unparsed content');
        }
        return $this->contentType === self::CONTENT_SITE_MAP;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function hasProducts(): bool
    {
        if ($this->contentType === null) {
            throw new \Exception('unparsed content');
        }
        return $this->contentType === self::CONTENT_PRODUCT;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }
}