<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 12:03 PM
 */

class MenuTypeHour implements \JsonSerializable
{
    private $hourId;
    private $menuTypeId;
    private $hoursDescription;

    function __construct($data) {
        if (isset($data['hourId']))
            $this->hourId = $data['hourId'];

        $this->menuTypeId = $data['menuTypeId'];
        $this->hoursDescription = $data['hoursDescription'];
    }

    public static function createNewTestMenuTypeHour(){
        $data = '{
                    "menuTypeId":1,
                    "hoursDescription":"Lunch Hours : Tue - Fri 11:30am - 2:30pm"
                 }';

        $data = json_decode($data, true);
        return new MenuTypeHour($data);
    }

    public function getData() {
        $data =
            [
                'hourId' => $this->hourId,
                'menuTypeId' => $this->menuTypeId,
                'hoursDescription' => $this->hoursDescription
            ];
        return $data;
    }

    public function jsonSerialize() {
        return $this->getData();
    }

    function copyFrom($source) {
        $this->hourId = $source->hourId;
        $this->menuTypeId = $source->menuTypeId;
        $this->hoursDescription = $source->hoursDescription;
    }

    public function getHourId() {
        return $this->hourId;
    }

    public function getMenuTypeId() {
        return $this->menuTypeId;
    }

    public function getHoursDescription() {
        return $this->hoursDescription;
    }
}