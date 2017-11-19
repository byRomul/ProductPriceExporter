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
    }

    /**
     * @return bool
     */
    abstract public function createTable(): bool;
}