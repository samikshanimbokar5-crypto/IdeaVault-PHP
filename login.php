<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/auth.php';

if (current_user()) {
    redirect('index.php');
}

$error = $_GET['error'] ?? '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $statement = $pdo->prepare('SELECT id, email, name, avatar_url, password_hash FROM users WHERE email = :email');
    $statement->execute(['email' => $email]);
    $user = $statement->fetch();
    if ($user && $user['password_hash'] && password_verify($password, $user['password_hash'])) {
        login_user($user);
        redirect(after_login_location());
    }
    $error = 'Email or password is incorrect.';
}
page_header('Log in');
?>
<section class="auth-page">
    <div class="auth-panel">
        <a class="back-link" href="index.php">← Back to IdeaVault</a>
        <p class="eyebrow">Welcome back</p>
        <h1>Keep your ideas close.</h1>
        <p class="auth-copy">Sign in to make your project collection personal and ready wherever you are.</p>
        <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
        <form class="auth-form" method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <label>Email address<input type="email" name="email" value="<?= e($email) ?>" required autocomplete="email"></label>
            <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
            <button class="button" type="submit">Log in <span>↗</span></button>
        </form>
        <div class="auth-divider"><span>New to IdeaVault?</span></div>
        <a class="google-button" href="register.php">Create an account</a>
        <?php if (google_configured($config)): ?><a class="google-button" href="google-login.php"><span class="google-mark">G</span> Continue with Google</a><?php endif; ?>
        <p class="auth-footnote">Your password is stored using secure one-way hashing.</p>
    </div>
</section>
<?php page_footer(); ?>