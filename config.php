<?php
    define('DB_NAME', 'gestion');
    define('DB_USER', 'root');
    define('DB_PASSWORD', null);
    define('DB_HOST', '127.0.0.1');

    try {
        $pdo = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST,
            DB_USER, DB_PASSWORD
        );
    } catch (PDOException $exception) {
        exit($exception->getMessage());
    }
