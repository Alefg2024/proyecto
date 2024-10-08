<?php
class BrandData
{
    public static $tablename = "brand";

    public $id;
    public $name;
    public $lastname;
    public $email;
    public $image;
    public $password;
    public $created_at;

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
        $sql = "insert into brand (name,created_at) ";
        $sql .= "value (\"$this->name\",$this->created_at)";
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
        $sql = "update " . self::$tablename . " set name=\"$this->name\" where id=$this->id";
        Executor::doit($sql);
    }

    public static function getById($id)
    {
        $sql = "select * from " . self::$tablename . " where id=$id";
        $query = Executor::doit($sql);
        return Model::one($query[0], 'BrandData');
    }

    public static function getAll()
    {
        $sql = "select * from " . self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], 'BrandData');
    }

    public static function getLike($q)
    {
        $sql = "select * from " . self::$tablename . " where name like '%$q%'";
        $query = Executor::doit($sql);
        return Model::many($query[0], 'BrandData');
    }
}
