<?php
class MarketGuzzleFacade {
    protected $login;
    protected $login_test;
    protected $password;
    protected $password_test;
    protected $url;
    protected $url_test;
    protected $client;

    public function __construct() {
        $config = new Config();
        $config->load('fe_config');

        $this->client = new GuzzleHttp\Client();
        $this->login = $config->get('fe_sm_login');
        $this->login_test = $config->get('fe_sm_login_test');
        $this->password = $config->get('fe_sm_password');
        $this->password_test = $config->get('fe_sm_password_test');
        $this->url = $config->get('fe_sm_url');
        $this->url_test = $config->get('fe_sm_url_test');
    }

    public function get($path) {
        try {
            $result = $this->client->get($this->url . $path, [
                'auth' => [$this->login, $this->password]
            ]);
            $result = strval($result->getBody());
            $result = substr($result, 3);
            return json_decode($result);
        } catch (\Exception $e) {
            return [];
        }

    }

    public function post($path, $body) {
        try {
            $result = $this->client->post($this->url . $path, [
                'json' => $body,
                'auth' => [$this->login, $this->password]
            ]);

            $result = strval($result->getBody());
            $result = substr($result, 3);
            return json_decode($result);
        } catch (\Exception $e) {
            return [];
        }

    }
}
