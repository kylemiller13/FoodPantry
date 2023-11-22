<?php
require_once('headers.php');
require_once('Database.php');


$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

if ($data !== null) {
    
    $template_name = $data['template_name'];
    $template_content = $data['template_content'];
    $template_type = $data['template_type'];
    

    Database::create_template($template_name, $template_content, $template_type);

    echo "success";
} else {
    http_response_code(400);
    echo "Invalid JSON data";
}
?>

