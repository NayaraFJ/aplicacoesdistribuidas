<!doctype html>
<?php
    include 'verifica_login.inc'
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Gestão de Usuários</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/pricing/">    

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/dataTables.dataTables.css">
    
    <!-- Icons -->
    <link rel="icon" href="assets/img/logo_login.png" sizes="32x32" type="image/png">
    <meta name="theme-color" content="#7952b3">

    <!-- Javascript -->
    <script src="assets/js/jquery-3.7.1.js"></script>
    <script src="assets/js/dataTables.js"></script>
        
        



    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="pricing.css" rel="stylesheet">
  </head>
  <body>
    

<div class="container py-3">
  <header>
    <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
      <a href="#" class="d-flex align-items-center text-dark text-decoration-none">
          <img src="assets/img/logo_login.png" style = "width:40px; height:40px;">
            <span class="fs-4">&nbsp;&nbsp;Gestão de Usuários</span>
      </a>
        
      <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
        <a class="me-3 py-2 text-dark text-decoration-none" href="#"><b>Usuário:</b> <?php echo $nome_usuario; ?></a>
        <a class="me-3 py-2 text-dark text-decoration-none" href="#"><b>Ultimo Acesso:</b><?php $data_form = explode("-", $data); echo $data_form[2]."/".$data_form[1]."/".$data_form[0]." - ". substr($hora,0,5)." - ".$host; ?></a>
        <a class="py-2 text-dark text-decoration-none" href="logout.php"><b>Logout</b></a>
      </nav>
    </div>

    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
      <h1 class="display-4 fw-normal">Usuários Cadastrados</h1>
      <p class="fs-5 text-muted">Cadastre, edite ou delete os usuários que estão na base de dados.</p>
    </div>
  </header>

  <main>
    <table id="people" class="display table text-center">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
      
    <div class="row row-cols-1 row-cols-md-2 mb-2 text-center">
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Novo Cadastro</h4>
          </div>
          <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="nome">
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1"> Ativo </option>
                        <option value="0"> Inativo </option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="button" id="btSalvar" value="Salvar" class="form-control w-100 btn btn-lg btn-outline-primary">
                </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Editar Cadastro</h4>
          </div>
          <div class="card-body">
                <div class="mb-3">
                    <label for="edidpessoa" class="form-label">ID</label>
                    <input type="text" class="form-control" id="edidpessoas" name="edidpessoas" disabled>
                </div>
                <div class="mb-3">
                    <label for="ednome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="ednome" name="ednome">
                </div>
                <div class="mb-3">
                    <label for="edstatus" class="form-label">Status</label>
                    <select name="edstatus" id="edstatus" class="form-control">
                        <option value="1"> Ativo </option>
                        <option value="0"> Inativo </option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="button" id="btAlterar" value="Alterar" class="form-control w-100 btn btn-lg btn-outline-primary">
                </div>
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


    
  </body>
</html>

<script src="assets/js/script.js"> </script>    
</html>
