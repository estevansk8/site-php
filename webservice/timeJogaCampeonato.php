<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Campeonato.php");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

// Verificar o vencedor do campeonato
if (method("GET")) {
    try {
        if (valid($_GET, ["id"])) {
            // Se um ID de time for passado, buscar campeonatos por time
            $lista = Campeonato::listarPorTime($_GET["id"]);
        } else {
            // Se nenhum ID for passado, listar todos os campeonatos
            $lista = Campeonato::listar();
        }

        output(200, $lista);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}
