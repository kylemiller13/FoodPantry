<?php
require_once("Database.php");
class Template implements JsonSerializable {
    private $template_id;
    private $template_name;
    private $template_content;
    private $template_type;

    private $template_subject;
    public function __construct($properties) {
        $this->template_id = $properties["id"];
        $this->template_content = $properties["message"];
        $this->template_name = $properties["name"];
        $this->template_type = $properties["notification_type"];
        $this->template_subject = $properties["subject"];

    }
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            "id" => $this->template_id,
            "message" => $this->template_content,
            "name" => $this->template_name,
            "notification_type" => $this->template_type,
            "subject" => $this->template_subject
        ];
    }

    public function getId() {
        return $this->template_id;
    }

    public function getName() {
        return $this->template_name;
    }

    public function getMessage() {
        return $this->template_content;
    }

    public function getType() {
        return $this->template_type;
    }

    public function getSubject() {
        return $this->template_subject;
    }
    
    public static function fetchTemplates() {
        return Database::get_all_templates();
    }
}