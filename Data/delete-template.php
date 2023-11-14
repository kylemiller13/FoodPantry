<?php
require_once('headers.php');
require_once('db-connection.php');

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

if ($data !== null) {
    
    $template_id = $data['template_id'];

    Database::delete_template($template_id);

    echo "success";
} else {
    http_response_code(400);
    echo "Invalid JSON data";
}
?>

