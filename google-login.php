<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

if (!google_configured($config)) {
    redirect('login.php?error=' . rawurlencode('Google sign-in is not configured yet.'));
}

$_SESSION['oauth_state'] = bin2hex(random_bytes(32));
$params = http_build_query([
    'client_id' => $config['google_client_id'],
    'redirect_uri' => $config['google_redirect_uri'],
    'response_type' => 'code',
    'scope' => 'openid email profile',
    'state' => $_SESSION['oauth_state'],
    'access_type' => 'online',
    'prompt' => 'select_account',
]);
header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . $params);
exit;