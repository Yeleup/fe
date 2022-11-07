<?php

class Laximo {

    function __construct($registry) {
        $this->config = $registry->get('config');
        $this->url = $registry->get('url');
        $this->url_oem = $this->config->get('fe_laximo_url_oem');
        $this->url_am = $this->config->get('fe_laximo_url_am');
        $this->login = $this->config->get('fe_laximo_login');
        $this->password = $this->config->get('fe_laximo_password');
        $this->oem = new SoapClient($this->url_oem);
        $this->am = new SoapClient($this->url_am);
        $this->lang = 'ru_RU';
        $this->url_api = 'extension/fe/laximo/api/laximo/';
    }


    private function oem($request) {
        $hmac = md5($request . $this->password);
        $result = $this->oem->QueryDataLogin([
            'request' => $request,
            'login' => $this->login,
            'hmac' => $hmac
        ]);
        return json_decode(json_encode(simplexml_load_string($result->return)), true);
    }

    private function am($request) {
        $hmac = md5($request . $this->password);
        $result = $this->am->QueryDataLogin([
            'request' => $request,
            'login' => $this->login,
            'hmac' => $hmac
        ]);
        return json_decode(json_encode(simplexml_load_string($result->return)), true);
    }

    private function toArr($val) {
        if (!($val[0] ?? false)) {
            $val = [$val];
        }
        return $val;
    }

    public function listCatalogs($ssd = '') {
        $result = $this->oem("ListCatalogs:Locale={$this->lang}|ssd={$ssd}");
        $result = $result['ListCatalogs'];
        foreach ($result['row'] as $k => $v) {
            $v['_links']['GetWizard2'] = $this->url->link($this->url_api . 'getWizard2', '', true) . "&catalog={$v['@attributes']['code']}&ssd={$ssd}";
            $result['row'][$k] = $v;
        }
        return $result;
    }

    public function findVehicleByVin($catalog, $vin, $localized = true) {
        $localized = $localized ? 'true' : 'false';
        $result = $this->oem("FindVehicleByVIN:Locale={$this->lang}|Catalog={$catalog}|VIN={$vin}|Localized={$localized}");
        $result = $result['FindVehicleByVIN'];

        if (!isset($result['row'])) {
            $result['row'] = [];
        }

        if ($result['row'] && !isset($result['row']['0'])) {
            $result = ['row' => [
                '0' => $result['row']
            ]];
        }

        foreach ($result['row'] as $k => $v) {
            $ssd = $v['@attributes']['ssd'];
            $vehicle_id = $v['@attributes']['vehicleid'];
            $catalog = $v['@attributes']['catalog'];

            $v['_links']['ListCategories'] = $this->url->link($this->url_api . 'ListCategories', '', true) . "&catalog={$catalog}&category_id=-1&vehicle_id={$vehicle_id}&ssd={$ssd}";
            $v['_links']['ListQuickGroup'] = $this->url->link($this->url_api . 'ListQuickGroup', '', true) . "&catalog={$catalog}&category_id=-1&vehicle_id={$vehicle_id}&ssd={$ssd}";

            $result['row'][$k] = $v;
        }

        return $result;
    }

    public function getWizard2($catalog, $ssd) {
        $result = $this->oem("GetWizard2:Locale={$this->lang}|Catalog={$catalog}|ssd={$ssd}");
        $result = $result['GetWizard2'];


        $r = $result['row'];
        $r = $this->toArr($r);
        $t = [];
        foreach ($r as $k => $v) {
            if ($v['options']['row'] ?? false) {
                $r1 = $v['options']['row'];
                $v['options']['row'] = $this->toArr($v['options']['row']);
                foreach ($v['options']['row'] as $k1 => $v1) {
                    $ssd_key = $v1['@attributes']['key'] ?? '';
                    if ($ssd_key) {
                        $v1['_links']['GetWizard2'] = $this->url->link($this->url_api . 'getWizard2', '', true) . "&catalog={$catalog}&ssd={$ssd_key}";
                        $v1['_links']['FindVehicleByWizard2'] = $this->url->link($this->url_api . 'FindVehicleByWizard2', '', true) . "&catalog={$catalog}&ssd={$ssd_key}";
                    }
                    $v['options']['row'][$k1] = $v1;
                }
                $t[] = $v;
            }
        }
        $result['row'] = $t;
        return $result;
    }

