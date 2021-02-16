<?php

namespace App\Controllers;

use App\Models\City;
use App\Models\Region;
use App\Models\Street;
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

    protected function actionFindCity()
    {
        if (Request::isPost()) {
            $cities = City::getListBySearchString(Request::post('city'), 20, true);
            $html = '';

            if (!empty($cities) && is_array($cities)) {
                foreach ($cities as $city) {
                    $html .= '
                        <li>
                            <a href="#" data-id="' . $city->id . '">' . $city->region . ', ' . $city->name . ' ' . $city->shortname . '</a>
                        </li>
                    ';
                }

                echo json_encode(['result' => true, 'cities' => $html]);
                die;
            }

            echo json_encode(['result' => false, 'message' => 'Не найдено']);
            die;
        }
    }

    protected function actionFindStreet()
    {
        if (Request::isPost()) {
            $streets = Street::getListBySearchStringAndCityId(Request::post('city_id'), Request::post('street'), 20, true);
            $html = '';

            if (!empty($streets) && is_array($streets)) {
                foreach ($streets as $street) {
                    $html .= '
                        <li>
                            <a href="#" data-id="' . $street->id . '">' . $street->name . ' ' . $street->shortname . '</a>
                        </li>
                    ';
                }

                echo json_encode(['result' => true, 'streets' => $html]);
                die;
            }

            echo json_encode(['result' => false, 'message' => 'Не найдено']);
            die;
        }
    }
}
