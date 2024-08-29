<?php
class PriceData
{
	public static $tablename = "price";

	public $id;
	public $price_out;
	public $product_id;
	public $stock_id;
	public $is_principal;
	public $created_at;
	public $updated_at;
	public $name;
	public $lastname;
	public $email;
	public $image;
	public $password;
	public $price;

	public function PriceData()
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
		$sql = "insert into price (price_out,product_id,stock_id) ";
		$sql .= "value (\"$this->price_out\",\"$this->product_id\",\"$this->stock_id\")";
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
		$sql = "update " . self::$tablename . " set price=\"$this->price\" where id=$this->id";
		Executor::doit($sql);
	}


	public static function getById($id)
	{
		$sql = "select * from " . self::$tablename . " where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0], 'PriceData');
	}

	public static function getByPS($product, $stock)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product and stock_id=$stock";
		$query = Executor::doit($sql);
		return Model::one($query[0], 'PriceData');
	}


	public static function getAll()
	{
		$sql = "select * from " . self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0], 'PriceData');
	}


	public static function getLike($q)
	{
		$sql = "select * from " . self::$tablename . " where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'PriceData');
	}
}
