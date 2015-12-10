<?php
namespace libs;

use mysqli;
use mysqli_result;

class Database {
    protected $connection;

    /**
     * @var DataBase
     */
    static $instance = null;

    /**
     * @return DateBase
     */
    public static function getInstance() {
        if(static::$instance == NULL) {
            static::$instance = new Database();
        }
        return static::$instance;
    }

    protected function __construct() {

		$servername = 'localhost';
        $username = 'Mercixsql1';
        $password = 'oaxZXhmvgi';
        $dbname = 'Mercixsql1';

        // Create connection
        $this->connection = new mysqli($servername, $username, $password);
        $this->connection->select_db($dbname);
    }

    public function escapeSqlString( $string ) {
        return $this->connection->escape_string($string);
    }

    public function getSingleResult($sql) {
        $sqlResult = false;
        $result = $this->connection->query($sql);
        if ($result instanceof mysqli_result) {
            $row = '';
            while ( !is_null($row) ) {
               $row = $result->fetch_row();
               if ($row != NULL) {
                   $sqlResult = $row[0];
               }
            }
        }
        return $sqlResult;
    }

    public function getResult($sql) {
        $sqlResultArray = array();
        $result = $this->connection->query($sql);
        if ($result instanceof mysqli_result) {
            $row = '';
            while ( !is_null($row) ) {
               $row = $result->fetch_assoc();
               if ($row != NULL) {
                   $sqlResultArray[] = $row;
               }
            }
        } else if($result) {
            return true;
        }
        return $sqlResultArray;
    }
}

