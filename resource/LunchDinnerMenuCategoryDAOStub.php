<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/30/2018
 * Time: 11:07 AM
 */

class LunchDinnerMenuCategoryDAOStub
{

    public function getAll() {

        $data[] = ['categoryId' => "5" , 'categoryCode' => 'Appetizers'];
        $data[] = ['categoryId' => "10" , 'categoryCode' => 'Wings'];
        $data[] = ['categoryId' => "15" , 'categoryCode' => 'Soups'];
        $data[] = ['categoryId' => "20" , 'categoryCode' => 'Salads'];
        $data[] = ['categoryId' => "25" , 'categoryCode' => 'Chaat'];
        $data[] = ['categoryId' => "30" , 'categoryCode' => 'Small Plates'];
        $data[] = ['categoryId' => "35" , 'categoryCode' => 'Tawa & Grill'];
        $data[] = ['categoryId' => "40" , 'categoryCode' => 'Curry Fare'];
        $data[] = ['categoryId' => "45" , 'categoryCode' => 'Vegetarian Entree'];
        $data[] = ['categoryId' => "50" , 'categoryCode' => 'Chicken Entree'];
        $data[] = ['categoryId' => "55" , 'categoryCode' => 'Lamb Entree'];
        $data[] = ['categoryId' => "60" , 'categoryCode' => 'Mutton Entree'];
        $data[] = ['categoryId' => "65" , 'categoryCode' => 'Seafood Entree'];
        $data[] = ['categoryId' => "70" , 'categoryCode' => 'Rice & Noodles'];
        $data[] = ['categoryId' => "75" , 'categoryCode' => 'Bread'];
        $data[] = ['categoryId' => "80" , 'categoryCode' => 'Dessert'];
        $data[] = ['categoryId' => "85" , 'categoryCode' => 'Drinks'];

        return $data;
    }
}