<?php
require_once '../private/url.php';
require_once PRIVATE_PATH . 'repository/ContactRepository.php';

if (!Auth::isLogged()) {
    header('Location:login.php');
    exit;
}

$cdContact = $_GET['ct'] ?? false;
$cdClient = Auth::getLogin();

$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$name = $_POST['name'] ?? '';
$note = $_POST['note'] ?? '';

$repo = ContactRepository::getInstance();

if ((empty($phone) || empty($name)) && $cdContact !== false) {
    // Get values to inputs
    $contact = $repo->readById($cdContact, $cdClient, ClientContacts::all_attributes);
    if ($contact === null) header('Location:index.php');
    $name = $contact['nm_contact'];
    $email = $contact['nm_email'];
    $phone = $contact['nm_phone'];
    $note = $contact['nm_note'];
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $phone = str_replace([' ', '-'], '', $phone);
        if ($cdContact === false) {
            $ct = $repo->create($cdClient, $name, $email, $phone, $note);
            if (!$ct) throw new Exception('Failed to create contact.');
            Alert::set("Contact added.", AlertType::success);
            header('Location:contact.php');
        }
        // update contact
        $ic = $repo->update($cdContact, $cdClient, $name, $email, $phone, $note);
        if (!$ic) throw new Exception('Failed to update contact.');
        Alert::set("Contact updated.", AlertType::success);
    }
} catch (\Throwable $th) {
    Alert::set($th->getMessage(), AlertType::error);
}

require_once PRIVATE_PATH . 'template/head.php';
require_once PRIVATE_PATH . 'template/header.php';
?>
<form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
    <div class="inpGroup"><input type="text" id="name" name="name" placeholder="John Doe" maxlength="50" value="<?= $name ?>" required><label for="name">Contact name</label></div>
    <div class="inpGroup"><input type="email" id="email" name="email" placeholder="example@example.com" maxlength="255" value="<?= $email ?>"><label for="email">Email</label></div>
    <div class="inpGroup"><input type="tel" id="tel" name="phone" placeholder="" maxlength="15" value="<?= $phone ?>" oninput="validatePhone(this)" required><label for="tel">Phone</label></div>
    <div class="inpGroup"><textarea id="note" name="note" placeholder=""><?= $note ?></textarea><label for="note">Notes</label></div>
    <div class="btnGroup centered"><input class="btn secondary" type="reset" value="Cancel"><button type="submit">Save</button></div>
    <?= Alert::render() ?>
</form>
<script>
    function validatePhone(input) {
        input.value = input.value.replace(/[^0-9+]/g, '');
    }
</script>
<?php require_once PRIVATE_PATH . 'template/footer.php'; ?>