<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 12:03 PM
 */

class ReserveOccasion implements \JsonSerializable{
    private $occasionId ;
    private $occasionCode;
    private $occasionDescription;

    function __construct($data) {
        if (array_key_exists('occasionId',$data))
            $this->occasionId = $data['occasionId'];

        $this->occasionCode = $data['occasionCode'];
        $this->occasionDescription = $data['occasionDescription'];
    }

    public static function createNewTestReserveOccasion(){
        $data = '{
                    "occasionCode":"Birthday party","occasionDescription":"Birthday party"
                 }';

        $data = json_decode($data,true);
        return new ReserveOccasion($data);
    }

    public function getData() {
        $data =
            ['occasionId' => $this->occasionId,
             'occasionCode' => $this->occasionCode,
             'occasionDescription' => $this->occasionDescription
            ];

        return $data;
    }

    public function jsonSerialize(){
        return $this->getData();
    }

    function copyFrom($source) {
        $this->occasionId = $source->occasionId;
        $this->occasionCode = $source->occasionCode;
        $this->occasionDescription = $source->occasionDescription;
    }

   public function getOccasionId(){
        return $this->occasionId;
    }

    public function getOccasionCode(){
        return $this->occasionCode;
    }

    public function getOccasionDescription(){
        return $this->occasionDescription;
    }
}