<?php
// 1. Conexão com o banco
require_once "../../conexao.php";

// Lógica para Navegação de Datas
$data_selecionada = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

// Calcula as datas de Ontem e Amanhã
$data_anterior = date('Y-m-d', strtotime('-1 day', strtotime($data_selecionada)));
$data_proxima  = date('Y-m-d', strtotime('+1 day', strtotime($data_selecionada)));

// Formatação para exibição
$data_formatada = date('d/m/Y', strtotime($data_selecionada));
$label_dia = ($data_selecionada == date('Y-m-d')) ? "Hoje" : $data_formatada;

// 2. Busca as consultas
try {
    $sql = "SELECT c.*, p.nome as nome_paciente 
            FROM consultas c 
            INNER JOIN pacientes p ON c.paciente_id = p.id 
            WHERE c.data_consulta = :data
            ORDER BY c.horario_consulta ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([':data' => $data_selecionada]);
    $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar consultas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <?php include_once "../../header.php"; ?>
  <style>
    /* Correção do alinhamento dos botões de ação */
    .btn-icon {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
    }
    
    .btn-icon i {
        font-size: 16px !important;
        line-height: 1 !important;
        margin: 0 !important;
    }

    /* Alinhamento vertical das células da tabela */
    .table td {
        vertical-align: middle !important;
    }

    /* Estilo do Navegador Integrado ao Header */
    .header-nav-container {
        display: flex;
        align-items: center;
        background: #fff;
        padding: 5px 15px;
        border-radius: 8px;
        border: 1px solid #ebedf2;
        margin-left: 20px;
    }
    
    .input-datepicker {
        border: none;
        font-weight: bold;
        color: #3e4b5b;
        font-size: 1rem;
        text-align: center;
        background: transparent;
        cursor: pointer;
        width: 140px;
        outline: none;
    }

    .btn-nav-arrow {
        padding: 0;
        color: #b66dff;
        font-size: 1.5rem;
        transition: 0.3s;
        text-decoration: none !important;
    }

    .btn-nav-arrow:hover { color: #8e44ad; }

    .btn-hoje-badge {
        font-size: 0.7rem;
        padding: 2px 8px;
        vertical-align: middle;
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
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-calendar-today"></i>
              </span> Agenda
            </h3>

            <div class="header-nav-container d-none d-md-flex">
                <a href="?data=<?php echo $data_anterior; ?>" class="btn-nav-arrow"><i class="mdi mdi-chevron-left"></i></a>
                
                <input type="date" 
                       id="seletorData" 
                       class="input-datepicker" 
                       value="<?php echo $data_selecionada; ?>" 
                       onchange="mudarData(this.value)">
                
                <a href="?data=<?php echo $data_proxima; ?>" class="btn-nav-arrow"><i class="mdi mdi-chevron-right"></i></a>
                
                <?php if($data_selecionada != date('Y-m-d')): ?>
                    <a href="listar-consultas.php" class="btn btn-inverse-primary btn-hoje-badge ms-2">HOJE</a>
                <?php endif; ?>
            </div>
          </div>
          
          <nav class="mt-2 mt-md-0">
            <a href="listar-todas.php" class="btn btn-gradient-info btn-sm me-2">
                <i class="mdi mdi-history"></i> Histórico Geral
            </a>
            <a href="agendar-consulta.php" class="btn btn-gradient-primary btn-sm">
                <i class="mdi mdi-plus"></i> Novo Agendamento
            </a>
          </nav>
        </div>

        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Pacientes para <?php echo $data_formatada; ?></h4>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th class="text-center"> Horário </th>
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
                            <td class="text-center"> 
                                <strong class="text-primary" style="font-size: 1.1rem;">
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
                          <td colspan="7" class="text-center py-5 text-muted">
                             Nenhum agendamento para este dia.
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
    function mudarData(novaData) {
        window.location.href = "listar-consultas.php?data=" + novaData;
    }

    function confirmarExclusao(id, statusPagamento) {
        if (confirm("Tem certeza que deseja excluir este agendamento?")) {
            let excluirFinanceiro = 0;
            if (statusPagamento === 'Pago' || statusPagamento === 'Parcial') {
                if (confirm("Identificamos um lançamento de pagamento. Deseja excluir também o registro financeiro do caixa?")) {
                    excluirFinanceiro = 1;
                }
            }
            window.location.href = "excluir-consulta.php?id=" + id + "&excluir_fin=" + excluirFinanceiro;
        }
    }
  </script>
</body>
</html>