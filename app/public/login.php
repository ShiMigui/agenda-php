<?php
require_once '../private/url.php';
require_once PRIVATE_PATH . 'repository/ClientRepository.php';

$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $password = trim(filter_input(INPUT_POST, 'password'));

        if (empty($email) || empty($password)) throw new Exception('All fields are required');

        $repository = ClientRepository::getInstance();
        $client = $repository->searchByEmail($email);

        if ($client === null || !password_verify($password, $client['nm_password'])) throw new Exception('Email or password is incorrect');
        Auth::login($client['cd_client']);
        header('Location:index.php');
    } catch (\Throwable $th) {
        Alert::set($th->getMessage(), AlertType::error);
    }
}

require_once PRIVATE_PATH . 'template/head.php';
?>
<form action="login.php" method="post">
    <div class="inpGroup"><input type="email" maxlength="255" name="email" id="email" placeholder="email@example.com" value="<?= $email ?>" required><label for="email">Email</label></div>
    <div class="inpGroup"><input type="password" minlength="8" name="password" id="password" placeholder="" required><label for="password">Password</label></div>

    <div class="btnGroup centered">
        <input type="reset" value="Cancel" class="btn secondary">
        <input type="submit" value="Login" class="btn">
    </div>
    <ul>
        <li>Don't have yet an account? <a href="register.php">Register</a></li>
    </ul>
    <?= Alert::render() ?>
</form>
<?php require_once PRIVATE_PATH . 'template/footer.php'; ?>