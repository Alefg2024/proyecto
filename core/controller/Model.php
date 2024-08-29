<?php

declare(strict_types=1);

class Model
{
    public static function exists(string $modelname): bool
    {
        $fullpath = self::getFullpath($modelname);
        return file_exists($fullpath);
    }

    public static function getFullpath(string $modelname): string
    {
        return "core/app/model/" . $modelname . ".php";
    }

    public static function many(mysqli_result $query, string $aclass): array
    {
        $array = [];
        while ($r = $query->fetch_array(MYSQLI_ASSOC)) {
            $obj = new $aclass;
            foreach ($r as $key => $v) {
                $obj->$key = $v;
            }
            $array[] = $obj;
        }
        return $array;
    }

    public static function one(mysqli_result $query, string $aclass): ?object
    {
        $data = null;
        if ($r = $query->fetch_array(MYSQLI_ASSOC)) {
            $data = new $aclass;
            foreach ($r as $key => $v) {
                $data->$key = $v;
            }
        }
        return $data;
    }
}
