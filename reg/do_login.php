<?php

require_once 'boot.php';
require_once 'jwtClass.php';
$token = new Token(1234);
$stmt = pdo()->prepare("SELECT * FROM `users` WHERE `email` = :email");
$stmt->execute(['email' => $_POST['email']]);

    ($user = $stmt->fetch(PDO::FETCH_ASSOC));

if (!$user or !password_verify($_POST['password'], $user['password'])) {
    flash('Пользователь с такими данными не зарегистрирован');
    header('Location: login.php');
    die;
}
unset($user['password']);

$user['iat'] = time();
$key = 1234567;
$tok = $token->createTokenHS256($user);
setcookie('jwt', $tok, time()+3600, '/toolbox');
$refreshToken = $token->createRefreshToken();
setcookie('rjwt', $refreshToken, time()+10800, '/toolbox');
$stmt = pdo()->prepare("INSERT INTO  `refresh_jwttoken`  (`user_id`, `refresh_token`, `expire`, `created_at`) VALUES (?,?,?,?)");
$stmt->execute([$user['id'], $refreshToken,time()+10800, time()]);
 header('Location: /toolbox');
