<?php

require_once(__DIR__ . "/../configs/Database.php");
require_once(__DIR__ . "/../configs/utils.php");

class User {

    // Inserir novo usuário
    public static function insert($nome, $email, $senha) {
        try {
            $conexao = Conexao::getConexao();
    
            // Verificar se o e-mail já existe
            $sql = $conexao->prepare("SELECT id FROM User WHERE email = ?");
            $sql->execute([$email]);
            if ($sql->fetch()) {
                throw new Exception("E-mail já cadastrado", 400);
            }
    
            // Inserir novo usuário
            $sql = $conexao->prepare("INSERT INTO User (nome, email, senha) VALUES (?, ?, ?)");
            $sql->execute([$nome, $email, $senha]);
    
            return $sql->rowCount();
        } catch (Exception $e) {
            output($e->getCode(), ["msg" => $e->getMessage()]);
        }
    }
    

    // Obter usuário por email
    public static function getByEmail($email) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT * FROM User WHERE email = ?");
            $sql->execute([$email]);

            return $sql->fetch();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Atualizar usuário
    public static function update($id, $nome, $email, $senha = null) {
        try {
            $conexao = Conexao::getConexao();

            if ($senha) {
                $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
                $sql = $conexao->prepare("UPDATE User SET nome = ?, email = ?, senha = ? WHERE id = ?");
                $sql->execute([$nome, $email, $senhaHash, $id]);
            } else {
                $sql = $conexao->prepare("UPDATE User SET nome = ?, email = ? WHERE id = ?");
                $sql->execute([$nome, $email, $id]);
            }

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Deletar usuário
    public static function deletar($id) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("DELETE FROM User WHERE id = ?");
            $sql->execute([$id]);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    // Verificar senha
    public static function verifyPassword($email, $senha) {
        try {
            $usuario = self::getByEmail($email);
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                return $usuario;
            }
            return false;
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }
}

?>
