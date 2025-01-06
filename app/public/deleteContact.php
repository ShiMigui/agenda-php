<?php
header('Content-type:text/json');
require_once '../private/url.php';

enum HTTPCode: int
{
    case OK = 200;
    case NOT_FOUND = 404;
    case NOT_ALLOWED = 405;
    case BAD_REQUEST = 400;
    case INTERNAL_SERVER_ERROR = 500;
}

function jsonResponse(HTTPCode $http = HTTPCode::BAD_REQUEST, ?string $msg = null): void
{
    http_response_code($http->value);
    echo json_encode(['msg' => $msg ?? $http->name, 'ok' => $http === HTTPCode::OK,]);
    exit;
}

if (!Auth::isLogged()) jsonResponse(HTTPCode::NOT_ALLOWED);

try {
    $ct = filter_var($_GET['ct'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $client = filter_var($_GET['client'] ?? '', FILTER_SANITIZE_NUMBER_INT);

    if ($ct <= 0 || $client <= 0) throw new Exception('Invalid contact or client ID.');

    if (Auth::getLogin() != $client) jsonResponse(HTTPCode::NOT_ALLOWED);

    require_once PRIVATE_PATH . 'repository/ContactRepository.php';
    $repository = ContactRepository::getInstance();

    if (!$repository->delete($ct, $client)) throw new Exception('Failed to delete contact.');

    jsonResponse(HTTPCode::OK, 'Contact deleted successfully.');
} catch (Exception $e) {
    jsonResponse(HTTPCode::BAD_REQUEST, $e->getMessage());
} catch (\Throwable $th) {
    jsonResponse(HTTPCode::INTERNAL_SERVER_ERROR, $th->getMessage());
}
