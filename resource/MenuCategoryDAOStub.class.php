<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/12/2018
 * Time: 1:05 PM
 */

require_once RESOURCE_PATH . "/LunchDinnerMenuCategoryDAOStub.php";
require_once RESOURCE_PATH . "/BarMenuCategoryDAOStub.php";


class MenuCategoryDAOStub
{
    public function getMenuCategoriesByMenuType($dbConnection,$menuType)
    {
        if (!isset($menuType) || (!in_array($menuType,[-1,-2]))) $menuType = -1;

        $menuCatDA = null;
        switch ($menuType) {

            case -1:
                $menuCatDA = new LunchDinnerMenuCategoryDAOStub();
                break;
            case -2:
                $menuCatDA = new BarMenuCategoryDAOStub();
                break;
        };

        $cats = $menuCatDA->getAll();

        return $cats;

    }
}