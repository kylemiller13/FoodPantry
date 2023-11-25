<?php
/**
*This is a helper file that can be called using ajax, which returns data from the database
*/
require_once("../Logic/Log.php");
require_once("../Data/Database.php");

use Logic\Log;

echo json_encode(Log::get_logs($_GET["start_date"], $_GET["end_date"]));