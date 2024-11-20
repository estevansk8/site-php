<?php

require_once(__DIR__ . "/../configs/Database.php");

class Clube
{
    public static function listar() {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT * FROM Clube");
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function obterPorId($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT * FROM Clube WHERE id = ?");
            $sql->execute([$id]);
    
            return $sql->fetch();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function insert($nome, $cidade, $estadio, $created_by = null) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("INSERT INTO Clube (nome, cidade, estadio, created_by) VALUES (?, ?, ?, ?)");
            $sql->execute([$nome, $cidade, $estadio, $created_by]);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function update($id, $nome = null, $cidade = null, $estadio = null) {
        try {
            $conexao = Conexao::getConexao();
            
            // Montar a query de atualização com base nos campos não nulos
            $fields = [];
            $params = [];
    
            if ($nome !== null) {
                $fields[] = "nome = ?";
                $params[] = $nome;
            }
            if ($cidade !== null) {
                $fields[] = "cidade = ?";
                $params[] = $cidade;
            }
            if ($estadio !== null) {
                $fields[] = "estadio = ?";
                $params[] = $estadio;
            }
    
            // Verificar se ao menos um campo foi enviado para ser atualizado
            if (empty($fields)) {
                throw new Exception("Nenhum campo válido para atualizar", 400);
            }
    
            // Adicionar o id para o WHERE da query
            $params[] = $id;
    
            // Atualizar apenas os campos que foram passados
            $sql = $conexao->prepare("UPDATE Clube SET " . implode(", ", $fields) . " WHERE id = ?");
            $sql->execute($params);
    
            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }
    

    public static function deletar($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("DELETE FROM Clube WHERE id = ?");
            $sql->execute([$id]);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    public static function exist($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT COUNT(*) FROM Clube WHERE id = ?");
            $sql->execute([$id]);

            return $sql->fetchColumn();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }
}
