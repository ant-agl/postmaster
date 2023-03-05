<?php

class MailRuAPI {
    private $accessToken;
    private $baseUrl = 'https://postmaster.mail.ru';

    public function __construct($accessToken) {
        $this->accessToken = $accessToken;
    }
    public function Request($url, $data = array()) {
        $headers = array(
            'Bearer: ' . $this->accessToken,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $url .'?'.http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function regList() {
        $url = '/ext-api/reg-list/';
        return $this->Request($url,[]);
    }

    public function troublesList() {
        $url = '/ext-api/troubles-list/';
        return $this->Request($url,[]);
    }

    public function statList($date_from, $date_to = null, $domen = null, $msgtype = null) {
        $url = '/ext-api/stat-list/';
        $data = [
            'date_from' => $date_from,
            'date_to' => $date_to,
            'domain' => $domen,
            'msgtype' => $msgtype];
        return $this->Request($url,$data);
    }

    public function statListDetailed($date_from, $date_to = null, $domen = null, $msgtype = null) {
        $url = '/ext-api/stat-list-detailed/';
        $data = [
            'date_from' => $date_from,
            'date_to' => $date_to,
            'domain' => $domen,
            'msgtype' => $msgtype];
        return $this->Request($url,$data);
    }
}
