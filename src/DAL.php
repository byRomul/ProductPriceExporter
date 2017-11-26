<?php

namespace App;

abstract class DAL
{
    /**
     * @var \SQLite3
     */
    protected $db;

    public function __construct()
    {
        $pathToDatabase = App::instance()->config('pathToDatabase');
        $this->db = new \SQLite3($pathToDatabase);
        $this->db->enableExceptions(true);
    }

    /**
     * @return bool
     */
    abstract public function createTable(): bool;

    /**
     * @return string
     */
    abstract public function tableName(): string;
}