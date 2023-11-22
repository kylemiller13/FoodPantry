<?php



class Database_Connection
{
  const DB_SERVER = "cisdbss.pcc.edu";
  const DB_DATABASE = "Wise_Horses";
  const DB_USER = "Wise_Horses";
  const DB_PASSWORD = "FallCis234A%1";

  const GET_ALL_EMAIL_USERS_SQL = "SELECT email_address FROM [User]";
  const INSERT_NOTIFICATION_SQL = "INSERT INTO Notification (sender_id, sent_at, number_of_subscribers, subject, body) VALUES (?, ?, ?, ?, ?)";

  private static $db = NULL;
  private static function connect(){
    if(empty(Database_Connection::$db)){
        Database_Connection::$db = new PDO(
        "sqlsrv:Server=" . Database_Connection::DB_SERVER . ";Database=" . Database_Connection::DB_DATABASE,
            Database_Connection::DB_USER,
            Database_Connection::DB_PASSWORD
      );
    }
  }

  public static function get_all_email_users() {
      Database_Connection::connect();
    $stmt = Database_Connection::$db->prepare(Database_Connection::GET_ALL_EMAIL_USERS_SQL);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public static function insert_notification($sender_id, $sent_at, $number_of_subscribers, $subject, $body) {
      Database_Connection::connect();
    $stmt = Database_Connection::$db->prepare(Database_Connection::INSERT_NOTIFICATION_SQL);
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

}

