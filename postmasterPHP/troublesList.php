<?php
require_once 'headers.php';
require_once 'TokenClass.php';
require_once 'MailClass.php';
require_once 'bd.php';
try {
    $client_id = $_GET['client_id'];
    $storage = new TokenStorage($pdo, $client_id);
    $access_token = $storage->getAccessToken();

    $r = new MailRuAPI($access_token);
    header('Content-Type: application/json;charset=UTF-8');
    echo $r->troublesList();
} catch (Exception $e) {
    // Если произошла ошибка, возвращаем клиенту сообщение об ошибке в формате JSON
    $response = array('error' => $e->getMessage());
    echo json_encode($response);
}