<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/30/2018
 * Time: 11:07 AM
 */

class BarMenuDAOStub
{
    public function getAll() {

        $categories[] =['categoryId' => 5,'categoryCode' => 'Red Wine'];
        $categories[] =['categoryId' => 10,'categoryCode' => 'White Wine'];
        $categories[] =['categoryId' => 15,'categoryCode' => 'Cocktails'];
        $categories[] =['categoryId' => 20,'categoryCode' => 'Beer'];
        $categories[] =['categoryId' => 25,'categoryCode' => 'Single Malt Scotch'];
        $categories[] =['categoryId' => 30,'categoryCode' => 'Blended Whiskey'];
        $categories[] =['categoryId' => 35,'categoryCode' => 'Bourbon'];
        $categories[] =['categoryId' => 40,'categoryCode' => 'Gin'];
        $categories[] =['categoryId' => 45,'categoryCode' => 'Tequila'];
        $categories[] =['categoryId' => 50,'categoryCode' => 'Rum'];
        $categories[] =['categoryId' => 55,'categoryCode' => 'Vodka'];


        $data[] = ['categoryId' => 5,'itemId' => 100 ,
            'itemCode' => 'White Zinfandel','itemDescription' => 'Canyon Road-California',
            'basePrice' => 6];


        $data[] = ['categoryId' => 10,'itemId' => 205 , 
            'itemCode' => 'Riesling Wente','itemDescription' => 'Monterey California',
            'basePrice' => 10];

        $data[] = ['categoryId' => 15,'itemId' => 205 ,
            'itemCode' => 'Bhangra Beach','itemDescription' => 'Mango Lassi, Malibu rum & splash of lime',
            'basePrice' => 10];

        $data[] = ['categoryId' => 15,'itemId' => 205 ,
            'itemCode' => 'Elegant Spice','itemDescription' => 'Bacardi Superior, triple sec, Grapefruit juice, splash of dry vermouth, cardamom bitters',
            'basePrice' => 10];

        $data[] = ['categoryId' => 20,'itemId' => 400 ,
            'itemCode' => 'Tajmahal(22 Oz)','itemDescription' => 'Indian Beer',
            'basePrice' => 10];

        $data[] = ['categoryId' => 20,'itemId' => 405 ,
            'itemCode' => 'Kingfisher','itemDescription' => 'Indian Beer',
            'basePrice' => 10];

        $hours = ['Happy Hour: Tue - Fri : 4:30pm - 6:30pm'];

        $menu = ['typeId' => -2, 'isAvailableOnline' => 0, 'typeCode'=>'Bar Menu','categories'=>$categories,'hourDescriptions'=>$hours,'items' =>$data,'options' => []];

        return $menu;
    }
}