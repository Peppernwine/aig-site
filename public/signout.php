<?php
include_once "bootstrap.php";
include_once RESOURCE_PATH . "/database.php";
include_once RESOURCE_PATH . "/session.php";
include_once RESOURCE_PATH . "/signout-helper.php";

signOut($db);