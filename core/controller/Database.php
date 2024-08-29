<?php

declare(strict_types=1);

class Database
{
    private string $user = "root";
    private string $pass = "1234";
    private string $host = "localhost";
    private string $ddbb = "inventiomax";

    public static ?Database $db = null;
    public static ?mysqli $con = null;

    public function connect(): mysqli
    {
        $con = new mysqli($this->host, $this->user, $this->pass, $this->ddbb);
        $con->query("set sql_mode='';");
        return $con;
    }

    public static function getCon(): mysqli
    {
        if (self::$con === null && self::$db === null) {
            self::$db = new Database();
            self::$con = self::$db->connect();
        }
        return self::$con;
    }
}
