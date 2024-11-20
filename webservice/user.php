<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/User.php");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

// Listar usuários (apenas exemplo, cuidado com segurança)
if (method("GET")) {
    try {
        if (valid($_GET, ["email"])) {
            $usuario = User::getByEmail($_GET["email"]);
            if (!$usuario) {
                throw new Exception("Usuário não encontrado", 404);
            }
            output(200, $usuario);
        } else {
            throw new Exception("Email não informado", 400);
        }
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Cadastrar usuário
if (method("POST")) {
    try {
        if (!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }
        if (!valid($data, ["nome", "email", "senha"])) {
            throw new Exception("Nome, e-mail e/ou senha não informados", 404);
        }

        $senhaHash = password_hash($data["senha"], PASSWORD_BCRYPT); // Criptografa a senha
        $res = User::insert($data["nome"], $data["email"], $senhaHash);
        if (!$res) {
            throw new Exception("Não foi possível cadastrar o usuário", 500);
        }

        output(200, ["msg" => "Usuário criado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Atualizar usuário
if (method("PUT")) {
    try {
        if (!valid($_GET, ["id"])) {
            throw new Exception("ID não enviado", 400);
        }
        if (!$data) {
            throw new Exception("Nenhuma informação encontrada", 400);
        }

        $usuario = User::update($_GET["id"], $data["nome"], $data["email"], $data["senha"] ?? null);
        if (!$usuario) {
            throw new Exception("Não foi possível atualizar o usuário", 500);
        }

        output(200, ["msg" => "Usuário atualizado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Deletar usuário
if (method("DELETE")) {
    try {
        if (!valid($_GET, ["id"])) {
            throw new Exception("ID não enviado", 400);
        }

        $res = User::deletar($_GET["id"]);
        if (!$res) {
            throw new Exception("Não foi possível deletar o usuário", 500);
        }

        output(200, ["msg" => "Usuário deletado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

?>
