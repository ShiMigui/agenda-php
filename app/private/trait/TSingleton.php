<?php
trait TSingleton
{
    private static ?self $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): static
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}