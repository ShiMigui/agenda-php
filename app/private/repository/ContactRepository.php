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

    public function readAllFromClient(int $cdClient, ClientContacts $config = ClientContacts::all_attributes): ?array
    {
        $sql = "SELECT $config->value FROM contact WHERE cd_client = ?";

        $stmt = self::$conn->prepare($sql);
        if (!$stmt) throw new Exception('Failed to prepare statement: ' . self::$conn->error);

        $stmt->bind_param('i', $cdClient);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
    }

    public function delete(int $cdContact, int $cdClient): bool{
        $stmt = self::$conn->prepare("DELETE FROM contact WHERE cd_contact=? AND cd_client=?");
        $stmt->bind_param('ii', $cdContact, $cdClient);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}
