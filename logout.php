<?php
declare(strict_types=1);

session_start();

if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
}

session_destroy();

header("Location: ./");
exit();
