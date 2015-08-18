<?php
/**
 * @author Dennis Jandt
 */
class Database {
    protected $connection;

    public function __construct() {
        // Database connection
        $servername = 'localhost';
        $username = 'root';
        $password = 'adsa4d564d1as65';
        $dbname = 'homepage';

        // Create connection
        $this->connection = new mysqli($servername, $username, $password);
        $this->connection->select_db($dbname);
    }

    public function getResult($sql) {
        $this->connection->begin_transaction();
        $result = $this->connection->query($sql);

        if ($result instanceof mysqli_result) {
            $row = '';
            while ($row != NULL) {
               $row = $result->fetch_row();
               if ($row != NULL) {
                   $sqlResultArray[] = $row;
               }
            }
        }
        return $sqlResultArray;
    }
}

