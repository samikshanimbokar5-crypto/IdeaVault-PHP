<?php

return [
    'host' => getenv('IDEAVAULT_DB_HOST') ?: getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: '127.0.0.1',
    'port' => getenv('IDEAVAULT_DB_PORT') ?: getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: '3306',
    'database' => getenv('IDEAVAULT_DB_NAME') ?: getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'ideavault_db',
    'username' => getenv('IDEAVAULT_DB_USER') ?: getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: 'root',
    'password' => getenv('IDEAVAULT_DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '',
];