<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 12:03 PM
 */

class MenuType implements \JsonSerializable{
    private $typeId ;
    private $typeCode;
    private $typeDescription;
    private $isAvailableOnline;
    private $hourDescriptions;

    function __construct($data) {
        $this->typeId = intval($data['typeId']);
        $this->typeCode = $data['typeCode'];
        $this->typeDescription = $data['typeDescription'];
        $this->isAvailableOnline = intval($data['isAvailableOnline']);
        if (array_key_exists('hourDescriptions',$data))
            $this->setHourDescriptions($data['hourDescriptions']);
    }

    public static function createNewTestMenuType($id){
        $data = '{
                    "typeId":' . $id . ',"typeCode":"Lunch & Dinner-'.$id.'","typeDescription":"Lunch & Dinner",
                    "isAvailableOnline": 1,
                    "hourDescriptions" : ["Lunch Hours : Tue - Fri 11:30am - 2:30pm","Dinner Hours : Tue - Fri 4:30pm - 9:30pm"]
                 }';

        $data = json_decode($data,true);
        return new MenuType($data);
    }

    public function getData() {
        $data =
            ['typeId' => $this->typeId,
             'typeCode' => $this->typeCode,
             'typeDescription' => $this->typeDescription,
             'isAvailableOnline' => $this->isAvailableOnline,
             'hourDescriptions' => $this->hourDescriptions
            ];

        return $data;
    }

    public function jsonSerialize(){
        return $this->getData();
    }

    function copyFrom($source) {
        $this->typeId = $source->typeId;
        $this->typeCode = $source->typeCode;
        $this->typeDescription = $source->typeDescription;
        $this->isAvailableOnline = $source->isAvailableOnline;
        $this->setHourDescriptions($source->hourDescriptions);
    }

   public function getTypeId(){
        return $this->typeId;
    }

    public function getTypeCode(){
        return $this->typeCode;
    }

    public function getTypeDescription(){
        return $this->typeDescription;
    }

    public function getIsAvailableOnline(){
        return $this->isAvailableOnline;
    }

    public function getHourDescriptions(){
        return $this->hourDescriptions;
    }

    public function setHourDescriptions($hourDescriptions) {
        $this->hourDescriptions = [];
        foreach ($hourDescriptions as $hourDescription) {
            $this->hourDescriptions[] = $hourDescription;
        }
    }
}