<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Viewer</title>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <form>
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
                    <div class="fv-row mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="text" id="email" name="email" class="form-control" placeholder="Digite seu e-mail" required />
                    </div>
                    <div class="fv-row mb-3">
                        <label class="form-label" for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha" required />
                    </div>
                    <div class="d-flex justify-content-between align-items-center">

                        <a href="resetarSenha.php" class="text-body">Esqueceu sua senha?</a>
                    </div>
                    <div class="text-center text-lg-end mt-4 pt-2">
                        <button type="submit" class="btn btn-primary" style="padding-left: 2.5rem; padding-right: 2.5rem;">Logar</button>
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
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();

        var email = document.getElementById('email').value;
        var senha = document.getElementById('senha').value;

        if (email === "" || senha === "") {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'E-mail e senha são de preenchimentos obrigatórios. Por favor, tente novamente.',
            });
        }

        var formData = new FormData(this);
        formData.append('action', 'login');

        axios.post('../controllers/UsuarioController.php', formData)
            .then(function(response) {
                if (!response.data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: response.data.message
                    });
                } else {
                    window.location.href = 'consultarDocumento.php';
                }
            })
            .catch(function(error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Ocorreu um erro ao realizar login. Por favor, tente novamente.'
                });
            });
    });
</script>