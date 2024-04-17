<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Viewer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type='text/javascript' src='http://code.jquery.com/jquery-3.6.0.js'></script>

</head>

<body>
    <form action="../controllers/UsuarioController.php" method="POST">
        <input type="hidden" name="action" value="consultarUsuario">
        <div>
            <?php
            include('../components/Navbar.php');
            ?>
        </div>
        <div style="margin-top: 10px; padding: 10px;">
            <span class="fw-bold text-center">
                <h5>
                    Consultar Usuário
                </h5>
            </span>
        </div>
        <div class="d-flex justify-content-center align-items-center">

            <div class="card shadow p-3 mt-5 me-6 cardUsuario">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome ou login">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-warning btn-sm me-3" id="btnPesquisar">
                            <i class="bi bi-search"></i> Pesquisar
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm" id="btnAdicionarUsuario">
                            <i class="bi bi-plus"></i> Adicionar novo usuário
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="d-flex justify-content-center align-items-center">
        <div class="card shadow p-3 mt-3 me-6 gridDocumentos" style="display: none;">
            <table class="table table-hover" id="gridUsuarios">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col" class="col-md-3">Nome</th>
                        <th scope="col" class="col-md-2">CPF/CNPJ</th>
                        <th scope="col" class="col-md-3">E-mail</th>
                        <th scope="col" class="col-md-2 text-center">Administrador</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</body>

</html>

<style>
    .formCadastro {
        padding: 10px;
    }

    .gridDocumentos {
        margin: 30px;
        width: 65vw;
    }

    .cardUsuario {
        margin: 30px;
        width: 65vw;
    }

    .cardConsulta {
        margin: 30px;
        width: 65vw;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var idUsuario;

        document.getElementById('btnPesquisar').addEventListener('click', function() {
            var gridDocumentos = document.querySelector('.gridDocumentos');
            gridDocumentos.style.display = 'block';

        });

        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            axios.post('../controllers/UsuarioController.php', formData, {
                    responseType: 'json',
                    action: 'consultarUsuario'
                })
                .then(function(response) {
                    popularGrid(response.data);
                })
                .catch(function(error) {
                    console.error('Erro ao enviar dados:', error);
                });
        });

        document.getElementById('btnAdicionarUsuario').addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '../views/cadastroUsuario.php';
        });

    });

    function popularGrid(data) {

        var tableBody = document.querySelector('#gridUsuarios tbody');
        tableBody.innerHTML = '';
        data.forEach(function(row) {
            var tr = document.createElement('tr');
            tr.innerHTML = `
            <td>
                <button class="btn btn-editar btnEditarUsuario" id="btnEditarUsuario" data-idUsuario="${row.idUsuario}" style="padding: 0px 5px; margin-left:10px;">
                    <i class="bi bi-person-fill-gear fa-2x" style=" color: #3B97D9; font-size: 25px;"></i>
                </button>
            </td>
            <td>${row.nomeUsuario}</td>
            <td>${row.cpfUsuario}</td>
            <td>${row.emailUsuario}</td>
            <td class="text-center">${formatarSeAdmin(row.isAdmin)}</td>
        `;
            tableBody.appendChild(tr);
        });

        // document.getElementById('btnEditarUsuario').addEventListener('click', function() {
        //     window.location.href = `../views/cadastroUsuario.php?idUsuario=${idUsuario}`;
        // });
        document.querySelectorAll('.btnEditarUsuario').forEach(button => {
            button.addEventListener('click', function() {
                var idUsuario = this.getAttribute('data-idUsuario');
                window.location.href = `../views/cadastroUsuario.php?idUsuario=${idUsuario}`;
            });
        });
    }

    function editarUsuario(idUsuario) {
        window.location.href = `../views/cadastroUsuario.php?idUsuario=${idUsuario}`;
    }

    function formatarSeAdmin(isAdmin) {
        return isAdmin === 1 ? "Sim" : "Não";
    }
</script>