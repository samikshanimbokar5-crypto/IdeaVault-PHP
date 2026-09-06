<?php

declare(strict_types=1);

session_start();

$config = file_exists(__DIR__ . '/config.local.php')
    ? require __DIR__ . '/config.local.php'
    : require __DIR__ . '/config.example.php';

try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['database']),
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    http_response_code(503);
    exit('IdeaVault is temporarily unavailable. Check the local database configuration.');
}