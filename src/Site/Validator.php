<?php

namespace App\Site;

class Validator
{
    /**
     * @var Site
     */
    private $site;
    /**
     * @var string
     */
    private $lastError = '';

    /**
     * @param Site $site
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if ($this->site->getSiteMap() === '') {
            $this->lastError = 'Wrong sitemap';
            return false;
        }
        if ($this->site->getCharset() === '') {
            $this->lastError = 'Wrong charset';
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getLastError(): string
    {
        return $this->lastError;
    }
}