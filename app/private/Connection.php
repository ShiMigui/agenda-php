<?php
class Connection
{
    private static ?mysqli $instance = null;

    private static string $DB_NAME;
    private static string $DB_HOST;
    private static string $DB_USER;
    private static string $DB_PASS;

    public static function loadEnv(): void
    {
        if (isset(self::$DB_PASS)) return;

        self::$DB_NAME = $_ENV['DB_NAME'] ?? throw new Exception("Missing DB_NAME to connect to the database");
        self::$DB_HOST = $_ENV['DB_HOST'] ?? throw new Exception("Missing DB_HOST to connect to the database");
        self::$DB_USER = $_ENV['DB_USER'] ?? throw new Exception("Missing DB_USER to connect to the database");
        self::$DB_PASS = $_ENV['DB_PASS'] ?? throw new Exception("Missing DB_PASS to connect to the database");
    }

    public static function getConnection(): mysqli
    {
        if (self::$instance === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            self::$instance = new mysqli(self::$DB_HOST, self::$DB_USER, self::$DB_PASS, self::$DB_NAME);

            if (self::$instance->connect_error) {
                throw new Exception("Connection failed: " . self::$instance->connect_error);
            }
        }

        return self::$instance;
    }

    public static function close(): void
    {
        if (self::$instance === null) return;
        self::$instance->close();
        self::$instance = null;
    }
}
Connection::loadEnv();
