<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 12:03 PM
 */

class MenuCategory implements \JsonSerializable{
    private $categoryId ;
    private $menuTypeId ;
    private $categoryCode;
    private $categoryDescription;

    function __construct($data) {
        if (array_key_exists('categoryId',$data))
            $this->categoryId = $data['categoryId'];
        $this->menuTypeId = $data['menuTypeId'];
        $this->categoryCode = $data['categoryCode'];
        $this->categoryDescription = $data['categoryDescription'];
    }

    public static function createNewTestMenuCategory(){
        $data = '{
                    "menuTypeId":1,"categoryCode":"Appetizer","categoryDescription":"Includes all Appetizers"
                 }';

        $data = json_decode($data,true);
        return new MenuCategory($data);
    }

    public function getData() {
        $data =
            ['categoryId' => $this->categoryId,
             'menuTypeId' => $this->menuTypeId,
             'categoryCode' => $this->categoryCode,
             'categoryDescription' => $this->categoryDescription
            ];

        return $data;
    }

    public function jsonSerialize(){
        return $this->getData();
    }

    function copyFrom($source) {
        $this->categoryId = $source->categoryId;
        $this->menuTypeId = $source->menuTypeId;
        $this->categoryCode = $source->categoryCode;
        $this->categoryDescription = $source->categoryDescription;
    }

   public function getCategoryId(){
        return $this->categoryId;
    }

    public function getMenuTypeId(){
        return $this->menuTypeId;
    }

    public function getCategoryCode(){
        return $this->categoryCode;
    }

    public function getCategoryDescription(){
        return $this->categoryDescription;
    }
}