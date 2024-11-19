<?php

require_once(__DIR__ . "/../configs/Database.php");

class Jogador
{
    public static function listar() {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT * FROM Jogador");
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function listarPorTime($time_id) {
        try {
            $conexao = Conexao::getConexao();
            
            // Filtrar jogadores pelo ID do time
            $sql = $conexao->prepare("SELECT * FROM Jogador WHERE time_id = ?");
            $sql->execute([$time_id]);
            
            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function insert($nome, $posicao, $time_id, $created_by = null) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("INSERT INTO Jogador (nome, posicao, time_id, created_by) VALUES (?, ?, ?, ?)");
            $sql->execute([$nome, $posicao, $time_id, $created_by]);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function update($id, $nome = null, $posicao = null, $time_id = null) {
        try {
            $conexao = Conexao::getConexao();
            
            // Montar a query de atualização com base nos campos não nulos
            $fields = [];
            $params = [];

            if ($nome !== null) {
                $fields[] = "nome = ?";
                $params[] = $nome;
            }
            if ($posicao !== null) {
                $fields[] = "posicao = ?";
                $params[] = $posicao;
            }
            if ($time_id !== null) {
                $fields[] = "time_id = ?";
                $params[] = $time_id;
            }

            // Verificar se ao menos um campo foi enviado para ser atualizado
            if (empty($fields)) {
                throw new Exception("Nenhum campo válido para atualizar", 400);
            }

            // Adicionar o id para o WHERE da query
            $params[] = $id;

            // Atualizar apenas os campos que foram passados
            $sql = $conexao->prepare("UPDATE Jogador SET " . implode(", ", $fields) . " WHERE id = ?");
            $sql->execute($params);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function deletar($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("DELETE FROM Jogador WHERE id = ?");
            $sql->execute([$id]);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function exist($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT COUNT(*) FROM Jogador WHERE id = ?");
            $sql->execute([$id]);

            return $sql->fetchColumn();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }
}
