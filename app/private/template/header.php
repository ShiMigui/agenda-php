<header>
    <div class="limited">
        <a href="/"><img src="" alt="">Agenda</a>
        <nav>
            <ul>
                <?php if(!Auth::isLogged()): ?>
                    <li class="btn secondary"><a href="createAccount.php">Create Account</a></li>
                    <li class="btn"><a href="login.php">Login</a></li>
                <?php else:?>
                    <li class="btn"><a href="logout.php">Logout</a></li>
                    <li class="btn"><a href="contact.php">New Contact</a></li>
                <?php endif;?>
            </ul>
        </nav>
    </div>
</header>