<?php
require_once('headers.php');
// Specify which request methods are allowed
require_once('Database.php');


echo json_encode(Database::get_all_templates());