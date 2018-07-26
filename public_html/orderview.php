<?php
$title = "View Order";
require_once "bootstrap.php";
require_once RESOURCE_PATH . "/OrderViewController.class.php";
?>


<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/20/2018
 * Time: 10:43 PM
 */

require_once RESOURCE_PATH . "/OrderViewController.class.php";

$orderId = -1;
if (!empty($_GET['orderId']))
    $orderId = intval($_GET['orderId']);

$mode = OrderViewController::CUSTOMER_VIEW;
if (!empty($_GET['mode']))
    $mode = intval($_GET['mode']);

$orderViewController = new OrderViewController($db,$orderId);

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--    <meta name="viewport" content="width=device-width,initial-scale=2"> -->
</head>
<body style="margin: 0; padding: 0;">
<?php $orderViewController->render($mode); ?>
</body>
</html>
