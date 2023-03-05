<?php
require_once 'vendor/autoload.php';

require_once 'boot.php';



function getTokenPayload($key,$jwtToken)
{
    $t = explode('.', $jwtToken);
    if (count($t) != 3) {
        return false;
    }
    $header = json_decode(base64_decode($t[0]));
    $payLoad = json_decode(base64_decode($t[1]));
    if ($payLoad->iat+3600 < time()){
        return false;
    }
    if (!$header or !$payLoad) {
        return false;
    }
    if (!isset($header->alg)) {
        return false;
    }
    if (!isset($header->typ)) {
        return false;
    }
    if ($header->typ != 'JWT') {
        return false;
    }
    // тут только под один тип,под другие надо будет переделать
    if ($header->alg != 'HS256'){
        return false;
    }
    $calculatedHash = base64_encode(hash_hmac('sha256', $t[0] . '.' . $t[1],$key,true));
    $rightCalculateHash = str_replace(['+', '/', '='], ['-', '_', ''], $calculatedHash);

    if ($t[2] != $rightCalculateHash) {
        return false;
    }
    return $payLoad;
}

function createTokenHS256($payload, $key) {
    $header = [
        'alg' => 'HS256',
        'typ' => 'JWT',
    ];
    $header_b64 = base64url_encode(json_encode($header));

    $payload_b64 = base64url_encode(json_encode($payload));


    $jw = $header_b64 . '.' . $payload_b64;

    $calculatedHash = base64url_encode(hash_hmac('sha256', $jw ,$key,true));

    return  $jw . '.' .$calculatedHash;
}

function base64url_encode($hash)
{
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($hash));
}

function createRefreshToken(int $length = 21): string {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(openssl_random_pseudo_bytes($length)));
}
function verifyCookieToken(){
    $key = '1234567';
    if  (!isset($_COOKIE['jwt'])) {
        if (!isset($_COOKIE['rjwt'])) {
            return false;
        }
        return updateRefreshToken();

    } else
        return (getTokenPayload($key,$_COOKIE['jwt']));

}

function updateRefreshToken(){
    $stmt = pdo()->prepare("SELECT * FROM `refresh_token` WHERE `refresh_token` = ?");
    $stmt->execute([$_COOKIE['rjwt']]);
    $token_info = $stmt->fetchObject();
    if (!isset($token_info)){
        setcookie('rjwt','', 1);
        return false;
    }
    if ($token_info->expire<time()){
        $stmt = pdo()->prepare("DELETE FROM `refresh_token` WHERE `refresh_token` =?");
        $stmt->execute([$_COOKIE['rjwt']]);
        setcookie('rjwt', '', 1);
        return false;
        }
        $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
        $stmt->execute(['id' => $token_info->user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $user['iat'] = time();
        $key = 1234567;
        $token = createTokenHS256($user,$key);
        setcookie('jwt', $token, time()+3600);
        $refreshToken = createRefreshToken();
        setcookie('rjwt', $refreshToken, time()+10800);
        $stmt = pdo()->prepare("UPDATE refresh_token SET refresh_token=?, expire=?, created_at=? WHERE refresh_token=?");
        $stmt->execute([$refreshToken,time()+10800, time(), $token_info->refresh_token]);
        return  (object)$user;
}