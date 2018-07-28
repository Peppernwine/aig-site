<?php

require_once "bootstrap.php";
require_once RESOURCE_PATH . "/SendFax.class.php";

(new SendFax())->sendToRestaurant("C:/order.pdf");

echo "Fax sent to Restaurant";