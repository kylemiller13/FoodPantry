<?php

use Logic\Log;

require_once ("template.php");

class Database
{
    const DB_SERVER = "cisdbss.pcc.edu";
    const DB_DATABASE = "Wise_Horses";
    const DB_USER = "Wise_Horses";
    const DB_PASSWORD = "FallCis234A%1";

    const GET_ALL_EMAIL_USERS_SQL = "SELECT email_address FROM [User]";
    const INSERT_NOTIFICATION_SQL = "INSERT INTO Notification (sender_id, sent_at, number_of_subscribers, subject, body) VALUES (?, ?, ?, ?, ?)";

    //query with variable start date and end date
    const QUERY = <<<QUERY
    SELECT subject, [User].username, CONVERT(varchar, sent_at, 1) AS date, CONVERT(varchar, sent_at, 8) AS time, number_of_subscribers, body
    FROM Notification
    JOIN [User] ON Notification.sender_id=[User].id
    WHERE sent_at >= :start_date AND sent_at <= :end_date
    ORDER BY sent_at;
    
    QUERY;

    const QUERY_ALL = <<<QUERY
    SELECT subject, [User].username, CONVERT(varchar, sent_at, 1) AS date, CONVERT(varchar, sent_at, 8) AS time, number_of_subscribers, body
    FROM Notification
    JOIN [User] ON Notification.sender_id=[User].id
    WHERE sent_at >= :start_date AND sent_at <= CURRENT_TIMESTAMP
    ORDER BY sent_at;
    
    QUERY;

    private static $db = NULL;

    private static function connect()
    {
        if (empty(Database::$db)) {
            $connectionOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch rows as associative arrays
            ];
            Database::$db = new PDO(
                "sqlsrv:Server=" . Database::DB_SERVER . ";Database=" . Database::DB_DATABASE,
                Database::DB_USER,
                Database::DB_PASSWORD,
                $connectionOptions
            );
        }
    }

    public static function get_all_email_users()
    {
        Database::connect();
        $stmt = Database::$db->prepare(Database::GET_ALL_EMAIL_USERS_SQL);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function insert_notification($sender_id, $sent_at, $number_of_subscribers, $subject, $body)
    {
        Database::connect();
        $stmt = Database::$db->prepare(Database::INSERT_NOTIFICATION_SQL);
        //binds values to the placeholders in the prepared SQL statement
        $stmt->bindParam(1, $sender_id);
        $stmt->bindParam(2, $sent_at);
        $stmt->bindParam(3, $number_of_subscribers);
        $stmt->bindParam(4, $subject);
        $stmt->bindParam(5, $body);

        if ($stmt->execute()) {
            // Insert successful
            return true;
        } else {
            // Insert failed
            return false;
        }
    }

    // Template story methods

    public static function get_all_templates()
    {
        self::connect();

        $query = "SELECT * FROM EmailTemplate";
        $statement = self::$db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $templateList = [];

        foreach ($result as $template) {
            $templateList[] = new Template($template);
        }
        return $templateList;
    }

    public static function update_template($template_id, $template_name, $template_content, $template_type, $template_subject)
    {
        self::connect();

        $query = "UPDATE EmailTemplate SET name = :template_name, message = :template_content, notification_type = :template_type, subject = :template_subject WHERE id = :template_id";
        $statement = self::$db->prepare($query);
        $statement->bindValue(':template_id', $template_id);
        $statement->bindValue(':template_name', $template_name);
        $statement->bindValue(':template_content', $template_content);
        $statement->bindValue(':template_type', $template_type);
        $statement->bindValue(':template_subject', $template_subject);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function delete_template($template_id)
    {
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

    public static function create_template($template_name, $template_content, $template_type, $template_subject)
    {
        self::connect();

        $query = "INSERT INTO EmailTemplate (name, message, notification_type, subject) VALUES (:template_name, :template_content, :template_type, :template_subject)";
        $statement = self::$db->prepare($query);
        $statement->bindValue(':template_name', $template_name);
        $statement->bindValue(':template_content', $template_content);
        $statement->bindValue(':template_type', $template_type);
        $statement->bindValue(':template_subject', $template_subject);
        $statement->execute();

        $returnedQuery = "SELECT * FROM EmailTemplate WHERE id = SELECT SCOPE_IDENTITY();";
        $returnedStatement = self::$db->prepare($returnedQuery);
        $returnedStatement->bindValue(':template_id', self::$db->lastInsertId());
        $returnedStatement->execute();

        $result = $returnedStatement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    // User methods

    public static function createUser($username, $hashedPassword, $email, $role = 'subscriber'): bool
    {
        Database::connect();
        $conn = Database::$db;

        if ($conn === null) {
            return false;
        }

        try {
            $stmt = $conn->prepare("INSERT INTO [User] (username, hashed_password, email_address, role) VALUES (:username, :hashedPassword, :email, :role)");
            if ($stmt === false) {
                throw new Exception('Failed to prepare the statement');
            }

            return $stmt->execute([
                ':username' => $username,
                ':hashedPassword' => $hashedPassword,
                ':email' => $email,
                ':role' => $role
                // No need to manually insert 'created_at', SQL Server will set it automatically
            ]);
        } catch (Exception $e) {
            error_log('An error occurred: ' . $e->getMessage());
            return false;
        }
    }


    public static function getUserByUsernameOrEmail($username, $email)
    {
        Database::connect();
        $conn = Database::$db;

        if ($conn === null) {
            return null;
        }

        $stmt = $conn->prepare("SELECT * FROM [User] WHERE username = :username OR email_address = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Review Logs methods

    /**
     * get a list of logs from the database
     * @param $start_date get logs starting from this date
     * @param $end_date get logs no later than this date
     * @return a list of objects representing individual logs
     * TODO: turn the output into Log objects and put them in a list
     */
    public static function get_logs($start_date, $end_date) {
        Database::connect();

        if(!$start_date) {
            $start_date = '1970-01-01T23:45:00';
        }

        if ($end_date) {
            $stmt = Database::$db->prepare(Database::QUERY);
            $stmt->execute([
                ":start_date"=>$start_date,
                ":end_date"=>$end_date
            ]);
        } else {
            $stmt = Database::$db->prepare(Database::QUERY_ALL);
            $stmt->execute([
                ":start_date"=>$start_date
            ]);
        }

        $logs = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $logList = [];
        foreach ($logs as $log) {
            $logList[] = new Log($log);
        }
        return $logList;
    }


}

