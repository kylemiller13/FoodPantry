<?php
/**
* This class exists to handle requests from the database specifically
* for the check logs functionality
*/
require_once("Log.php");
class Log_Database
{
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
    
    //variable to hold the database object/connection
    private static $db = NULL;
    
    /**
    * set the connection to the database
    */
    private static function connect () {
        if(empty(Log_Database::$db)) {
            Log_Database::$db = new PDO("sqlsrv:Server=cisdbss.pcc.edu;Database=Wise_Horses", "Wise_Horses", "FallCis234A%1");
        }
    }
    
    /**
    * get a list of logs from the database
    * @param $start_date get logs starting from this date
    * @param $end_date get logs no later than this date
    * @return a list of objects representing individual logs
    * TODO: turn the output into Log objects and put them in a list
    */
    public static function get_logs($start_date, $end_date) {
        Log_Database::connect();
        
        if(!$start_date) {
            $start_date = '1970-01-01T23:45:00';
        }
        
        if ($end_date) {
            $stmt = Log_Database::$db->prepare(Log_Database::QUERY);
            $stmt->execute([
                ":start_date"=>$start_date,
                ":end_date"=>$end_date
            ]);
        } else {
            $stmt = Log_Database::$db->prepare(Log_Database::QUERY_ALL);
            $stmt->execute([
                ":start_date"=>$start_date
            ]);
        };
        
        $logs = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $logList = [];
        foreach ($logs as $log) {
            $logList[] = new Log($log); 
        }
        return $logList;
    }
    
    
}

//test

/**
$logs = Log_Database::get_logs("1/1/1980", null);

//print_r($logs);

foreach ($logs as $log) {
    print("<br>");
    print($log->get_subject());
}
*/