<?php
require_once "conexao.php";

// Configurações de Data e Filtros
$hoje = date('Y-m-d');
$agora = new DateTime();

$filtro_age = $_GET['f_age'] ?? 'hoje';
$filtro_can = $_GET['f_can'] ?? 'hoje';
$filtro_ret = $_GET['f_ret'] ?? 'hoje';

function getFiltroSQL($tipo, $campo = 'data_consulta') {
    switch ($tipo) {
        case 'semana': return "YEARWEEK($campo, 1) = YEARWEEK(CURDATE(), 1)";
        case 'mes':    return "MONTH($campo) = MONTH(CURDATE()) AND YEAR($campo) = YEAR(CURDATE())";
        case 'ano':    return "YEAR($campo) = YEAR(CURDATE())";
        default:       return "$campo = CURDATE()"; 
    }
}

try {
    // 1. Cards de Resumo
    $where_age = getFiltroSQL($filtro_age);
    $total_agendados = $conn->query("SELECT COUNT(*) FROM consultas WHERE $where_age AND status != 'Cancelado'")->fetchColumn();

    $where_can = getFiltroSQL($filtro_can);
    $total_cancelados = $conn->query("SELECT COUNT(*) FROM consultas WHERE $where_can AND status = 'Cancelado'")->fetchColumn();

    $where_ret = getFiltroSQL($filtro_ret, 'data_retorno');
    $total_retorno = $conn->query("SELECT COUNT(*) FROM consultas WHERE $where_ret AND status != 'Cancelado' AND data_retorno IS NOT NULL")->fetchColumn();

    // 2. Fila de Atendimento
    $sql_fila = "SELECT c.id, c.horario_consulta, c.status, p.nome 
                 FROM consultas c
                 JOIN pacientes p ON c.paciente_id = p.id
                 WHERE c.data_consulta = :hoje 
                 AND c.status NOT IN ('Realizado', 'Cancelado')
                 ORDER BY c.horario_consulta ASC";
    $stmt_fila = $conn->prepare($sql_fila);
    $stmt_fila->execute([':hoje' => $hoje]);
    $pacientes_fila = $stmt_fila->fetchAll(PDO::FETCH_ASSOC);

    // 3. LOGICA PARA GRÁFICO DE BARRAS AGRUPADAS
    $meses_labels = [];
    $dados_agrupados = []; 
    $meses_pt = ['Jan'=>'Jan','Feb'=>'Fev','Mar'=>'Mar','Apr'=>'Abr','May'=>'Mai','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Ago','Sep'=>'Set','Oct'=>'Out','Nov'=>'Nov','Dec'=>'Dez'];

    $todas_especialidades = $conn->query("SELECT DISTINCT especialidade FROM consultas WHERE especialidade IS NOT NULL AND especialidade != ''")->fetchAll(PDO::FETCH_COLUMN);

    for ($i = 2; $i >= 0; $i--) {
        $data_obj = (new DateTime())->modify("-$i months");
        $mes_ref = $data_obj->format('Y-m');
        $label_en = $data_obj->format('M');
        $meses_labels[] = $meses_pt[$label_en] ?? $label_en;

        foreach ($todas_especialidades as $esp) {
            $sql = "SELECT COUNT(*) FROM consultas WHERE DATE_FORMAT(data_consulta, '%Y-%m') = '$mes_ref' AND especialidade = ? AND status != 'Cancelado'";
            $stmt_esp = $conn->prepare($sql);
            $stmt_esp->execute([$esp]);
            $dados_agrupados[$esp][] = (int)$stmt_esp->fetchColumn();
        }
    }

    // 4. Top 3 Especialidades (Pizza)
    $sql_top = "SELECT especialidade, COUNT(*) as total 
                FROM consultas 
                WHERE status != 'Cancelado' 
                AND especialidade IS NOT NULL AND especialidade != '' 
                GROUP BY especialidade 
                ORDER BY total DESC LIMIT 3";
    $top_especialidades = $conn->query($sql_top)->fetchAll(PDO::FETCH_ASSOC);
    $pie_labels = array_column($top_especialidades, 'especialidade');
    $pie_valores = array_column($top_especialidades, 'total');

} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>OdontoClinics - Painel</title>
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="shortcut icon" href="assets/images/favicon.png" />
  <style>
    .card-img-absolute { position: absolute; top: 0; right: 0; height: 100%; opacity: 0.5 !important; filter: brightness(0) invert(1); }
    .dropdown-card { position: absolute; top: 15px; right: 15px; z-index: 10; }
    .dropdown-card .btn { color: white !important; opacity: 0.9; font-size: 0.8rem; border: 1px solid rgba(255,255,255,0.4); }
    .card-link { text-decoration: none; color: inherit; display: block; }
  </style>
