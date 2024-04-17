<?php
class UsuarioModel
{

    static function AutenticarUsuario($UsuLogin, $UsuSenha)
    {
        global $host, $database, $username, $password;

        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM usuario WHERE emailUsuario = ? AND senhaUsuario = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da Consulta: " . $conn->error);
        }

        $stmt->bind_param("ss", $UsuLogin, $UsuSenha);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $user;
    }

    static function consultarUsuario($nomeLogin) {
        global $host, $database, $username, $password;
    
        $conn = new mysqli($host, $username, $password, $database);
    
        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }
    
        $nomeLogin = "%$nomeLogin%";
    
        $sql = "SELECT * FROM usuario WHERE nomeUsuario LIKE ? OR emailUsuario LIKE ?";
    
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            die("Erro na preparação da Consulta: " . $conn->error);
        }
    
        $stmt->bind_param("ss", $nomeLogin, $nomeLogin);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $users = array();
    
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    
        $stmt->close();
        $conn->close();
    
        return $users; 
    
    }
    

    static function consultarUsuarioPorId($idUsuario) {
        global $host, $database, $username, $password;
    
        $conn = new mysqli($host, $username, $password, $database);
    
        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }
    
        $sql = "SELECT * FROM usuario WHERE idUsuario = ?";
        
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            die("Erro na preparação da Consulta: " . $conn->error);
        }
    
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    
        $stmt->close();
        $conn->close();
    
        return json_encode(array($user));
    }
    

    static function consultarUsuarioPorEmail($email) {
        global $host, $database, $username, $password;
    
        $conn = new mysqli($host, $username, $password, $database);
    
        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }
    
        $sql = "SELECT * FROM usuario WHERE emailUsuario = ?";
        
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            die("Erro na preparação da Consulta: " . $conn->error);
        }
    
        $stmt->bind_param("s", $email);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $user = $result->fetch_object();
    
        $stmt->close();
        $conn->close();
    
        return $user;
    }

    
    static function atualizarUsuario($post) {
        global $host, $database, $username, $password;
    
        try {
            $conn = new mysqli($host, $username, $password, $database);
    
            if ($conn->connect_error) {
                die("Erro na conexão: " . $conn->connect_error);
            }
    
            $sql = "UPDATE usuario SET nomeUsuario = ?, cpfUsuario = ?, emailUsuario = ?, senhaUsuario = ?, isAdmin = ? WHERE idUsuario = ?";
    
            $stmt = $conn->prepare($sql);
    
            if ($stmt === false) {
                die("Erro na preparação da Consulta: " . $conn->error);
            }
    
            $idUsuario = $post['idUsuario'];
            $nome = $post['nome'];
            $cpfSemMascara = str_replace(['.', '-', '/'], '', $post['cpf']);
            $email = $post['email'];
            $administrador = isset($post['administrador']) ? 1 : 0;
    
            if (isset($post['senha']) && !empty($post['senha'])) {
                $usuSenha = md5($post['senha']);
            } else {
                $usuarioExistente = json_decode(self::consultarUsuarioPorId($idUsuario), true);
                $usuSenha = $usuarioExistente[0]['senhaUsuario'];
            }
    
            $stmt->bind_param("ssssii", $nome, $cpfSemMascara, $email, $usuSenha, $administrador, $idUsuario);
    
            if ($stmt->execute() === FALSE) {
                die("Erro na execução da consulta: " . $stmt->error);
            }
    
            $stmt->close();
            $conn->close();
    
            return json_encode(array("message" => "Usuário atualizado com sucesso."));
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    static function RedefinirSenha($idUsuario, $novaSenha){
        global $host, $database, $username, $password;
        try {
            $conn = new mysqli($host, $username, $password, $database);
    
            if ($conn->connect_error) {
                die("Erro na conexão: " . $conn->connect_error);
            }
    
            $sql = "UPDATE usuario SET senhaUsuario = ? WHERE idUsuario = ?";
    
            $stmt = $conn->prepare($sql);
    
            if ($stmt === false) {
                die("Erro na preparação da Consulta: " . $conn->error);
            }
    
            if (isset($novaSenha) && !empty($novaSenha)) {
                $usuSenha = md5($novaSenha);
            } else {
                return false;
            }

            $stmt->bind_param("ss", $usuSenha, $idUsuario);
    
            if ($stmt->execute() === FALSE) {
                die("Erro na execução da consulta: " . $stmt->error);
            }
    
            $stmt->close();
            $conn->close();
    
            return true;

        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    
    
}
