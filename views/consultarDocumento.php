<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Viewer</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script type='text/javascript' src='http://code.jquery.com/jquery-3.6.0.js'></script>
    <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>


</head>

<body>
    <form>
        <input type="hidden" name="action" value="buscarDocumentos">
        <input type="hidden" name="idDocumento" value="">
        <?php include('../components/Navbar.php'); ?>
        <div style="margin-top: 10px; padding: 10px;">
            <h5 class="fw-bold text-center">Consultar Documento</h5>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div class="card shadow p-3 mt-3 me-6 cardConsulta">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="tipoDocumental">Tipo Documental</label>
                            <select class="form-select" aria-label=".form-select-sm example" name="tipoDocumental" id="tipoDocumental">
                                <option value="CTB" selected>Contabilidade</option>
                                <option value="LCT">Licitação</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="lote">Lote</label>
                            <input type="text" class="form-control" id="lote" name="lote" placeholder="Lote">
                        </div>
                        <div class="col-md-4">
                            <label for="nrProcessoPregao">Nº Processo</label>
                            <input type="text" class="form-control" id="nrProcessoPregao" name="nrProcessoPregao" placeholder="Nº Processo">
                        </div>
                        <div class="col-md-4">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <label for="cpf">CPF/CNPJ Credor</label>
                            <input type="text" class="form-control" id="cpfCnpjCredor" name="cpfCnpjCredor" placeholder="CPF/CNPJ Credor">
                        </div>
                        <div class="col-md-4 mt-3">
                            <label for="cpf">Data Processo</label>
                            <input type="date" class="form-control" id="dataProcesso" name="dataProcesso" placeholder="Data Processo">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary" id="btnPesquisar">
                            <i class="bi bi-search"></i> Pesquisar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="d-flex justify-content-center align-items-center">
        <div class="card shadow p-3 mt-3 me-6 gridDocumentos" style="display: none;">
            <table class="table table-hover" id="gridDocumentos">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Lote</th>
                        <th scope="col" id="numeroProcessoHeader">Nº Processo</th>
                        <th scope="col">Nome Documento</th>
                        <th scope="col">Tipo Documental</th>
                        <th scope="col">Data Processo</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="modal" id="modalPdf">
        <div class="modal-content">
            <div class="modalHeader me-3 mb-2">
                <span class="close">&times;</span>
            </div>
            <div class="iframe-container">
                <iframe id="pdfFrame" frameborder="0" src=""></iframe>
            </div>
        </div>
    </div>
</body>


</html>


<style>
    .cardConsulta {
        margin: 30px;
        width: 65vw;
    }
    
    .gridDocumentos {
        margin: 30px;
        width: 65vw;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        max-width: 980px;
        height: 733px;
        overflow: hidden;
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        background-color: #F0F0F0;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    #iframeContainer {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        overflow: hidden;

    }

    #iframeContainer iframe {
        width: 90%;
        height: 500px;
        overflow: auto;
    }

    #pdfViewer {
        display: flex;
        flex-direction: row;
        justify-content: center;
        width: 900px;
        height: 700px;
    }

    #pdfFrame {
        width: 915px;
        height: 635px;
        overflow: hidden;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('btnPesquisar').addEventListener('click', function() {
            var gridDocumentos = document.querySelector('.gridDocumentos');
            gridDocumentos.style.display = 'block';

        });

        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            axios.post('../controllers/DocumentoController.php', formData, {
                    responseType: 'json'
                })
                .then(function(response) {
                    popularGrid(response.data);
                })
                .catch(function(error) {
                    console.error('Erro ao enviar dados:', error);
                });
        });

        var tipoDocumentalSelect = document.getElementById('tipoDocumental');
        var numeroProcessoLabel = document.querySelector('label[for="nrProcessoPregao"]');
        var numeroProcessoInput = document.getElementById('nrProcessoPregao');

        tipoDocumentalSelect.addEventListener('change', function() {
            if (this.value === 'LCT') {
                numeroProcessoLabel.innerText = 'Pregão';
                numeroProcessoInput.placeholder = 'Pregão';
                numeroProcessoHeader.innerText = 'Pregão';
            } else {
                numeroProcessoLabel.innerText = 'Nº Processo';
                numeroProcessoInput.placeholder = 'Nº Processo';
                numeroProcessoHeader.innerText = 'Nº Processo';
            }
        });

        $('#cpfCnpjCredor').inputmask({
            mask: ['999.999.999-99', '99.999.999/9999-99'],
            keepStatic: true
        });

    });

    function popularGrid(data) {
        var tableBody = document.querySelector('#gridDocumentos tbody');
        tableBody.innerHTML = '';
        data.forEach(function(row) {
            var tr = document.createElement('tr');
            tr.innerHTML = `
            <td>
                <button class="btn btn-editar btnVisualizarDoc" id="btnVisualizarDoc" onclick="consultarDocumento(${row.idDocumento})" style="padding: 0px 5px; margin-left:10px;">
                    <i class="bi bi-file-earmark-pdf-fill fa-2x" style=" color: #3B97D9; font-size: 25px;"></i>
                </button>
            </td>
            <td>${row.lote}</td>
            <td>${row.nrProcessoPregao}</td>
            <td>${row.nomeDocumento}</td>
            <td>${formatarTipoDocumental(row.tipoDocumental)}</td>
            <td>${new Date(row.dataProcesso).toLocaleDateString('pt-BR')}</td>
        `;
            tableBody.appendChild(tr);
        });
    }

    function consultarDocumento(idDoc) {
        var caminhoArquivo = idDoc;
        $.ajax({
            type: 'POST',
            url: '../controllers/DocumentoController.php',
            data: {
                action: 'buscarArquivo',
                caminhoArquivo: caminhoArquivo
            },
            success: function(response) {
                pdfjsLib.getDocument(response).promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        var scale = 1.5;
                        var viewport = page.getViewport({
                            scale: scale
                        });

                        var canvas = document.createElement("canvas");
                        var context = canvas.getContext("2d");
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext).promise.then(function() {
                            var imageDataUrl = canvas.toDataURL("image/png");

                            document.getElementById('pdfFrame').src = imageDataUrl;
                        });
                    });
                });

                document.getElementById("modalPdf").style.display = "block";
            },
            error: function(xhr, status, error) {
                window.alert('Ocorreu um erro ao carregar o arquivo PDF');
            }
        });
    }


    document.getElementsByClassName("close")[0].addEventListener("click", function() {
        document.getElementById("modalPdf").style.display = "none";
    });

    function exibirPDF(caminhoCompleto) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', caminhoCompleto, true);
        xhr.responseType = 'blob';
        xhr.onload = function() {
            if (xhr.status === 200) {
                var reader = new FileReader();
                reader.onloadend = function() {
                    var base64PDF = reader.result.split(',')[1];
                    document.getElementById('pdfFrame').src = 'data:application/pdf;base64,' + base64PDF;
                };
                reader.readAsDataURL(xhr.response);
            }
        };
        xhr.send();
    }

    $j = jQuery.noConflict();

    $j(document).ready(function() {

    });

    function formatarTipoDocumental(tpDocumental) {
        return tpDocumental === "CTB" ? "Contabilidade" : "Licitação";
    }
</script>