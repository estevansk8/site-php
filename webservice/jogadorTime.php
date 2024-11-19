<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Jogador.php");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

if (method("GET")) {
    try {
        // Verifica se o parâmetro "id" foi passado na requisição GET
        if (valid($_GET, ["id"])) {
            // Se um ID de time for passado, buscar jogadores por time
            $lista = Jogador::listarPorTime($_GET["id"]);
        } else {
            // Se nenhum ID for passado, listar todos os jogadores
            $lista = Jogador::listar();
        }

        // Retorna os dados em formato JSON
        output(200, $lista);
    } catch (Exception $e) {
        // Retorna a mensagem de erro e o código de erro
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

