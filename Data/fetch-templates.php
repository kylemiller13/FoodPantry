<?php
require_once('headers.php');
// Specify which request methods are allowed
require_once('db-connection.php');


echo json_encode(Database::get_all_templates());