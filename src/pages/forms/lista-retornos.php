<?php
// 1. AJUSTE DE CONEXÃO: Sobe duas pastas para encontrar o conexao.php na raiz
require_once "../../conexao.php"; 

// Captura o filtro da URL (padrão é 'hoje')
$filtro = $_GET['f'] ?? 'hoje';

function getFiltroSQL($tipo, $campo = 'data_retorno') {
    switch ($tipo) {
        case 'semana': return "YEARWEEK($campo, 1) = YEARWEEK(CURDATE(), 1)";
        case 'mes':    return "MONTH($campo) = MONTH(CURDATE()) AND YEAR($campo) = YEAR(CURDATE())";
        case 'ano':    return "YEAR($campo) = YEAR(CURDATE())";
        default:       return "DATE($campo) = CURDATE()"; 
    }
}

try {
    $where = getFiltroSQL($filtro);
    // SQL traz a diferença de dias entre hoje e a data de retorno
    $sql = "SELECT c.id, c.data_retorno, p.nome, p.telefone,
            DATEDIFF(c.data_retorno, CURDATE()) as dias_faltando
            FROM consultas c
            JOIN pacientes p ON c.paciente_id = p.id
            WHERE $where AND c.status != 'Cancelada' AND c.data_retorno IS NOT NULL
            ORDER BY c.data_retorno ASC";
    $stmt = $conn->query($sql);
    $retornos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao carregar retornos: " . $e->getMessage());
}

$path = "http://localhost:8888/odontoclinics/src/";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Retornos Pendentes - OdontoClinics</title>
  <link rel="stylesheet" href="../../assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="shortcut icon" href="../../assets/images/favicon.png" />
  <style>
    .badge-atrasado { background-color: #fe7c96; color: white; }
    .badge-hoje { background-color: #1bcfb4; color: white; }
    .badge-futuro { background-color: #b66dff; color: white; }
  </style>
</head>
<body>
  <div class="container-scroller">
    <?php if(file_exists("../../navbar.php")) { include_once "../../navbar.php"; } ?>
    <div class="container-fluid page-body-wrapper">
      <?php if(file_exists("../../sidebar.php")) { include_once "../../sidebar.php"; } ?>

      <div class="main-panel">
        <div class="content-wrapper">
          
          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-calendar-check"></i>
              </span> Gestão de Retornos
            </h3>
            
            <div class="btn-group" role="group">
              <a href="?f=hoje" class="btn btn-outline-primary <?= $filtro == 'hoje' ? 'active' : '' ?>">Hoje</a>
              <a href="?f=semana" class="btn btn-outline-primary <?= $filtro == 'semana' ? 'active' : '' ?>">Semana</a>
              <a href="?f=mes" class="btn btn-outline-primary <?= $filtro == 'mes' ? 'active' : '' ?>">Mês</a>
              <a href="?f=ano" class="btn btn-outline-primary <?= $filtro == 'ano' ? 'active' : '' ?>">Ano</a>
            </div>
          </div>

          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Pacientes para Retorno (Filtro: <?= ucfirst($filtro) ?>)</h4>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th> Paciente </th>
                          <th> Data Prevista </th>
                          <th> Status / Prazo </th>
                          <th> Ação </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (count($retornos) > 0): ?>
                          <?php foreach ($retornos as $r): 
                              $whats = preg_replace('/[^0-9]/', '', $r['telefone']);
                              $msg = urlencode("Olá " . $r['nome'] . ", aqui é da clínica da Dra. Priscilla. Gostaríamos de agendar seu retorno previsto para o dia " . date('d/m', strtotime($r['data_retorno'])) . ".");
                              
                              // Lógica visual do prazo
                              $dias = $r['dias_faltando'];
                              if ($dias < 0) {
                                  $status_txt = "Atrasado " . abs($dias) . " dia(s)";
                                  $classe_badge = "badge-atrasado";
                              } elseif ($dias == 0) {
                                  $status_txt = "É HOJE!";
                                  $classe_badge = "badge-hoje";
                              } else {
                                  $status_txt = "Faltam $dias dia(s)";
                                  $classe_badge = "badge-futuro";
                              }
                          ?>
                          <tr>
                            <td>
                              <i class="mdi mdi-account-circle text-info me-2"></i>
                              <?= htmlspecialchars($r['nome']) ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($r['data_retorno'])) ?></td>
                            <td>
                              <label class="badge <?= $classe_badge ?>"><?= $status_txt ?></label>
                            </td>
                            <td>
                              <a href="https://wa.me/55<?= $whats ?>?text=<?= $msg ?>" target="_blank" class="btn btn-gradient-success btn-sm">
                                <i class="mdi mdi-whatsapp"></i> Chamar
                              </a>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr><td colspan="4" class="text-center py-5 text-muted">Nenhum retorno encontrado para este filtro.</td></tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php if(file_exists("../../footer.php")) { include_once "../../footer.php"; } ?>
      </div>
    </div>
  </div>

  <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="../../assets/js/off-canvas.js"></script>
  <script src="../../assets/js/hoverable-collapse.js"></script>
  <script src="../../assets/js/misc.js"></script>
</body>
</html>