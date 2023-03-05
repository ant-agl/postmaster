<?php
require_once 'headers.php';
require_once 'bd.php';
require_once 'TokenClass.php';
require_once '../reg/jwtClass.php';
$token = new Token(1234);
if (isset($_COOKIE['jwt']))
    $jwtToken = $_COOKIE['jwt'];
$payload = $token->getTokenPayload($jwtToken);
$id = $payload->id;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $client_id = $data->email;
    $storage = new TokenStorage($pdo,$client_id,$_COOKIE['rjwt']);
    $tokens_json = $data->tokens_json;
    try {
        $storage->init($tokens_json);
        // $storage = new TokenStorage($pdo, $client_id, $_COOKIE['rjwt']);
        $access_token = $storage->getAccessToken();
        $response = array('ok' => true, 'message' => 'Успешно!');
        echo json_encode($response);
        // if ($id !== null) {
        //     $stmt = $pdo->prepare("UPDATE vars SET user_id = ? WHERE `name` = ?");
        //     $stmt->execute([$id, 'postmaster_' . $client_id]);
        // }
    } catch (\Exception $e) {
        $response = array('ok' => false, 'error_description' => 'Неправильные данные');
        echo json_encode($response);
    }

}