<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/28/2018
 * Time: 8:04 PM
 */

require_once dirname(__FILE__) . "/../bootstrap.php";
require_once RESOURCE_PATH . "/ReserveOccasionDAO.class.php";

header("Content-Type: application/json");

$reserveOccasionDAO = new ReserveOccasionDAO();

$occasions  = $reserveOccasionDAO->getAllReserveOccasions($db);

echo json_encode($occasions, JSON_PRETTY_PRINT);