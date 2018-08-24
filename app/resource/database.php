<?php
$userName = DB_USER;
$password = DB_PASSWORD;
$host = DB_HOST;
$dbName = DB_NAME;
$dsn = "mysql:host=$host;dbname=$dbName";

$db = null;
try {
    $db = new PDO($dsn,$userName,$password);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES , false);

} catch (PDOException $ex) {
    echo "Connection to database failed:" . $ex->getMessage();
}



