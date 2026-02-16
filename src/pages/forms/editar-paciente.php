<?php
require_once "../../conexao.php";

if (!isset($_GET['id'])) { header("Location: listar-pacientes.php"); exit; }
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM pacientes WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) { echo "Paciente não encontrado!"; exit; }
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
          <h3 class="page-title"> Editar Paciente </h3>
        </div>
        <div class="card">
          <div class="card-body">
            <form class="forms-sample" method="POST" action="atualizar-paciente.php">
              <input type="hidden" name="id" value="<?php echo $p['id']; ?>">

              <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($p['nome']); ?>" required>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>CPF</label>
                    <input type="text" name="cpf" class="form-control" value="<?php echo htmlspecialchars($p['cpf'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Data de Nascimento</label>
                    <input type="date" name="data_nascimento" class="form-control" value="<?php echo $p['data_nascimento']; ?>">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($p['email'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Telefone/WhatsApp</label>
                    <input type="text" name="telefone" class="form-control" value="<?php echo htmlspecialchars($p['telefone'] ?? ''); ?>">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Gênero</label>
                <select name="genero" class="form-control">
                  <option value="Masculino" <?php echo ($p['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                  <option value="Feminino" <?php echo ($p['genero'] == 'Feminino') ? 'selected' : ''; ?>>Feminino</option>
                  <option value="Outro" <?php echo ($p['genero'] == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                </select>
              </div>

              <hr class="my-4">
              <h4 class="card-title text-primary">Endereço</h4>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>CEP</label>
                    <input type="text" name="cep" id="cep" class="form-control" value="<?php echo htmlspecialchars($p['cep'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label>Rua/Avenida</label>
                    <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?php echo htmlspecialchars($p['logradouro'] ?? ''); ?>">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Número</label>
                    <input type="text" name="numero" class="form-control" value="<?php echo htmlspecialchars($p['numero'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bairro</label>
                    <input type="text" name="bairro" id="bairro" class="form-control" value="<?php echo htmlspecialchars($p['bairro'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label>Complemento</label>
                    <input type="text" name="complemento" class="form-control" value="<?php echo htmlspecialchars($p['complemento'] ?? ''); ?>">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label>Cidade</label>
                    <input type="text" name="cidade" id="cidade" class="form-control" value="<?php echo htmlspecialchars($p['cidade'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Estado</label>
                    <input type="text" name="estado" id="estado" class="form-control" value="<?php echo htmlspecialchars($p['estado'] ?? ''); ?>">
                  </div>
                </div>
              </div>

              <hr class="my-4">
              <div class="form-group">
                <label>Anamnese / Observações</label>
                <textarea name="anamnese" class="form-control" rows="5"><?php echo htmlspecialchars($p['anamnese'] ?? ''); ?></textarea>
              </div>

              <button type="submit" class="btn btn-gradient-primary me-2">Salvar Alterações</button>
              <a href="listar-pacientes.php" class="btn btn-light">Voltar</a>
            </form>
          </div>
        </div>
      </div>
      <?php include_once "../../footer.php"; ?>
    </div>
  </div>
  <?php include_once "../../scripts.php"; ?>
  <script>
    document.getElementById('cep').addEventListener('blur', function () {
        let cep = this.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(res => res.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('logradouro').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('estado').value = data.uf;
                }
            });
        }
    });
  </script>
</body>
</html>