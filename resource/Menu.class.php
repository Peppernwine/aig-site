<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/12/2018
 * Time: 7:16 PM
 */

class Menu implements \JsonSerializable{
    private $data;
    public function addType($type) {
        $typeValues = $type->getData();
        foreach($typeValues as $typeField => $typeValue) {
            $this->data[$typeField] = $typeValue;
        }
    }

    public function addCategories($categories) {
        $catData = [];
        foreach ($categories as $category) {
            $catData[] = $category->getData();
        }
        $this->data['categories'] = $catData;
    }

    public function addOptions($options) {
        $optsData = [];
        foreach ($options as $option) {
            $optsData[] = $option->getData();
        }
        $this->data['options'] = $optsData;
    }

    public function addItem($item,$itemOptions) {
        $itemData = $item->getData();

        $itemData['options'] = $itemOptions;
        $this->data['items'][] = $itemData;
    }

    public function addReserveOccasions($reserveOccasions) {
        $occasionsData = [];
        foreach ($reserveOccasions as $reserveOccasion) {
            $occasionsData[] = $reserveOccasion->getData();
        }
        $this->data['reserveOccasions'] = $occasionsData;
    }

    public function jsonSerialize(){
        return $this->data;
    }
}