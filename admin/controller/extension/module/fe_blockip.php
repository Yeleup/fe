<?php

class ControllerExtensionModuleFeBlockip extends Controller {

    protected $filename = 'fe_blockip.txt';

    public function index() {
        $filepath = DIR_CONFIG . $this->filename;
        $user_token = $this->session->data['user_token'];

        $file_content = $this->request->post['file_content'] ?? null;
        if ($file_content !== null) {
            file_put_contents($filepath, $file_content);
            $this->writeRegexIps($file_content);
        }

        if (file_exists($filepath)) {
            $file_content = file_get_contents($filepath);
        }

        if (file_exists($filepath . '.regex')) {
            $file_content_regex = file_get_contents($filepath . '.regex');
        }

        $data['file_content'] = $file_content ?? '';
        $data['file_content_regex'] = $file_content_regex ?? '';
        $data['heading_title'] = 'FE BlockIP';
        $data['action'] = $this->url->link('extension/module/fe_blockip', '', true) . "&user_token={$user_token}";

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/fe_blockip', $data));
    }

    private function writeRegexIps($text_ips) {
        $filepath = DIR_CONFIG . $this->filename;
        $ips = preg_split('/[\s]+/', $text_ips);
        $ips_regex = [];
        foreach ($ips as $ip) {
            if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\/\d{1,2}$/', $ip)) { // Regex for ips. Example: 192.168.1.123/24
                $ip_parts = preg_split('/\//', $ip);
                $ip = $ip_parts[0];
                $mask = floor((int)$ip_parts[1] / 8);

                $ip_replace = [];
                for ($i = 1; $i <= 4; $i++) {
                    if ($i <= $mask) {
                        $ip_replace[] = '${' . $i . '}';
                    } else {
                        $ip_replace[] = '\d+';
                    }
                }
                $ip_replace = implode('\.', $ip_replace);
                $ip_regex = preg_replace('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/', $ip_replace, $ip);
                $ip_regex = '/^' . $ip_regex . '/';
                $ips_regex[] = $ip_regex;
            }
        }
        $ips_regex = implode("\n", $ips_regex) . "\n";
        file_put_contents($filepath . '.regex', $ips_regex);
    }

    public function install() {
        file_put_contents(DIR_CONFIG . $this->filename, '', FILE_APPEND);
    }

    public function uninstall() {
    }

}
