<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 12:03 PM
 */

class MenuItemProfile implements \JsonSerializable{
    private $itemProfileId ;
    private $itemProfileCode;
    private $itemProfileDescription;

    function __construct($data) {
        if (isset($data['itemProfileId']))
            $this->itemProfileId = $data['itemProfileId'];
        $this->itemProfileCode = $data['itemProfileCode'];
        $this->itemProfileDescription = $data['itemProfileDescription'];
    }

    public static function createNewTestMenuItemProfile(){
        $data = '{
                    "itemProfileCode":"Protein and Spicy","itemProfileDescription":"Protein and Spicy"
                 }';

        $data = json_decode($data,true);
        return new MenuItemProfile($data);
    }

    public function getData() {
        $data =
            ['itemProfileId' => $this->itemProfileId,
             'itemProfileCode' => $this->itemProfileCode,
             'itemProfileDescription' => $this->itemProfileDescription
            ];

        return $data;
    }

    public function jsonSerialize(){
        return $this->getData();
    }

    function copyFrom($source) {
        $this->itemProfileId = $source->itemProfileId;
        $this->itemProfileCode = $source->itemProfileCode;
        $this->itemProfileDescription = $source->itemProfileDescription;
    }

   public function getItemProfileId(){
        return $this->itemProfileId;
    }

    public function getItemProfileCode(){
        return $this->itemProfileCode;
    }

    public function getItemProfileDescription(){
        return $this->itemProfileDescription;
    }
}