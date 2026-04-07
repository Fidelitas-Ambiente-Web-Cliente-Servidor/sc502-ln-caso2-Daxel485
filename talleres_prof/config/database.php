<?php

class Database
{

    private $host = "localhost";
    private $db = "appdb";
    private $user = "root";
    private $pass = "";

    public function connect()
    {

        $conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );

        if ($conn->connect_error) {
            die("Error conexión: " . $conn->connect_error);
        }

        return $conn;
    }
}