    public function FindVehicleByWizard2($catalog, $ssd = '', $localized = true) {
        $localized = $localized ? 'true' : 'false';
        $result = $this->oem("FindVehicleByWizard2:Locale={$this->lang}|Catalog={$catalog}|ssd={$ssd}|Localized={$localized}");
        $result = $result['FindVehicleByWizard2'];
        foreach ($result['row'] as $k => $v) {
            $v['_links']['ListCategories'] = $this->url->link($this->url_api . 'ListCategories', '', true) . "&catalog={$catalog}&vehicle_id={$v['@attributes']['vehicleid']}&category_id=-1&ssd={$v['@attributes']['ssd']}";
            $v['_links']['ListQuickGroup'] = $this->url->link($this->url_api . 'ListQuickGroup', '', true) . "&catalog={$catalog}&vehicle_id={$v['@attributes']['vehicleid']}&category_id=-1&ssd={$v['@attributes']['ssd']}";
            $result['row'][$k] = $v;
        }
        return $result;
    }

    public function ListQuickGroup($catalog, $vehicle_id, $ssd = '') {
        $addLinks = function($row) use (&$addLinks, $catalog, $vehicle_id, $ssd) {
            $hasLink = $row['@attributes']['link'] ?? 'false';

            if ($hasLink == 'true') {
                $row['_links']['ListQuickDetail'] = $this->url->link($this->url_api . 'ListQuickDetail', '', true) . "&catalog={$catalog}&vehicle_id={$vehicle_id}&quick_group_id={$row['@attributes']['quickgroupid']}&ssd={$ssd}";
            }

            if (!($row['row'] ?? false)) {
                return $row;
            }

            if (!isset($row['row'][0])) {
                $tmp = $row['row'];
                $row['row'] = [];
                $row['row'][] = $tmp;
            }

            foreach ($row['row'] as $k => $v) {
                $row['row'][$k] = $addLinks($v);
            }

            return $row;
        };

        $result = $this->oem("ListQuickGroup:Locale={$this->lang}|Catalog={$catalog}|VehicleId={$vehicle_id}|ssd={$ssd}");
        $result = ['row' => $addLinks($result['ListQuickGroups']['row'])];
        return $result;
    }

    public function ListQuickDetail($catalog, $vehicle_id, $quick_group_id, $ssd = '', $localized = true) {
        $localized = $localized ? 'true' : 'false';
        $result = $this->oem("ListQuickDetail:Locale={$this->lang}|Catalog={$catalog}|VehicleId={$vehicle_id}|QuickGroupId={$quick_group_id}|ssd={$ssd}|Localized={$localized}|All=1");

        $category = $result['ListQuickDetail']['Category'];
        if (!isset($category['0'])) {
            $result['ListQuickDetail']['Category'] = [$category];
        }

        foreach ($result['ListQuickDetail']['Category'] as $cat_idx => $category) {
            if (!isset($category['Unit']['0'])) {
                $category['Unit'] = [$category['Unit']];
            }

            foreach ($category['Unit'] as $unit_key => $unit) {
                if (!isset($unit['Detail']['0'])) {
                    $unit['Detail'] = [$unit['Detail']];
                }

                foreach ($unit['Detail'] as $detail_key => $detail) {
                    $oem = $detail['@attributes']['oem'] ?? null;
                    if ($oem) {
                        $detail['_links']['FindOem'] = $this->url->link($this->url_api . 'FindOem', '', true) . "&oem={$oem}";
                        $detail['_links']['PageListDetails'] = $this->url->link('fe/pages/list_details', '', true) . "&laximo_oem={$oem}";
                    }
                    $unit['Detail'][$detail_key] = $detail;
                }

                $category['Unit'][$unit_key] = $unit;
            }

            $result['ListQuickDetail']['Category'][$cat_idx] = $category;
        }

        return $result['ListQuickDetail'];
    }

