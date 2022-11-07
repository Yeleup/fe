<?php
class ModelFeMarketSearch extends Model {

    private $url;
    private $client;
    private $user;
    private $password;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $config = new Config();
        $config->load('fe_config');

        $this->url = $config->get('fe_sm_search_url');
        $this->client = new GuzzleHttp\Client();
        $this->user = $config->get('fe_sm_search_user');
        $this->password = $config->get('fe_sm_search_password');
    }

    public function search($value) {
        $result = $this->client->get($this->url . "/$value", [
            'auth' => [$this->user, $this->password]
        ]);
        return $result->json()['result'];
    }

}
