<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/20/2018
 * Time: 10:43 PM
 */

require_once "info-page-helper.php";

class ReservationView {

    private $order;
    private $reservation;
    private $occasion;
    private $mode ;
    private $suppressGraphics;

    public function __construct($reservation,$occasion,$order, $mode, $suppressGraphics){


        $this->reservation = $reservation;
        $this->occasion = $occasion;
        $this->order = $order;
        $this->mode = $mode; //1 = Customer ; 2 = Restaurant
        $this->suppressGraphics = $suppressGraphics;
    }

    private function getBackgroundColor($originalBackgroundColor,$originalColor,&$newBackgroundColor,&$newColor) {
        if ($this->suppressGraphics) {
            $newBackgroundColor =  '#0';
            $newColor = 'black';

        } else {
            $newBackgroundColor = $originalBackgroundColor;
            $newColor = $originalColor;
        }

    }

    private function getCustomerModeHeader() {
        $html = '';

        $backgroundColor = '';
        $color = '';

        $this->getBackgroundColor('rgba(239,141,36,.8)','black',$backgroundColor,$color);
        $html .= "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse;color:$color;background-color:$backgroundColor;width:100%;padding:10px'>";
        if (!$this->suppressGraphics) {
            $html .= "<tr>";
            $html .= "<td align='left' style='width:50%;padding: 2px 2px 2px 2px'>";
            $html .= "<img width='150px' src='/aig-customer-site/images/logo.png'/>";
            $html .= "</td>";
            }
        $html .= "<td align='right' style='width:50%;padding: 2px 2px 2px 2px'>";
        $html .= "<p style='font-size:14px;font-weight:bold;margin:0px'>320 West Main Street</p>";
        $html .= "<p style='font-size:14px;font-weight:bold;margin:0px'>Avon, CT-06001</p>";
        $html .= "<p style='font-size:14px;font-weight:bold;margin:0px'>(860)-284-4466</p>";
        $html .= "<p style='font-size:14px;font-weight:bold;margin:0px'>www.avonindiangrill.com</p>";
        $html .= "<p style='font-size:14px;font-weight:bold;margin:0px'>info@avonindiangrill.com</p>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";

        return $html;
    }

    private function getRestaurantModeHeader() {
        $html = '';

        $reservationDescription = ' RESERVATION - ' . $this->reservation->getDisplayReservationDateTime();
        if (!empty($this->order))
            $paymentType = '--'. $this->order->getDisplayPaymentType() . '--';
        else
            $paymentType = '';

        $html .= "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse;border:1px solid black;width:100%;padding:10px'>";
        $html .= "<tr>";
        $html .= "<td align='center' style='width:100%;padding: 2px 2px 2px 2px'>";
        $html .= "<p style='text-align:center;font-size:18px;font-weight:bold;margin:0px'>$reservationDescription</p>";
        $html .= "<p style='text-align:center;color:red;font-size:18px;font-weight:bold;margin:0px'>$paymentType</p>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";

        return $html;
    }

    private function getHeader() {
        if ($this->mode == 1)
            return $this->getCustomerModeHeader();
        else
            return $this->getRestaurantModeHeader();
    }

    private function getEmptyRow() {
        $html  = '';
        $html .= "<tr>";
        $html .= "<td>";
        $html .= "&nbsp;";
        $html .= "</td>";
        $html .= "</tr>";
        return $html;
    }

    private function getButton($caption,$url) {
        $html = '';
        $html .= "<tr>";
        $html .= "<td style='font-weight:bold;box-shadow:0 2px 5px 0 rgba(0, 0, 0, 0.26), 0 2px 10px 0 rgba(0, 0, 0, 0.22);background-color:#b70038; padding:8px 0; text-align:center'>";
        $html .= "<a href='$url' target='_blank' style='width:160px; color:#ffffff;padding:0px; text-decoration:none; text-align:center'>$caption</a>";
        $html .= "</td>";


        $html .= "</tr>";
        return $html;
    }

