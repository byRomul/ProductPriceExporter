<?php

namespace App\Site;

class DAL extends \App\DAL
{
    /**
     * @param string $host
     * @return Site|bool
     */
    public function getSiteByHost(string $host)
    {
        $sql = sprintf('SELECT *
            FROM %s
            WHERE host = :host ;', $this->tableName());
        $query = $this->db->prepare($sql);
        $query->bindValue(':host', $host, SQLITE3_TEXT);
        $data = $query->execute()->fetchArray(SQLITE3_ASSOC);
        if ($data !== false) {
            return $this->decorate($data);
        }
        return false;
    }

    /**
     * @return Site[]|bool
     */
    public function getAll()
    {
        $sql = sprintf('SELECT *
            FROM %s ;', $this->tableName());
        $query = $this->db->prepare($sql);
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
     * @param Site $row
     */
    public function create(Site $row)
    {
        $sql = sprintf('INSERT INTO %s (scheme, host, charset, sitemap)
            VALUES (:scheme, :host, :charset, :sitemap) ;', $this->tableName());
        $query = $this->db->prepare($sql);
        $query->bindValue(':scheme', $row->getScheme(), SQLITE3_TEXT);
        $query->bindValue(':host', $row->getHost(), SQLITE3_TEXT);
        $query->bindValue(':charset', $row->getCharset(), SQLITE3_TEXT);
        $query->bindValue(':sitemap', $row->getSiteMap(), SQLITE3_TEXT);
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
            scheme TEXT,
            host TEXT,
            charset TEXT,
            sitemap TEXT
        ) ;', $this->tableName());
        return $this->db->exec($sql);
    }

    /**
     * @inheritdoc
     */
    public function tableName(): string
    {
        return 'site';
    }

    /**
     * @param array $data
     * @return Site
     */
    private function decorate(array $data): Site
    {
        $row = new Site();
        $row->setId($data['id']);
        $row->setScheme($data['scheme']);
        $row->setHost($data['host']);
        $row->setCharset($data['charset']);
        $row->setSiteMap($data['sitemap']);
        return $row;
    }
}