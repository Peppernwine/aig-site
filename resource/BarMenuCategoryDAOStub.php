<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/30/2018
 * Time: 11:07 AM
 */

class BarMenuCategoryDAOStub
{

    public function getAll() {

        $data[] = ['categoryId' => "5" , 'categoryCode' => 'Red Wine'];
        $data[] = ['categoryId' => "10" , 'categoryCode' => 'White Wine'];
        $data[] = ['categoryId' => "15" , 'categoryCode' => 'Cocktails'];
        $data[] = ['categoryId' => "20" , 'categoryCode' => 'Beer'];
        $data[] = ['categoryId' => "25" , 'categoryCode' => 'Single Malt Scotch'];
        $data[] = ['categoryId' => "30" , 'categoryCode' => 'Blended Whiskey'];
        $data[] = ['categoryId' => "35" , 'categoryCode' => 'Bourbon'];
        $data[] = ['categoryId' => "40" , 'categoryCode' => 'Gin'];
        $data[] = ['categoryId' => "45" , 'categoryCode' => 'Tequila'];
        $data[] = ['categoryId' => "50" , 'categoryCode' => 'Rum'];
        $data[] = ['categoryId' => "55" , 'categoryCode' => 'Vodka'];

        return $data;
    }
}