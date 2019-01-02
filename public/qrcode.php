<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 12/31/2018
 * Time: 6:35 PM
 */
    require_once "bootstrap.php";
?>
<?php

if (!empty($_GET['text']))
    $text = $_GET['text'];
else
    $text = 'error';

// set the barcode content and type
$barcodeobj = new TCPDF2DBarcode('http://www.tcpdf.org', 'QRCODE,H');

// output the barcode as PNG image
$barcodeobj->getBarcodePNG(6, 6, array(0,0,0));

?>
//============================================================+
// END OF FILE
//============================================================+
