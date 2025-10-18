<!doctype html>
<?php
    include 'verifica_login.inc';
?>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestão de Dados Pessoais</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/dataTables.dataTables.css">

    <link rel="icon" href="assets/img/logo_login.png" sizes="32x32" type="image/png">
    <meta name="theme-color" content="#7952b3">

    <script src="assets/js/jquery-3.7.1.js"></script>
    <script src="assets/js/dataTables.js"></script>

    <style>
      .card form .form-control,
      .card form select,
      .card form textarea {
        margin-bottom: 0.75rem;
      }

      textarea {
        resize: vertical;
      }
    </style>
  </head>
  <body>

<div class="container py-3">
  <header>
    <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
      <a href="#" class="d-flex align-items-center text-dark text-decoration-none">
          <img src="assets/img/logo_login.png" style="width:40px; height:40px;" alt="Logotipo">
            <span class="fs-4">&nbsp;&nbsp;Gestão de Dados Pessoais</span>
      </a>

      <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
        <a class="me-3 py-2 text-dark text-decoration-none" href="#"><b>Usuário:</b> <?php echo $nome_usuario; ?></a>
        <a class="me-3 py-2 text-dark text-decoration-none" href="#"><b>Último acesso:</b> <?php $data_form = explode("-", $data); echo $data_form[2]."/".$data_form[1]."/".$data_form[0]." - ". substr($hora,0,5)." - ".$host; ?></a>
        <a class="py-2 text-dark text-decoration-none" href="logout.php"><b>Logout</b></a>
      </nav>
    </div>

    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
      <h1 class="display-5 fw-normal">Cadastro de Dados Pessoais</h1>
      <p class="fs-5 text-muted">Mantenha atualizado o cadastro de pessoas com informações de contato e situação.</p>
    </div>
  </header>

  <main>
    <div class="table-responsive">
      <table id="dados-pessoais" class="display table table-striped table-bordered align-middle">
              <thead class="table-light">
                  <tr>
                      <th>Matrícula</th>
                      <th>Nome</th>
                      <th>Email</th>
                      <th>Celular</th>
                      <th>Nascimento</th>
                      <th>Ações</th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
    </div>

    <div class="row row-cols-1 row-cols-xl-2 mb-2 g-4">
      <div class="col">
        <div class="card h-100 shadow-sm">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Novo cadastro</h4>
          </div>
          <div class="card-body">
            <form id="form-create">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                <label for="celular" class="form-label">Celular</label>
                <input type="text" class="form-control" id="celular" name="celular" placeholder="Celular">
                <label for="data_nascimento" class="form-label">Data de nascimento</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento">
                <button type="button" id="btSalvarDados" class="w-100 btn btn-lg btn-outline-primary mt-2">Salvar</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Editar cadastro</h4>
          </div>
          <div class="card-body">
                <form id="form-edit">
                    <input type="hidden" id="edmatricula" name="matricula">
                    <label for="ednome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="ednome" name="nome" required>
                    <label for="edemail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="edemail" name="email">
                    <label for="edcelular" class="form-label">Celular</label>
                    <input type="text" class="form-control" id="edcelular" name="celular">
                    <label for="eddata_nascimento" class="form-label">Data de nascimento</label>
                    <input type="date" class="form-control" id="eddata_nascimento" name="data_nascimento">
                    <button type="button" id="btAlterarDados" class="w-100 btn btn-lg btn-outline-primary mt-3">Alterar</button>
                </form>
          </div>
        </div>
      </div>
    </div>

  </main>

  <footer class="pt-4 my-md-5 pt-md-5 border-top">
    <div class="row">
      <div class="col-12 col-md">
        <small class="d-block mb-3 text-muted">&copy; 2025 - Desenvolvimento de Aplicações Distribuídas - Prof Paulo Henrique Rodrigues</small>
        <small class="d-block mb-3 text-muted">PUC Minas Barreiro - Sistemas de Informação</small>
      </div>
    </div>
  </footer>
</div>

  <script src="assets/js/dados_pessoais.js"></script>
  </body>
</html>
