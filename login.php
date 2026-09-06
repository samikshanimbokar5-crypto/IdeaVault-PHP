<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

if (current_user()) {
    redirect('index.php');
}

$error = $_GET['error'] ?? '';
page_header('Log in');
?>
<section class="auth-page">
    <div class="auth-panel">
        <a class="back-link" href="index.php">← Back to IdeaVault</a>
        <p class="eyebrow">Welcome back</p>
        <h1>Keep your ideas close.</h1>
        <p class="auth-copy">Sign in to make your project collection personal and ready wherever you are.</p>
        <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
        <?php if (google_configured($config)): ?>
            <a class="google-button" href="google-login.php"><span class="google-mark">G</span> Continue with Google</a>
        <?php else: ?>
            <div class="setup-note"><strong>Google sign-in is not configured yet.</strong><span>Add the Google OAuth variables from the setup guide, then redeploy.</span></div>
        <?php endif; ?>
        <p class="auth-footnote">Your Google password is never shared with IdeaVault.</p>
    </div>
</section>
<?php page_footer(); ?>