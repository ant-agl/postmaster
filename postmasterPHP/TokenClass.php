<?php
require_once 'bd.php';

class TokenStorage
{
    private string $name;
    private int $user_id;
    private PDO $pdo;

    public function __construct(PDO $pdo, $client_id = null, $token = null)
    {
        $this->pdo = $pdo;
        $this->setClinetId($client_id?:'tokens');

        if ($token) {
            $s = $this->pdo->prepare('SELECT user_id FROM refresh_jwttoken WHERE `refresh_token`=?');
            $s->execute([$token]);
            $this->user_id = $s->fetch()['user_id'];
        }
    }

    public function setClinetId($id)
    {
        $this->name = 'postmaster_' . $id;
    }

    public function getAccessToken()
    {
        $info = json_decode($this->getVar($this->name, $this->user_id));
        if (!$info) {
            throw new \Exception('Нет данных токена!');
        }
        if ($info->expired_at < time()) {
            $info = $this->refresh($info->refresh_token);
        }
        return $info->access_token;
    }

    public function init(string $tokens_json)
    {
        $tokens = json_decode($tokens_json);
        if (!isset($tokens->refresh_token)) {
            throw new \Exception('Некорректный JSON с токенами');
        }
        $result = ['expired_at'=>time() - 60, 'refresh_token'=>$tokens->refresh_token, 'access_token'=> ''];
        $this->setVar($this->name, $this->user_id, json_encode($result));
        return (object)$result;
    }

    private function refresh($token)
    {
        $postData = array(
            'grant_type' => 'refresh_token',
            'client_id' => 'postmaster_api_client',
            'refresh_token' => $token
        );
        // Отправляем POST-запрос на сервер Mail.ru
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://o2.mail.ru/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_USERAGENT, 'postmaster');
        $response = curl_exec($ch);
        curl_close($ch);
        $tokens = json_decode($response);
        if (!$tokens) {
            throw new \Exception('Не удалось получить новый токен');
        }
        if (!isset($tokens->access_token) or !isset($tokens->expires_in)) {
            throw new \Exception(isset($tokens->error_description) ? $tokens->error_description : 'Некорректный формат токенов');
        }
        $result = ['expired_at'=>time() + $tokens->expires_in - 60, 'refresh_token'=>$token, 'access_token'=> $tokens->access_token];
        $this->setVar($this->name, $this->user_id, json_encode($result));
        return (object)$result;
    }
    private function setVar($name, $user_id, $value)
    {
        if ($this->getVar($name, $user_id)) {
            $s = $this->pdo->prepare('UPDATE vars SET value=? WHERE name=? AND `user_id`=?');
            $s->execute([$value, $name, $user_id]);
        } else {
            $s = $this->pdo->prepare('INSERT INTO vars (name, value, `user_id`) VALUES (?, ?, ?)');
            $s->execute([$name, $value, $user_id]);
        }
    }

    private function getVar($name, $user_id)
    {
        $s = $this->pdo->prepare('SELECT value FROM vars WHERE name=? AND `user_id`=?');
        $s->execute([$name, $user_id]);
        return $s->fetchColumn();
    }
}