<?php 
require_once "../../conexao.php"; 
$path = "http://localhost:8888/odontoclinics/src/";

// Busca os dados atuais do lançamento para preencher o formulário
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM financeiro WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $l = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$l) { 
        header("Location: fluxo-caixa.php"); 
        exit; 
    }
} else {
    header("Location: fluxo-caixa.php"); 
    exit;
}

// Verifica se este lançamento é vinculado a uma consulta para mostrar o aviso
$is_consulta = (strpos($l['observacoes'], 'Consulta ID:') !== false);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once "../../header.php"; ?>
</head>
<body>
  <div class="container-scroller">
    <?php include_once "../../sidebar.php"; ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
          <h3 class="page-title">
            <span class="page-title-icon bg-gradient-warning text-white me-2">
              <i class="mdi mdi-pencil"></i>
            </span> Editar Lançamento
          </h3>
        </div>

        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Alterar Registro Financeiro</h4>
            <p class="card-description"> Edite as informações abaixo e clique em atualizar para salvar. </p>
            
            <?php if($is_consulta): ?>
              <div class="alert alert-fill-primary d-flex align-items-center" role="alert">
                <i class="mdi mdi-information-outline me-2"></i>
                <div>
                  Este lançamento está vinculado a um <strong>Agendamento</strong>. Alterar o valor aqui atualizará automaticamente o valor na Agenda.
                </div>
              </div>
            <?php endif; ?>

            <form action="atualizar-lancamento.php" method="POST" class="forms-sample">
              <input type="hidden" name="id" value="<?= $l['id'] ?>">
              
              <input type="hidden" name="observacoes" value="<?= $l['observacoes'] ?>">
              
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label>Descrição</label>
                    <input type="text" name="descricao" class="form-control" value="<?= htmlspecialchars($l['descricao']) ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Valor (R$)</label>
                    <input type="number" step="0.01" name="valor" class="form-control font-weight-bold" value="<?= $l['valor'] ?>" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Tipo</label>
                    <select name="tipo" class="form-control" required>
                      <option value="Receita" <?= $l['tipo'] == 'Receita' ? 'selected' : '' ?>>Receita (Entrada)</option>
                      <option value="Despesa" <?= $l['tipo'] == 'Despesa' ? 'selected' : '' ?>>Despesa (Saída)</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Data</label>
                    <input type="date" name="data_movimentacao" class="form-control" value="<?= $l['data_movimentacao'] ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Documento (CPF/CNPJ)</label>
                    <input type="text" name="documento_numero" class="form-control" value="<?= $l['documento_numero'] ?>">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="form-check form-check-flat form-check-primary">
                  <label class="form-check-label">
                    <input type="checkbox" name="dedutivel_ir" value="1" class="form-check-input" <?= $l['dedutivel_ir'] ? 'checked' : '' ?>> 
                    Esta despesa é dedutível no Imposto de Renda?
                  </label>
                </div>
              </div>

              <div class="mt-4">
                <button type="submit" class="btn btn-gradient-warning me-2">
                    <i class="mdi mdi-content-save"></i> Atualizar Lançamento
                </button>
                <a href="fluxo-caixa.php" class="btn btn-light">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php include_once "../../footer.php"; ?>
    </div>
  </div>
  <?php include_once "../../scripts.php"; ?>
</body>
</html>