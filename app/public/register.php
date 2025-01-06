<?php
require_once '../private/url.php';
require_once PRIVATE_PATH . 'repository/ClientRepository.php';

$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $passwordRepeat = trim(filter_input(INPUT_POST, 'passwordRepeat'));
        $password = trim(filter_input(INPUT_POST, 'password'));
        $name = trim(filter_input(INPUT_POST, 'name'));

        if (empty($email) || empty($password) || empty($name) || empty($passwordRepeat)) throw new Exception('All fields are required');

        if ($password !== $passwordRepeat) throw new Exception('Passwords do not match');

        $repository = ClientRepository::getInstance();
        $ic = $repository->create($email, $password, $name);

        if ($ic === false) throw new Exception('An error occurred while creating the account');
        Auth::login($ic);
        header('Location:index.php');
    } catch (\Throwable $th) {
        Alert::set($th->getMessage(), AlertType::error);
    }
}

require_once PRIVATE_PATH . 'template/head.php';
?>
<form action="register.php" method="post">
    <div class="inpGroup"><input type="text" minlength="10" maxlength="100" name="name" id="name" placeholder="John Doe" required><label for="name">Name</label></div>
    <div class="inpGroup"><input type="email" maxlength="255" name="email" id="email" placeholder="email@example.com" value="<?= $email ?>" required><label for="email">Email</label></div>
    <div class="inpGroup"><input type="password" minlength="8" name="password" id="password" placeholder="" required><label for="password">Password</label></div>
    <div class="inpGroup"><input type="password" minlength="8" name="passwordRepeat" id="passwordRepeat" placeholder="" required><label for="passwordRepeat">Repeat password</label></div>

    <div class="btnGroup centered">
        <input type="reset" value="Cancel" class="btn secondary">
        <input type="submit" value="Register" class="btn">
    </div>
    <ul>
        <li>Already has an account? <a href="login.php">Login</a></li>
    </ul>
    <?= Alert::render() ?>
</form>
<?php require_once PRIVATE_PATH . 'template/footer.php'; ?>