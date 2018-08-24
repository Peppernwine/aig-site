<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/30/2018
 * Time: 11:07 AM
 */

class LunchDinnerMenuDAOStub
{

    public function getAll() {

        $reserveOccasions[] = ['occasionId' => 1,'occasionCode' => 'Quick Lunch/Dinner'];
        $reserveOccasions[] = ['occasionId' => 2,'occasionCode' => 'Group Lunch/Dinner'];
        $reserveOccasions[] = ['occasionId' => 3,'occasionCode' => 'Birthday Party'];
        $reserveOccasions[] = ['occasionId' => 4,'occasionCode' => 'Business Lunch/Dinner'];
        $reserveOccasions[] = ['occasionId' => 4,'occasionCode' => 'Family Lunch/Dinner'];

        $proteinChoices[] = ['optionChoiceId' => 1, 'optionChoiceCode' => 'Paneer','priceDelta' => 0.0];
        $proteinChoices[] = ['optionChoiceId' => 2, 'optionChoiceCode' => 'Mushroom','priceDelta' => 1.0];
        $proteinChoices[] = ['optionChoiceId' => 3, 'optionChoiceCode' => 'Chicken','priceDelta' => 2.0];
        $proteinChoices[] = ['optionChoiceId' => 4, 'optionChoiceCode' => 'Lamb','priceDelta' => 3.0];
        $proteinChoices[] = ['optionChoiceId' => 5, 'optionChoiceCode' => 'Shrimp','priceDelta' => 4.0];

        $spiceLevelChoices[] = ['optionChoiceId' => 1, 'optionChoiceCode' => 'Mild','priceDelta' => 0.0];
        $spiceLevelChoices[] = ['optionChoiceId' => 2, 'optionChoiceCode' => 'Medium','priceDelta' => 0.0];
        $spiceLevelChoices[] = ['optionChoiceId' => 3, 'optionChoiceCode' => 'Hot','priceDelta' => 0.0];
        $spiceLevelChoices[] = ['optionChoiceId' => 4, 'optionChoiceCode' => 'Extra Hot','priceDelta' => 0.0];
        $spiceLevelChoices[] = ['optionChoiceId' => 5, 'optionChoiceCode' => 'Insane','priceDelta' => 0.0];

        $categories[] =['categoryId' => 5,'categoryCode' => 'Appetizers'];
        $categories[] =['categoryId' => 10,'categoryCode' => 'Wings'];
        $categories[] =['categoryId' => 15,'categoryCode' => 'Soups'];
        $categories[] =['categoryId' => 20,'categoryCode' => 'Salads'];
        $categories[] =['categoryId' => 25,'categoryCode' => 'Chaat'];
        $categories[] =['categoryId' => 30,'categoryCode' => 'Small Plates'];
        $categories[] =['categoryId' => 35,'categoryCode' => 'Tawa & Grill'];
        $categories[] =['categoryId' => 40,'categoryCode' => 'Curry Fare'];
        $categories[] =['categoryId' => 45,'categoryCode' => 'Vegetarian Entree'];
        $categories[] =['categoryId' => 50,'categoryCode' => 'Chicken Entree'];
        $categories[] =['categoryId' => 55,'categoryCode' => 'Lamb Entree'];
        $categories[] =['categoryId' => 60,'categoryCode' => 'Mutton Entree'];
        $categories[] =['categoryId' => 65,'categoryCode' => 'Seafood Entree'];
        $categories[] =['categoryId' => 70,'categoryCode' => 'Rice & Noodles'];
        $categories[] =['categoryId' => 75,'categoryCode' => 'Bread'];
        $categories[] =['categoryId' => 80,'categoryCode' => 'Dessert'];
        $categories[] =['categoryId' => 85,'categoryCode' => 'Drinks'];

        $options[] = ['optionId' => 2,'optionCode' => 'Spice Level','sortOrder' => 1,  'isRequired' => 1,
                      'hasExtraCost' => 0, 'optionLabel' => 'Choose your spice level',
                      'optionChoices' => $spiceLevelChoices ,'displayAllOptions' => 0];

        $options[] = ['optionId' => 1,'optionCode' => 'Protein', 'sortOrder' => 2, 'isRequired' => 1,
                      'hasExtraCost' => 1, 'optionLabel' => 'Choose your Protein',
                      'optionChoices' => $proteinChoices,'displayAllOptions' => 0];

//$appetizerOptions[] = ['optionId' => 1, 'optionsFilterFlag' => 1,'optionChoices'=> []];
        $appetizerOptions[] = ['optionId' => 2, 'optionsFilterFlag' => 1, 'optionChoices'=> []];


//$appetizerMediumOnlyOptions[] = ['optionId' => 1, 'optionsFilterFlag' => 1, 'optionChoices'=> []];
        $appetizerMediumOnlyOptions[] = ['optionId' => 2, 'optionsFilterFlag' => 0,'optionChoices'=> [2]];

//$appetizerHotOnlyOptions[] = ['optionId' => 1, 'optionsFilterFlag' => 1, 'optionChoices'=> []];
        $appetizerHotOnlyOptions[] = ['optionId' => 2, 'optionsFilterFlag' => 0,'optionChoices'=> [3]];


        /*
        $appetizerEmptyOptions[] = ['optionId' => 1, 'optionsFilterFlag' => 1, 'optionChoices'=> []];
        $appetizerEmptyOptions[] = ['optionId' => 2, 'optionsFilterFlag' => 1, 'optionChoices'=> []];
        */

//$appetizerHotOptions[] = ['optionId' => 1, 'optionsFilterFlag' => 1, 'optionChoices'=> []];
        $appetizerHotOptions[] = ['optionId' => 2, 'optionsFilterFlag' => 0,'optionChoices'=> [2,3,4,5]];

        $appetizerEmptyOptions = [];


        $curryOptions[] = ['optionId' => 1, 'optionsFilterFlag' => 1, 'optionChoices'=> []];
        $curryOptions[] = ['optionId' => 2, 'optionsFilterFlag' => 1, 'optionChoices'=> []];

        $curryHotOptions[] = ['optionId' => 1, 'optionsFilterFlag' => 1, 'optionChoices'=> []];
        $curryHotOptions[] = ['optionId' => 2, 'optionsFilterFlag' => 0, 'optionChoices'=> [3,4,5]];


        $data[] = ['menuCategoryId' => 5,'itemId' => 100 , 'options' => $appetizerEmptyOptions,
            'itemCode' => 'Samosa','itemDescription' => 'Crispy turnovers made with mildly spiced potatoes & peas',
            'basePrice' => 6,'isChefSpecial' => '0','isNutFree' => '0','isGlutenFree' => '0'];

        $data[] = ['menuCategoryId' => 5,'itemId' => 105 , 'options' => $appetizerMediumOnlyOptions,
            'itemCode' => 'Chicken 65','itemDescription' => 'Boneless chicken marinated in spices',
            'basePrice' => 10,'isChefSpecial' => '1','isNutFree' => '1','isGlutenFree' => '1'];

        $data[] = ['menuCategoryId' => 5,'itemId' => 110 , 'options' => $appetizerOptions,
            'itemCode' => 'Chilli Chicken','itemDescription' => 'Boneless chicken marinated in spices & sweet chilly sauce',
            'basePrice' => 10,'isChefSpecial' => '0','isNutFree' => '1','isGlutenFree' => '1'];

        $data[] = ['menuCategoryId' => 5,'itemId' => 115 , 'options' => $appetizerHotOptions,
            'itemCode' => 'Gobi Kempu','itemDescription' => 'Cauliflower marinated in spices',
            'basePrice' => 10,'isChefSpecial' => '0','isNutFree' => '0','isGlutenFree' => '1'];

        $data[] = ['menuCategoryId' => 5,'itemId' => 120 , 'options' => $appetizerHotOnlyOptions,
            'itemCode' => 'Meenu Pollichadu','itemDescription' => 'Fish marinated in special masala',
            'basePrice' => 10,  'isChefSpecial' => '1','isNutFree' => '0','isGlutenFree' => '1'];

        $data[] = ['menuCategoryId' => 10,'itemId' => 200 , 'options' => $appetizerOptions,
            'itemCode' => 'Korean Wings','itemDescription' => 'Marinated with special Korean BBQ sauce',
            'basePrice' => 10, 'isChefSpecial' => '0','isNutFree' => '0','isGlutenFree' => '1'];

        $data[] = ['menuCategoryId' => 10,'itemId' => 205 , 'options' => $appetizerHotOptions,
            'itemCode' => 'Dynamite','itemDescription' => 'Hot & spicy with Indian spices & herbs grilled in clay oven',
            'basePrice' => 10,'isChefSpecial' => '1','isNutFree' => '0','isGlutenFree' => '1'];

        $data[] = ['menuCategoryId' => 40,'itemId' => 400 , 'options' => $curryOptions,'itemCode' => 'Tikka Masala',
            'itemDescription' => 'Pit oven meat simmered in a tangy tomato, onion, and cream sauce',
            'basePrice' => 10,'isChefSpecial' => '0','isNutFree' => '0','isGlutenFree' => '1'];

        $data[] = ['menuCategoryId' => 40,'itemId' => 405 , 'options' => $curryHotOptions, 'itemCode' => 'Vindaloo',
            'itemDescription' => 'Traditional spicy Goan-style curry in a fiery vinegar flavored red sauce.',
            'basePrice' => 10,'isChefSpecial' => '1','isNutFree' => '0','isGlutenFree' => '1'];

        $hours = ['Lunch Hours : Tue - Fri 11:30am - 2:30pm, Sat & Sun 11:30am - 3pm','Dinner Hours : Tue - Thu & Sun 4:30pm - 9:30pm, Fri & Sat 4:30pm - 10pm'];

        $menu = ['typeId' => -1, 'isAvailableOnline' => 1,'typeCode'=>'Lunch & Dinner Menu','categories'=>$categories,'hourDescriptions'=>$hours,'items' =>$data,'options' => $options,'reserveOccasions' => $reserveOccasions];

        return $menu;
    }
}