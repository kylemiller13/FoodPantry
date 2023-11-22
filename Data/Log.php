<?php
/**
* Class to represent individual Notification logs pulled from the database
*/
class Log implements JsonSerializable
{
    private $subject;
    private $username;
    private $date_sent;
    private $time_sent;
    private $num_subs;
    private $body;
    
    public function __construct($log) {
        $this->subject = $log["subject"];
        $this->username = $log["username"];
        $this->date_sent = $log["date"];
        $this->time_sent = $log["time"];
        $this->num_subs = $log["number_of_subscribers"];
        $this->body = $log["body"];
    }
    
    public function get_subject() {
        return $this->subject;
    }
    
    public function get_username() {
        return $this->username;
    }
    
    public function get_date_sent() {
        return $this->date_sent;
    }
    
    public function get_time_sent() {
        return $this->time_sent;
    }
    
    public function get_num_subs() {
        return $this->num_subs;
    }
    
    public function get_body() {
        return $this->body;
    }
    
    public function jsonSerialize() {
        return [
            "subject" => $this->subject,
            "username" => $this->username,
            "date_sent" => $this->date_sent,
            "time_sent" => $this->time_sent,
            "num_subs" => $this->num_subs,
            "body" => $this->body
            
        ];
    }

    public static function get_logs($start_date, $end_date) {
        return Log_Database::get_logs($start_date, $end_date);
    }
}