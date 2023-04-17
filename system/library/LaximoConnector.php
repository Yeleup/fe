<?php
class LaximoConnector {

    protected $config;
    protected $login;
    protected $password;
    protected $soap;

    public function __construct() {
        $config = new Config();
        $this->config = $config;

        $config->load('fe_config');

        $this->login = $config->get('fe_laximo_login');
        $this->password = $config->get('fe_laximo_password');
    }

    public function query($request) {
        $hmac = md5($request . $this->password);

        $result = $this->soap->QueryDataLogin([
            'request' => $request,
            'login' => $this->login,
            'hmac' => $hmac
        ]);

        return simplexml_load_string($result->return);
        return null;
    }

}

class LaximoConnectorOem extends LaximoConnector {
    public function __construct() {
        parent::__construct();
        $this->soap = new SoapClient($this->config->get('fe_laximo_url_oem'));
    }
}

class LaximoConnectorAm extends LaximoConnector {
    public function __construct() {
        parent::__construct();
        $this->soap = new SoapClient($this->config->get('fe_laximo_url_am'));
    }
}
