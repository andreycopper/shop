<?php

namespace App\Controllers;

use App\Models\City;
use App\Models\Region;
use App\Models\User;
use App\System\Request;

class Location extends Controller
{
    protected function actionRegions()
    {
        if (Request::isPost()) {
            $regions = Region::getListByDistrictId(intval(Request::post('district')), true);
            $html = '';

            if (!empty($regions) && is_array($regions)) {
                foreach ($regions as $region) {
                    $html .= '
                        <li>
                            <a href="#" data-id="' . $region->id . '">' . $region->name . '</a>
                        </li>
                    ';
                }
            }

            echo json_encode($html);die;
        }
    }

    protected function actionCities()
    {
        if (Request::isPost()) {
            $cities = City::getListByRegionId(intval(Request::post('region')), true);
            $html = '';

            if (!empty($cities) && is_array($cities)) {
                foreach ($cities as $city) {
                    $html .= '
                        <li>
                            <a href="#" data-id="' . $city->id . '">' . $city->name . '</a>
                        </li>
                    ';
                }
            }

            echo json_encode($html);die;
        }
    }

    protected function actionSetCity()
    {
        if (Request::isPost()) {

            $city = User::setLocation(strip_tags(trim(Request::post('city'))));

            echo json_encode($city);
            die;
        }
    }
}
