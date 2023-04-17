<?php
class ModelFeLocalizationLanguage extends Model {

    public function getCurrentId() {
        $code = $this->language->get('code');
        $this->load->model('localisation/language');
        $result = $this->model_localisation_language->getLanguageByCode('ru-ru');
        return $result ? $result['language_id'] : false;
    }

}
