<?php
/**
*This is a helper file that can be called using ajax, which returns data from the database
*/
require_once("Log.php");
require_once("Log_Database.php");

echo json_encode(Log_Database::get_logs($_GET["start_date"], $_GET["end_date"]));