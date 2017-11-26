<?php

namespace App;

class Example
{
    const COLS_NUM = 3;

    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $title;
    /**
     * @var float
     */
    private $price;

    /**
     * @param array $data
     * @return self
     * @throws \Exception
     */
    public static function build(array $data): self
    {
        if (count($data) !== static::COLS_NUM) {
            throw new \Exception('Wrong num of cols');
        }
        if (filter_var($data[0], FILTER_VALIDATE_URL) === false) {
            throw new \Exception('Wrong url');
        }
        if (strlen($data[1]) === 0) {
            throw new \Exception('Empty title');
        }
        if (is_numeric($data[2]) === false) {
            throw new \Exception('Wrong price');
        }
        $example = new Example();
        $example->setUrl($data[0]);
        $example->setTitle($data[1]);
        $example->setPrice($data[2]);
        return $example;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }
}