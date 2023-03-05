<?php
require_once 'headers.php';
require_once 'TokenClass.php';
require_once 'MailClass.php';
require_once 'bd.php';
$client_id = $_GET['client_id'];
$storage = new TokenStorage($pdo,$client_id);
$access_token = $storage->getAccessToken();
$r = new MailRuAPI($access_token);
header('Content-Type: application/json;charset=UTF-8');

try {
    $result = $r->regList();
    echo $result;
} catch (Exception $e) {
    $error = array(
        'ok' => false,
        'error' => 'Неверные данные'
    );
    echo json_encode($error);
}