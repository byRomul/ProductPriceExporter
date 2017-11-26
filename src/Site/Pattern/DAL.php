<?php

namespace App\Site\Pattern;

class DAL extends \App\DAL
{
    /**
     * @param int $siteId
     * @return Pattern[]|bool
     */
    public function getPatternsBySiteId(int $siteId)
    {
        $sql = sprintf('SELECT *
            FROM %s
            WHERE site_id = :site_id ;', $this->tableName());
        $query = $this->db->prepare($sql);
        $query->bindValue(':site_id', $siteId, SQLITE3_INTEGER);
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
     * @param Pattern $row
     */
    public function create(Pattern $row)
    {
        $sql = sprintf('INSERT INTO %s (site_id, name, left, right)
            VALUES (:site_id, :name, :left, :right) ;', $this->tableName());
        $query = $this->db->prepare($sql);
        $query->bindValue(':site_id', $row->getSiteId(), SQLITE3_INTEGER);
        $query->bindValue(':name', $row->getName(), SQLITE3_TEXT);
        $query->bindValue(':left', $row->getLeft(), SQLITE3_TEXT);
        $query->bindValue(':right', $row->getRight(), SQLITE3_TEXT);
        $query->execute();
        $row->setId($this->db->lastInsertRowID());
    }

    /**
     * @param int $siteId
     */
    public function deleteBySiteId(int $siteId)
    {
        $sql = sprintf('DELETE FROM %s WHERE site_id = :site_id ;', $this->tableName());
        $query = $this->db->prepare($sql);
        $query->bindValue(':site_id', $siteId, SQLITE3_INTEGER);
        $query->execute();
    }

    /**
     * @inheritdoc
     */
    public function createTable(): bool
    {
        $sql = sprintf('CREATE TABLE %s (
            id INTEGER PRIMARY KEY,
            site_id TEXT,
            name TEXT,
            left TEXT,
            right TEXT
        ) ;', $this->tableName());
        return $this->db->exec($sql);
    }

    /**
     * @inheritdoc
     */
    public function tableName(): string
    {
        return 'site_pattern';
    }

    /**
     * @param array $data
     * @return Pattern
     */
    private function decorate(array $data): Pattern
    {
        $row = new Pattern();
        $row->setId($data['id']);
        $row->setSiteId($data['site_id']);
        $row->setName($data['name']);
        $row->setLeft($data['left']);
        $row->setRight($data['right']);
        return $row;
    }
}