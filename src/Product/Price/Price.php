<?php

namespace App\Product\Price;

class Price
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $productId;
    /**
     * @var int
     */
    private $revId;
    /**
     * @var float
     */
    private $price;

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
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getRevId(): int
    {
        return $this->revId;
    }

    /**
     * @param int $revId
     */
    public function setRevId(int $revId)
    {
        $this->revId = $revId;
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