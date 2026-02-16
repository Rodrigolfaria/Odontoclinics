<?php 
require_once "../../conexao.php"; 
// Definimos o path aqui caso o sidebar precise, mas o header.php costuma cuidar disso
$path = "http://localhost:8888/odontoclinics/src/";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <?php include_once "../../header.php"; ?>
  
  <style>
    .form-check .form-check-label {
        margin-left: 1.5rem;
        font-weight: bold;
        color: #b66dff;
    }
  </style>
</head>
<body>
  <div class="container-scroller">
    <?php include_once "../../sidebar.php"; ?>
    
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
          <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
              <i class="mdi mdi-cash-plus"></i>
            </span> Novo Lançamento Financeiro
          </h3>
        </div>

        <div class="row">
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Registro de Entrada/Saída</h4>
                <p class="card-description"> Utilize este formulário para alimentar seu fluxo de caixa e preparar o Livro Caixa (IR). </p>
                
                <form action="salvar-lancamento.php" method="POST" class="forms-sample">
                  
                  <div class="row">
                    <div class="col-md-8">
                      <div class="form-group">
                        <label for="descricao">Descrição do Lançamento</label>
                        <input type="text" name="descricao" class="form-control" id="descricao" placeholder="Ex: Compra de Resinas, Aluguel Sala 202, etc." required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="valor">Valor (R$)</label>
                        <input type="number" step="0.01" name="valor" class="form-control" id="valor" placeholder="0.00" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Tipo de Movimentação</label>
                        <select name="tipo" class="form-control" required>
                          <option value="Receita">➕ Receita (Entrada)</option>
                          <option value="Despesa">➖ Despesa (Saída)</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Categoria</label>
                        <select name="categoria" class="form-control">
                          <option value="Consulta">Consulta / Procedimento</option>
                          <option value="Material">Material Odontológico</option>
                          <option value="Aluguel">Aluguel / Condomínio</option>
                          <option value="Salário">Salário / Pro-labore</option>
                          <option value="Marketing">Marketing / Tráfego Pago</option>
                          <option value="Outros">Outros</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Data</label>
                        <input type="date" name="data_movimentacao" class="form-control" value="<?= date('Y-m-d') ?>" required>
                      </div>
                    </div>
                  </div>

                  <hr>
                  <h4 class="card-title text-info">Dados para Imposto de Renda / NF</h4>
                  
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Documento</label>
                        <select name="documento_tipo" class="form-control">
                          <option value="CPF">CPF</option>
                          <option value="CNPJ">CNPJ</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <label>Número do Documento (CPF/CNPJ do Paciente ou Fornecedor)</label>
                        <input type="text" name="documento_numero" class="form-control" placeholder="000.000.000-00">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="dedutivel_ir" value="1" class="form-check-input"> 
                        Despesa Dedutível? (Marque para Aluguel, Materiais e Salários para abater no IRPF)
                      </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Observações Adicionais</label>
                    <textarea name="observacoes" class="form-control" rows="3"></textarea>
                  </div>

                  <button type="submit" class="btn btn-gradient-success me-2">Finalizar Lançamento</button>
                  <a href="fluxo-caixa.php" class="btn btn-light">Cancelar</a>
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