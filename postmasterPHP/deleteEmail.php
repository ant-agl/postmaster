<?php
require_once 'headers.php';
require_once 'bd.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Получаем email из JSON-данных и добавляем к нему приставку postmaster_
    $email = 'postmaster_' . $data['email'];

    // Выполняем удаление записи из базы данных
    $stmt = $pdo->prepare("DELETE FROM vars WHERE name = ?");
    if ($stmt->execute([$email])) {
        // Если удаление прошло успешно, возвращаем ответ клиенту в формате JSON
        $response = array('success' => true);
        echo json_encode($response);
    } else {
        // Если удаление не удалось, возвращаем ответ клиенту с сообщением об ошибке в формате JSON
        $response = array('error' => 'Не удалось удалить запись из базы данных');
        echo json_encode($response);
    }
}