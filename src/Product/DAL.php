<?php

namespace App\Product;

class DAL extends \App\DAL
{
    /**
     * @param int $offset
     * @param int $limit
     * @return Product[]|bool
     */
    public function getAll(int $offset = 0, $limit = 10)
    {
        $sql = sprintf('SELECT *
            FROM %s
            ORDER BY id ASC
            LIMIT :offset, :limit ;', $this->tableName());
        $query = $this->db->prepare($sql);
        $query->bindValue(':offset', $offset, SQLITE3_INTEGER);
        $query->bindValue(':limit', $limit, SQLITE3_INTEGER);
        $rows = $query->execute();
        if ($rows->numColumns()) {
            $result = [];
            while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
                $result[] = $this->decorate($row);
            }
            return $result;
        }
        return false;
    }

    /**
     * @param int $revId
     * @param int $offset
     * @param int $limit
     * @return Product[]|bool
     */
    public function getAllWithPrice(int $revId, int $offset = 0, $limit = 10)
    {
        $sql = sprintf('SELECT *
            FROM %s AS p INNER JOIN %s AS pp ON p.id = pp.product_id
            WHERE rev_id = :rev_id
            ORDER BY p.id ASC
            LIMIT :offset, :limit ;', $this->tableName(), (new Price\DAL())->tableName());
        $query = $this->db->prepare($sql);
        $query->bindValue(':rev_id', $revId, SQLITE3_INTEGER);
        $query->bindValue(':offset', $offset, SQLITE3_INTEGER);
        $query->bindValue(':limit', $limit, SQLITE3_INTEGER);
        $rows = $query->execute();
        if ($rows->numColumns()) {
            $result = [];
            while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
                $result[] = $this->decorate($row);
            }
            return $result;
        }
        return false;
    }

    /**
     * @param Product $row
     */
    public function create(Product $row)
    {
        $sql = sprintf('INSERT INTO %s (site_id, hash, url, title)
            VALUES (:site_id, :hash, :url, :title) ;', $this->tableName());
        $query = $this->db->prepare($sql);
        $query->bindValue(':site_id', $row->getSiteId(), SQLITE3_INTEGER);
        $query->bindValue(':hash', $row->getHash(), SQLITE3_TEXT);
        $query->bindValue(':url', $row->getUrl(), SQLITE3_TEXT);
        $query->bindValue(':title', $row->getTitle(), SQLITE3_TEXT);
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
            site_id INTEGER,
            hash TEXT,
            url TEXT,
            title TEXT
        ) ; CREATE UNIQUE INDEX hash_idx ON %s(hash) ;', $this->tableName(), $this->tableName());
        return $this->db->exec($sql);
    }

    /**
     * @inheritdoc
     */
    public function tableName(): string
    {
        return 'product';
    }

    /**
     * @param array $data
     * @return Product
     */
    private function decorate(array $data): Product
    {
        $row = new Product();
        $row->setId($data['id']);
        $row->setSiteId($data['site_id']);
        $row->setHash($data['hash']);
        $row->setUrl($data['url']);
        $row->setTitle($data['title']);
        if (isset($data['price'])) {
            $row->setPrice((float)$data['price']);
        }
        return $row;
    }
}