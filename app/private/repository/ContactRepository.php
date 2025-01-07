<?php
require_once PRIVATE_PATH . 'trait/TSingleton.php';
require_once PRIVATE_PATH . 'Connection.php';

enum ClientContacts: string
{
    case all_attributes = '*';
    case cd_nm = 'cd_contact, nm_contact';
}

class ContactRepository
{
    use TSingleton;

    private static ?mysqli $conn;

    private function __construct()
    {
        self::$conn = Connection::getConnection();
    }

    public function create(int $cdClient, string $name, string $email, string $phone, string $note): int|false
    {
        if (!ContactValidator::validatePhone($phone)) throw new Exception('Invalid phone number.');
        if (!empty($email) && !ContactValidator::validateEmail($email)) throw new Exception('Invalid email address.');
        $stmt = self::$conn->prepare("CALL insert_contact(?,?,?,?,?);");
        $stmt->bind_param('issss', $cdClient, $name, $email, $phone, $note);
        if (!$stmt->execute()) return false;

        $stmt->store_result();
        $stmt->bind_result($cdContact);
        if ($stmt->fetch()) return (int) $cdContact;
        return false;
    }

    public function readById(int $cdContact, int $cdClient, ClientContacts $config = ClientContacts::all_attributes): ?array
    {
        $sql = "SELECT $config->value FROM contact WHERE cd_contact=? AND cd_client=?";

        $stmt = self::$conn->prepare($sql);
        if (!$stmt) throw new Exception('Failed to prepare statement: ' . self::$conn->error);

        $stmt->bind_param('ii', $cdContact, $cdClient);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function readAllFromClient(int $cdClient, ClientContacts $config = ClientContacts::all_attributes): ?array
    {
        $sql = "SELECT $config->value FROM contact WHERE cd_client=?";

        $stmt = self::$conn->prepare($sql);
        if (!$stmt) throw new Exception('Failed to prepare statement: ' . self::$conn->error);

        $stmt->bind_param('i', $cdClient);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
    }

    public function update(int $cdContact, int $cdClient, string $name, string $email, string $phone, string $note): bool
    {
        if (!ContactValidator::validatePhone($phone)) throw new Exception('Invalid phone number.');
        if (!empty($email) && !ContactValidator::validateEmail($email)) throw new Exception('Invalid email address.');
        $stmt = self::$conn->prepare("UPDATE contact SET nm_contact=?, nm_email=?, nm_phone=?, nm_note=? WHERE cd_contact=? AND cd_client=?");
        $stmt->bind_param('ssssii', $name, $email, $phone, $note, $cdContact, $cdClient);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function delete(int $cdContact, int $cdClient): bool
    {
        $stmt = self::$conn->prepare("DELETE FROM contact WHERE cd_contact=? AND cd_client=?");
        $stmt->bind_param('ii', $cdContact, $cdClient);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}

class ContactValidator
{
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public static function validatePhone(string $phone): bool
    {
        return preg_match('/^\+?[0-9]{10,15}$/', $phone) === 1;
    }
}
