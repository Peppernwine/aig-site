<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 2:07 PM
 */

require_once RESOURCE_PATH . "/BaseDAO.class.php";
require_once RESOURCE_PATH . "/Reservation.class.php";
require_once RESOURCE_PATH . "/database.php";

class ReservationDAO extends BaseDAO
{
    private $reservationMap = [
        'reservationId' => 'reservation_id',
        'orderHeaderId' => 'order_header_id',
        'reserveDate' => 'reserve_date',
        'requestDate' => 'request_date',
        'requestTime' => 'request_time',
        'guestCount' => 'guest_count',
        'occasionId' => 'occasion_id',
        'reservationName' => 'reservation_name',
        'instructions' => 'instructions',
        'customerId' => 'customer_id',
        'customerFirstName' => 'customer_fname',
        'customerLastName' => 'customer_lname',
        'customerEmailId' => 'customer_email_id',
        'customerCellPhone' => 'customer_cell_phone'
    ];

    private $entityTableMap = ['Reservation' => 'reservation'];

    private $idMap = ['Reservation' => 'reservationId'];

    private $calculatedFields = ['Reservation' => []];

    private $fieldMap;

    public function __construct()
    {
        $this->fieldMap = ['Reservation' => $this->reservationMap];
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

    private function gatherReservationFields($row,&$reservation) {
        $this->gatherEntityFields($row,'Reservation',$reservation);
    }

    private function getReservationsFromSQLRow($rows) {
        $idx = 0;

        $reservations = [];
        while($idx <= count($rows) - 1) {
            $reservationData = [];
            $this->gatherReservationFields($rows[$idx],$reservationData);
            $reservation = new Reservation($reservationData);
            $reservations[] = $reservation;
            $idx++;
        }
        return $reservations;
    }

    public function getReservation($dbConnection, $reservationId)
    {
        $selectReservationSQL =
            'SELECT *
                 FROM reservation r
             WHERE
                r.reservation_id = :reservationId';

        $statement = $dbConnection->prepare($selectReservationSQL);
        $statement->execute(array(':reservationId' => $reservationId));
        $rows = $statement->fetchAll();
        $reservations = $this->getReservationsFromSQLRow($rows);
        return $reservations[0];
    }

    private function getSearchParams($searchParams) {
        $params = [];

        if (array_key_exists('customerId',$searchParams) && !empty($searchParams['customerId'])) {
            $params['noCustomerIdParam'] = 0;
            $params['customerId'] = $searchParams['customerId'];
        } else {
            $params['noCustomerIdParam'] = 1;
            $params['customerId'] = -1;
        }

        if (array_key_exists('reservationId',$searchParams) && !empty($searchParams['reservationId']) ) {
            $params['noReservationIdParam'] = 0;
            $params['reservationId'] = $searchParams['reservationId'];
        } else {
            $params['noReservationIdParam'] = 1;
            $params['reservationId'] = -1;
        }

        if (array_key_exists('startDate',$searchParams) && !empty($searchParams['startDate'])) {
            $params['noStartDateParam'] = 0;
            $searchParams['startDate'] = trim($searchParams['startDate'],"'");
            $params['startDate'] = date_format(date_create_from_format('Y-m-d',$searchParams['startDate']),"Y-m-d");
        } else {
            $params['noStartDateParam'] = 1;
            $params['startDate'] = 0;
        }

        if (array_key_exists('endDate',$searchParams) && !empty($searchParams['endDate'])) {
            $params['noEndDateParam'] = 0;
            $searchParams['endDate'] = trim($searchParams['endDate'],"'");
            $params['endDate'] =  date_format(date_create_from_format('Y-m-d',$searchParams['endDate']),"Y-m-d");
        } else {
            $params['noEndDateParam'] = 1;
            $params['endDate'] = 0;
        }

        return $params;
    }

    public function getReservations($dbConnection, $searchParams)
    {
        $params = $this->getSearchParams($searchParams);

        if (array_key_exists('batchSize',$searchParams) && !empty($searchParams['batchSize'])) {
            $limit = $searchParams['batchSize'];
            $offset = $limit * $searchParams['lastPage'];
        } else {
            $limit = 15;
            $offset = 0;
        }

        $selectReservationSQL =
            "SELECT *
                 FROM reservation r 
             WHERE
                (1=:noStartDateParam OR r.request_date >= :startDate) AND 
                (1=:noEndDateParam OR r.request_date <= :endDate) AND
                (1=:noCustomerIdParam OR IFNULL(r.customer_id,-1) = :customerId) AND 
                (1=:noReservationIdParam OR r.reservation_id = :reservationId)
             ORDER BY r.reservation_id DESC  
                LIMIT $offset ,$limit ";


        $statement = $dbConnection->prepare($selectReservationSQL);

        $statement->execute($params);

        $rows = $statement->fetchAll();

        $reservations = $this->getReservationsFromSQLRow($rows);

        if (sizeof($reservations)  > 0)
            return $reservations;
        else
            return [];
    }


    public function createReservation($dbConnection, $reservation)
    {
        $insertReservationSQL = $this->generateInsertSQL('Reservation');


        $reservationStatement = $dbConnection->prepare($insertReservationSQL);

        $reservationStatement->execute($this->getTableInsertParameters('Reservation', $reservation,[]));
        $reservationId = $dbConnection->lastInsertId();
        return $reservationId;
    }
}

/*
$x = new ReservationDAO();
$reservationId = $x->createReservation($db,Reservation::createNewTestReservation());
echo 'Created Test Reservation # ' . $reservationId . '<br>';

$reservation  = $x->getReservation($db,$reservationId);
echo json_encode($reservation);

*/