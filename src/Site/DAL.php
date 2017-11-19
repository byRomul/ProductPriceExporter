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
        $query = $this->db->prepare('SELECT * FROM sites WHERE host = :host ;');
        $query->bindValue(':host', $host, SQLITE3_TEXT);
        $data = $query->execute()->fetchArray(SQLITE3_ASSOC);
        if ($data !== false) {
            return $this->decorate($data);
        }
        return false;
    }

    /**
     * @param Site $row
     */
    public function create(Site $row)
    {
        $query = $this->db->prepare('INSERT INTO sites (scheme, host) VALUES (:scheme, :host) ;');
        $query->bindValue(':scheme', $row->getScheme(), SQLITE3_TEXT);
        $query->bindValue(':host', $row->getHost(), SQLITE3_TEXT);
        $query->execute();
        $row->setId($this->db->lastInsertRowID());
    }

    /**
     * @inheritdoc
     */
    public function createTable(): bool
    {
        $query = 'CREATE TABLE sites (
            id INTEGER PRIMARY KEY,
            scheme TEXT,
            host TEXT
        )';
        return $this->db->exec($query);
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
        return $row;
    }
}