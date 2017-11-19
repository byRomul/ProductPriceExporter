<?php

namespace App\Site;

class Validator
{
    /**
     * @var Site
     */
    private $site;

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
        if ($this->site->getSiteMapUrl() !== false) {
            return true;
        }
        return false;
    }
}