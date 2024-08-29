<?php
declare(strict_types=1);

date_default_timezone_set('America/Mexico_City');
if (isset($_SESSION["user_id"])) {
    Core::$user = UserData::getById((int)$_SESSION["user_id"]);
    Core::$symbol = ConfigurationData::getByPreffix("currency")->val;
}

/// en caso de que el parametro action este definido evitamos que se muestre
/// el layout por defecto y ejecutamos el action sin mostrar nada de vista
if (!isset($_GET["action"])) {
    Module::loadLayout("index");
} else {
    Action::load($_GET["action"]);
}
