<?php
class UserData
{
    public static $tablename = "user";
    
    public $id;
    public $comision;
    public $name;
    public $lastname;
    public $username;
    public $email;
    public $image;
    public $password;
    public $created_at;
    public $kind;
    public $status;
    public $stock_id;
    public $status_text;
    public $is_admin;

    public function getStock()
    {
        return StockData::getById($this->stock_id);
    }

    public function __construct()
    {
        $this->name = "";
        $this->lastname = "";
        $this->email = "";
        $this->image = "";
        $this->password = "";
        $this->created_at = "NOW()";
    }

    public function add()
    {
        $sql = "insert into user (comision,name,lastname,username,email,image,kind,stock_id,password,created_at) ";
        $sql .= "value ($this->comision,\"$this->name\",\"$this->lastname\",\"$this->username\",\"$this->email\",\"$this->image\",\"$this->kind\",$this->stock_id,\"$this->password\",$this->created_at)";
        Executor::doit($sql);
    }

    public static function delById($id)
    {
        $sql = "delete from " . self::$tablename . " where id=$id";
        Executor::doit($sql);
    }

    public function del()
    {
        $sql = "delete from " . self::$tablename . " where id=$this->id";
        Executor::doit($sql);
    }

    public function update()
    {
        $sql = "update " . self::$tablename . " set name=\"$this->name\",email=\"$this->email\",username=\"$this->username\",lastname=\"$this->lastname\",image=\"$this->image\",status=\"$this->status\",comision=$this->comision,stock_id=$this->stock_id where id=$this->id";
        Executor::doit($sql);
    }

    public function update_passwd()
    {
        $sql = "update " . self::$tablename . " set password=\"$this->password\" where id=$this->id";
        Executor::doit($sql);
    }

    public static function getById($id)
    {
        $sql = "select * from " . self::$tablename . " where id=$id";
        $query = Executor::doit($sql);
        return Model::one($query[0], 'UserData');
    }

    public static function getAll()
    {
        $sql = "select * from " . self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], 'UserData');
    }

    public static function getLike($q)
    {
        $sql = "select * from " . self::$tablename . " where name like '%$q%'";
        $query = Executor::doit($sql);
        return Model::many($query[0], 'UserData');
    }
}
