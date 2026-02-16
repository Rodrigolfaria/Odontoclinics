<?php
require_once "../../conexao.php";

// 1. Verifica se o ID foi passado
if (!isset($_GET['id'])) { header("Location: listar-consultas.php"); exit; }
$id = $_GET['id'];

try {
    // 2. Busca os dados da consulta atual
    $stmt = $conn->prepare("SELECT * FROM consultas WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $c = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$c) { die("Consulta não encontrada!"); }

    // 3. Busca todos os pacientes para o Select
    $stmtPacientes = $conn->query("SELECT id, nome FROM pacientes ORDER BY nome ASC");
    $pacientes = $stmtPacientes->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <?php include_once "../../header.php"; ?>
  <style>
    .btn-icon { display: inline-flex !important; align-items: center !important; justify-content: center !important; }
    .form-control { height: calc(2.25rem + 2px); }
    .financeiro-box { background: rgba(0, 210, 91, 0.05); padding: 20px; border-radius: 8px; border: 1px dashed #00d25b; }
    .retorno-box { background: rgba(182, 109, 255, 0.05); padding: 20px; border-radius: 8px; border: 1px dashed #b66dff; }
    .btn-shortcut { padding: 5px 10px; font-size: 0.75rem; margin-top: 5px; }
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
              <i class="mdi mdi-calendar-edit"></i>
            </span> Finalizar Atendimento e Editar
          </h3>
        </div>

        <div class="card">
          <div class="card-body">
            <form class="forms-sample" action="atualizar-consulta.php" method="POST">
              <input type="hidden" name="id" value="<?= $c['id'] ?>">
              <input type="hidden" name="tipo_procedimento" value="<?= htmlspecialchars($c['tipo_procedimento'] ?? '') ?>">

              <h4 class="card-title">Dados Clínicos</h4>
              
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Paciente</label>
                        <select name="paciente_id" class="form-control text-dark" required>
                          <?php foreach($pacientes as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ($p['id'] == $c['paciente_id']) ? 'selected' : '' ?>>
                              <?= htmlspecialchars($p['nome']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Especialidade</label>
                        <select name="especialidade" class="form-control text-dark">
                          <option value="">Selecione a especialidade...</option>
                          <option value="Avaliação" <?= ($c['especialidade'] == 'Avaliação') ? 'selected' : '' ?>>Avaliação / Check-up</option>
                          <option value="Limpeza" <?= ($c['especialidade'] == 'Limpeza') ? 'selected' : '' ?>>Profilaxia</option>
                          <option value="Restauração" <?= ($c['especialidade'] == 'Restauração') ? 'selected' : '' ?>>Restauração</option>
                          <option value="Endodontia" <?= ($c['especialidade'] == 'Endodontia') ? 'selected' : '' ?>>Endodontia</option>
                          <option value="Ortodontia" <?= ($c['especialidade'] == 'Ortodontia') ? 'selected' : '' ?>>Ortodontia</option>
                          <option value="Implante" <?= ($c['especialidade'] == 'Implante') ? 'selected' : '' ?>>Implante</option>
                          <option value="Prótese" <?= ($c['especialidade'] == 'Prótese') ? 'selected' : '' ?>>Prótese</option>
                          <option value="Extração" <?= ($c['especialidade'] == 'Extração') ? 'selected' : '' ?>>Extração</option>
                          <option value="Estética" <?= ($c['especialidade'] == 'Estética') ? 'selected' : '' ?>>Estética</option>
                        </select>
                      </div>
                  </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Dentista Responsável</label>
                    <input type="text" name="dentista_responsavel" class="form-control" value="<?= htmlspecialchars($c['dentista'] ?? '') ?>" placeholder="Nome do Dentista">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Data da Consulta</label>
                    <input type="date" name="data_consulta" class="form-control" value="<?= $c['data_consulta'] ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Horário</label>
                    <input type="time" name="horario_consulta" class="form-control" value="<?= $c['horario_consulta'] ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Status da Consulta</label>
                    <select name="status" class="form-control font-weight-bold">
                        <option value="Agendado" class="text-info" <?= ($c['status'] == 'Agendado') ? 'selected' : '' ?>>📅 Agendado</option>
                        <option value="Confirmado" class="text-primary" <?= ($c['status'] == 'Confirmado') ? 'selected' : '' ?>>✅ Confirmado</option>
                        <option value="Realizado" class="text-success" <?= ($c['status'] == 'Realizado') ? 'selected' : '' ?>>🏁 Realizado</option>
                        <option value="Cancelado" class="text-danger" <?= ($c['status'] == 'Cancelado') ? 'selected' : '' ?>>❌ Cancelado</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="retorno-box mt-4">
                  <h4 class="card-title text-primary">
                    <i class="mdi mdi-calendar-star me-2"></i>Planejamento de Retorno
                  </h4>
                  <div class="row align-items-end">
                      <div class="col-md-5">
                          <div class="form-group mb-0">
                              <label>Data Prevista para o Próximo Retorno</label>
                              <input type="date" name="data_retorno" id="data_retorno" class="form-control border-primary" value="<?= $c['data_retorno'] ?>">
                          </div>
                      </div>
                      <div class="col-md-7">
                          <div class="d-flex flex-wrap gap-2">
                              <button type="button" class="btn btn-outline-primary btn-shortcut" onclick="setRetorno(15)">+15 dias</button>
                              <button type="button" class="btn btn-outline-primary btn-shortcut" onclick="setRetorno(90)">+3 meses</button>
                              <button type="button" class="btn btn-outline-primary btn-shortcut" onclick="setRetorno(180)">+6 meses</button>
                              <button type="button" class="btn btn-outline-primary btn-shortcut" onclick="setRetorno(365)">+1 ano</button>
                              <button type="button" class="btn btn-outline-secondary btn-shortcut" onclick="document.getElementById('data_retorno').value = ''">Limpar</button>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="financeiro-box mt-4">
                  <h4 class="card-title text-success">
                    <i class="mdi mdi-cash-multiple me-2"></i>Financeiro e Pagamento
                  </h4>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Valor Cobrado (R$)</label>
                        <input type="number" step="0.01" name="valor" class="form-control border-success" value="<?= $c['valor'] ?>" placeholder="0.00">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Forma de Pagamento</label>
                        <select name="forma_pagamento" class="form-control">
                            <option value="">Selecione...</option>
                            <option value="Dinheiro" <?= ($c['forma_pagamento'] == 'Dinheiro') ? 'selected' : '' ?>>Dinheiro</option>
                            <option value="Pix" <?= ($c['forma_pagamento'] == 'Pix') ? 'selected' : '' ?>>Pix</option>
                            <option value="Cartão de Crédito" <?= ($c['forma_pagamento'] == 'Cartão de Crédito') ? 'selected' : '' ?>>Cartão de Crédito</option>
                            <option value="Cartão de Débito" <?= ($c['forma_pagamento'] == 'Cartão de Débito') ? 'selected' : '' ?>>Cartão de Débito</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Status Financeiro</label>
                        <select name="status_pagamento" class="form-control font-weight-bold">
                          <option value="Pendente" <?= ($c['status_pagamento'] == 'Pendente') ? 'selected' : '' ?>>⏳ Pendente</option>
                          <option value="Pago" <?= ($c['status_pagamento'] == 'Pago') ? 'selected' : '' ?>>💰 Pago</option>
                          <option value="Parcial" <?= ($c['status_pagamento'] == 'Parcial') ? 'selected' : '' ?>>🌗 Parcial</option>
                        </select>
                      </div>
                    </div>
                  </div>
              </div>

              <div class="form-group mt-3">
                <label>Observações / Detalhes do Procedimento</label>
                <textarea name="observacoes" class="form-control" rows="4" placeholder="Descreva aqui os detalhes do atendimento..."><?= htmlspecialchars($c['observacoes'] ?? '') ?></textarea>
              </div>

              <div class="mt-4 d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-gradient-primary btn-lg me-2">
                        <i class="mdi mdi-content-save"></i> Atualizar e Salvar
                    </button>
                    <a href="listar-consultas.php" class="btn btn-light btn-lg">Cancelar</a>
                </div>
                
                <?php if($c['status'] != 'Cancelado'): ?>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmarCancelamento()">
                    <i class="mdi mdi-close-circle"></i> Cancelar Consulta
                </button>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php include_once "../../footer.php"; ?>
    </div>
  </div>
  <?php include_once "../../scripts.php"; ?>

  <script>
    function setRetorno(dias) {
        const date = new Date();
        date.setDate(date.getDate() + dias);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        document.getElementById('data_retorno').value = `${year}-${month}-${day}`;
    }

    function confirmarCancelamento() {
        if(confirm("Deseja realmente cancelar esta consulta?")) {
            document.getElementsByName('status')[0].value = 'Cancelado';
            document.querySelector('form').submit();
        }
    }
  </script>
</body>
</html>