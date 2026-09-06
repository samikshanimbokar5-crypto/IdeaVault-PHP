<?php

return [
    'host' => getenv('IDEAVAULT_DB_HOST') ?: '127.0.0.1',
    'port' => getenv('IDEAVAULT_DB_PORT') ?: '3306',
    'database' => getenv('IDEAVAULT_DB_NAME') ?: 'ideavault_db',
    'username' => getenv('IDEAVAULT_DB_USER') ?: 'root',
    'password' => getenv('IDEAVAULT_DB_PASSWORD') ?: '',
];