<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/ReserveOccasion.class.php";
require_once RESOURCE_PATH . "/database.php";

class ReserveOccasionDAO extends BaseDAO
{
    private $reserveOccasionMap = [
        'occasionId' => 'occasion_id',
        'occasionCode' => 'occasion_code',
        'occasionDescription' => 'occasion_description'
    ];

    private $entityTableMap = ['ReserveOccasion' => 'reserve_occasion'];

    private $idMap = ['ReserveOccasion' => 'occasionId'];

    private $calculatedFields = ['ReserveOccasion' => []];

    private $fieldMap;

    public function __construct()
    {
        $this->fieldMap = ['ReserveOccasion' => $this->reserveOccasionMap];
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

    private function gatherReserveOccasionFields($row,&$reserveOccasion) {
        $this->gatherEntityFields($row,'ReserveOccasion',$reserveOccasion);
    }


    private function getReserveOccasionsFromSQLRow($rows) {
        $idx = 0;

        $reserveOccasions = [];
        while($idx <= count($rows) - 1) {
            $reserveOccasionData = [];
            $this->gatherReserveOccasionFields($rows[$idx],$reserveOccasionData);

            $reserveOccasions[] = new ReserveOccasion($reserveOccasionData);
            $idx++;
        }
        return $reserveOccasions;
    }

    public function getReserveOccasion($dbConnection, $reserveOccasionId)
    {
        $selectReserveOccasionSQL =
            'SELECT *
                 FROM reserve_occasion r
             WHERE
                r.occasion_id = :reserveOccasionId';

        $statement = $dbConnection->prepare($selectReserveOccasionSQL);
        $statement->execute(array(':reserveOccasionId' => $reserveOccasionId));
        $rows = $statement->fetchAll();
        $reserveOccasions = $this->getReserveOccasionsFromSQLRow($rows);
        return $reserveOccasions[0];
    }


    public function getAllReserveOccasions($dbConnection)
    {
        $selectReserveOccasionSQL ='SELECT * FROM reserve_occasion ORDER BY occasion_code';
             
        $statement = $dbConnection->prepare($selectReserveOccasionSQL);
        $statement->execute();
        $rows = $statement->fetchAll();

        $reserveOccasions = $this->getReserveOccasionsFromSQLRow($rows);
        return $reserveOccasions;
    }

    public function createReserveOccasion($dbConnection, $reserveOccasion)
    {
        $insertReserveOccasionSQL = $this->generateInsertSQL('ReserveOccasion');


        $reserveOccasionStatement = $dbConnection->prepare($insertReserveOccasionSQL);

        $reserveOccasionStatement->execute($this->getTableInsertParameters('ReserveOccasion', $reserveOccasion,[]));
        $reserveOccasionId = $dbConnection->lastInsertId();
        return $reserveOccasionId;
    }
}

/*
$x = new ReserveOccasionDAO();
$reserveOccasionId = $x->createReserveOccasion($db,ReserveOccasion::createNewTestReserveOccasion());
echo 'Created Test ReserveOccasion # ' . $reserveOccasionId . '<br>';

$reserveOccasion  = $x->getReserveOccasion($db,$reserveOccasionId);
echo "JSON representation of newly created Occasion: <br>" . json_encode($reserveOccasion);

echo '<br>';

echo 'JSON representation of list of all occasions <br>';

$reservations  = $x->getAllReserveOccasions($db);
echo json_encode($reservations);

*/