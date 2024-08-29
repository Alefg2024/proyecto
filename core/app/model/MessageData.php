<?php
class MessageData
{
    public static $tablename = "message";
    
    public $id;
    public $code;
    public $message;
    public $user_from;
    public $user_to;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $is_read;
    public $is_deleted;
    public $is_archived;
    public $is_starred;
    public $is_important;
    public $is_draft;
    public $is_trashed;
    public $is_highlighted;
    public $is_notified;
    public $is_spam;
    public $is_group;
    public $concept;
    public $description;
    public $date_at;
    public $amount;
    public $category;

    public function __construct()
    {
        $this->created_at = "NOW()";
    }

    public function getFrom()
    {
        return UserData::getById($this->user_from);
    }
    public function getTo()
    {
        return UserData::getById($this->user_to);
    }

    public function add()
    {
        $sql = "insert into " . self::$tablename . " (code,message,user_from,user_to,created_at) ";
        $sql .= "value (\"$this->code\",\"$this->message\",\"$this->user_from\",\"$this->user_to\",$this->created_at)";
        Executor::doit($sql);
    }

    public function del()
    {
        $sql = "delete from " . self::$tablename . " where id=$this->id";
        Executor::doit($sql);
    }

    public static function delBy($k, $v)
    {
        $sql = "delete from " . self::$tablename . " where $k=\"$v\"";
        Executor::doit($sql);
    }

    public function update()
    {
        $sql = "update " . self::$tablename . " set concept=\"$this->concept\",description=\"$this->description\",date_at=\"$this->date_at\",amount=\"$this->amount\" where id=$this->id";
        Executor::doit($sql);
    }

    public function read()
    {
        $sql = "update " . self::$tablename . " set is_read=1 where id=$this->id";
        Executor::doit($sql);
    }

    public function updateById($k, $v)
    {
        $sql = "update " . self::$tablename . " set $k=\"$v\" where id=$this->id";
        Executor::doit($sql);
    }

    public static function sumByKind($k)
    {
        $sql = "select sum(amount) as s from " . self::$tablename . " where kind=\"$k\"";
        $query = Executor::doit($sql);
        return Model::one($query[0], 'MessageData');
    }

    public static function getAllByKind($k)
    {
        $sql = "select * from " . self::$tablename . " where kind=$k order by date_at desc";
        $query = Executor::doit($sql);
        return Model::many($query[0], 'MessageData');
    }

    public static function getAllByKindDate($d, $k)
    {
        $sql = "select * from " . self::$tablename . " where kind=$k and date_at=\"$d\" order by date_at desc";
        $query = Executor::doit($sql);
        return Model::many($query[0], 'MessageData');
    }

    public static function getSumByKindDate($d, $k)
    {
        $sql = "select sum(amount) as t from " . self::$tablename . " where kind=$k and date_at=\"$d\" order by date_at desc";
        $query = Executor::doit($sql);
        return Model::many($query[0], 'MessageData');
    }

    public static function getAllBy($k, $v)
    {
        $sql = "select * from " . self::$tablename . " where $k=\"$v\"";
        $query = Executor::doit($sql);
        return Model::many($query[0], 'MessageData');
    }

    public static function getLike($q)
    {
        $sql = "select * from " . self::$tablename . " where name like '%$q%'";
        $query = Executor::doit($sql);
        return Model::many($query[0], 'MessageData');
    }

    public static function getById($id)
    {
        $sql = "select * from ".self::$tablename." where id=$id";
        $query = Executor::doit($sql);
        return Model::one($query[0], 'MessageData');  
    }

    public static function getBy($k, $v)
    {
        $sql = "select * from ".self::$tablename." where $k=\"$v\"";
        $query = Executor::doit($sql);
        return Model::one($query[0], 'MessageData');  
    }

    public static function getAll()
    {
        $sql = "select * from ".self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], 'MessageData');  
    }

    public static function getInboxByUserId($user)
    {
        $sql = "select * from ".self::$tablename." where user_from=$user or user_to=$user group by code";
        $query = Executor::doit($sql);
        return Model::many($query[0], 'MessageData');  
    }

    public static function getUnreadedByUserId($user)
    {
        $sql = "select * from ".self::$tablename." where user_to=$user and is_read=0";
        $query = Executor::doit($sql);
        return Model::many($query[0], 'MessageData');  
    }
}