    private function getReservationHighlights() {

        $html = '';

        $html .= "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse:collapse;padding:10px'>";

        $html .= "<tr>";
        $html .= "<td style='padding: 2px 2px 2px 2px' >";
        $html .= "<h2>Thanks for your Reservation!</h2>";
        $html .= "</td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td style='font-weight:bold;padding: 2px 2px 2px 2px'>";
        $html .= "Reservation ID:";
        $html .= "</td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td style='padding: 2px 2px 2px 2px'>";
        $html .= $this->reservation->getDisplayReservationId();
        $html .= "</td>";
        $html .= "</tr>";

        $html .= $this->getEmptyRow();

        $html .= "<tr>";
        $html .= "<td style='font-weight: bold;padding: 2px 2px 2px 2px'>";
        $html .= "Reservation Time:";
        $html .= "</td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td>";
        $html .= $this->reservation->getDisplayReservationDateTime();
        $html .= "</td>";
        $html .= "</tr>";

        if (!empty($this->order)) {
            $html .= $this->getEmptyRow();

            $html .= "<tr>";
            $html .= "<td style='font-weight: bold;padding: 2px 2px 2px 2px'>";
            $html .= "Payment:";
            $html .= "</td>";
            $html .= "</tr>";

            $html .= "<tr>";
            $html .= "<td style='padding: 2px 2px 2px 2px'>";
            $html .= $this->order->getDisplayPaymentType();
            $html .= "</td>";
            $html .= "</tr>";
        }

        $html .= $this->getEmptyRow();

        $html .= "<tr>";
        $html .= "<td style='padding: 2px 2px 2px 2px'>";
        $html .= "Please contact us at (860)&nbsp;284-4466 if you need any assistance with your reservation.";
        $html .= "</td>";
        $html .= "</tr>";

        $html .= $this->getEmptyRow();

        $html .= $this->getButton("I'M HERE",'notifyCustomerArrival.php');

        $html .= $this->getEmptyRow();


        $backgroundColor = '';
        $color = '';

        $this->getBackgroundColor('rgba(239,141,36,.8)','black',$backgroundColor,$color);

        $html .= "<tr>";
        $html .= "<td style='background-color:$backgroundColor; padding:8px 0; text-align:center'>";
        $html .= "<p style='margin:10px;color:white;font-weight:bold;font-size:28px'>Email Club</p>";
        $html .= "</td>";
        $html .= "</tr>";

        $html .= $this->getEmptyRow();

        $html .= "<tr>";
        $html .= "<td>";
        $html .= "<p style='margin:0px'>Join our Email Club to receive our Discount Coupons, exclusive offers, promotions and invitations to special events</p>";
        $html .= "</td>";
        $html .= "</tr>";

        $html .= $this->getEmptyRow();

        $html .= "<tr>";
        $html .= "<td>";
        $html .= "<p style='margin:0px;color:#b70038'><b>Get your first reward just for signing up!</b></p>";
        $html .= "</td>";
        $html .= "</tr>";

        $html .= $this->getEmptyRow();

        $html .= $this->getButton("JOIN NOW",'signup.php');

        $html .= "</table>";

        return $html;
    }

    private function getColumnHeader($heading,$colspan = 2) {
        $backgroundColor = '';
        $color = '';

        $this->getBackgroundColor('#333','white',$backgroundColor,$color);


        $header = '';
        $header .= "<tr>";
        $header .= "<td colspan='$colspan' style='border:1px solid black;font-weight:bold;padding:5px;width:100%;background-color:$backgroundColor;color:$color'>";
        $header .= $heading;
        $header .= "</td>";
        $header .= "</tr>";

        return $header;
    }

    private function getNameValueTable($caption,$colInfo,$data) {
        $header = $this->getColumnHeader($caption);
        $html = '';
        $html .= "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse:collapse;padding:10px;width:100%'>";
        $html .= '<colgroup>';
        $html .= "<col width='35%'>";
        $html .= "<col width='65%'>";
        $html .= '</colgroup>';

        $html .= $header;

        foreach ($colInfo as $colName=>$colLabel) {
            $html .= "<tr>";
            $html .= "<td style='padding:2px 0px 2px 0px;font-weight: bold'>";
            $html .= "$colLabel";
            $html .= "</td>";
            $html .= "<td>";
            $html .= $data[$colName];
            $html .= "</td>";
            $html .= "</tr>";
        }
        $html .= "</table>";

        return $html;

    }

