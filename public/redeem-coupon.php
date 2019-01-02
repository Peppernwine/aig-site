<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 12/31/2018
 * Time: 6:35 PM
 */

$title = "Referral Program";
$security = ['minUserType' => 1 ];
require_once "bootstrap.php";
require_once RESOURCE_PATH . "/validate-signin.php";
require_once RESOURCE_PATH . "/CSRFTokenGenerator.class.php";
//require_once "header-html.php";
?>
<html>
<head>


</head>
<body style="background:red">

<p>TEST TEST</p>

<?php

// include 2D barcode class (search for installation path)
//require_once(dirname(__FILE__).'/tcpdf_barcodes_2d_include.php');

// set the barcode content and type
$barcodeobj = new TCPDF2DBarcode('http://www.tcpdf.org', 'QRCODE,H');

// output the barcode as PNG image
$barcodeobj->getBarcodePNG(6, 6, array(0,0,0));
?>

</body>
</html>
//============================================================+
// END OF FILE
//============================================================+
