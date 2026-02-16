<?php
// 1. Conexão com o banco
require_once "../../conexao.php";

// Lógica de Filtro (Opcional, mas útil para o usuário não se perder)
$filtro = isset($_GET['periodo']) ? $_GET['periodo'] : 'todos';
$where = "1=1";

if ($filtro == 'mes') {
    $where = "MONTH(c.data_consulta) = MONTH(CURRENT_DATE()) AND YEAR(c.data_consulta) = YEAR(CURRENT_DATE())";
} elseif ($filtro == 'ano') {
    $where = "YEAR(c.data_consulta) = YEAR(CURRENT_DATE())";
}

// 2. Busca TODAS as consultas
try {
    $sql = "SELECT c.*, p.nome as nome_paciente 
            FROM consultas c 
            INNER JOIN pacientes p ON c.paciente_id = p.id 
            WHERE $where
            ORDER BY c.data_consulta DESC, c.horario_consulta ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar histórico: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <?php include_once "../../header.php"; ?>
  <style>
    .btn-icon {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
    }
    .btn-icon i { font-size: 16px !important; line-height: 1 !important; margin: 0 !important; }
    .table td { vertical-align: middle !important; }
    
    /* Estilo para destacar a data no histórico */
    .data-badge {
        background: #f3f4f9;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: bold;
        color: #3e4b5b;
        display: block;
        margin-bottom: 2px;
    }
  </style>
</head>
<body>
  <div class="container-scroller">
    <?php include_once "../../sidebar.php"; ?>

    <div class="main-panel">
      <div class="content-wrapper">
        
        <div class="page-header flex-wrap">
          <div class="d-flex align-items-center">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-info text-white me-2">
                <i class="mdi mdi-history"></i>
              </span> Histórico Geral
            </h3>
          </div>
          
          <nav class="mt-2 mt-md-0">
            <div class="btn-group me-2">
                <a href="?periodo=todos" class="btn btn-outline-secondary btn-sm <?php echo $filtro == 'todos' ? 'active' : ''; ?>">Tudo</a>
                <a href="?periodo=mes" class="btn btn-outline-secondary btn-sm <?php echo $filtro == 'mes' ? 'active' : ''; ?>">Este Mês</a>
                <a href="?periodo=ano" class="btn btn-outline-secondary btn-sm <?php echo $filtro == 'ano' ? 'active' : ''; ?>">Este Ano</a>
            </div>
            <a href="listar-consultas.php" class="btn btn-gradient-primary btn-sm">
                <i class="mdi mdi-calendar"></i> Ver Agenda de Hoje
            </a>
          </nav>
        </div>

        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Todas as Consultas Registradas</h4>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th> Data </th>
                        <th> Horário </th>
                        <th> Paciente </th>
                        <th> Procedimento </th>
                        <th> Valor </th>
                        <th> Pagamento </th>
                        <th> Status </th>
                        <th class="text-center"> Ações </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (count($consultas) > 0): ?>
                        <?php foreach ($consultas as $c): ?>
                          <tr>
                            <td>
                                <span class="data-badge">
                                    <?php echo date('d/m/Y', strtotime($c['data_consulta'])); ?>
                                </span>
                            </td>
                            <td> 
                                <strong class="text-primary">
                                    <?php echo date('H:i', strtotime($c['horario_consulta'])); ?>
                                </strong>
                            </td>
                            <td> <?php echo htmlspecialchars($c['nome_paciente']); ?> </td>
                            <td> <?php echo htmlspecialchars($c['tipo_procedimento'] ?? "---"); ?> </td>
                            <td> R$ <?php echo number_format($c['valor'] ?? 0, 2, ',', '.'); ?> </td>
                            <td>
                                <label class="badge <?php echo ($c['status_pagamento'] == 'Pago') ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo $c['status_pagamento']; ?>
                                </label>
                            </td>
                            <td>
                                <span class="text-small font-weight-bold"><?php echo $c['status']; ?></span>
                            </td>
                            <td class="text-center">
                              <div class="d-flex justify-content-center align-items-center">
                                <a href="editar-consulta.php?id=<?php echo $c['id']; ?>" 
                                   class="btn btn-inverse-info btn-icon me-2" 
                                   title="Editar">
                                   <i class="mdi mdi-pencil"></i>
                                </a>
                                <button type="button" 
                                   class="btn btn-inverse-danger btn-icon" 
                                   onclick="confirmarExclusao(<?php echo $c['id']; ?>, '<?php echo $c['status_pagamento']; ?>')" 
                                   title="Excluir">
                                   <i class="mdi mdi-delete"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="8" class="text-center py-5 text-muted">
                             Nenhum registro encontrado no banco de dados.
                          </td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include_once "../../footer.php"; ?>
    </div>
  </div>
  <?php include_once "../../scripts.php"; ?>

  <script>
    function confirmarExclusao(id, statusPagamento) {
        if (confirm("Tem certeza que deseja excluir este registro do histórico?")) {
            let excluirFinanceiro = 0;
            if (statusPagamento === 'Pago' || statusPagamento === 'Parcial') {
                if (confirm("Deseja excluir também o registro financeiro vinculado?")) {
                    excluirFinanceiro = 1;
                }
            }
            window.location.href = "excluir-consulta.php?id=" + id + "&excluir_fin=" + excluirFinanceiro;
        }
    }
  </script>
</body>
</html>