<?php

namespace App\Site;

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
     * @var string
     */
    private $siteMap;
    /**
     * @var string
     */
    private $charset;

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
     * @return string
     */
    public function getSiteMap(): string
    {
        return $this->siteMap;
    }

    /**
     * @param string $siteMap
     */
    public function setSiteMap(string $siteMap)
    {
        $this->siteMap = $siteMap;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     */
    public function setCharset(string $charset)
    {
        $this->charset = $charset;
    }
}