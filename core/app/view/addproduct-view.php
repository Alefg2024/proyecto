<?php

use Verot\Upload\Upload;

if (count($_POST) > 0) {
  $product = new ProductData();
  $product->kind = $_POST["kind"];
  $product->code = $_POST["code"];
  $product->barcode = $_POST["barcode"];
  $product->name = $_POST["name"];
  $product->price_in = $_POST["price_in"];
  $product->price_out = $_POST["price_out"];
  $product->unit = $_POST["unit"];
  $product->description = $_POST["description"];
  $product->presentation = $_POST["presentation"];
  $product->width = $_POST["width"];
  $product->height = $_POST["height"];
  $product->weight = $_POST["weight"];
  $product->expire_at = $_POST["expire_at"];
  $product->brand_id = $_POST["brand_id"] != "" ? $_POST["brand_id"] : "NULL";
  $product->category_id = $_POST["category_id"] != "" ? $_POST["category_id"] : "NULL";
  $product->inventary_min = $_POST["inventary_min"] != "" ? $_POST["inventary_min"] : "10";
  $product->user_id = $_SESSION["user_id"];

  if (isset($_FILES["image"])) {
    $image = new Upload($_FILES["image"]);
    if ($image->uploaded) {
      $image->Process("storage/products/");
      if ($image->processed) {
        $product->image = $image->file_dst_name;
      }
    }
  }
  $prod = $product->add();
  if ($_POST["kind"] == "1") {
    if ($_POST["q"] != "" || $_POST["q"] > "0") {

      $y = new YYData();
      $yy = $y->add();
      $sell = new SellData();
      $sell->ref_id = $yy[1];
      $sell->user_id = $_SESSION["user_id"];
      $sell->invoice_code = "";
      $sell->p_id = 1;
      $sell->d_id = 1;
      $sell->f_id = 1;
      $sell->total = $_POST["q"] * $_POST["price_in"];
      $sell->stock_to_id = StockData::getPrincipal()->id;
      $sell->person_id = "NULL";
      $s = $sell->add_re();
      $op = new OperationData();
      $op->sell_id = $s[1];
      $op->product_id = $prod[1];
      $op->stock_id = StockData::getPrincipal()->id;
      $op->operation_type_id = OperationTypeData::getByName("entrada")->id;
      $op->price_in = $_POST["price_in"];
      $op->price_out = $_POST["price_out"];
      $op->q = $_POST["q"];
      $op->is_oficial = 1;
      $op->add();
    }
  }
  print "<script>window.location='index.php?view=products';</script>";
}
