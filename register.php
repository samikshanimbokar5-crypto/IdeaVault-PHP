<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/auth.php';

if (current_user()) {
    redirect('index.php');
}
$errors = [];
$name = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    if ($name === '' || mb_strlen($name) > 150) $errors[] = 'Enter a name up to 150 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    if (!$errors) {
        $exists = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $exists->execute(['email' => $email]);
        if ($exists->fetch()) {
            $errors[] = 'An account with this email already exists.';
        } else {
            $statement = $pdo->prepare('INSERT INTO users (email, name, password_hash) VALUES (:email, :name, :password_hash)');
            $statement->execute(['email' => $email, 'name' => $name, 'password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
            $user = ['id' => $pdo->lastInsertId(), 'email' => $email, 'name' => $name, 'avatar_url' => null];
            login_user($user);
            redirect(after_login_location());
        }
    }
}
page_header('Create account');
?>
<section class="auth-page"><div class="auth-panel">
    <a class="back-link" href="login.php">← Back to login</a>
    <p class="eyebrow">Start your vault</p><h1>Make ideas easier to keep.</h1>
    <p class="auth-copy">Create a simple IdeaVault account and pick up where you left off.</p>
    <?php if ($errors): ?><div class="alert alert-error"><?= e(implode(' ', $errors)) ?></div><?php endif; ?>
    <form class="auth-form" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>Your name<input name="name" value="<?= e($name) ?>" required autocomplete="name"></label>
        <label>Email address<input type="email" name="email" value="<?= e($email) ?>" required autocomplete="email"></label>
        <label>Password<input type="password" name="password" required minlength="8" autocomplete="new-password"><small>Use at least 8 characters.</small></label>
        <button class="button" type="submit">Create account <span>↗</span></button>
    </form>
</div></section>
<?php page_footer(); ?>