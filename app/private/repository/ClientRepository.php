<?php
require_once PRIVATE_PATH . 'trait/TSingleton.php';
require_once PRIVATE_PATH . 'Connection.php';

class ClientRepository
{
    use TSingleton;

    private static ?mysqli $conn;

    private function __construct()
    {
        self::$conn = Connection::getConnection();
    }

    public function create(string $email, string $password, string $name): int|false
    {
        if (!ClientValidator::isValidEmail($email)) throw new Exception('Invalid email format');
        if (!ClientValidator::isValidPassword($password)) throw new Exception('Password does not meet the requirements');
        $password = ClientValidator::hashPassword($password);

        $stmt = self::$conn->prepare("INSERT INTO client (nm_email, nm_password, nm_client) VALUES (?,?,?)");
        $stmt->bind_param('sss', $email, $password, $name);

        if (!$stmt->execute() || $stmt->affected_rows <= 0) return false;
        return $stmt->insert_id;
    }

    public function read(int $cd): ?array
    {
        $stmt = self::$conn->prepare("SELECT nm_email, nm_password, nm_client FROM client WHERE cd_client=?");
        $stmt->bind_param('i', $cd);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function searchByEmail(string $email): ?array
    {
        $stmt = self::$conn->prepare("SELECT cd_client, nm_password, nm_client FROM client WHERE nm_email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function delete(int $cd): bool
    {
        $stmt = self::$conn->prepare("DELETE FROM client WHERE cd_client=?");
        $stmt->bind_param('ii', $cd, $cd);

        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}

class ClientValidator
{
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function isValidPassword(string $password): bool
    {
        return strlen($password) >= 8;
        // && preg_match('/[0-9]/', $password) && preg_match('/[^\w]/', $password)
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
