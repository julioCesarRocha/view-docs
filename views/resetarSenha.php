<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document Viewer</title>
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <form action="../controllers/UsuarioController.php" method="POST">
    <input type="hidden" name="action" value="login">
    <section class="vh-100">
      <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-md-9 col-lg-6 col-xl-5 backgroundLogo" style='background-image:url("../assets/imagens/auth-bg.png")'>
            <img src="../assets/imagens/ISYOUlogin.svg" class="img-fluid" alt="Sample image">
          </div>
          <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <div class="text-center mb-11 displayNoneIfResponsive">
              <a href="/" class="mb-12">
                <img alt="Logo" src="../assets/imagens/logo.svg" style="height: 15vh;" />
              </a>
            </div>
            <div class="divider d-flex align-items-center my-4" />
          </div>
          <div class="text-center mb-10">
            <h3 class="text-dark fw-bolder mb-3">
              Esqueceu sua senha?
            </h3>

            <div class="text-gray-500 fw-semibold fs-6">
              Insira seu e-mail para redefinir sua senha.
            </div>
          </div>
          <div id="divEmail">
            <div class="fv-row mb-3">
              <label class="form-label" for="email">Email</label>
              <input type="text" id="email" name="email" class="form-control" placeholder="Digite seu e-mail" />
            </div>
            <div class="d-flex justify-content-end mt-3">
              <a href="login.php" class="btn btn-secondary me-3">
                <i class="bi bi-arrow-left"></i> Voltar
              </a>
              <a class="btn btn-primary" id="btnEnviar">
                <i class="bi bi-check-lg"></i> Enviar
              </a>
            </div>
          </div>

          <div class="mt-3" id="divRecuperarSenha" style="display: none;">
            <div class="fv-row mb-3">
              <label class="form-label" for="senha">Senha</label>
              <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha" />
            </div>
            <div class="fv-row mb-3">
              <label class="form-label" for="confirmacaoSenha">Confirmar Senha</label>
              <input type="password" id="confirmacaoSenha" name="confirmacaoSenha" class="form-control" placeholder="Confirme sua senha" />
            </div>
            <div class="d-flex justify-content-end mt-3">
              <a href="login.php" class="btn btn-secondary me-3">
                <i class="bi bi-arrow-left"></i> Voltar
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-floppy"></i> Recuperar Senha
              </button>
            </div>
          </div>

        </div>
      </div>
      </div>
      <div class="d-flex flex-column flex-md-row align-items-center justify-content-center py-4 px-4 px-xl-5 bg-secondary-light">
        <div class="text-black mb-3 mb-md-0">
          Copyright © 2024. All rights reserved.
        </div>
      </div>
    </section>
  </form>
</body>

</html>

<style>
  .divider:after,
  .divider:before {
    content: "";
    flex: 1;
    height: 1px;
    background: #eee;
  }

  .h-custom {
    height: calc(100% - 73px);
  }

  @media (max-width: 450px) {
    .h-custom {
      height: 100%;
    }
  }

  .backgroundLogo {
    height: 100%;
    width: 50%;
    margin-left: -9%;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('btnEnviar').addEventListener('click', function() {
      var email = document.getElementById('email').value;
      if (email) {
        document.getElementById('divEmail').style.display = 'none';
        document.getElementById('divRecuperarSenha').style.display = 'block';
      }
    });

    document.querySelector('form').addEventListener('submit', function(e) {
      e.preventDefault();

      var email = document.getElementById('email').value;
      var senha = document.getElementById('senha').value;
      var confirmacaoSenha = document.getElementById('confirmacaoSenha').value;

      if (confirmacaoSenha !== senha) {
        Swal.fire({
          icon: 'error',
          title: 'Erro!',
          text: 'As senhas inseridas não conferem. Por favor, tente novamente.',
        });
        return;
      }

      var formData = new FormData(this);
      formData.append('action', 'recuperarSenha');

      axios.post('../controllers/UsuarioController.php', formData)
        .then(function(response) {
          Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: "Senha atualizada com sucesso.",
            showConfirmButton: false,
            timer: 2000
          });

          setTimeout(function() {
            window.location.href = 'login.php';
          }, 2000);
        })
        .catch(function(error) {
          console.error('Erro ao enviar dados:', error);
          Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Ocorreu um erro ao recuperar sua senha. Por favor, tente novamente.',
          });
        });
    });

  });
</script>