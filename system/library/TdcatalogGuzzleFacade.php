<?php
class TdcatalogGuzzleFacade {
    protected $url;
    protected $client;

    public function __construct() {
        $config = new Config();
        $config->load('fe_config');

        $this->client = new GuzzleHttp\Client();
        $this->url = $config->get('fe_td_url');
    }

    public function get($path) {
        try {
            $result = $this->client->get($this->url . $path);
            $result = strval($result->getBody());
        } catch (GuzzleHttp\Exception\ServerException $e) {
            $result = null;
        }
        return $result;
    }
}
