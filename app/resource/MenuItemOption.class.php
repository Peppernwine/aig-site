<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/30/2018
 * Time: 4:56 PM
 */

class MenuItemOption implements \JsonSerializable{
    private $menuItemId;
    private $options;

    public function getMenuItemId () {
        return $this->menuItemId;
    }

    public function getOptions(){
        return $this->options;
    }


    public function setOptions($options) {
        $this->options = [];
        foreach ($options as $option) {
            $this->options[] = $option;
        }
    }

    function __construct($data){
        $this->menuItemId = intval($data['menuItemId']);
        //var_dump($data['options'] );
        $options = [];
        foreach ($data['options'] as $optionData)
        {
            $option['optionId'] = intval($optionData['optionId']);
            $optionChoices = [];
            foreach($optionData['optionChoices'] as $optionChoiceData) {
                $optionChoices[] = intval($optionChoiceData);
            }
            $option['optionChoices'] = $optionChoices;
            $options[] = $option;

        }
        $this->setOptions($options);
    }

    public function getData() {
        $data =
            ['menuItemId' => $this->menuItemId,
             'options' => $this->options
            ];

        return $data;
    }

    public function jsonSerialize(){
        return $this->getData();
    }
}
