<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/MenuCategory.class.php";
require_once RESOURCE_PATH . "/database.php";

class MenuCategoryDAO extends BaseDAO
{
    private $menuCategoryMap = [
        'categoryId' => 'category_id',
        'menuTypeId' => 'menu_type_id',
        'categoryCode' => 'category_code',
        'categoryDescription' => 'category_description'
    ];

    private $entityTableMap = ['MenuCategory' => 'menu_category'];

    private $idMap = ['MenuCategory' => 'categoryId'];

    private $calculatedFields = ['MenuCategory' => []];

    private $fieldMap;

    public function __construct()
    {
        $this->fieldMap = ['MenuCategory' => $this->menuCategoryMap];
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

    private function gatherMenuCategoryFields($row,&$menuCategory) {
        $this->gatherEntityFields($row,'MenuCategory',$menuCategory);
    }


    private function getMenuCategoriesFromSQLRow($rows) {
        $idx = 0;

        $menuCategories = [];
        while($idx <= count($rows) - 1) {
            $menuCategoryData = [];
            $this->gatherMenuCategoryFields($rows[$idx],$menuCategoryData);
            $menuCategories[] = new MenuCategory($menuCategoryData);
            $idx++;
        }
        return $menuCategories;
    }

    public function getMenuCategory($dbConnection, $Id)
    {
        $selectMenuCategorySQL =
            'SELECT *
                 FROM menu_category r
             WHERE
                r.category_id = :categoryId';

        $statement = $dbConnection->prepare($selectMenuCategorySQL);
        $statement->execute(array(':categoryId' => $Id));
        $rows = $statement->fetchAll();
        $menuCategories = $this->getMenuCategoriesFromSQLRow($rows);
        return $menuCategories[0];
    }


    public function getMenuCategoriesByMenuType($dbConnection,$menuTypeId)
    {
        $selectMenuCategorySQL ='SELECT * FROM menu_category WHERE menu_type_id=:menuTypeId ORDER BY category_code ';
             
        $statement = $dbConnection->prepare($selectMenuCategorySQL);
        $statement->execute(array(':menuTypeId' => $menuTypeId));
        $rows = $statement->fetchAll();

        $menuCategories = $this->getMenuCategoriesFromSQLRow($rows);
        return $menuCategories;
    }

    public function createMenuCategory($dbConnection, $menuCategory)
    {
        $insertMenuCategorySQL = $this->generateInsertSQL('MenuCategory');

        $menuCategoryStatement = $dbConnection->prepare($insertMenuCategorySQL);

        $menuCategoryStatement->execute($this->getTableInsertParameters('MenuCategory', $menuCategory,[]));
        $menuCategoryId = $dbConnection->lastInsertId();
        return $menuCategoryId;
    }
}

/*
$x = new MenuCategoryDAO();
$id = $x->createMenuCategory($db,MenuCategory::createNewTestMenuCategory());
echo 'Created Test MenuCategory # ' . $id . '<br>';

$menuCategory  = $x->getMenuCategory($db,$id);
echo "JSON representation of newly created menuCategory: <br>" . json_encode($menuCategory);

echo '<br>';

echo 'JSON representation of list of all menuCategories by MenuType=1 <br>';

$menuCategories  = $x->getMenuCategoriesByMenuType($db,1);
echo json_encode($menuCategories);
*/