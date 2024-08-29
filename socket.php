<?php
declare(strict_types=1);

function socket_print(): void
{
    $service_port = 2000;
    $address = '127.0.0.1';

    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        throw new RuntimeException("socket_create() failed: " . socket_strerror(socket_last_error()));
    }

    $result = socket_connect($socket, $address, $service_port);
    if ($result === false) {
        throw new RuntimeException("socket_connect() failed: " . socket_strerror(socket_last_error($socket)));
    }

    $in = "|print|";
    socket_write($socket, $in, strlen($in));

    socket_close($socket);
}
