<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Clube.php");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

if (method("GET")) {
    try {
        if (isset($_GET["id"])) {
            // Verifica se o clube existe
            if (!Clube::exist($_GET["id"])) {
                throw new Exception("Clube não encontrado", 404);
            }

            // Obtém o clube pelo ID
            $clube = Clube::obterPorId($_GET["id"]);
            output(200, $clube);
        } else {
            // Lista todos os clubes se o ID não for informado
            $lista = Clube::listar();
            output(200, $lista);
        }
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}
