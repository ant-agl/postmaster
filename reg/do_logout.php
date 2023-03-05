<?php

require_once 'boot.php';

$rjwt = filter_input(INPUT_COOKIE, 'rjwt');
if ($rjwt) {
    $stmt = pdo()->prepare("DELETE FROM `refresh_jwttoken` WHERE `refresh_token` =?");

    $stmt->execute([$rjwt]);
}

setcookie('jwt','', 1, '/toolbox');
setcookie('rjwt', '', 1, '/toolbox');
header('Location: login.php');