
<?php

/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/18/2018
 * Time: 3:50 PM
 */

require_once "bootstrap.php";

require_once "header-html.php";


?>

<h2 style="text-align: center;">Bootbox Dialog Test</h2>


<div style="text-align: center" >
<button type="button" onclick="showDialog()">Show Dialog </button>
</div>
<script>

function showDialog(e) {


    var popup = bootbox.dialog({
        title: 'Submitting Order',
        closeButton:true,
        message: '<p><i class="fa fa-spin fa-spinner"></i>Placing Order</p>'
    });



    popup.init(function(){
        var msg = 'Sending order to Restaurant';

        popup.find('.bootbox-body').html('<p><i class="fa fa-spin fa-spinner"></i>' + msg +'</p>');

    });
}


</script>

<?php
require_once "footer-html.php";

?>