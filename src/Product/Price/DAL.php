<?php

namespace App\Product\Price;

use App\Product;

class DAL extends \App\DAL
{
    /**
     * @param Price $row
     */
    public function create(Price $row)
    {
        $sql = sprintf('INSERT INTO %s (product_id, rev_id, price)
            VALUES (:product_id, :rev_id, :price) ;', $this->tableName());
        $query = $this->db->prepare($sql);
        $query->bindValue(':product_id', $row->getProductId(), SQLITE3_INTEGER);
        $query->bindValue(':rev_id', $row->getRevId(), SQLITE3_INTEGER);
        $query->bindValue(':price', $row->getPrice(), SQLITE3_FLOAT);
        $query->execute();
        $row->setId($this->db->lastInsertRowID());
    }

    /**
     * @inheritdoc
     */
    public function createTable(): bool
    {
        $sql = sprintf('CREATE TABLE %s (
            id INTEGER PRIMARY KEY,
            product_id INTEGER,
            rev_id INTEGER,
            price FLOAT
        ) ; CREATE UNIQUE INDEX price_idx ON %s(product_id, rev_id) ;', $this->tableName(), $this->tableName());
        return $this->db->exec($sql);
    }

    /**
     * @inheritdoc
     */
    public function tableName(): string
    {
        return 'product_price';
    }
}