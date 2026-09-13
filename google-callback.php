<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

if (!google_configured($config) || !hash_equals($_SESSION['oauth_state'] ?? '', (string) ($_GET['state'] ?? ''))) {
    redirect('login.php?error=' . rawurlencode('The Google sign-in session expired. Please try again.'));
}
unset($_SESSION['oauth_state']);

$tokenContext = stream_context_create(['http' => [
    'method' => 'POST',
    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
    'content' => http_build_query([
        'code' => $_GET['code'] ?? '',
        'client_id' => $config['google_client_id'],
        'client_secret' => $config['google_client_secret'],
        'redirect_uri' => $config['google_redirect_uri'],
        'grant_type' => 'authorization_code',
    ]),
    'ignore_errors' => true,
]]);
$tokenResponse = @file_get_contents('https://oauth2.googleapis.com/token', false, $tokenContext);
$token = json_decode($tokenResponse ?: '', true);
if (empty($token['access_token'])) {
    redirect('login.php?error=' . rawurlencode('Google sign-in could not be completed.'));
}

$profileContext = stream_context_create(['http' => [
    'header' => 'Authorization: Bearer ' . $token['access_token'] . "\r\n",
    'ignore_errors' => true,
]]);
$profile = json_decode(@file_get_contents('https://openidconnect.googleapis.com/v1/userinfo', false, $profileContext) ?: '', true);
if (empty($profile['sub']) || empty($profile['email']) || ($profile['email_verified'] ?? false) !== true) {
    redirect('login.php?error=' . rawurlencode('Google did not return a verified email address.'));
}

$statement = $pdo->prepare('INSERT INTO users (google_id, email, name, avatar_url) VALUES (:google_id, :email, :name, :avatar_url) ON DUPLICATE KEY UPDATE email = VALUES(email), name = VALUES(name), avatar_url = VALUES(avatar_url)');
$statement->execute([
    'google_id' => $profile['sub'],
    'email' => $profile['email'],
    'name' => $profile['name'] ?? $profile['email'],
    'avatar_url' => $profile['picture'] ?? null,
]);
$userStatement = $pdo->prepare('SELECT id, email, name, avatar_url FROM users WHERE google_id = :google_id');
$userStatement->execute(['google_id' => $profile['sub']]);
session_regenerate_id(true);
$_SESSION['user'] = $userStatement->fetch();
redirect('index.php');