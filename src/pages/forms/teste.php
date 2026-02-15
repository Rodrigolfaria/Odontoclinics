<!DOCTYPE html>
<html lang="pt-br">
<head>
  <?php 
    // Tentando carregar o header subindo dois níveis
    if (file_exists("../../header.php")) {
        include_once "../../header.php";
    } else {
        echo "<style>body{background:red; color:white;}</style><h1>ERRO: O PHP não achou ../../header.php</h1>";
    }
  ?>
  
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
  <div class="container-scroller">
    
    <?php if(file_exists("../../navbar.php")) include_once "../../navbar.php"; ?>

    <div class="container-fluid page-body-wrapper">
      
      <?php if(file_exists("../../sidebar.php")) include_once "../../sidebar.php"; ?>

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-body">
              <h2 class="text-primary">Teste de Pasta: FORMS</h2>
              <p>Se você está vendo isso, o PHP encontrou os arquivos.</p>
              <hr>
              <p>Caminho atual: <code>src/pages/forms/teste.php</code></p>
            </div>
          </div>
        </div>
        
        <?php if(file_exists("../../footer.php")) include_once "../../footer.php"; ?>
      </div>
    </div>
  </div>

  <?php if(file_exists("../../scripts.php")) include_once "../../scripts.php"; ?>
</body>
</html>