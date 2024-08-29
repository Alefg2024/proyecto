<?php

declare(strict_types=1);

class Core
{
    public static ?object $user = null;
    public static ?string $symbol = null;
    public static bool $debug_sql = false;

    public static string $email_user = "";
    public static string $email_password = "";

    public static string $pdf_footer = "Generado por el Sistema de Inventario";
    public static string $email_footer = "Correo generado Automaticamente por el Sistema de Inventario";

    public static string $pdf_table_fillcolor = "[100, 100, 100]";
    public static string $pdf_table_column_fillcolor = "255";
    public static bool $send_alert_emails = true;

    public static int $discount_method = 2;

    public static function includeCSS(): void
    {
        $path = "res/css/";
        $handle = opendir($path);
        if ($handle) {
            while (($entry = readdir($handle)) !== false) {
                if ($entry != "." && $entry != "..") {
                    $fullpath = $path . $entry;
                    if (!is_dir($fullpath)) {
                        echo "<link rel='stylesheet' type='text/css' href='{$fullpath}' />";
                    }
                }
            }
            closedir($handle);
        }
    }

    public static function alert(string $text): void
    {
        echo "<script>alert('" . addslashes($text) . "');</script>";
    }

    public static function redir(string $url): void
    {
        echo "<script>window.location='" . addslashes($url) . "';</script>";
    }

    public static function includeJS(): void
    {
        $path = "res/js/";
        $handle = opendir($path);
        if ($handle) {
            while (($entry = readdir($handle)) !== false) {
                if ($entry != "." && $entry != "..") {
                    $fullpath = $path . $entry;
                    if (!is_dir($fullpath)) {
                        echo "<script type='text/javascript' src='{$fullpath}'></script>";
                    }
                }
            }
            closedir($handle);
        }
    }
}
