<?php

require_once 'boot.php';
require_once 'jwtClass.php';
$stmt = pdo()->prepare("SELECT * FROM users WHERE email =:email");

$stmt->execute(['email' => $_POST['email']]);

if ($stmt->rowCount() > 0) {
    flash('Это имя пользователя уже занято.');
    header('Location: register.php');
    die;
}

$stmt = pdo()->prepare("INSERT INTO users (username,email, password) VALUES (:username,:email, :password)");
$stmt->execute([
    'username' => $_POST['username'],
    'email' => $_POST['email'],
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
]);

$stmt = pdo()->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $_POST['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

unset($user['password']);

$user['iat'] = time();
$key = 1234567;
$token = new Token(1234);
$tok = $token->createTokenHS256($user);
setcookie('jwt', $tok, time()+3600, '/toolbox');
$refreshToken = $token->createRefreshToken();
setcookie('rjwt', $refreshToken, time()+10800, '/toolbox');
$stmt = pdo()->prepare("INSERT INTO refresh_jwttoken (user_id, refresh_token, expire, created_at) VALUES (?,?,?,?)");
$stmt->execute([$user['id'], $refreshToken,time()+10800, time()]);
header('Location: /toolbox');