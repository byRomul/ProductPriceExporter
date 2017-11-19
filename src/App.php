<?php

namespace App;

class App
{
    /**
     * @var array
     */
    private $config = [];
    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @var self
     */
    private static $instance;

    private function __construct()
    {
        $this->config = include __DIR__ . '/../config.php';
        $this->dateTime = new \DateTime();
    }

    /**
     * @return self
     */
    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param string $param
     * @return mixed
     */
    public function config($param)
    {
        return $this->config[$param];
    }

    /**
     * @return \DateTime
     */
    public function now(): \DateTime
    {
        return $this->dateTime;
    }
}