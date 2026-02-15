<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>OdontoClinics - Painel</title>

  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    
    <?php 
      if(file_exists("navbar.php")) {
          include_once "navbar.php"; 
      } else {
          // Fallback caso o arquivo não seja encontrado para não quebrar o layout
          echo "";
      }
    ?>

    <div class="container-fluid page-body-wrapper">
      
      <?php 
        if(file_exists("sidebar.php")) {
            include_once "sidebar.php"; 
        }
      ?>

      <div class="main-panel">
        <div class="content-wrapper">
          
          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
              </span> Dashboard
            </h3>
          </div>

          <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Consultas Hoje <i class="mdi mdi-chart-line mdi-24px float-end"></i></h4>
                  <h2 class="mb-5">12</h2>
                  <h6 class="card-text">Próxima às 14:30</h6>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Consultas na Semana <i class="mdi mdi-bookmark-outline mdi-24px float-end"></i></h4>
                  <h2 class="mb-5">48</h2>
                  <h6 class="card-text">8% a mais que a semana passada</h6>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                  <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Alerta de Retorno <i class="mdi mdi-diamond mdi-24px float-end"></i></h4>
                  <h2 class="mb-5">07</h2>
                  <h6 class="card-text">Pacientes para ligar hoje</h6>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-7 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title float-start">Fluxo de Pacientes</h4>
                  <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-end"></div>
                  <canvas id="visit-sale-chart" class="mt-4"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-5 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Especialidades</h4>
                  <canvas id="traffic-chart"></canvas>
                  <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Fila de Atendimento</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th> Paciente </th>
                          <th> Chegada </th>
                          <th> Tempo de Espera </th>
                          <th> Sala </th>
                          <th> Ação </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><img src="assets/images/faces/face1.jpg" class="me-2" alt="image"> Roberto Souza </td>
                          <td> 13:45 </td>
                          <td> <span class="text-danger">15 min</span> </td>
                          <td> Consultório 01 </td>
                          <td><button class="btn btn-gradient-primary btn-sm">Chamar</button></td>
                        </tr>
                        <tr>
                          <td><img src="assets/images/faces/face2.jpg" class="me-2" alt="image"> Juliana Lins </td>
                          <td> 14:05 </td>
                          <td> <span class="text-success">5 min</span> </td>
                          <td> Recepção </td>
                          <td><button class="btn btn-gradient-light btn-sm">Aguardar</button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div> <?php include_once "footer.php"; ?>

      </div> </div> </div> <div id="proBanner" style="display:none !important;"><button id="bannerClose"></button></div>

  <?php include_once "scripts.php"; ?>
</body>
</html>