<?php
include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";

include "core/app/model/UserData.php";
include "core/app/model/SellData.php";
include "core/app/model/OperationData.php";
include "core/app/model/ProductData.php";
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";

include "fpdf/fpdf.php";

session_start();
if (isset($_SESSION["user_id"])) {
    Core::$user = UserData::getById((int)$_SESSION["user_id"]);
}

$currency = ConfigurationData::getByPreffix("currency")->val ?? '$';
$title = ConfigurationData::getByPreffix("ticket_title")->val ?? 'Ticket';

$sell = SellData::getById((int)$_GET["id"]);
if (!$sell) {
    die("Venta no encontrada");
}

$stock = $sell->getStockTo();
$operations = OperationData::getAllProductsBySellId((int)$_GET["id"]);
$user = $sell->getUser();

$pdf = new FPDF($orientation = 'P', $unit = 'mm', array(45, 350));
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 8);

$pdf->setY(2);
$pdf->setX(2);
$pdf->Cell(5, 5, strtoupper($title));

$pdf->SetFont('Arial', 'B', 5);
$pdf->setX(2);
$pdf->Cell(5, 11, strtoupper($stock->address ?? 'Dirección no disponible'));
$pdf->setX(2);
$pdf->Cell(5, 17, "TEL. " . strtoupper($stock->phone ?? 'Teléfono no disponible'));
$pdf->setX(2);
$pdf->Cell(5, 23, '-------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5, 29, 'CANT.  ARTICULO       PRECIO               TOTAL');

$total = 0;
$off = 35;
foreach ($operations as $op) {
    $product = $op->getProduct();
    if ($product) {
        $pdf->setX(2);
        $pdf->Cell(5, $off, $op->q);
        $pdf->setX(6);
        $pdf->Cell(35, $off, strtoupper(substr($product->name ?? '', 0, 12)));

        $price_in = is_numeric($product->price_in) ? (float)$product->price_in : 0;
        $quantity = is_numeric($op->q) ? (float)$op->q : 0;

        $pdf->setX(20);
        $pdf->Cell(11, $off, $currency . " " . number_format($price_in, 2, ".", ","), 0, 0, "R");
        $pdf->setX(32);
        $pdf->Cell(11, $off, $currency . " " . number_format($price_in * $quantity, 2, ".", ","), 0, 0, "R");

        $total += $price_in * $quantity;
        $off += 6;
    }
}

$pdf->setX(2);
$pdf->Cell(5, $off + 18, "TOTAL: ");
$pdf->setX(38);
$pdf->Cell(5, $off + 18, $currency . " " . number_format($total, 2, ".", ","), 0, 0, "R");

$pdf->setX(2);
$pdf->Cell(5, $off + 36, '-------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5, $off + 42, "SUCURSAL ORIGEN: " . strtoupper($stock->name ?? 'Nombre no disponible'));
$pdf->setX(2);
$pdf->Cell(5, $off + 48, "SUCURSAL DESTINO: " . strtoupper($stock->name ?? 'Nombre no disponible'));
$pdf->setX(2);
$pdf->Cell(5, $off + 54, "FOLIO: " . $sell->id . ' - FECHA: ' . $sell->created_at);
$pdf->setX(2);
$pdf->Cell(5, $off + 60, 'ATENDIDO POR ' . strtoupper($user->name . " " . $user->lastname));
$pdf->setX(2);
$pdf->Cell(5, $off + 66, 'GRACIAS POR TU COMPRA');

$pdf->Output();
