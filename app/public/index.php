<?php
require_once '../private/url.php';

if (!Auth::isLogged()) {
    header('Location:login.php');
    exit;
}

require_once PRIVATE_PATH . 'template/head.php';
require_once PRIVATE_PATH . 'template/header.php';
?>
<main>

</main>
<?php require_once PRIVATE_PATH . 'template/footer.php'; ?>