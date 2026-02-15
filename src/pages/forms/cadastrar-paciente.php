<!DOCTYPE html>
<html lang="pt-br">

<head>
  <?php include_once "../../header.php"; ?>
  
  <link rel="stylesheet" href="../../assets/vendors/select2/select2.min.css">
  <link rel="stylesheet" href="../../assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
</head>

<body>
  <div class="container-scroller">
    
    <?php 
      /* IMPORTANTE: Como seu sidebar.php já inclui a Navbar 
         e abre a div 'page-body-wrapper', chamamos apenas ele.
      */
      include_once "../../sidebar.php"; 
    ?>

    <div class="main-panel">
      <div class="content-wrapper">
        
        <div class="page-header">
          <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
              <i class="mdi mdi-account-plus"></i>
            </span> Cadastro de Pacientes
          </h3>
        </div>

        <div class="row">
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Informações do Paciente</h4>
                <form class="forms-sample" id="formCadastroPaciente">
                  
                  <div class="form-group">
                    <label for="nomePaciente">Nome Completo</label>
                    <input type="text" class="form-control" id="nomePaciente" placeholder="Ex: Maria Oliveira" required>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="cpfPaciente">CPF</label>
                        <input type="text" class="form-control" id="cpfPaciente" placeholder="000.000.000-00">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="dataNascimento">Data de Nascimento</label>
                        <input type="date" class="form-control" id="dataNascimento">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="emailPaciente">E-mail</label>
                        <input type="email" class="form-control" id="emailPaciente" placeholder="paciente@email.com">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="telefonePaciente">Telefone/WhatsApp</label>
                        <input type="text" class="form-control" id="telefonePaciente" placeholder="(11) 99999-9999">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Gênero</label>
                    <select class="form-control">
                      <option selected disabled>Selecione o gênero</option>
                      <option>Masculino</option>
                      <option>Feminino</option>
                      <option>Outro</option>
                    </select>
                  </div>

                  <hr class="mt-4 mb-4">
                  <h4 class="card-title text-primary"><i class="mdi mdi-map-marker me-2"></i>Endereço Completo</h4>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="cep">CEP</label>
                        <input type="text" class="form-control" id="cep" placeholder="00000-000">
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <label for="logradouro">Rua/Avenida</label>
                        <input type="text" class="form-control" id="logradouro" placeholder="Ex: Rua das Flores">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="numero">Número</label>
                        <input type="text" class="form-control" id="numero" placeholder="123">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" class="form-control" id="bairro" placeholder="Centro">
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" class="form-control" id="complemento" placeholder="Apto, Bloco, etc.">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-8">
                      <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" class="form-control" id="cidade" placeholder="Sua Cidade">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" class="form-control" id="estado" placeholder="UF">
                      </div>
                    </div>
                  </div>

                  <hr class="mt-4 mb-4">
                  <h4 class="card-title text-primary"><i class="mdi mdi-medical-bag me-2"></i>Histórico Clínico</h4>
                  <div class="form-group">
                    <label for="anamnesePaciente">Anamnese / Observações Médicas</label>
                    <textarea class="form-control" id="anamnesePaciente" rows="6" placeholder="Alergias, medicamentos, cirurgias..."></textarea>
                  </div>

                  <button type="submit" class="btn btn-gradient-primary me-2">Salvar Cadastro</button>
                  <button type="button" class="btn btn-light">Cancelar</button>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div> <?php include_once "../../footer.php"; ?>

    </div> </div> </div> <?php include_once "../../scripts.php"; ?>

<script>
  // Script do ViaCEP
  document.getElementById('cep').addEventListener('blur', function () {
    let cep = this.value.replace(/\D/g, '');
    if (cep !== "") {
      let validacep = /^[0-9]{8}$/;
      if (validacep.test(cep)) {
        document.getElementById('logradouro').value = "...";
        document.getElementById('bairro').value = "...";
        document.getElementById('cidade').value = "...";
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
          .then(response => response.json())
          .then(dados => {
            if (!("erro" in dados)) {
              document.getElementById('logradouro').value = dados.logradouro;
              document.getElementById('bairro').value = dados.bairro;
              document.getElementById('cidade').value = dados.localidade;
              document.getElementById('estado').value = dados.uf;
            } else {
              alert("CEP não encontrado.");
            }
          })
          .catch(() => alert("Erro ao buscar o CEP."));
      }
    }
  });
</script>

</body>
</html>