    public function ListCategories($catalog, $vehicle_id, $category_id = '-1', $ssd = '') {
        $result = $this->oem("ListCategories:Locale={$this->lang}|Catalog={$catalog}|VehicleId={$vehicle_id}|CategoryId={$category_id}|ssd={$ssd}");
        $result = $result['ListCategories'];
        foreach ($result['row'] as $k => $v) {
            if ($v['@attributes']['childrens'] !== 'false') {
                $v['_links']['ListCategories'] = $this->url->link($this->url_api . 'ListCategories', '', true) . "&catalog={$catalog}&vehicle_id={$vehicle_id}&category_id={$v['@attributes']['categoryid']}&ssd={$v['@attributes']['ssd']}";
            }
            $v['_links']['ListUnits'] = $this->url->link($this->url_api . 'ListUnits', '', true) . "&catalog={$catalog}&vehicle_id={$vehicle_id}&category_id={$v['@attributes']['categoryid']}&ssd={$v['@attributes']['ssd']}";
            $result['row'][$k] = $v;
        }
        return $result;
    }

    public function ListUnits($catalog, $vehicle_id, $category_id = '-1', $ssd = '', $localized = true) {
        $localized = $localized ? 'true' : 'false';
        $result = $this->oem("ListUnits:Locale={$this->lang}|Catalog={$catalog}|VehicleId={$vehicle_id}|CategoryId={$category_id}|ssd={$ssd}|Localized={$localized}");
        $result = $result['ListUnits'];
        foreach ($result['row'] as $k => $v) {
            $img_url = urlencode($v['@attributes']['imageurl']);
            $v['_links']['ListDetailByUnit'] = $this->url->link($this->url_api . 'ListDetailByUnit', '', true) . "&catalog={$catalog}&unit_id={$v['@attributes']['unitid']}&ssd={$v['@attributes']['ssd']}";
            $v['_links']['ListImageMapByUnit'] = $this->url->link($this->url_api . 'ListImageMapByUnit', '', true) . "&catalog={$catalog}&unit_id={$v['@attributes']['unitid']}&ssd={$v['@attributes']['ssd']}";
            $v['_links']['DetailsView'] = $this->url->link('extension/fe/laximo/view/details', '', true) . "&catalog={$catalog}&unit_id={$v['@attributes']['unitid']}&ssd={$v['@attributes']['ssd']}&img_url={$img_url}";
            $result['row'][$k] = $v;
        }
        return $result;
    }

    public function ListImageMapByUnit($catalog, $unit_id, $ssd = '') {
        $result = $this->oem("ListImageMapByUnit:Catalog={$catalog}|UnitId={$unit_id}|ssd={$ssd}");
        $result = $result['ListImageMapByUnit'];
        return $result;
    }

    public function ListDetailByUnit($catalog, $unit_id, $ssd = '', $localized = true) {
        $localized = $localized ? 'true' : 'false';
        $result = $this->oem("ListDetailByUnit:Locale={$this->lang}|Catalog={$catalog}|UnitId={$unit_id}|ssd={$ssd}|Localized={$localized}");
        $result = $result['ListDetailsByUnit'];
        $row = $result['row'] ?? [];
        foreach ($row as $k => $v) {
            $v['_links']['FindOem'] = $this->url->link($this->url_api . 'FindOem', '', true) . "&oem={$v['@attributes']['oem']}";
            $result['row'][$k] = $v;
        }
        return ['row' => $row];
    }

    public function FindOem($oem) {
        $result = $this->am("FindOEM:Locale=en_US|OEM=0913128000|ReplacementTypes=|Options=");
        $result = $result['FindOEM'];

        foreach ($result['detail'] as $k => $v) {
            $v['_links']['FindReplacements'] = $this->url->link($this->url_api . 'FindReplacements', '', true) . "&detail_id={$v['@attributes']['detailid']}";
            $v['_links']['FindReplacementsOemOnly'] = $this->url->link($this->url_api . 'FindReplacementsOemOnly', '', true) . "&detail_id={$v['@attributes']['detailid']}";
            $result['detail'][$k] = $v;
        }

        return ['row' => $result['detail']];
    }

    public function FindReplacements($detail_id) {
        $result = $this->am("FindReplacements:Locale={$this->lang}|DetailId={$detail_id}");
        $result = $result['FindReplacements'];
        return $result;
    }

    public function FindReplacementsOemOnly($detail_id) {
        $result = $this->FindReplacements($detail_id);
        $oems = [];

        if ($result['row'] ?? false) {
            foreach ($result['row'] as $k => $v) {
                $oem = $v['@attributes']['oem'] ?? null;
                if ($oem) {
                    $oems[$oem] = 1;
                }
            }
        }

        return ['row' => array_keys($oems)];
    }

}
