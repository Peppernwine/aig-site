<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/30/2018
 * Time: 11:07 AM
 */

require_once RESOURCE_PATH . "/LunchDinnerMenuDAOStub.php";
require_once RESOURCE_PATH . "/BarMenuDAOStub.php";

class MenuItemDAOStub
{
    public function getAllMenuItemsByType($dbConnection,$menuType)
    {
        if (!isset($menuType) || (!in_array($menuType,[-1,-2])))
            $menuType = -1;

        $menuItemDA = null;
        switch ($menuType) {
            case -1:
                $menuItemDA = new LunchDinnerMenuDAOStub();
                break;
            case -2:
                $menuItemDA = new BarMenuDAOStub();
                break;
        };

        $cats = $menuItemDA->getAll();

        return $cats;

    }
}