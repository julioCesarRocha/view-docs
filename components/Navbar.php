<?php 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require '../config/config.php';
global $idUsuLogado, $isAdmins;

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Usuário</title>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../assets/imagens/logo.png" alt="" width="36" height="30" class="d-inline-block align-text-top">
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="../views/consultarDocumento.php">Consultar Documento</a>
                    </li>
                    <?php if ($_SESSION['isAdmin'] == 1) : ?>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="../views/consultarUsuario.php">Cadastrar Usuário</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <span class="navbar-text d-flex justify-content-end me-3 fw-bold">
                <a class="nav-link fw-bold" href="../views/login.php">Sair</a>
            </span>
        </div>
    </nav>
</body>

</html>