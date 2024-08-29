<?php

if (isset($_SESSION["cotization"])) {
	$cart = $_SESSION["cotization"];
	if (count($cart) > 0) {
		$num_succ = 0;
		$process = true;

		if ($process == true) {
			$sell = new SellData();
			if (isset($_SESSION["user_id"])) {
				$sell->user_id = $_SESSION["user_id"];
				$s = $sell->add_cotization();
			} else if (isset($_SESSION["client_id"])) {
				$sell->person_id = $_SESSION["client_id"];
				$s = $sell->add_cotization_by_client();
			}

			foreach ($cart as  $c) {
				$operation_type = "salida";
				if (isset($_POST["d_id"]) && $_POST["d_id"] == 2) {
					$operation_type = "salida-pendiente";
				}

				$product = ProductData::getById($c["product_id"]);
				$op = new OperationData();
				$op->product_id = $c["product_id"];
				$op->price_in = $product->price_in;
				$op->price_out = $product->price_out;
				$op->operation_type_id = OperationTypeData::getByName($operation_type)->id;
				$op->stock_id = StockData::getPrincipal()->id;
				$op->sell_id = $s[1];
				$op->q = $c["q"];

				$add = $op->add_cotization();

				unset($_SESSION["cotization"]);
				setcookie("selled", "selled");
			}
			print "<script>window.location='index.php?view=onecotization&id=$s[1]';</script>";
		}
	}
}
