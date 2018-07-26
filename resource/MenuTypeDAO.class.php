<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/MenuType.class.php";
require_once RESOURCE_PATH . "/database.php";

class MenuTypeDAO extends BaseDAO
{
    private $menuTypeMap = [
        'typeId' => 'type_id',
        'typeCode' => 'type_code',
        'typeDescription' => 'type_description',
        'isAvailableOnline' => 'is_available_online'
    ];

    private $entityTableMap = ['MenuType' => 'menu_type'];

    private $idMap = []; //'MenuType' => 'typeId'...meant to indicate autoincrement ID field only

    private $calculatedFields = ['MenuType' => []];

    private $fieldMap;

    public function __construct()
    {
        $this->fieldMap = ['MenuType' => $this->menuTypeMap];
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

    private function gatherMenuTypeFields($row,&$menuType) {
        $this->gatherEntityFields($row,'MenuType',$menuType);
    }

    private function getMenuTypesFromSQLRow($rows) {
        $idx = 0;

        $menuTypes = [];
        while($idx <= count($rows) - 1) {
            $menuTypeData = [];
            $this->gatherMenuTypeFields($rows[$idx],$menuTypeData);

            $hours = [];
            if (isset($rows[$idx]['hours_description'])) {
                while($idx <= count($rows) - 1 && ($menuTypeData['typeId'] === $rows[$idx]['type_id'] ))  {
                    $hours[] = $rows[$idx]['hours_description'];
                    $idx++;
                }
            }
            else
                $idx++;

            $menuTypeData['hourDescriptions'] = $hours;
            $menuTypes[] = new MenuType($menuTypeData);
        }
        return $menuTypes;
    }

    public function getMenuTypeById($dbConnection, $typeId){
        $selectMenuTypeSQL =
            'SELECT * FROM menu_type mt lEFT OUTER JOIN menu_type_hour mth ON (mt.type_id = mth.menu_type_id)
             WHERE 
                mt.type_id = :typeId';

        $statement = $dbConnection->prepare($selectMenuTypeSQL);
        $statement->execute(array(':typeId' => $typeId));
        $rows = $statement->fetchAll();
        $menuTypes = $this->getMenuTypesFromSQLRow($rows);
        return $menuTypes[0];
    }

    public function getMaxMenuTypeId($dbConnection) {
        $selectMenuTypeSQL ='SELECT MAX(type_id) max_menu_type_id FROM menu_type';

        $statement = $dbConnection->prepare($selectMenuTypeSQL);
        $statement->execute();
        $rows = $statement->fetchAll();

        $maxId = $rows[0]['max_menu_type_id'];
        if (empty($maxId))
            $maxId = 0;
        return $maxId;
    }

    public function getAllMenuTypes($dbConnection){
        $selectMenuTypeSQL ='SELECT * FROM menu_type mt 
                                lEFT OUTER JOIN menu_type_hour mth ON (mt.type_id = mth.menu_type_id)
                              ORDER BY 
                                type_code';

        $statement = $dbConnection->prepare($selectMenuTypeSQL);
        $statement->execute();
        $rows = $statement->fetchAll();

        $menuTypes = $this->getMenuTypesFromSQLRow($rows);
           return $menuTypes;
    }

    public function getHourDescriptionInsertSQL() {
        $insertHoursSQL = "INSERT INTO menu_type_hour
                                     (menu_type_id,hours_description)
                                 VALUES
                                     (:menuTypeId,:hoursDescription)";
        return $insertHoursSQL;
    }

    public function getHourDescriptionInsertParameters($menuTypeId,$hours) {
        return array(
            ':menuTypeId' => $menuTypeId,
            ':hoursDescription' => $hours
        );
    }

    public function createMenuType($dbConnection, $menuType){
        $insertMenuTypeSQL = $this->generateInsertSQL('MenuType');
        $insertHoursSQL = $this->getHourDescriptionInsertSQL();

        $menuTypeStatement = $dbConnection->prepare($insertMenuTypeSQL);
        $hoursStatement = $dbConnection->prepare($insertHoursSQL);

        $menuTypeStatement->execute($this->getTableInsertParameters('MenuType', $menuType,[]));
        $menuTypeId = $menuType->getTypeId();

        $hourDescriptions = $menuType->getHourDescriptions();
        foreach ($hourDescriptions as $hoursDescription) {
            $hoursStatement->execute($this->getHourDescriptionInsertParameters($menuTypeId,$hoursDescription));
        }
        return $menuTypeId;
    }
}

/*
$x = new MenuTypeDAO();
$maxId = $x->getMaxMenuTypeId($db) + 1;
$menuTypeId = $x->createMenuType($db,MenuType::createNewTestMenuType($maxId));
echo 'Created Test MenuType # ' . $menuTypeId . '<br>';

$menuType  = $x->getMenuTypeById($db,$menuTypeId);
echo "JSON representation of newly created menuType: <br>" . json_encode($menuType);

echo '<br>';

echo 'JSON representation of list of all menuTypes <br>';

$menuTypes  = $x->getAllMenuTypes($db);
echo json_encode($menuTypes);
*/