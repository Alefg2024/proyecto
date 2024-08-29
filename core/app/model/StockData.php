<?php
class StockData
{
    public static $tablename = "stock";

    public $id;
    public $name;
    public $address;
    public $phone;
    public $email;
    public $image;
    public $password;
    public $created_at;
    public $is_principal;
    public $lastname;
    public $is_oficial;

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
        $sql = "insert into stock (name,address,phone,email) ";
        $sql .= "value (\"$this->name\",\"$this->address\",\"$this->phone\",\"$this->email\")";
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

    public static function unset_principal()
    {
        $sql = "update " . self::$tablename . " set is_principal=0";
        Executor::doit($sql);
    }

    public static function set_principal($id)
    {
        $sql = "update " . self::$tablename . " set is_principal=1 where id=$id";
        Executor::doit($sql);
    }

    public function update()
    {
        $sql = "update " . self::$tablename . " set name=\"$this->name\",address=\"$this->address\",phone=\"$this->phone\",email=\"$this->email\" where id=$this->id";
        Executor::doit($sql);
    }

    public static function getById($id)
    {
        $sql = "select * from " . self::$tablename . " where id=$id";
        $query = Executor::doit($sql);
        return Model::one($query[0], 'StockData');
    }

    public static function getPrincipal()
    {
        if (Core::$user->kind == 2 || Core::$user->kind == 3) {
            $sql = "select * from " . self::$tablename . " where id=" . Core::$user->stock_id;
            $query = Executor::doit($sql);
            return Model::one($query[0], 'StockData');
        } else {
            $sql = "select * from " . self::$tablename . " where is_principal=1";
            $query = Executor::doit($sql);
            return Model::one($query[0], 'StockData');
        }
    }

    public static function getAll()
    {
        $sql = "select * from " . self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], 'StockData');
    }

    public static function getLike($q)
    {
        $sql = "select * from " . self::$tablename . " where name like '%$q%'";
        $query = Executor::doit($sql);
        return Model::many($query[0], 'StockData');
    }
}
