<?php
$dbHost     = "localhost";
$dbName     = "aig-user";
$dbUser     = "root";
$dbPassword = "";

$iDbCon = mysqli_connect($dbHost,$dbUser,$dbPassword,$dbName);
mysqli_set_charset($iDbCon, 'utf8');