</head>
<body>
  <div class="container-scroller">
    <?php if(file_exists("navbar.php")) include_once "navbar.php"; ?>
    <div class="container-fluid page-body-wrapper">
      <?php if(file_exists("sidebar.php")) include_once "sidebar.php"; ?>

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
              <div class="card bg-gradient-primary card-img-holder text-white">
                <div class="card-body">
                  <div class="dropdown dropdown-card">
                    <button class="btn btn-link btn-sm dropdown-toggle text-white p-0" type="button" data-bs-toggle="dropdown">
                      <?= ucfirst($filtro_age) ?></button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="?f_age=hoje&f_can=<?= $filtro_can ?>&f_ret=<?= $filtro_ret ?>">Hoje</a></li>
                      <li><a class="dropdown-item" href="?f_age=semana&f_can=<?= $filtro_can ?>&f_ret=<?= $filtro_ret ?>">Semana</a></li>
                      <li><a class="dropdown-item" href="?f_age=mes&f_can=<?= $filtro_can ?>&f_ret=<?= $filtro_ret ?>">Mês</a></li>
                      <li><a class="dropdown-item" href="?f_age=ano&f_can=<?= $filtro_can ?>&f_ret=<?= $filtro_ret ?>">Ano</a></li>
                    </ul>
                  </div>
                  <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Agendamentos <i class="mdi mdi-calendar-check mdi-24px float-end"></i></h4>
                  <h2 class="mb-5"><?= $total_agendados ?></h2>
                </div>
              </div>
            </div>

            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                  <div class="dropdown dropdown-card">
                    <button class="btn btn-link btn-sm dropdown-toggle text-white p-0" type="button" data-bs-toggle="dropdown">
                      <?= ucfirst($filtro_can) ?></button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="?f_can=hoje&f_age=<?= $filtro_age ?>&f_ret=<?= $filtro_ret ?>">Hoje</a></li>
                      <li><a class="dropdown-item" href="?f_can=semana&f_age=<?= $filtro_age ?>&f_ret=<?= $filtro_ret ?>">Semana</a></li>
                      <li><a class="dropdown-item" href="?f_can=mes&f_age=<?= $filtro_age ?>&f_ret=<?= $filtro_ret ?>">Mês</a></li>
                      <li><a class="dropdown-item" href="?f_can=ano&f_age=<?= $filtro_age ?>&f_ret=<?= $filtro_ret ?>">Ano</a></li>
                    </ul>
                  </div>
                  <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Cancelados <i class="mdi mdi-calendar-remove mdi-24px float-end"></i></h4>
                  <h2 class="mb-5"><?= $total_cancelados ?></h2>
                </div>
              </div>
            </div>

            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <div class="dropdown dropdown-card">
                    <button class="btn btn-link btn-sm dropdown-toggle text-white p-0" type="button" data-bs-toggle="dropdown">
                      <?= ucfirst($filtro_ret) ?></button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="?f_ret=hoje&f_age=<?= $filtro_age ?>&f_can=<?= $filtro_can ?>">Hoje</a></li>
                      <li><a class="dropdown-item" href="?f_ret=semana&f_age=<?= $filtro_age ?>&f_can=<?= $filtro_can ?>">Semana</a></li>
                      <li><a class="dropdown-item" href="?f_ret=mes&f_age=<?= $filtro_age ?>&f_can=<?= $filtro_can ?>">Mês</a></li>
                      <li><a class="dropdown-item" href="?f_ret=ano&f_age=<?= $filtro_age ?>&f_can=<?= $filtro_can ?>">Ano</a></li>
                    </ul>
                  </div>
                  <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Retornos Pendentes <i class="mdi mdi-account-star mdi-24px float-end"></i></h4>
                  <a href="pages/forms/lista-retornos.php?f=<?= $filtro_ret ?>" class="card-link">
                    <h2 class="mb-5"><?= $total_retorno ?></h2>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-7 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Ranking de Especialidades por Mês</h4>
                  <canvas id="visit-sale-chart" class="mt-4"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-5 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Top 3 Especialidades</h4>
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
                  <h4 class="card-title">Fila de Atendimento - Hoje</h4>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th> Paciente </th>
                          <th> Horário </th>
                          <th> Status </th>
                          <th> Ação </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(count($pacientes_fila) > 0): ?>
                          <?php foreach ($pacientes_fila as $p): ?>
                            <tr>
                              <td><?= htmlspecialchars($p['nome']) ?></td>
                              <td><?= date('H:i', strtotime($p['horario_consulta'])) ?></td>
                              <td><label class="badge badge-gradient-info"><?= $p['status'] ?></label></td>
                              <td><a href="pages/forms/editar-consulta.php?id=<?= $p['id'] ?>" class="btn btn-gradient-primary btn-sm">Acessar</a></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr><td colspan="4" class="text-center py-4 text-muted">Nenhum atendimento pendente para hoje.</td></tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div> 
        <?php if(file_exists("footer.php")) include_once "footer.php"; ?>
      </div> 
    </div> 
  </div>

  <?php if(file_exists("scripts.php")) include_once "scripts.php"; ?>

  <script>
  $(function () {

    // Destroy charts (Chart.js 3/4)
    if (window.Chart) {
      const barChart = Chart.getChart('visit-sale-chart');
      if (barChart) barChart.destroy();

      const pieChart = Chart.getChart('traffic-chart');
      if (pieChart) pieChart.destroy();
    }

    var mesesLabels = <?php echo json_encode($meses_labels); ?>;
    var dadosAgrupados = <?php echo json_encode($dados_agrupados); ?>;
    var pieLabels = <?php echo json_encode($pie_labels); ?>;
    var pieValores = <?php echo json_encode($pie_valores); ?>;

    var paletaCores = ['#b66dff', '#fe7096', '#00d25b', '#ffab00', '#0090e7', '#3e4b5b'];

    var datasetsBarras = [];
    var idx = 0;
    for (var esp in dadosAgrupados) {
      datasetsBarras.push({
        label: esp,
        data: dadosAgrupados[esp],
        backgroundColor: paletaCores[idx % paletaCores.length],
        borderColor: paletaCores[idx % paletaCores.length],
        borderWidth: 0,
        barPercentage: 0.4,
        categoryPercentage: 0.5
      });
      idx++;
    }

    if ($("#visit-sale-chart").length) {
      var ctxBar = document.getElementById('visit-sale-chart').getContext("2d");

      // MAIOR VALOR + 5 (robusto contra null/strings)
      var todosValores = Object.values(dadosAgrupados)
        .flat()
        .map(v => Number(v))
        .filter(v => Number.isFinite(v));

      var maxValorEncontrado = todosValores.length ? Math.max(...todosValores) : 0;
      var escalaYMax = maxValorEncontrado + 5;

      new Chart(ctxBar, {
        type: 'bar',
        data: {
          labels: mesesLabels,
          datasets: datasetsBarras
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              display: true,
              position: 'bottom',
              labels: { boxWidth: 15, padding: 20, color: "#9c9fa6" }
            }
          },
          scales: {
            y: {
              display: true,
              beginAtZero: true,
              max: escalaYMax,     // ✅ FORÇA O TOPO DO EIXO (Chart.js 3/4)
              grace: 0,            // ✅ se quiser mais folga ainda, troca pra: 5 ou '10%'
              grid: {
                display: false,
                drawBorder: false
              },
              ticks: {
                color: "#9c9fa6",
                padding: 10
              }
            },
            x: {
              grid: {
                display: false,
                drawBorder: false
              },
              ticks: {
                color: "#9c9fa6",
                padding: 10
              }
            }
          }
        }
      });
    }

    if ($("#traffic-chart").length) {
      var ctxPie = document.getElementById('traffic-chart').getContext("2d");

      new Chart(ctxPie, {
        type: 'doughnut',
        data: {
          labels: pieLabels,
          datasets: [{
            data: pieValores,
            backgroundColor: ['#b66dff', '#fe7096', '#00d25b'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          animation: { animateScale: true, animateRotate: true },
          plugins: {
            legend: {
              display: true,          // ✅ LEGENDA LIGADA NO PIE
              position: 'bottom',
              labels: { color: "#9c9fa6", padding: 20, boxWidth: 12 }
            }
          }
        }
      });
    }

  });
</script>

</body>
</html> 
