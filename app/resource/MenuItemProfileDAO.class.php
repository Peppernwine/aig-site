<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/MenuItemProfile.class.php";
require_once RESOURCE_PATH . "/database.php";

class MenuItemProfileDAO extends BaseDAO
{

    private $menuItemProfileMap = [
        'itemProfileId' => 'item_profile_id',
        'itemProfileCode' => 'item_profile_code',
        'itemProfileDescription' => 'item_profile_description'
    ];

    private $entityTableMap = ['MenuItemProfile' => 'menu_item_profile'];

    private $idMap = []; //'MenuType' => 'typeId'...meant to indicate autoincrement ID field only

    private $calculatedFields = ['MenuItemProfile' => []];

    private $fieldMap;

    public function __construct()
    {
        $this->fieldMap = ['MenuItemProfile' => $this->menuItemProfileMap];
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

    private function gatherMenuItemProfileFields($row, &$menuType) {
        $this->gatherEntityFields($row,'MenuItemProfile',$menuType);
    }


    private function getMenuItemProfiles($rows) {
        $idx = 0;

        $menuItemProfiles = [];
        while($idx <= count($rows) - 1) {
            $menuItemProfileData = [];
            $this->gatherMenuItemProfileFields($rows[$idx],$menuItemProfileData);
            $menuItemProfiles[] = new MenuItemProfile($menuItemProfileData);
            $idx++;
        }
        return $menuItemProfiles;
    }

    public function getMenuItemProfileById($dbConnection, $itemProfileId)
    {
        $selectMenuItemProfileSQL =
            'SELECT * FROM menu_item_profile WHERE item_profile_id = :itemProfileId';

        $statement = $dbConnection->prepare($selectMenuItemProfileSQL);
        $statement->execute(array(':itemProfileId' => $itemProfileId));
        $rows = $statement->fetchAll();
        $menuItemProfiles = $this->getMenuItemProfiles($rows);
        return $menuItemProfiles[0];
    }

    public function getAllMenuItemProfiles($dbConnection)
    {
        $selectMenuItemProfileSQL ='SELECT * FROM menu_item_profile ORDER BY item_profile_code';

        $statement = $dbConnection->prepare($selectMenuItemProfileSQL);
        $statement->execute();
        $rows = $statement->fetchAll();

        $menuItemProfiles = $this->getMenuItemProfiles($rows);
        return $menuItemProfiles;
    }

    public function createMenuOptionProfile($dbConnection, $menuOptionProfile)
    {
        $insertMenuOptionProfileSQL = $this->generateInsertSQL('MenuItemProfile');

        $menuOptionProfileStatement = $dbConnection->prepare($insertMenuOptionProfileSQL);

        $menuOptionProfileStatement->execute($this->getTableInsertParameters('MenuItemProfile', $menuOptionProfile,[]));
        $optionProfileId = $dbConnection->lastInsertId();
        return $optionProfileId;
    }
}

/*

$x = new MenuItemProfileDAO();
$menuItemProfileId = $x->createMenuOptionProfile($db,MenuItemProfile::createNewTestMenuItemProfile());
echo 'Created Test Menu Item Profile # ' . $menuItemProfileId . '<br>';

$menuItemProfile  = $x->getMenuItemProfileById($db,$menuItemProfileId);
echo "JSON representation of newly created Menu Item Profile : <br>" . json_encode($menuItemProfile);

echo '<br>';

echo 'JSON representation of list of all Menu Item Profiles <br>';

$menuItemProfiles  = $x->getAllMenuItemProfiles($db);
echo json_encode($menuItemProfiles);

*/