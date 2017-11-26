<?php

namespace App\Site\Pattern;

class Pattern
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $siteId;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $left;
    /**
     * @var string
     */
    private $right;

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
     * @return int
     */
    public function getSiteId(): int
    {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     */
    public function setSiteId(int $siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLeft(): string
    {
        return $this->left;
    }

    /**
     * @param string $left
     */
    public function setLeft(string $left)
    {
        $this->left = $left;
    }

    /**
     * @return string
     */
    public function getRight(): string
    {
        return $this->right;
    }

    /**
     * @param string $right
     */
    public function setRight(string $right)
    {
        $this->right = $right;
    }
}