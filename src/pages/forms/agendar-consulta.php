<?php
require_once "../../conexao.php";

// Busca todos os pacientes para preencher o Select
$stmt = $conn->query("SELECT id, nome FROM pacientes ORDER BY nome ASC");
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once "../../header.php"; ?>
    <style>
      .secao-titulo { border-left: 4px solid #b66dff; padding-left: 10px; margin-bottom: 20px; font-weight: bold; }
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
              <i class="mdi mdi-calendar-plus"></i>
            </span> Agendar Nova Consulta
          </h3>
        </div>

        <div class="row">
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                
                <form class="forms-sample" action="processar-consulta.php" method="POST">
                  
                  <h4 class="secao-titulo text-primary">Informações da Consulta</h4>

                  <div class="form-group">
                    <label>Selecione o Paciente</label>
                    <select name="paciente_id" class="form-control" required>
                      <option value="">Escolha um paciente...</option>
                      <?php foreach($pacientes as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Data</label>
                        <input type="date" name="data_consulta" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Horário</label>
                        <input type="time" name="horario_consulta" class="form-control" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Especialidade</label>
                        <select name="especialidade" class="form-control" required>
                          <option value="">Selecione a especialidade...</option>
                          <option value="Avaliação">Avaliação / Check-up</option>
                          <option value="Limpeza">Profilaxia</option>
                          <option value="Restauração">Restauração</option>
                          <option value="Endodontia">Endodontia</option>
                          <option value="Ortodontia">Ortodontia</option>
                          <option value="Implante">Implante</option>
                          <option value="Prótese">Prótese</option>
                          <option value="Extração">Extração</option>
                          <option value="Estética">Estética</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Dentista Responsável</label>
                        <select name="dentista" class="form-control" required>
                          <option value="Dra. Priscilla">Dra. Priscilla</option>
                          <option value="Dr. Colaborador">Dr. Colaborador</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Detalhes do Procedimento (Opcional)</label>
                    <input type="text" name="tipo_procedimento" class="form-control" placeholder="Ex: Restauração no dente 24, troca de braquetes...">
                  </div>

                  <hr class="my-4">
                  <h4 class="secao-titulo text-success">Informações Financeiras</h4>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Valor da Consulta (R$)</label>
                        <input type="number" step="0.01" name="valor" class="form-control" placeholder="0.00">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Forma de Pagamento</label>
                        <select name="forma_pagamento" class="form-control">
                          <option value="">Selecione...</option>
                          <option value="Dinheiro">Dinheiro</option>
                          <option value="Pix">Pix</option>
                          <option value="Cartão de Crédito">Cartão de Crédito</option>
                          <option value="Cartão de Débito">Cartão de Débito</option>
                          <option value="Convênio">Convênio</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Status de Pagamento</label>
                        <select name="status_pagamento" class="form-control">
                          <option value="Pendente">Pendente</option>
                          <option value="Pago">Pago</option>
                          <option value="Parcial">Parcial</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Observações Adicionais</label>
                    <textarea name="observacoes" class="form-control" rows="4" placeholder="Observações clínicas importantes..."></textarea>
                  </div>

                  <div class="mt-4">
                    <button type="submit" class="btn btn-gradient-primary btn-lg me-2">Finalizar Agendamento</button>
                    <a href="listar-consultas.php" class="btn btn-light btn-lg">Cancelar</a>
                  </div>
                </form>
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