    private function getReservationSummary () {

        $occasionCode = "Not specified";
        if (!empty($this->occasion))
            $occasionCode = $this->occasion->getOccasionCode();
        return $this->getNameValueTable("Reservation Summary",
            ['reservationId'=> 'Reservation ID:',
                'reservationName'=>'Name:',
                'reservationDateTime'=>'Time:',
                'guestCount'=>'Guest Count',
                'occasion'=>'Occasion:'],
            ['reservationId'=> $this->reservation->getDisplayReservationId(),
                'reservationName'=>$this->reservation->getReservationName(),
                'reservationDateTime'=>$this->reservation->getDisplayReservationDateTime(),
                'guestCount'=>$this->reservation->getGuestCount(),
                'occasion'=> $occasionCode
            ]);
    }
    private function getOrderSummary() {

        if (empty($this->order))
            return '';

        $deliveryLabel= $this->order->getDeliveryLabel() . ':';
        return $this->getNameValueTable("Order Summary",
                                ['orderNumber'=> 'Order Number:',
                                'orderTypeId'=>'Order Type:',
                                'deliveryDateTime'=>$deliveryLabel,
                                'total'=>'Total:',
                                'payment'=>'Payment:'],
                                ['orderNumber'=> $this->order->getDisplayOrderHeaderId(),
                                 'orderTypeId'=>$this->order->getDisplayOrderType(),
                                 'deliveryDateTime'=>$this->order->getDisplayDeliveryDateTime(),
                                 'total'=>$this->order->getDisplayTotal(),
                                 'payment'=>$this->order->getDisplayPaymentType()
                                ]);

    }

    private function getCustomerInfo() {

        return $this->getNameValueTable("Customer Information",
            ['customerName'=> 'Name:',
             'customerPhone'=>'Phone:'
            ],
            ['customerName'=> $this->reservation->getDisplayCustomerName(),
             'customerPhone'=>$this->reservation->getCustomerCellPhone()
            ]);
    }

    private function getOrderitem($qty,$item,$price,$amount,$optionText,$instructions) {
        $html = '';
        $html .= "<tr>";
        $html .= "<td width='10%' style='padding:2px 2px 2px 2px'>";
        $html .= "$qty";
        $html .= "</td>";
        $html .= "<td width='60%' style='padding:2px 2px 2px 2px'>";
        $html .= "$item" ;
        $html .= "</td>";
        $html .= "<td align='right' width='12.5%' style='padding:2px 2px 2px 2px'>";
        $html .= $price;
        $html .= "</td>";
        $html .= "<td align='right' width='15%' style='padding:2px 2px 2px 2px'>";
        $html .= $amount;
        $html .= "</td>";
        $html .= "</tr>";

        if (!empty($optionText)) {
            $html .= "<tr>";
            $html .= "<td width='10%' style='padding:2px 2px 2px 2px'>";
            $html .= "&nbsp;";
            $html .= "</td>";
            $html .= "<td width='60%' style='padding:2px 2px 2px 2px'>";
            $html .= "$optionText" ;
            $html .= "</td>";
            $html .= "<td align='right' width='12.5%' style='padding:2px 2px 2px 2px'>";
            $html .= "&nbsp;";
            $html .= "</td>";
            $html .= "<td align='right' width='15%' style='padding:2px 2px 2px 2px'>";
            $html .= "&nbsp;";
            $html .= "</td>";
            $html .= "</tr>";
        }

        if (!empty($instructions)) {

            $html .= "<tr>";
            $html .= "<td width='10%' style='padding:2px 2px 2px 2px'>";
            $html .= "&nbsp;";
            $html .= "</td>";
            $html .= "<td width='60%' style='padding:2px 2px 2px 2px'>";
            $html .= "$instructions";
            $html .= "</td>";
            $html .= "<td align='right' width='12.5%' style='padding:2px 2px 2px 2px'>";
            $html .= "&nbsp;";
            $html .= "</td>";
            $html .= "<td align='right' width='15%' style='padding:2px 2px 2px 2px'>";
            $html .= "&nbsp;";
            $html .= "</td>";
            $html .= "</tr>";
        }

        return $html;
    }

    private function getOrderItemColumnHeader() {
        $html = '';
        $html .= "<tr>";
        $html .= "<td style='font-weight:bold;padding:2px 2px 2px 2px' width='10%'>";
        $html .= "Qty.";
        $html .= "</td>";
        $html .= "<td style='font-weight:bold;padding:2px 2px 2px 2px' width='60%'>";
        $html .= "Item";
        $html .= "</td>";
        $html .= "<td style='font-weight:bold;padding:2px 2px 2px 2px' align='right' width='12.5%'>";
        $html .= "Price";
        $html .= "</td>";
        $html .= "<td style='font-weight:bold;padding:2px 2px 2px 2px' align='right' width='15%'>";
        $html .= "Total";
        $html .= "</td>";
        $html .= "</tr>";
        return $html ;
    }

