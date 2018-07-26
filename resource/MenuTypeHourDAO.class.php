<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/MenuTypeHour.class.php";
require_once RESOURCE_PATH . "/database.php";

class MenuTypeHourDAO extends BaseDAO
{

    private $menuTypeHourMap = [
        'hourId' => 'hour_id',
        'menuTypeId' => 'menu_type_id',
        'hoursDescription' => 'hours_description'
    ];

    private $entityTableMap = ['MenuTypeHour' => 'menu_type_hour'];

    private $idMap = ['hourId'];

    private $calculatedFields = ['MenuTypeHour' => []];

    private $fieldMap;

    public function __construct()
    {
        $this->fieldMap = ['MenuTypeHour' => $this->menuTypeHourMap];
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

    private function gatherMenuTypeHourFields($row,&$menuType) {
        $this->gatherEntityFields($row,'MenuTypeHour',$menuType);
    }


    private function getMenuTypeHoursFromSQLRow($rows) {
        $idx = 0;

        $menuTypeHours = [];
        while($idx <= count($rows) - 1) {
            $menuTypeHourData = [];
            $this->gatherMenuTypeHourFields($rows[$idx],$menuTypeHourData);
            $menuTypeHours[] = new MenuTypeHour($menuTypeHourData);
            $idx++;
        }
        return $menuTypeHours;
    }

    public function getMenuTypeHour($dbConnection, $hourId)
    {
        $selectMenuTypeHourSQL =
            'SELECT *
                 FROM menu_type_hour mh
             WHERE
                mh.hour_id = :hourId';

        $statement = $dbConnection->prepare($selectMenuTypeHourSQL);
        $statement->execute(array(':hourId' => $hourId));
        $rows = $statement->fetchAll();
        $menuTypeHours = $this->getMenuTypeHoursFromSQLRow($rows);
        return $menuTypeHours[0];
    }

    public function getHoursByMenuTypeId($dbConnection,$menuTypeId){
        $selectMenuTypeHourSQL ='SELECT * FROM menu_type_hour WHERE menu_type_id = :menuTypeId';

        $statement = $dbConnection->prepare($selectMenuTypeHourSQL);
        $statement->execute(array(':menuTypeId' => $menuTypeId));
        $rows = $statement->fetchAll();

        $menuTypes = $this->getMenuTypeHoursFromSQLRow($rows);
        return $menuTypes;
    }

    public function createMenuTypeHour($dbConnection, $menuTypeHour)
    {
        $insertMenuTypeSQL = $this->generateInsertSQL('MenuTypeHour');

        $menuTypeStatement = $dbConnection->prepare($insertMenuTypeSQL);

        $menuTypeStatement->execute($this->getTableInsertParameters('MenuTypeHour', $menuTypeHour,[]));
        $hourId = $menuTypeHour->getHourId();
        return $hourId;
    }
}

/*
$x = new MenuTypeHourDAO();
$menuTypeHourId = $x->createMenuTypeHour($db,MenuTypeHour::createNewTestMenuTypeHour());
echo 'Created Test MenuTypeHour# ' . $menuTypeHourId . '<br>';

$menuTypeHour  = $x->getMenuTypeHour($db,$menuTypeHourId);
echo "JSON representation of newly created menuTypeHour: <br>" . json_encode($menuTypeHour);

echo '<br>';

echo 'JSON representation of list of hours by menuType=1<br>';

$menuTypeHours  = $x->getHoursByMenuTypeId($db,1);
echo json_encode($menuTypeHours);
*/