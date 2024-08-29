<?php

declare(strict_types=1);

require_once "core/controller/Core.php";
require_once "core/controller/Database.php";
require_once "core/controller/Executor.php";
require_once "core/controller/Model.php";

require_once "core/app/model/UserData.php";
require_once "core/app/model/SellData.php";
require_once "core/app/model/OperationData.php";
require_once "core/app/model/ProductData.php";
require_once "core/app/model/StockData.php";
require_once "core/app/model/ConfigurationData.php";
require_once "fpdf/fpdf.php";

// Clase FPDF personalizada con soporte UTF-8
class PDF_UTF8 extends FPDF
{
    function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        $txt = iconv('UTF-8', 'windows-1252', $txt);
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }
}

session_start();
if (isset($_SESSION["user_id"])) {
    Core::$user = UserData::getById((int)$_SESSION["user_id"]);
}

// Recuperación segura de valores de configuración con valores predeterminados
$titleConfig = ConfigurationData::getByPreffix("ticket_title");
$title = $titleConfig && $titleConfig->val ? $titleConfig->val : 'Título Predeterminado';

$symbolConfig = ConfigurationData::getByPreffix("currency");
$symbol = $symbolConfig && $symbolConfig->val ? $symbolConfig->val : 'Q';

$ivaConfig = ConfigurationData::getByPreffix("imp-val");
$iva_val = $ivaConfig && $ivaConfig->val ? (float)$ivaConfig->val : 0.0;

$stock = StockData::getPrincipal();
$sell = SellData::getById((int)$_GET["id"]);
$operations = OperationData::getAllProductsBySellId((int)$_GET["id"]);
$user = $sell->getUser();

// Crear PDF con tamaño inicial
$pdf = new PDF_UTF8($orientation = 'P', $unit = 'mm', array(80, 150));
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 10);

$pdf->SetFont('Arial', 'B', 8);
$pdf->setY(2);
$pdf->setX(2);
$pdf->Cell(58, 5, mb_strtoupper($title, 'UTF-8'), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 6);
$pdf->setX(2);
$pdf->Cell(58, 5, mb_strtoupper('Dirección', 'UTF-8'), 0, 1, 'L');
$pdf->setX(2);
$pdf->Cell(58, 5, "Teléfono", 0, 1, 'L');
$pdf->Cell(58, 5, str_repeat('-', 58), 0, 1, 'L');

// Cabecera de la tabla con anchos ajustados
$pdf->Cell(10, 5, 'CANT.', 0, 0, 'L');
$pdf->Cell(24, 5, 'ARTICULO', 0, 0, 'L');
$pdf->Cell(12, 5, 'PRECIO', 0, 0, 'R');
$pdf->Cell(12, 5, 'TOTAL', 0, 1, 'R');

$pdf->SetFont('Arial', '', 6); // Fuente normal para los detalles

$total = 0;
foreach ($operations as $op) {
    $product = $op->getProduct();
    $pdf->Cell(10, 5, (string)($op->q ?? 0), 0, 0, 'L');
    $pdf->Cell(24, 5, mb_strtoupper(substr($product->name ?? 'Desconocido', 0, 15), 'UTF-8'), 0, 0, 'L');
    $pdf->Cell(12, 5, "$symbol " . number_format((float)($product->price_out ?? 0), 2, ".", ","), 0, 0, "R");
    $pdf->Cell(12, 5, "$symbol " . number_format((float)(($op->q ?? 0) * ($product->price_out ?? 0)), 2, ".", ","), 0, 1, "R");

    $total += (float)(($op->q ?? 0) * ($product->price_out ?? 0));
}

$pdf->Cell(58, 5, str_repeat('-', 58), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(46, 5, "SUBTOTAL: ", 0, 0, 'R');
$pdf->Cell(12, 5, "$symbol " . number_format((float)(($total) / (1 + ($iva_val / 100))), 2, ".", ","), 0, 1, "R");
$pdf->Cell(46, 5, "DESCUENTO: ", 0, 0, 'R');
$pdf->Cell(12, 5, "$symbol " . number_format((float)($sell->discount ?? 0), 2, ".", ","), 0, 1, "R");
$pdf->Cell(46, 5, "IMPUESTO: ", 0, 0, 'R');
$pdf->Cell(12, 5, "$symbol " . number_format((float)((($total) / (1 + ($iva_val / 100))) * ($iva_val / 100)), 2, '.', ','), 0, 1, "R");
$pdf->Cell(46, 5, "TOTAL: ", 0, 0, 'R');
$pdf->Cell(12, 5, "$symbol " . number_format((float)$total, 2, ".", ","), 0, 1, "R");
$pdf->Cell(46, 5, "EFECTIVO: ", 0, 0, 'R');
$pdf->Cell(12, 5, "$symbol " . number_format((float)($sell->cash ?? 0), 2, ".", ","), 0, 1, "R");
$pdf->Cell(46, 5, "CAMBIO: ", 0, 0, 'R');
$pdf->Cell(12, 5, "$symbol " . number_format((float)(($sell->cash ?? 0) - ($total - ($sell->discount ?? 0))), 2, ".", ","), 0, 1, "R");

$pdf->Cell(58, 5, str_repeat('-', 58), 0, 1, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(58, 5, "SUCURSAL: " . mb_strtoupper($stock->name ?? 'Desconocida', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(58, 5, "FOLIO: " . ($sell->id ?? 'N/A') . ' - FECHA: ' . ($sell->created_at ?? 'Desconocido'), 0, 1, 'L');
$pdf->Cell(58, 5, 'ATENDIDO POR ' . mb_strtoupper(($user->name ?? 'Desconocido') . " " . ($user->lastname ?? ''), 'UTF-8'), 0, 1, 'L');
$pdf->Cell(58, 5, 'GRACIAS POR TU COMPRA', 0, 1, 'C');

$pdf->Output('I', 'ticket.pdf');