    private function getOrderTotalComponent($caption,$value) {
        $html = '';
        $html .= "<tr>";
        $html .= "<td style='padding:2px 2px 2px 2px;font-weight:bold'  width='50%'>";
        $html .= "$caption";
        $html .= "</td>";
        $html .= "<td style='padding:2px 2px 2px 2px;font-weight:bold' align='right' width='50%'>";
        $html .= "$value";
        $html .= "</td>";
        $html .= "</tr>";

        return $html ;
    }

    private function getOrderTotals($subTotal,$tax,$tips,$total) {
        $html = '';

        $html .= $this->getOrderTotalComponent('Subtotal:',$this->order->getDisplaySubTotal());
        $html .= $this->getOrderTotalComponent('Sales Tax:',$this->order->getDisplaySalesTax());
        $html .= $this->getOrderTotalComponent('Tips:',$this->order->getDisplayTips());
        $html .= $this->getOrderTotalComponent('Total:',$this->order->getDisplayTotal());

        return $html ;
    }

    private function getOrderItems() {
        if (empty($this->order))
            return '';

        $header = $this->getColumnHeader("Order Items",4);

        $html = '';
        $html .= "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse;padding:15px;width:100%'>";
        $html .= $header;

        $html .= $this->getOrderItemColumnHeader();

        foreach ($this->order->getItems() as $item) {
            $html .= $this->getOrderitem($item->getQty(),$item->getMenuItemName(),$item->getDisplayPrice(),
                                         $item->getDisplayAmount(), $item->getOptionsText(),$item->getInstructions());
        }

        $html .= "</table>";

        $html .= "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse;border:1px solid black;padding:10px;width:100%'>";
        $html .= $this->getOrderTotals(9999.99,99.99,99.00,9999.99);
        $html .= "</table>";

        return $html;
    }

    private function getReservationInstructions() {
        $header = $this->getColumnHeader("Special Instructions",1);

        $instructions = $this->reservation->getInstructions() ;
        if (empty($instructions))
            $instructions = 'N/A';

        $html = '';
        $html .= "<table style='border-collapse:collapse;padding:10px;width:100%'>";
        $html .= $header;
        $html .= "<tr>";
        $html .= "<td style='padding:2px 2px 2px 2px'>";
        $html .= $instructions;
        $html .= "</td>";

        $html .= "</tr>";

        $html .= "</table>";

        return $html;
    }

    public function render() {

        header("Content-Type: text/html");

        if (empty($this->reservation)) {
            displayInfoPage(ERROR_MESSAGE,'Invalid Reservation');
            /*echo
                "<div style='height: 100vh;position: relative;'>".
                "<div style='position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);width:35vw;box-shadow: 0px 0px 20px 5px rgba(0, 0, 0, .6);border: 1px solid black;'>" .
                "<p style='color:red;text-align:center'> </p>".
                "</div>".
                "</div>";
            */
            return;
        }


        $highlightSectionWidth = '35%';
        $mainSectionWidth = '65%';

        if($this->mode == 1)  { //Customer View
            $highlightSectionDisplay = 'table-cell';
        } else {
            $highlightSectionWidth = '0%';
            $mainSectionWidth = '100%';
            $highlightSectionDisplay = 'none';
        }

        $header = $this->getHeader();
        $reservationHighlights = $this->getReservationHighlights();
        $customerInfo = $this->getCustomerInfo();
        $reservationSummary = $this->getReservationSummary();
        $orderSummary = $this->getOrderSummary();
        $orderItems = $this->getOrderItems();
        $reservationInstructions = $this->getReservationInstructions();

        $backgroundColor = '';
        $color = '';

        $this->getBackgroundColor('rgba(255,193,7,.3)','black',$backgroundColor,$color);

        $smarty = new Smarty;
        //$smarty->caching = 0;
        $smarty->assign('backgroundColor', $backgroundColor);
        $smarty->assign('highlightSectionWidth', $highlightSectionWidth);
        $smarty->assign('highlightSectionDisplay', $highlightSectionDisplay);
        $smarty->assign('mainSectionWidth', $mainSectionWidth);
        $smarty->assign('header', $header);
        $smarty->assign('highlights', $reservationHighlights);
        $smarty->assign('customerInfo', $customerInfo);
        $smarty->assign('reservationSummary', $reservationSummary);
        $smarty->assign('orderSummary', $orderSummary);
        $smarty->assign('orderItems', $orderItems);
        $smarty->assign('instructions', $reservationInstructions);

        $smarty->display(TEMPLATE_PATH . '\orderview.tpl');
    }
}