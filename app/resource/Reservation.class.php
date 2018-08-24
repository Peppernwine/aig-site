<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/1/2018
 * Time: 12:03 PM
 */

require_once RESOURCE_PATH . "/MandatoryFieldValidation.php";

class Reservation implements \JsonSerializable{
    private $reservationId;
    private $orderHeaderId;
    private $reserveDate;
    private $reservationName;
    private $requestDate;
    private $requestTime;
    private $occasionId;
    private $guestCount;
    private $instructions;
    private $customerId;
    private $customerFirstName;
    private $customerLastName;
    private $customerEmailId;
    private $customerCellPhone;

    function __construct($data) {

        if (!empty($data)) {
            if (array_key_exists('reservationId',$data))
                $this->reservationId = $data['reservationId'];

            if (array_key_exists('orderHeaderId',$data))
                $this->orderHeaderId = $data['orderHeaderId'];

            $this->reserveDate = $data['reserveDate'];

            if (array_key_exists('occasionId',$data))
                $this->occasionId = $data['occasionId'];

            $this->requestDate = $data['requestDate'];
            $this->requestTime = $data['requestTime'];
            $this->guestCount = $data['guestCount'];

            if (array_key_exists('customerId',$data))
                $this->customerId = $data['customerId'];

            if (array_key_exists('instructions',$data))
                $this->instructions = $data['instructions'];

            $this->customerFirstName = $data['customerFirstName'];
            $this->customerLastName = $data['customerLastName'];
            $this->customerEmailId = $data['customerEmailId'];
            $this->customerCellPhone = $data['customerCellPhone'];

            if (array_key_exists('reservationName',$data))
                $this->reservationName = $data['reservationName'];
        }
    }

    public static function createNewTestReservation(){
        $data = '{
                            "reservationId":999,"orderHeaderId":100,"reserveDate":"2018-1-1","occasionId":1,
                            "reservationName":"Sams group","requestDate":"2018-1-1","requestTime":"11:50 AM",
                            "guestCount":8,"instructions": "ASAP/Mild/no nuts",
                            "customerId":1,"customerFirstName":"John","customerLastName":"Doe",
                            "customerEmailId":"john.doeglobalnet.com","customerCellPhone":"111-111-1111"
a                  }';

        $data = json_decode($data,true);
        $result = new Reservation([]);
        $result->reserve($data);
        return $result ;
    }

    public function getData() {
        $data =
            ['reservationId' => $this->reservationId,
             'orderHeaderId' => $this->orderHeaderId,
             'reserveDate' => $this->reserveDate,
             'reservationName' => $this->reservationName,
             'requestDate' => $this->requestDate,
             'requestTime'=> $this->requestTime ,
             'occasionId' => $this->occasionId,
             'guestCount'=> $this->guestCount ,
             'instructions' => $this->instructions,
             'customerId' => $this->customerId,
             'customerFirstName' => $this->customerFirstName,
             'customerLastName' => $this->customerLastName,
             'customerEmailId'=> $this->customerEmailId ,
             'customerCellPhone' => $this->customerCellPhone
            ];

        return $data;
    }


    public function jsonSerialize(){
        return $this->getData();
    }

    public function getReservationId(){
        return $this->reservationId;
    }

    public function getDisplayReservationId(){
        return '#' . $this->reservationId;
    }

    public function getReserveDate(){
        return $this->reserveDate;
    }

    public function getOrderHeaderId(){
        return $this->orderHeaderId;
    }


    public function getReservationName(){
        return $this->reservationName;
    }

    public function getRequestDate(){
        return $this->requestDate;
    }

    public function getRequestTime(){
        return $this->requestTime;
    }

    public function getDisplayReservationDateTime(){
        $today = new DateTime('00:00');
        $requestDate = new DateTime($this->requestDate);
        if ($today === $this->requestDate)
            return 'Today,'.$this->requestTime;
        else
            return date_format($requestDate,"m/d/Y") . ','.$this->requestTime;
    }

    public function getOccasionId(){
        return $this->occasionId;
    }

    public function getGuestCount(){
        return $this->guestCount;
    }

    public function getInstructions(){
        return $this->instructions;
    }

    public function getCustomerId(){
        return $this->customerId;
    }

    public function getCustomerFirstName(){
        return $this->customerFirstName;
    }

    public function getCustomerLastName(){
        return $this->customerLastName;
    }

    public function getDisplayCustomerName(){
        return $this->customerLastName . ',' . $this->customerFirstName;
    }

    public function getCustomerEmailId(){
        return $this->customerEmailId;
    }

    public function getCustomerCellPhone(){
        return $this->customerCellPhone;
    }

    private function setReservationInfo($reservationInfo) {
        $val = new MandatoryFieldValidation(['requestDate' => 'Request Date','requestTime' => 'Request Time',
            'customerFirstName' => 'First Name','customerLastName' => 'Last Name',
            'customerEmailId' => 'Email Id','customerCellPhone' => 'Cell Phone','guestCount' => ' Guest Count'],
            $reservationInfo);

        $val->validate();

        if (array_key_exists('orderHeaderId',$reservationInfo))
            $this->orderHeaderId = $reservationInfo['orderHeaderId'];

        if (empty($reservationInfo['reserveDate']))
            $this->reserveDate = date("Y-m-d");
        else
            $this->reserveDate = $reservationInfo['reserveDate'];

        if (array_key_exists('customerId',$reservationInfo))
            $this->customerId = $reservationInfo['customerId'];

        $this->customerFirstName = $reservationInfo['customerFirstName'];
        $this->customerLastName = $reservationInfo['customerLastName'];
        $this->customerEmailId = $reservationInfo['customerEmailId'];
        $this->customerCellPhone = $reservationInfo['customerCellPhone'];

        if (array_key_exists('reservationName',$reservationInfo))
            $this->reservationName = $reservationInfo['reservationName'];

        $this->requestDate = $reservationInfo['requestDate'];
        $this->requestTime = $reservationInfo['requestTime'];

        if (array_key_exists('occasionId',$reservationInfo))
            $this->occasionId = $reservationInfo['occasionId'];

        $this->guestCount = $reservationInfo['guestCount'];

        if (array_key_exists('instructions',$reservationInfo))
            $this->instructions = $reservationInfo['instructions'];
    }

    public function reserve($reservationInfo) {
        $this->setReservationInfo($reservationInfo);
    }
}
