<?php
require_once 'headers.php';
require_once 'bd.php';


try {
    $token = $_COOKIE['rjwt'];
    $stmt = $pdo->query("SELECT REPLACE(name, 'postmaster_', '') as name_clean FROM vars
                         WHERE name LIKE 'postmaster_%' AND 
                         `user_id` IN (SELECT user_id FROM refresh_jwttoken WHERE `refresh_token`='".$token."')");
    
    // $stmt = $pdo->query("SELECT REPLACE(name, 'postmaster_', '') as name_clean FROM vars WHERE name LIKE 'postmaster_%'");
    $result = array();
    while ($row = $stmt->fetch()) {
        $result[] = $row['name_clean'];
    }
    echo json_encode($result);
} catch (Exception $e) {
    // Если выполнение запроса не удалось, возвращаем клиенту сообщение об ошибке в формате JSON
    $response = array('error' => $e->getMessage());
    return json_encode($response);
}