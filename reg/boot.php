<?php

function pdo(): PDO {
    static $pdo;

    if (!$pdo) {
        $config = require 'config.php';
        $dsn = 'mysql:dbname='.$config['db_name'].';host='.$config['db_host'];
        $pdo = new PDO($dsn, $config['db_user'], $config['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Проверка существования базы данных и создание, если не существует
        $stmt = $pdo->query("SELECT 1 FROM information_schema.schemata WHERE schema_name = '{$config['db_name']}'");
        if ($stmt->rowCount() == 0) {
            $pdo->query("CREATE DATABASE {$config['db_name']}");
        }

        // Создание таблиц, если не существуют
        $pdo->query("
            CREATE TABLE IF NOT EXISTS users (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL, 
                password VARCHAR(255) NOT NULL
            )
        ");
        $pdo->query("
            CREATE TABLE IF NOT EXISTS refresh_jwttoken (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT  NOT NULL,
                refresh_token TEXT NOT NULL,
                expire INT NOT NULL,
                created_at INT NOT NULL
            )
        ");
    }
    return $pdo;
}
function flash (?string $message = null){
    if ($message) {
        // $_COOKIE['flash'] = $message;
        setcookie('flash', $message, '', '/toolbox');
    } else {
        if (!empty($_COOKIE['flash'])) { ?>
        <div class="form-row alert">
            <?=$_COOKIE['flash']?>
        </div>
        <?php }
        // unset($_COOKIE['flash']);
        setcookie('flash', '', '', '/toolbox');
    }
}

function chech_auth(): bool{
    return !!($_COOKIE['rjwt'] ?? false);
}