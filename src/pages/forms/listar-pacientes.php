<?php
// 1. Conexão com o banco
require_once "../../conexao.php";

// 2. Busca os pacientes no banco
try {
    $stmt = $conn->prepare("SELECT id, nome, cpf, telefone, cidade, criado_em FROM pacientes ORDER BY criado_em DESC");
    $stmt->execute();
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar pacientes: " . $e->getMessage());
}
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
            <span class="page-title-icon bg-gradient-primary text-white me-2">
              <i class="mdi mdi-account-group"></i>
            </span> Pacientes Cadastrados
          </h3>
          <nav aria-label="breadcrumb">
            <a href="cadastrar-paciente.php" class="btn btn-gradient-primary btn-sm">+ Novo Paciente</a>
          </nav>
        </div>

        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Listagem Geral</h4>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th> Nome </th>
                        <th> CPF </th>
                        <th> Telefone </th>
                        <th> Cidade </th>
                        <th> Cadastro </th>
                        <th> Ações </th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php if (count($pacientes) > 0): ?>
                            <?php foreach ($pacientes as $p): ?>
                                <tr>
                                    <td> <?php echo htmlspecialchars($p['nome'] ?? ""); ?> </td>
                                    <td> <?php echo htmlspecialchars($p['cpf'] ?? ""); ?> </td>
                                    <td> <?php echo htmlspecialchars($p['telefone'] ?? ""); ?> </td>
                                    <td> <?php echo htmlspecialchars($p['cidade'] ?? ""); ?> </td>
                                    <td> <?php echo ($p['criado_em']) ? date('d/m/Y', strtotime($p['criado_em'])) : ""; ?> </td>
                                    <td>
                                      <a href="editar-paciente.php?id=<?php echo $p['id']; ?>" 
                                        class="btn btn-inverse-info btn-icon btn-sm" 
                                        title="Editar"
                                        style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                                        <i class="mdi mdi-pencil"></i>
                                      </a>
                                         
                                      
                                      
                                      <a href="excluir-paciente.php?id=<?php echo $p['id']; ?>" 
                                         class="btn btn-inverse-danger btn-icon btn-sm" 
                                         title="Excluir"
                                         onclick="return confirm('Tem certeza que deseja excluir este paciente? Esta ação não pode ser desfeita.');"
                                         style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                                          <i class="mdi mdi-delete"></i>
                                      </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                          <td colspan="6" class="text-center"> Nenhum paciente encontrado. </td>
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
</body>
</html>