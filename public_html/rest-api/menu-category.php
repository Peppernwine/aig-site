<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/28/2018
 * Time: 8:04 PM
 */

require_once dirname(__FILE__) . "/../bootstrap.php";
require_once RESOURCE_PATH . "/MenuCategoryDAO.class.php";
require_once RESOURCE_PATH . "/MenuCategoryDAOStub.class.php";

header("Content-Type: application/json");

if (isset($_GET['typeId']) )
    $menuTypeId = $_GET['typeId'];
else
    $menuTypeId = 1;

if (!isset($menuTypeId)) $menuTypeId = -1;

$menuCatDAO = null;
if ($menuTypeId > 0)
    $menuCatDAO = new MenuCategoryDAO();
else
    $menuCatDAO = new MenuCategoryDAOStub();

$cats  = $menuCatDAO->getMenuCategoriesByMenuType($db,$menuTypeId);

echo json_encode($cats, JSON_PRETTY_PRINT);