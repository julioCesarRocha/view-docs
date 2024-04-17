<?php

require '../config/config.php';
include('../models/UsuarioModel.php');

session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['action']) && $_GET['action'] === 'update' && isset($_GET['idUsuario'])) {
            $idUsuario = $_GET['idUsuario'];
            $usuario = UsuarioModel::consultarUsuarioPorId($idUsuario);
            echo json_encode($usuario);
            exit;
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {

            $action = $_POST['action'];

            switch ($action) {
                case 'login':
                    $resultado = logar();
                    if ($resultado === true) {
                        echo json_encode(array('success' => true, 'message' => 'Login bem-sucedido.'));
                    } else {
                        echo json_encode(array('success' => false, 'message' => $resultado));
                    }
                    break;
                case 'create':
                    inserirUsuario($_POST['nome'], $_POST['cpf'], $_POST['email'], $_POST['senha'], isset($_POST['administrador']) ? 1 : 0);
                    break;
                case 'update':
                    $usuario = UsuarioModel::atualizarUsuario($_POST);
                    echo json_encode($usuario);
                    break;
                case 'consultarUsuario':
                    if (isset($_POST['nome'])) {
                        $usuarios = UsuarioModel::consultarUsuario($_POST['nome']);
                        echo json_encode($usuarios);
                    }
                    break;
                case 'recuperarSenha':
                    if (isset($_POST['email'])) {
                        $usuario = UsuarioModel::consultarUsuarioPorEmail($_POST['email']);
                        if ($usuario && UsuarioModel::RedefinirSenha($usuario->idUsuario, $_POST['senha'])) {
                            echo true;
                        } else {
                            echo false;
                        }
                    }
                    break;
            }
        }
    }
} 
catch (Exception $ex){
    throw $ex;
}

function inserirUsuario($nome, $cpf, $email, $senha, $administrador) {

    global $host, $username, $password, $database;

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    try {

        $sql = "INSERT INTO usuario (nomeUsuario, cpfUsuario, emailUsuario, senhaUsuario, isAdmin, dtCriacao) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da declaração SQL: " . $conn->error);
        }

        $hashSenha = md5($senha);
        $cpfSemMascara = str_replace(['.', '-', '/'], '', $cpf);

        $stmt->bind_param("ssssis", $nome, $cpfSemMascara, $email, $hashSenha, $administrador, date('Y-m-d H:i:s'));


        if ($stmt->execute() === TRUE) 
        {
            session_start();
            $_SESSION['success_message'] = "Registro inserido com sucesso!";
            header("Location: ../views/cadastroUsuario.php");
            exit();
        } 
        
        $stmt->close();
        
    } catch (PDOException $e) {
        die('Erro ao inserir usuário: ' . $e->getMessage());
    }
    
    $conn->close();
}

function logar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['email']) && isset($_POST['senha'])) {
            $email = trim($_POST['email']);
            $senha = trim($_POST['senha']);

            if (!empty($senha) && !empty($email)) {
                $encodeSenha = md5($senha);

                if (!ctype_digit($email)) {
                    $userLogado = UsuarioModel::AutenticarUsuario($email, $encodeSenha);                    
                    if ($userLogado != null) {
                        $_SESSION['idUsuario'] = $userLogado['idUsuario'];
                        $_SESSION['isAdmin'] = $userLogado['isAdmin'];
                        // header("Location: ../views/consultarDocumento.php");
                        return true;
                    } else {
                        return "Credenciais Inválidas";
                    }
                } 
              
            } elseif (empty($senha) && !empty($email)) {
                return "E-mail e senha são obrigatórios";
            }
        } else {
            return "E-mail e senha são obrigatórios";
        }
    }
}










