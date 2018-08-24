<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 12:03 PM
 */

class MenuItem implements \JsonSerializable
{
    private $itemId;
    private $menuTypeId;
    private $menuCategoryId;
    private $itemCode;
    private $itemDescription;
    private $basePrice;
    private $isChefsSpecial;
    private $isGlutenFree;
    private $isNutFree;
    private $menuItemProfileId;

    function __construct($data) {
        if (isset($data['itemId']))
            $this->itemId = intval($data['itemId']);

        $this->menuTypeId = intval($data['menuTypeId']);
        $this->menuCategoryId = intval($data['menuCategoryId']);
        $this->itemCode = $data['itemCode'];
        $this->itemDescription = $data['itemDescription'];
        $this->basePrice = floatval($data['basePrice']);
        $this->isChefsSpecial = intval($data['isChefsSpecial']);
        $this->isGlutenFree = intval($data['isGlutenFree']);
        $this->isNutFree = intval($data['isNutFree']);

        if (isset($data['menuItemProfileId']))
            $this->menuItemProfileId = intval($data['menuItemProfileId']);
    }

    public static function createNewTestMenuItem(){
        $data = '{
                    "menuTypeId":1,
                    "menuCategoryId":1,
                    "itemCode":"Tikka Masala",
                    "itemDescription":"Tikka Masala",
                    "basePrice":16.00,
                    "isChefsSpecial":1,
                    "isGlutenFree":0,
                    "isNutFree":0,
                    "menuItemProfileId":1
                 }';

        $data = json_decode($data, true);
        return new MenuItem($data);
    }

    public function getData() {
        $data =
            [
                'itemId' => $this->itemId,
                'menuTypeId' => $this->menuTypeId,
                'menuCategoryId' => $this->menuCategoryId,
                'itemCode' => $this->itemCode,
                'itemDescription' => $this->itemDescription,
                'basePrice' => $this->basePrice,
                'isChefsSpecial' => $this->isChefsSpecial,
                'isGlutenFree' => $this->isGlutenFree,
                'isNutFree' => $this->isNutFree,
                'menuItemProfileId' => $this->menuItemProfileId
            ];
        return $data;
    }

    public function jsonSerialize() {
        return $this->getData();
    }

    function copyFrom($source) {
        $this->itemId = $source->itemId;
        $this->menuTypeId = $source->menuTypeId;
        $this->menuCategoryId = $source->menuCategoryId;
        $this->itemCode = $source->itemCode;
        $this->itemDescription = $source->itemDescription;
        $this->basePrice = $source->basePrice;
        $this->isChefsSpecial = $source->isChefsSpecial;
        $this->isGlutenFree = $source->isGlutenFree;
        $this->isNutFree = $source->isNutFree;
        $this->menuItemProfileId = $source->menuItemProfileId;
    }

    public function getItemId() {
        return $this->itemId;
    }

    public function getMenuTypeId() {
        return $this->menuTypeId;
    }

    public function getItemCode() {
        return $this->itemCode;
    }

    public function getItemDescription() {
        return $this->itemDescription;
    }

    public function getMenuCategoryId() {
        return $this->menuCategoryId;
    }

    public function getBasePrice(){
        return $this->basePrice;
    }

    public function getMenuItemProfileId(){
        return $this->menuItemProfileId;
    }

    public function getIsChefsSpecial(){
        return $this->isChefsSpecial;
    }

    public function getIsGlutenFree() {
        return $this->isGlutenFree;
    }

    public function getIsNutFree() {
        return $this->isNutFree;
    }
}