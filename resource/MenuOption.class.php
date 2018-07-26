<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/30/2018
 * Time: 4:56 PM
 */

class MenuOption implements \JsonSerializable{
    private $optionId;
    private $optionCode;
    private $optionDescription;
    private $optionLabel;
    //private $menuTypeId;
    private $sortOrder;
    private $isRequired;
    private $hasExtraCost;
    private $displayAllOptions;
    private $optionChoices;

    public function getOptionId () {
        return $this->optionId;
    }

    public function getOptionCode () {
        return $this->optionCode;
    }

    public function getOptionDescription (){
        return $this->optionDescription ;
    }

    public function getOptionLabel (){
        return $this->optionLabel ;
    }

    /*
    public function getMenuTypeId (){
        return $this->menuTypeId ;
    }
*/

    public function getSortOrder  (){
        return $this->sortOrder ;
    }

    public function getIsRequired (){
        return $this->isRequired ;
    }

    public function getHasExtraCost (){
        return $this->hasExtraCost ;
    }

    public function getDisplayAllOptions(){
        return $this->displayAllOptions ;
    }

    public function getOptionChoices(){
        return $this->optionChoices ;
    }

    public function setOptionChoices($optionChoices) {
        $this->optionChoices = [];
        foreach ($optionChoices as $optionChoice) {
            $this->optionChoices[] = $optionChoice;
        }
    }

    function __construct($data){
        if (array_key_exists('optionId',$data))
            $this->optionId = intval($data['optionId']);
        $this->optionCode = $data['optionCode'];
        $this->optionDescription = $data['optionDescription'];
        $this->optionLabel = $data['optionLabel'];
        //$this->menuTypeId = $data['menuTypeId'];
        $this->sortOrder = intval($data['sortOrder']);
        $this->isRequired = intval($data['isRequired']);
        $this->hasExtraCost = intval($data['hasExtraCost']);
        $this->displayAllOptions = intval($data['displayAllOptions']);


        $optionChoices = [];
        foreach($data['optionChoices'] as $optionChoice) {
            $optionChoice['optionChoiceId'] = intval($optionChoice['optionChoiceId']);
            $optionChoice['optionChoiceValue'] = intval($optionChoice['optionChoiceValue']);
            $optionChoice['priceDelta'] = floatval($optionChoice['priceDelta']);
            $optionChoices[] = $optionChoice;
        }
        $this->setOptionChoices($optionChoices);


    }

    public static function createNewTestMenuOption()
    {
        //"menuTypeId" :1,
        $testData = '{
                     "optionCode": "Spice Level","optionDescription" : "Spice Level",
                     "optionLabel" : "Pick your spice level",
                     "sortOrder" : 1,"isRequired" : 1,"hasExtraCost" : 0,
                     "displayAllOptions" : 0,
                     "optionChoices" : 
                                                [
                                                {"optionChoiceId":1,"optionChoiceValue":1,"optionChoiceCode":"Mild","optionChoiceDescription":"Mild spice","priceDelta":0},
                                                {"optionChoiceId":1,"optionChoiceValue":2,"optionChoiceCode":"Hot","optionChoiceDescription":"Hot spice","priceDelta":0}
                                                ]
                    
                      }';
        $testData = json_decode($testData,true);
        $newMenuOption = new MenuOption($testData);
        return $newMenuOption;
    }

    function copyFrom($source) {
        $this->optionId = $source->optionId;
        $this->optionCode = $source->optionCode;
        $this->optionDescription = $source->optionDescription;
        $this->optionLabel = $source->optionLabel;
        //$this->menuTypeId = $source->menuTypeId;
        $this->sortOrder = $source->sortOrder;
        $this->isRequired = $source->isRequired;
        $this->hasExtraCost = $source->hasExtraCost;
        $this->displayAllOptions = $source->displayAllOptions;
        $this->setOptionChoices($source->optionChoices);
    }

    public function getData() {
        $data =
            ['optionId' => $this->optionId,
            'optionCode' => $this->optionCode,
            'optionDescription' => $this->optionDescription,
            'optionLabel' => $this->optionLabel,
            //'menuTypeId' => $this->menuTypeId,
            'sortOrder' => $this->sortOrder,
            'isRequired'=> $this->isRequired ,
            'hasExtraCost' => $this->hasExtraCost,
            'displayAllOptions' => $this->displayAllOptions,
            'optionChoices' => $this->optionChoices
            ];

        return $data;
    }

    public function jsonSerialize(){
        return $this->getData();
    }

}