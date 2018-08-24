<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 5/30/2018
 * Time: 11:07 AM
 */

require_once RESOURCE_PATH . "/LunchDinnerMenuDAOStub.php";
require_once RESOURCE_PATH . "/BarMenuDAOStub.php";

class MenuDAOStub
{
    private $menuTypeId;

    public function __construct($dbConnection,$menuTypeId) {
        if (!isset($menuTypeId) || (!in_array($menuTypeId,[-1,-2])))
            $menuTypeId = -1;

        $this->menuTypeId  = $menuTypeId;
    }

    public function get()
    {
        $menuDAOStub = null;
        switch ($this->menuTypeId) {
            case -1:
                $menuDAOStub = new LunchDinnerMenuDAOStub();
                break;
            case -2:
                $menuDAOStub = new BarMenuDAOStub();
                break;
        };

        $cats = $menuDAOStub->getAll();

        return $cats;
    }
}