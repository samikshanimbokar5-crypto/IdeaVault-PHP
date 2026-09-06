<?php

return [
    'host' => getenv('IDEAVAULT_DB_HOST') ?: getenv('MYSQLHOST') ?: '127.0.0.1',
    'port' => getenv('IDEAVAULT_DB_PORT') ?: getenv('MYSQLPORT') ?: '3306',
    'database' => getenv('IDEAVAULT_DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'ideavault_db',
    'username' => getenv('IDEAVAULT_DB_USER') ?: getenv('MYSQLUSER') ?: 'root',
    'password' => getenv('IDEAVAULT_DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '',
];