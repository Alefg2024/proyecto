<?php

if ($_POST["q"] != "" || $_POST["q"] != "0") {

	$product = ProductData::getById($_POST["product_id"]);
	$op = new OperationData();
	$op->price_in = $product->price_in;
	$op->price_out = $product->price_out;
	$op->stock_id = $_POST["stock"];
	$op->product_id = $_POST["product_id"];
	$op->operation_type_id = 1;
	$op->sell_id = "NULL";
	$op->q = $_POST["q"];

	$add = $op->add();

	Core::redir("./?view=inventary&stock=" . $_POST["stock"]);
}
