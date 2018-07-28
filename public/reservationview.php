<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/20/2018
 * Time: 10:43 PM
 */

require_once "bootstrap.php";
require_once RESOURCE_PATH."/OrderDAO.class.php";
require_once RESOURCE_PATH."/OrderHeader.class.php";
require_once RESOURCE_PATH."/ReservationDAO.class.php";
require_once RESOURCE_PATH."/Reservation.class.php";
require_once RESOURCE_PATH."/ReservationView.class.php";
require_once RESOURCE_PATH."/ReserveOccasion.class.php";
require_once RESOURCE_PATH."/ReserveOccasionDAO.class.php";
require_once RESOURCE_PATH . "/ReservationViewController.class.php";

$reservationId = -1;
if (!empty($_GET['reservationId']))
    $reservationId = intval($_GET['reservationId']);


if (empty($reservationId))
    $reservationId = -1;

$mode = ReservationViewController::CUSTOMER_VIEW;
if (!empty($_GET['mode']))
    $mode = intval($_GET['mode']);

$reservationViewController = new ReservationViewController($db,$reservationId);
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--    <meta name="viewport" content="width=device-width,initial-scale=2"> -->
</head>
<body style="margin: 0; padding: 0;">
<?php $reservationViewController->render($mode) ?>
</body>
</html>
