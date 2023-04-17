<?php
class ControllerFeApiMarketInAuthValidator extends Controller {

    public function validate() {
        $user = $_SERVER['PHP_AUTH_USER'] ?? false;
        $password = $_SERVER['PHP_AUTH_PW'] ?? false;

        if (!$user || !$password) {
            return false;
        }

        $this->load->config('fe_config');
        $config_user = $this->config->get('fe_sm_in_user') ?? false;
        $config_password = $this->config->get('fe_sm_in_password') ?? false;

        if (!$config_user || !$config_password) {
            echo "Config not set!";
            return false;
        }

        if ($user !== $config_user) {
            return false;
        }

        if ($password === $config_password) {
            return true;
        }

        return false;
    }

}
