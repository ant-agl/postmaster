<?php
require_once 'headers.php';
require_once 'TokenClass.php';
require_once 'MailClass.php';
require_once 'bd.php';
$client_id = $_GET['client_id'];
$token = $_COOKIE['rjwt'];
$storage = new TokenStorage($pdo,$client_id,$token);
$access_token = $storage->getAccessToken();

$r = new MailRuAPI($access_token);
header('Content-Type: application/json;charset=UTF-8');
if (isset($_GET['date_from'])) {
    $date_from =  $_GET['date_from'];
    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : null;
    $domen = isset($_GET['domen']) ? $_GET['domen'] : null;
    $msgtype = isset($_GET['msgtype']) ? $_GET['msgtype'] : null;

    echo $r->statList($date_from, $date_to, $domen, $msgtype);
} else {
    // Если параметры отсутствуют, возвращаем ошибку
    echo json_encode(array('error' => 'Неверные данные'));
}