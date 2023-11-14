<?php
class Database {
    const DB_HOST = "cisdbss.pcc.edu";
    const DB_USERNAME = "Wise_Horses";
    const DB_PASSWORD = "FallCis234A%1";
    const DB_NAME = "Wise_Horses";

    private static $db = NULL;

    public static function connect() {
        if (self::$db == NULL) {
            self::$db = new PDO("sqlsrv:Server=" . self::DB_HOST . ";Database=" . self::DB_NAME, self::DB_USERNAME, self::DB_PASSWORD);
        }
    }

    public static function get_all_templates() {
        self::connect();

        $query = "SELECT * FROM EmailTemplate";
        $statement = self::$db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function update_template($template_id, $template_name, $template_content, $template_type) {
        self::connect();

        $query = "UPDATE EmailTemplate SET name = :template_name, message = :template_content, notification_type = :template_type WHERE id = :template_id";
        $statement = self::$db->prepare($query);
        $statement->bindValue(':template_id', $template_id);
        $statement->bindValue(':template_name', $template_name);
        $statement->bindValue(':template_content', $template_content);
        $statement->bindValue(':template_type', $template_type);
        $statement->execute();

        $returnedQuery = "SELECT * FROM EmailTemplate WHERE id = :template_id";
        $returnedStatement = self::$db->prepare($returnedQuery);
        $returnedStatement->bindValue(':template_id', $template_id);
        $returnedStatement->execute();

        $result = $returnedStatement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function delete_template($template_id) {
        self::connect();

        $query = "DELETE FROM EmailTemplate WHERE id = :template_id";
        $statement = self::$db->prepare($query);
        $statement->bindValue(':template_id', $template_id);
        $statement->execute();

        
        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function create_template($template_name, $template_content, $template_type) {
        self::connect();
        $getCountQuery = "SELECT COUNT(*) FROM EmailTemplate";
        $getCountStatement = self::$db->prepare($getCountQuery);
        $getCountStatement->execute();

        $count = $getCountStatement->fetchColumn();
    
        $query = "INSERT INTO EmailTemplate (id, name, message, notification_type) VALUES ($count + 1, :template_name, :template_content, :template_type)";
        $statement = self::$db->prepare($query);
        $statement->bindValue(':template_name', $template_name);
        $statement->bindValue(':template_content', $template_content);
        $statement->bindValue(':template_type', $template_type);
        $statement->execute();

        $returnedQuery = "SELECT * FROM EmailTemplate WHERE id = :template_id";
        $returnedStatement = self::$db->prepare($returnedQuery);
        $returnedStatement->bindValue(':template_id', self::$db->lastInsertId());
        $returnedStatement->execute();

        $result = $returnedStatement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


}


