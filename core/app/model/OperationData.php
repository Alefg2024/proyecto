<?php
class OperationData
{
	public static $tablename = "operation";

	public $id;
	public $name;
	public $product_id;
	public $q;
	public $cut_id;
	public $status;
	public $operation_type_id;
	public $operation_from_id;
	public $created_at;
	public $is_traspase;
	public $is_oficial;
	public $price_in;
	public $price_out;
	public $stock_id;
	public $sell_id;
	public $query;
	public $operation_to_id;
	public $operation_to_name;
	public $operation_to_type;
	public $operation_to_product_id;
	public $operation_to_product_name;
	public $operation_to_product_type;
	public $operation_to_product_price;
	public $operation_to_product_price_total;
	public $operation_to_product_price_total_total;

	public function __construct()
	{
		$this->name = "";
		$this->product_id = "";
		$this->q = "";
		$this->cut_id = "";
		$this->status = "1";
		$this->operation_type_id = "";
		$this->operation_from_id = "NULL";
		$this->created_at = "NOW()";
		$this->is_traspase = 0;
	}

	public function add()
	{
		$sql = "insert into " . self::$tablename . " (is_traspase,status, price_in,price_out,stock_id,product_id,q,operation_type_id,sell_id,operation_from_id,created_at) ";
		$sql .= "value ($this->is_traspase, $this->status, $this->price_in,$this->price_out,$this->stock_id,\"$this->product_id\",\"$this->q\",$this->operation_type_id,$this->sell_id,$this->operation_from_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public function add_cotization()
	{
		$sql = "insert into " . self::$tablename . " (price_in,price_out,is_draft,stock_id,product_id,q,operation_type_id,sell_id,created_at) ";
		$sql .= "value ($this->price_in,$this->price_out,1,$this->stock_id,\"$this->product_id\",\"$this->q\",$this->operation_type_id,$this->sell_id,$this->created_at)";
		return Executor::doit($sql);
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
		$sql = "update " . self::$tablename . " set product_id=\"$this->product_id\",q=\"$this->q\" where id=$this->id";
		Executor::doit($sql);
	}

	public function set_draft($d)
	{
		$sql = "update " . self::$tablename . " set is_draft=\"$d\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_q()
	{
		$sql = "update " . self::$tablename . " set q=\"$this->q\" where id=$this->id";
		Executor::doit($sql);
	}

	public function cancel()
	{
		$sql = "update " . self::$tablename . " set status=0 where id=$this->id";
		Executor::doit($sql);
	}

	public function uncancel()
	{
		$sql = "update " . self::$tablename . " set status=1 where id=$this->id";
		Executor::doit($sql);
	}

	public function update_type()
	{
		$sql = "update " . self::$tablename . " set operation_type_id=\"$this->operation_type_id\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_status()
	{
		$sql = "update " . self::$tablename . " set status=$this->status where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "select * from " . self::$tablename . " where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0], 'OperationData');
	}

	public static function getAll()
	{
		$sql = "select * from " . self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getAllByOT($p, $ot, $s)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$p and operation_type_id=$ot and status=1 and stock_id=$s";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getAllBySQL($sqlextra)
	{
		$sql = "select * from " . self::$tablename . " $sqlextra";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getAllByDateOfficial($stock, $start, $end)
	{
		$sql = "select * from " . self::$tablename . " where (date(created_at) >= \"$start\" and date(created_at) <= \"$end\") and stock_id=$stock order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getPPByDateOfficial($start, $end)
	{
		$sql = "select *,sum(q) as total from " . self::$tablename . " where (date(created_at) >= \"$start\" and date(created_at) <= \"$end\") and operation_type_id=2 group by product_id order by total desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getAllByDateOfficialBP($stock, $product, $start, $end)
	{
		$sql = "select * from " . self::$tablename . " where (date(created_at) >= \"$start\" and date(created_at) <= \"$end\") and product_id=$product and stock_id=$stock order by created_at desc";
		if ($start == $end) {
			$sql = "select * from " . self::$tablename . " where date(created_at) = \"$start\" order by created_at desc";
		}
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public function getProduct()
	{
		return ProductData::getById($this->product_id);
	}
	public function getOperationtype()
	{
		return OperationTypeData::getById($this->operation_type_id);
	}

	public static function getQ($product_id)
	{
		$q = 0;
		$operations = self::getAllByProductId($product_id);
		$input_id = OperationTypeData::getByName("Entrada")->id;
		$output_id = OperationTypeData::getByName("Salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		return $q;
	}

	public static function getQByStock($product_id, $stock_id)
	{
		$q = 0;
		$operations = self::getAllByProductIdAndStock($product_id, $stock_id);
		$input_id = OperationTypeData::getByName("Entrada")->id;
		$output_id = OperationTypeData::getByName("Salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		return $q;
	}

	public static function getRByStock($product_id, $stock_id)
	{
		$q = 0;
		$operations = self::getAllByProductIdAndStock($product_id, $stock_id);
		$input_id = OperationTypeData::getByName("Entrada-pendiente")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			}
		}
		return $q;
	}

	public static function getDByStock($product_id, $stock_id)
	{
		$q = 0;
		$operations = self::getAllByProductIdAndStock($product_id, $stock_id);
		$input_id = OperationTypeData::getByName("Salida-pendiente")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			}
		}
		return $q;
	}

	public static function getAllByProductIdCutId($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getAllByProductId($product_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getAllByProductIdAndStock($product_id, $stock_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and stock_id=$stock_id and is_draft=0 and status=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getAllByProductIdCutIdOficial($product_id, $cut_id)
	{
		if ($product_id === null || $cut_id === null) {
			return array();
		}
		$sql = "select * from " . self::$tablename . " where product_id=? and cut_id=? order by created_at desc";
		$query = Executor::doit($sql, array($product_id, $cut_id));
		return Model::many($query[0], 'OperationData');
	}

	public static function getAllByProductIdCutIdYesF($product_id, $cut_id)
	{
		if ($product_id === null || $cut_id === null) {
			return array();
		}
		$sql = "select * from " . self::$tablename . " where product_id=? and cut_id=? order by created_at desc";
		$query = Executor::doit($sql, array($product_id, $cut_id));
		return Model::many($query[0], 'OperationData');
	}

	public static function getAllProductsBySellId($sell_id)
	{
		$sql = "select * from " . self::$tablename . " where sell_id=$sell_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}
	public static function getOutputQ($product_id, $cut_id)
	{
		$q = 0;
		$operations = self::getOutputByProductIdCutId($product_id, $cut_id);
		$input_id = OperationTypeData::getByName("Entrada")->id;
		$output_id = OperationTypeData::getByName("Salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		return $q;
	}

	public static function getOutputQYesF($product_id)
	{
		$q = 0;
		$operations = self::getOutputByProductId($product_id);
		$input_id = OperationTypeData::getByName("Entrada")->id;
		$output_id = OperationTypeData::getByName("Salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		return $q;
	}

	public static function getInputQByStock($product_id, $stock_id)
	{
		$q = 0;
		$operations = self::getInputByProductIdAndStock($product_id, $stock_id);
		$input_id = OperationTypeData::getByName("Entrada")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			}
		}
		return $q;
	}

	public static function getOutputByProductIdCutId($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getOutputByProductId($product_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getInputQ($product_id, $cut_id)
	{
		$q = 0;
		$operations = self::getInputByProductId($product_id);
		$input_id = OperationTypeData::getByName("Entrada")->id;
		$output_id = OperationTypeData::getByName("Salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		return $q;
	}

	public static function getInputByProductIdCutId($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getInputByProductId($product_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getInputByProductIdAndStock($product_id, $stock_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and operation_type_id=1 and stock_id=$stock_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getInputByProductIdCutIdYesF($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], 'OperationData');
	}

	public static function getQNoF($product_id, $cut_id)
	{
		$q = 0;
		$operations = self::getAllByProductIdCutId($product_id, $cut_id);
		$input_id = OperationTypeData::getByName("Entrada")->id;
		$output_id = OperationTypeData::getByName("Salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q -= $operation->q;
			}
		}
		return $q;
	}

	public static function getQYesF($product_id, $cut_id)
	{
		$q = 0;
		$operations = self::getAllByProductIdCutIdOficial($product_id, $cut_id);
		$input_id = OperationTypeData::getByName("Entrada")->id;
		$output_id = OperationTypeData::getByName("Salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id && $operation->is_oficial == 1) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id && $operation->is_oficial == 1) {
				$q -= $operation->q;
			}
		}
		return $q;
	}
}
