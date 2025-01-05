<?php
enum AlertType: int
{
    case none = 0;
    case error = 1;
    case success = 2;
}

class Alert
{
    public static function set(string $message, AlertType $type = AlertType::none): void
    {
        $_SESSION['AlertMsg'] = $message;
        $_SESSION['AlertType'] = $type->value;
    }

    public static function clear(): void
    {
        unset($_SESSION['AlertMsg'], $_SESSION['AlertType']);
    }

    public static function has(): bool
    {
        return isset($_SESSION['AlertMsg']);
    }

    public static function getType(): AlertType
    {
        return AlertType::from($_SESSION['AlertType'] ?? AlertType::none);
    }

    public static function getMessage(): ?string
    {
        return $_SESSION['AlertMsg'] ?? null;
    }

    public static function render()
    {
        if (!self::has()) return;
        $type = self::getType();
        $msg = self::getMessage();
        echo "<div id='alert' type='$type'>$msg</div>";
        Alert::clear();
    }
}
