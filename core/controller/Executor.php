<?php

declare(strict_types=1);

class Executor
{
    public static function doit(string $sql): array
    {
        $con = Database::getCon();
        if (Core::$debug_sql) {
            echo "<pre>" . htmlspecialchars($sql) . "</pre>";
        }
        $result = $con->query($sql);
        return [$result, $con->insert_id];
    }
}
