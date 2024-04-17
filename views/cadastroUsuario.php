<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document Viewer</title>
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
  <script type='text/javascript' src='http://code.jquery.com/jquery-3.6.0.js'></script>
  <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <form action="../controllers/UsuarioController.php" method="POST">
    <input type="hidden" name="action" id="action" value="update">
    <div>
      <?php
      include('../components/Navbar.php');
      ?>
    </div>
    <div style="margin-top: 10px; padding: 10px;">
      <span class="fw-bold text-center">
        <h5>
          Cadastro de Usuário
        </h5>
      </span>
    </div>
    <div class="d-flex justify-content-center align-items-center">

      <div class="card shadow p-3 mt-5 me-6 cardUsuario">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <label for="nome">Nome</label>
              <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
            </div>
            <div class="col-md-6">
              <label for="cpf">CPF</label>
              <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label for="nome">E-mail</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="col-md-6">
              <label for="cpf">Senha</label>
              <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha"> 
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mt-3">
              <label class="form-check-label fw-bold" for="flexCheckChecked">Administrador?</label><br />
              <input class="form-check-input" type="checkbox" name="administrador" id="administrador" value="1" id="flexCheckChecked" required>
            </div>
          </div>
          <div class="d-flex justify-content-end mt-3">
            <a href="consultarUsuario.php" class="btn btn-secondary btn-sm me-3">
              <i class="bi bi-arrow-left"></i> Voltar
            </a>
            <button type="submit" class="btn btn-success btn-sm">
              <i class="bi bi-floppy"></i> Salvar
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

</body>

</html>

<style>
  .formCadastro {
    padding: 10px;
  }

  .cardUsuario {
    margin: 30px;
    width: 65vw;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var idUsuario = obterParametroUrl('idUsuario');

    if (idUsuario) {
      buscarDadosUsuarioPorId(idUsuario);
    } else {
      document.getElementById('senha').setAttribute('required', 'required');
    }

    function preencherFormulario(usuario) {
      var dadosUsuario = JSON.parse(usuario);

      if (dadosUsuario.length > 0) {
        dadosUsuario = dadosUsuario[0];

        document.getElementById('nome').value = dadosUsuario.nomeUsuario || '';
        document.getElementById('cpf').value = dadosUsuario.cpfUsuario || '';
        document.getElementById('email').value = dadosUsuario.emailUsuario || '';
        document.getElementById('senha').value = '';
        document.getElementById('administrador').checked = dadosUsuario.isAdmin === 1;
      }
    }

    function obterParametroUrl(nome) {
      var urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(nome);
    }

    function buscarDadosUsuarioPorId(idUsuario) {
      axios.get(`../controllers/UsuarioController.php?action=update&idUsuario=${idUsuario}`)
        .then(function(response) {
          preencherFormulario(response.data);
        })
        .catch(function(error) {
          console.error('Erro ao buscar dados do usuário:', error);
        });
    }

    document.querySelector('form').addEventListener('submit', function(e) {
      e.preventDefault();

      var action = idUsuario ? 'update' : 'create';
      var formData = new FormData(this);
      formData.append('action', action);

      if (idUsuario) {
        formData.append('idUsuario', idUsuario);
      }

      axios.post('../controllers/UsuarioController.php', formData)
        .then(function(response) {
          Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: idUsuario ? "Usuário Atualizado com sucesso." : "Usuário inserido com sucesso.",
            showConfirmButton: false,
            timer: 2000
          });

          setTimeout(function() {
            window.location.href = 'consultarUsuario.php';
          }, 2000);
        })
        .catch(function(error) {
          console.error('Erro ao enviar dados:', error);
          Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente.',
          });
        });
    });

    $j = jQuery.noConflict();

    $j(document).ready(function() {
      $('#cpf').inputmask({
        mask: ['999.999.999-99', '99.999.999/9999-99'],
        keepStatic: true
      });
    });

  });
</script>