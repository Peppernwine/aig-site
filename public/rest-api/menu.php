<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/28/2018
 * Time: 8:04 PM
 */

require_once dirname(__FILE__) . "/../bootstrap.php";
require_once RESOURCE_PATH . "/MenuDAO.class.php";
require_once RESOURCE_PATH . "/MenuTypeDAO.class.php";
require_once RESOURCE_PATH . "/MenuCategoryDAO.class.php";
require_once RESOURCE_PATH . "/MenuDAO.class.php";
require_once RESOURCE_PATH . "/MenuItemOptionDAO.class.php";
require_once RESOURCE_PATH . "/MenuItemDAO.class.php";
require_once RESOURCE_PATH . "/MenuDAOStub.class.php";


header("Content-Type: application/json");

if (isset($_GET['typeId']) )
    $menuTypeId = $_GET['typeId'];
else
    $menuTypeId = 1;

$menuDAO = null;
if ($menuTypeId > 0) {
    $menuDAO = new MenuDAO($db,$menuTypeId,new MenuTypeDAO(),new MenuCategoryDAO(),new MenuOptionDAO(),
                                           new MenuItemDAO(),new MenuItemOptionDAO(),new ReserveOccasionDAO());
} else {
    $menuDAO = new MenuDAOStub($db,$menuTypeId);
}


$menu = $menuDAO->get();

echo json_encode($menu, JSON_PRETTY_PRINT);

