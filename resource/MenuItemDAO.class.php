<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/MenuItem.class.php";
require_once RESOURCE_PATH . "/database.php";

class MenuItemDAO extends BaseDAO
{
    private $menuItemMap = [
        'itemId' => 'item_id',
        'menuTypeId' => 'menu_type_id',
        'menuCategoryId' => 'menu_category_id',
        'itemCode' => 'item_code',
        'itemDescription' => 'item_description',
        'basePrice' => 'base_price',
        'isChefsSpecial' => 'is_chefs_special',
        'isGlutenFree' => 'is_gluten_free',
        'isNutFree' => 'is_nut_free',
        'menuItemProfileId' => 'menu_item_profile_id'
    ];

    private $entityTableMap = ['MenuItem' => 'menu_item'];

    private $idMap = ['itemId']; //'MenuType' => 'typeId'...meant to indicate autoincrement ID field only

    private $calculatedFields = ['MenuItem' => []];

    private $fieldMap;

    public function __construct()
    {
        $this->fieldMap = ['MenuItem' => $this->menuItemMap];
        parent::__construct();
    }


    protected function getEntityTableMap() {
        return $this->entityTableMap;
    }


    protected function getIdMap() {
        return $this->idMap;
    }

    protected function getCalculatedFields() {
        return $this->calculatedFields;
    }

    protected function getFieldMap() {
        return $this->fieldMap;
    }

    private function gatherMenuItemFields($row, &$menuItem) {
        $this->gatherEntityFields($row,'MenuItem',$menuItem);
    }

    private function getMenuItems($rows) {
        $idx = 0;

        $menuItems = [];
        while($idx <= count($rows) - 1) {
            $menuItemData = [];
            $this->gatherMenuItemFields($rows[$idx],$menuItemData);
            $menuItems[] = new MenuItem($menuItemData);
            $idx++;
        }
        return $menuItems;
    }

    public function getMenuItemById($dbConnection, $itemId)
    {
        $selectMenuItemSQL =
            'SELECT * FROM menu_item WHERE item_id = :itemId';

        $statement = $dbConnection->prepare($selectMenuItemSQL);
        $statement->execute(array(':itemId' => $itemId));
        $rows = $statement->fetchAll();
        $menuItems = $this->getMenuItems($rows);
        return $menuItems[0];
    }

    public function getAllMenuItemsByType($dbConnection,$menuTypeId)
    {
        $selectMenuItemSQL ='SELECT * FROM menu_item WHERE menu_type_id = :menuTypeId ORDER BY item_code';

        $statement = $dbConnection->prepare($selectMenuItemSQL);
        $statement->execute(array(':menuTypeId' => $menuTypeId));
        $rows = $statement->fetchAll();

        $menuItems = $this->getMenuItems($rows);

        return $menuItems;
    }

    public function createMenuItem($dbConnection, $menuItem)
    {
        $insertMenuItemSQL = $this->generateInsertSQL('MenuItem');
        $menuItemStatement = $dbConnection->prepare($insertMenuItemSQL);
        $menuItemStatement->execute($this->getTableInsertParameters('MenuItem', $menuItem,[]));
        $menuItemId = $dbConnection->lastInsertId();
        return $menuItemId;
    }
}

/*

$x = new MenuItemDAO();
$menuItemId = $x->createMenuItem($db,MenuItem::createNewTestMenuItem());
echo 'Created Test Menu Item # ' . $menuItemId . '<br>';

$menuItem  = $x->getMenuItemById($db,$menuItemId);
echo "JSON representation of newly created Menu Item  : <br>" . json_encode($menuItem);

echo '<br>';

echo 'JSON representation of list of all Menu Items<br>';

$menuItems  = $x->getAllMenuItemsByType($db,1);
echo json_encode($menuItems);

*/