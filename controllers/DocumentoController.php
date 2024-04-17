<?php

require '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'buscarDocumentos') {
        getDocumentos($_POST);
    } 
    elseif ($_POST['action'] === 'buscarArquivo') {
        buscarArquivo($_POST['caminhoArquivo']);
    }
}

function getDocumentos($postData)
{    
    global $host, $username, $password, $database;
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM documento WHERE tipoDocumental = '{$postData['tipoDocumental']}'";


    if (!empty($postData['lote'])) {
        $sql .= " AND lote = '{$postData['lote']}'";
    }

    if (!empty($postData['nrProcessoPregao'])) {
        $sql .= " AND nrProcessoPregao = '{$postData['nrProcessoPregao']}'";
    }

    if (!empty($postData['nome'])) {
        $sql .= " AND nomeDocumento = '{$postData['nome']}'";
    }

    if (!empty($postData['cpfCnpjCredor'])) {
        $sql .= " AND cpfCnpjCredor = '{$postData['cpfCnpjCredor']}'";
    }

    if (!empty($postData['dataProcesso'])) {
        $dataFormatada = date('Y-m-d', strtotime(str_replace('/', '-', $postData['dataProcesso'])));
        $sql .= " AND DATE(dataProcesso) = '{$dataFormatada}'";
    }

    $sql .= " ORDER BY idDocumento";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $dados = array();
        while ($row = $result->fetch_assoc()) {
            $dados[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($dados);
    } else {
        echo json_encode(array());
    }

    $conn->close();
}


function buscarArquivo($idDocumento) {
    global $pathArquivo, $host, $username, $password, $database;

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $sql = "SELECT caminhoArquivo FROM documento WHERE idDocumento = $idDocumento";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $caminhoDoc = $row['caminhoArquivo'];

        $caminhoCompleto = $pathArquivo . $caminhoDoc;

        if (file_exists($caminhoCompleto)) {
            if (!file_exists('fileTemp')) {
                mkdir('fileTemp', 0777, true);
            }

            $ArquivoNovo = date('Y-m-d_H-i-s') . '.pdf';
            $pathCompleto = realpath('fileTemp/');

            if (!file_exists($pathCompleto)) {
                mkdir($pathCompleto, 0777, true);
            }

            if (copy($caminhoCompleto, $pathCompleto . '/' . $ArquivoNovo)) {
                echo '../controllers/fileTemp/' . $ArquivoNovo;
            } else {
                echo 'Erro ao copiar o arquivo';
            }
        }
        
    } else {
        echo "Documento não encontrado";
    }

    $conn->close();
}
