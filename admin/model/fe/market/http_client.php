<?php
class ModelFeMarketHttpClient extends Model {

    private $client;
    private $url;
    private $login;
    private $password;

    public function __construct($registry) {
        parent::__construct($registry);
        $config = new Config();
        $config->load('fe_config');
        $this->client = new GuzzleHttp\Client();
        $this->url = $config->get('fe_sm_url');
        $this->login = $config->get('fe_sm_login');
        $this->password = $config->get('fe_sm_password');
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

}
