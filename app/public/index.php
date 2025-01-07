<?php
require_once '../private/url.php';

if (!Auth::isLogged()) {
    header('Location:login.php');
    exit;
}

require_once PRIVATE_PATH . 'repository/ContactRepository.php';
$repository = ContactRepository::getInstance();

$cdClient = Auth::getLogin();
$contacts = $repository->readAllFromClient($cdClient, ClientContacts::cd_nm);

require_once PRIVATE_PATH . 'template/head.php';
require_once PRIVATE_PATH . 'template/header.php';
?>
<main>
    <?php if (!empty($contacts)): ?>
        <table>
            <thead><tr><th colspan="3">Contacts</th></tr></thead>
            <tbody>
                <?php foreach ($contacts as $ct):
                    $nm = $ct['nm_contact'];
                    $cd = $ct['cd_contact'];
                    $link = "ct=$cd";
                ?>
                    <tr>
                        <td><?= $nm ?></td>
                        <td><a class="btn" href="contact.php?<?= $link ?>">Edit</a></td>
                        <td><button class="secondary" data-href="deleteContact.php?<?= $link ?>" onclick="deleteContact(event)">Delete</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?><?= Alert::write('No contacts found for this client.') ?><?php endif; ?>

    <script src="scripts/deleteContact.js"></script>
</main>
<?php require_once PRIVATE_PATH . 'template/footer.php'; ?>