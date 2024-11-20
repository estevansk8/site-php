<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Campeonato.php");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

if (method("GET")) {
    try {
        if (isset($_GET["id"])) {
            if (!Campeonato::exist($_GET["id"])) {
                throw new Exception("Campeonato não encontrado", 404);
            }

            $clube = Campeonato::obterPorId($_GET["id"]);
            output(200, $clube);
        } else {

            $lista = Campeonato::listar();
            output(200, $lista);
        }
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}
