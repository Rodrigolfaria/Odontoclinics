<?php
require_once "../../conexao.php";

// 1. Configuração do Path
$path = "http://localhost:8888/odontoclinics/src/";

// 2. Filtros
$mes_atual = date('Y-m');
$filtro_data = $_GET['mes'] ?? $mes_atual;
$apenas_dedutivel = isset($_GET['dedutivel']) && $_GET['dedutivel'] == '1';
$ver_todos = isset($_GET['todos']) && $_GET['todos'] == '1';

try {
    // 3. Query Principal Dinâmica
    $condicoes = [];
    $parametros = [];

    // Se NÃO for "Ver Tudo", filtra pelo mês selecionado
    if (!$ver_todos) {
        $condicoes[] = "data_movimentacao LIKE :mes";
        $parametros[':mes'] = $filtro_data . '%';
    }

    if ($apenas_dedutivel) {
        $condicoes[] = "dedutivel_ir = 1";
    }

    $sql = "SELECT * FROM financeiro";
    if (!empty($condicoes)) {
        $sql .= " WHERE " . implode(" AND ", $condicoes);
    }
    $sql .= " ORDER BY data_movimentacao DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($parametros);
    $lancamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Cálculos de Resumo
    $total_receitas = 0;
    $total_despesas = 0;

    foreach ($lancamentos as $l) {
        if ($l['tipo'] == 'Receita') {
            $total_receitas += $l['valor'];
        } else {
            $total_despesas += $l['valor'];
        }
    }
    $saldo = $total_receitas - $total_despesas;

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once "../../header.php"; ?>
    <style>
        .card-img-absolute { position: absolute; top: 0; right: 0; height: 100%; opacity: 0.5; }
        .btn-active-custom { background: linear-gradient(to right, #da8cff, #9a55ff) !important; color: white !important; border: none !important; }
    </style>
</head>
<body>
  <div class="container-scroller">
    <?php include_once "../../sidebar.php"; ?>
    <div class="main-panel">
      <div class="content-wrapper">
        
        <div class="page-header">
          <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
              <i class="mdi mdi-cash-usd"></i>
            </span> Fluxo de Caixa <?= $ver_todos ? '(Geral)' : '' ?>
          </h3>
          <form class="d-flex align-items-center" method="GET">
             <input type="month" name="mes" class="form-control form-control-sm me-2" value="<?= $filtro_data ?>" <?= $ver_todos ? 'disabled' : '' ?>>
             <button type="submit" class="btn btn-gradient-primary btn-sm">Filtrar</button>
          </form>
        </div>

        <div class="row">
          <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
              <div class="card-body">
                <img src="<?= $path ?>assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Receitas <i class="mdi mdi-trending-up mdi-24px float-end"></i></h4>
                <h2 class="mb-5">R$ <?= number_format($total_receitas, 2, ',', '.') ?></h2>
                <p class="card-text"><?= $ver_todos ? 'Total Histórico' : 'No período selecionado' ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
              <div class="card-body">
                <img src="<?= $path ?>assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Despesas <i class="mdi mdi-trending-down mdi-24px float-end"></i></h4>
                <h2 class="mb-5">R$ <?= number_format($total_despesas, 2, ',', '.') ?></h2>
                <p class="card-text"><?= $ver_todos ? 'Total Histórico' : 'No período selecionado' ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
              <div class="card-body">
                <img src="<?= $path ?>assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Saldo Líquido <i class="mdi mdi-scale-balance mdi-24px float-end"></i></h4>
                <h2 class="mb-5">R$ <?= number_format($saldo, 2, ',', '.') ?></h2>
                <p class="card-text"><?= ($saldo >= 0) ? 'Operação Positiva' : 'Atenção ao Saldo'; ?></p>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 grid-margin">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Movimentações Detalhadas</h4>
                    <div class="btn-group">
                        <a href="fluxo-caixa.php?todos=1" class="btn btn-outline-primary btn-sm <?= $ver_todos ? 'btn-active-custom' : '' ?>">
                            <i class="mdi mdi-all-inclusive"></i> Listar Tudo
                        </a>
                        <a href="fluxo-caixa.php?mes=<?= $filtro_data ?>&dedutivel=1" class="btn btn-outline-info btn-sm <?= $apenas_dedutivel ? 'active' : '' ?>">
                            <i class="mdi mdi-file-document"></i> Dedutíveis (IR)
                        </a>
                        <a href="fluxo-caixa.php?mes=<?= $filtro_data ?>" class="btn btn-outline-secondary btn-sm <?= (!$apenas_dedutivel && !$ver_todos) ? 'active' : '' ?>">Mês Atual</a>
                    </div>
                </div>
                
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th> Data </th>
                        <th> Descrição </th>
                        <th> Categoria </th>
                        <th> Valor </th>
                        <th class="text-center"> IR </th>
                        <th class="text-center"> Ações </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (count($lancamentos) > 0): ?>
                        <?php foreach($lancamentos as $l): ?>
                        <tr>
                          <td> <?= date('d/m/Y', strtotime($l['data_movimentacao'])) ?> </td>
                          <td> 
                              <strong><?= htmlspecialchars($l['descricao']) ?></strong>
                              <br><small class="text-muted"><?= $l['documento_numero'] ?></small>
                          </td>
                          <td> <label class="badge badge-outline-dark"><?= $l['categoria'] ?></label> </td>
                          <td class="<?= ($l['tipo'] == 'Receita') ? 'text-success' : 'text-danger' ?> font-weight-bold">
                             <?= ($l['tipo'] == 'Receita') ? '+' : '-' ?> R$ <?= number_format($l['valor'], 2, ',', '.') ?>
                          </td>
                          <td class="text-center">
                              <?php if($l['dedutivel_ir']): ?>
                                  <i class="mdi mdi-check-decagram text-info"></i>
                              <?php else: ?>
                                  <span class="text-muted">-</span>
                              <?php endif; ?>
                          </td>
                          <td class="text-center">
                              <a href="editar-lancamento.php?id=<?= $l['id'] ?>" class="btn btn-link text-info p-0 me-2">
                                  <i class="mdi mdi-pencil" style="font-size: 1.2rem;"></i>
                              </a>
                              <a href="excluir-lancamento.php?id=<?= $l['id'] ?>" class="btn btn-link text-danger p-0" onclick="return confirm('Excluir lançamento?');">
                                  <i class="mdi mdi-delete" style="font-size: 1.2rem;"></i>
                              </a>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr><td colspan="6" class="text-center py-4">Nenhum registro encontrado.</td></tr>
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
</body>
</html>