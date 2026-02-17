<?php 
include '../../header.php';
include '../../sidebar.php';
include '../../conexao.php';

// Buscar dados atuais (PDO)
$stmt = $conn->prepare("SELECT * FROM configuracao_clinica WHERE id = 1");
$stmt->execute();
$dados = $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
?>

<div class="main-panel">
    <div class="content-wrapper">

        <div class="page-header">
            <h3 class="page-title">Configuração da Clínica</h3>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <form action="processar-configuracao.php" method="POST" enctype="multipart/form-data">

                            <div class="form-group">
                                <label>Nome da Clínica</label>
                                <input type="text" name="nome_clinica" class="form-control" 
                                       value="<?php echo $dados['nome_clinica']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Endereço</label>
                                <input type="text" name="endereco" class="form-control"
                                       value="<?php echo $dados['endereco']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Telefone</label>
                                <input type="text" name="telefone" class="form-control"
                                       value="<?php echo $dados['telefone']; ?>">
                            </div>

                            <div class="form-group">
                                <label>WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control"
                                       value="<?php echo $dados['whatsapp']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Nome do Dentista Responsável</label>
                                <input type="text" name="nome_dentista" class="form-control"
                                       value="<?php echo $dados['nome_dentista']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Especialidades (separadas por vírgula)</label>
                                <textarea name="especialidades" class="form-control"><?php echo $dados['especialidades']; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Horário de Funcionamento</label>
                                <input type="text" name="horario" class="form-control"
                                       value="<?php echo $dados['horario']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Logo da Clínica</label><br>

                                <?php if (!empty($dados['logo'])): ?>
                                    <img src="<?php echo $dados['logo']; ?>" 
                                         style="width: 150px; margin-bottom: 10px;">
                                <?php endif; ?>

                                <input type="file" name="logo" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-gradient-primary me-2">Salvar Configurações</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 🔥 O footer DEVE ficar aqui dentro -->
    <?php include '../../footer.php'; ?>
</div>

<!-- Scripts SEMPRE ficam fora da main-panel -->
<?php include '../../scripts.php'; ?>
