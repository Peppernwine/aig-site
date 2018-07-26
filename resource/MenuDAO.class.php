<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/12/2018
 * Time: 7:08 PM
 */

require_once RESOURCE_PATH . "/Menu.class.php";
require_once RESOURCE_PATH . "/MenuTypeDAO.class.php";
require_once RESOURCE_PATH . "/MenuCategoryDAO.class.php";
require_once RESOURCE_PATH . "/MenuOptionDAO.class.php";
require_once RESOURCE_PATH . "/MenuItemDAO.class.php";
require_once RESOURCE_PATH . "/MenuItemOptionDAO.class.php";
require_once RESOURCE_PATH . "/ReserveOccasionDAO.class.php";

class MenuDAO
{
    private $dbConnection;
    private $menuTypeId;
    private $menuTypeDAO;
    private $menuCategoryDAO;
    private $menuOptionDAO;
    private $menuItemDAO;
    private $menuItemOptionDAO;
    private $menuItemOptions = null;
    private $reserveOccasionDAO;

    public function __construct($dbConnection,$menuTypeId,$menuTypeDAO, $menuCategoryDAO,$menuOptionDAO,
                                                          $menuItemDAO,$menuItemOptionDAO,$reserveOccasionDAO) {
        $this->dbConnection = $dbConnection;
        $this->menuTypeId  = $menuTypeId;
        $this->menuTypeDAO  = $menuTypeDAO;
        $this->menuCategoryDAO  = $menuCategoryDAO;
        $this->menuOptionDAO  = $menuOptionDAO;
        $this->menuItemDAO  = $menuItemDAO;
        $this->menuItemOptionDAO = $menuItemOptionDAO;
        $this->reserveOccasionDAO = $reserveOccasionDAO;
    }

    private function addType($menu) {
        $mt = $this->menuTypeDAO->getMenuTypeById($this->dbConnection,$this->menuTypeId);
        $menu->addType($mt);
    }

    private function addCategories($menu) {
        $cats = $this->menuCategoryDAO->getMenuCategoriesByMenuType($this->dbConnection,$this->menuTypeId);
        $menu->addCategories($cats);
    }

    private function addOptions($menu) {
        $opts = $this->menuOptionDAO->getAllMenuOptions($this->dbConnection);
        $menu->addOptions($opts);
    }

    private function getItemOption($item) {
        if (!isset($this->menuItemOptions)) {
            $this->menuItemOptions = $this->menuItemOptionDAO->getMenuItemOptionsByMenuTypeId(
                                                                    $this->dbConnection,
                                                                    $this->menuTypeId
                                                                );
        }

        $itemOpts = null;
        foreach($this->menuItemOptions as $menuItemOption) {
            if ($item->getItemId() === $menuItemOption->getMenuItemId()) {
                $itemOpts = $menuItemOption->getOptions();
            }
        }

        return $itemOpts;
    }

    private function addItems($menu) {
        $items = $this->menuItemDAO->getAllMenuItemsByType ($this->dbConnection,$this->menuTypeId);

        foreach ($items as $item) {
            $itemOpt = null;
            $itemOpts = $this->getItemOption($item);
            $menu->addItem($item,$itemOpts);
        }
    }

    private function addReserveOccasions($menu) {
        $reserveOccasions = $this->reserveOccasionDAO->getAllReserveOccasions($this->dbConnection);
        $menu->addReserveOccasions($reserveOccasions);
    }

    public function get() {
        $menu = new Menu();

        $this->addType($menu);
        $this->addCategories($menu);
        $this->addOptions($menu);
        $this->addItems($menu);
        $this->addReserveOccasions($menu);

        return $menu;
    }
}

/*

$mDAO = new MenuDAO($db,1,new MenuTypeDAO(),new MenuCategoryDAO(),new MenuOptionDAO(),new MenuItemDAO(),new MenuItemOptionDAO());

$m = $mDAO->get();

echo json_encode($m,JSON_PRETTY_PRINT);

*/