<?php
// 1. Configuração do caminho base para LINKS (HTML/Navegador)
$path = "http://localhost:8888/odontoclinics/src/";
$nome_dentista = "Dra. Priscilla"; 

// 2. Lógica para detectar onde o arquivo está e ajustar o caminho do PHP (Sistema de Arquivos)
// Se o arquivo atual estiver em uma subpasta (contém 'pages'), precisamos voltar níveis
$diretorio_atual = dirname($_SERVER['PHP_SELF']);
$prefixo_php = (strpos($diretorio_atual, 'pages') !== false) ? "../../" : "";

// Lógica de Ativação de Menus
$pagina_atual = basename($_SERVER['PHP_SELF']);
$menu_consultas_ativo = (
    $pagina_atual == 'agendar-consulta.php' || 
    $pagina_atual == 'listar-consultas.php' || 
    $pagina_atual == 'listar-todas.php' || 
    $pagina_atual == 'lista-retornos.php'
);

$menu_pacientes_ativo = ($pagina_atual == 'cadastrar-paciente.php' || $pagina_atual == 'listar-pacientes.php');
$menu_financeiro_ativo = ($pagina_atual == 'novo-lancamento.php' || $pagina_atual == 'fluxo-caixa.php');
?>

<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <a class="navbar-brand brand-logo" href="<?php echo $path; ?>index.php">
        <img src="<?php echo $path; ?>assets/images/logo.png" alt="logo" style="width: 160px; height: auto;" />
    </a>
    <a class="navbar-brand brand-logo-mini" href="<?php echo $path; ?>index.php">
        <img src="<?php echo $path; ?>assets/images/logo.png" alt="logo" />
    </a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>
    
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="nav-profile-img">
            <img src="<?php echo $path; ?>assets/images/faces/face1.jpg" alt="image">
            <span class="availability-status online"></span>
          </div>
          <div class="nav-profile-text">
            <p class="mb-1 text-black"><?php echo $nome_dentista; ?></p>
          </div>
        </a>
        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="#">
            <i class="mdi mdi-cached me-2 text-success"></i> Log de Atividade </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">
            <i class="mdi mdi-logout me-2 text-primary"></i> Sair </a>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>

<div class="container-fluid page-body-wrapper">
  
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item nav-profile">
        <a href="#" class="nav-link">
          <div class="nav-profile-image">
            <img src="<?php echo $path; ?>assets/images/faces/face1.jpg" alt="profile" />
            <span class="login-status online"></span>
          </div>
          <div class="nav-profile-text d-flex flex-column">
            <span class="font-weight-bold mb-2"><?php echo $nome_dentista; ?></span>
            <span class="text-secondary text-small">Cirurgião Dentista</span>
          </div>
          <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
        </a>
      </li>
      
      <li class="nav-item <?php echo ($pagina_atual == 'index.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="<?php echo $path; ?>index.php">
          <span class="menu-title">Dashboard</span>
          <i class="mdi mdi-monitor-dashboard menu-icon"></i>
        </a>
      </li>

      <li class="nav-item <?php echo ($menu_pacientes_ativo) ? 'active' : ''; ?>">
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-pacientes" aria-expanded="<?php echo ($menu_pacientes_ativo) ? 'true' : 'false'; ?>" aria-controls="ui-pacientes">
          <span class="menu-title">Pacientes</span>
          <i class="menu-arrow"></i>
          <i class="mdi mdi-account-group menu-icon"></i>
        </a>
        <div class="collapse <?php echo ($menu_pacientes_ativo) ? 'show' : ''; ?>" id="ui-pacientes">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $path; ?>pages/forms/cadastrar-paciente.php">Novo Paciente</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $path; ?>pages/forms/listar-pacientes.php">Listar Todos</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item <?php echo ($menu_consultas_ativo) ? 'active' : ''; ?>">
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-consultas" aria-expanded="<?php echo ($menu_consultas_ativo) ? 'true' : 'false'; ?>" aria-controls="ui-consultas">
          <span class="menu-title">Consultas</span>
          <i class="menu-arrow"></i>
          <i class="mdi mdi-calendar-edit menu-icon"></i>
        </a>
        <div class="collapse <?php echo ($menu_consultas_ativo) ? 'show' : ''; ?>" id="ui-consultas">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="<?php echo $path; ?>pages/forms/agendar-consulta.php">Nova Consulta</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?php echo $path; ?>pages/forms/listar-consultas.php">Agenda de Hoje</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?php echo $path; ?>pages/forms/listar-todas.php">Histórico Geral</a></li>
            <li class="nav-item"> <a class="nav-link" href="<?php echo $path; ?>pages/forms/lista-retornos.php">Retornos Pendentes</a></li>
          </ul>
        </div>
      </li>

      <li class="nav-item <?php echo ($pagina_atual == 'agenda.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="<?php echo $path; ?>pages/forms/agenda.php">
          <span class="menu-title">Agenda Visual</span>
          <i class="mdi mdi-calendar-clock menu-icon"></i>
        </a>
      </li>

      <li class="nav-item <?php echo ($menu_financeiro_ativo) ? 'active' : ''; ?>">
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-financeiro" aria-expanded="<?php echo ($menu_financeiro_ativo) ? 'true' : 'false'; ?>" aria-controls="ui-financeiro">
          <span class="menu-title">Financeiro</span>
          <i class="menu-arrow"></i>
          <i class="mdi mdi-currency-usd menu-icon"></i>
        </a>
        <div class="collapse <?php echo ($menu_financeiro_ativo) ? 'show' : ''; ?>" id="ui-financeiro">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $path; ?>pages/forms/novo-lancamento.php">Novo Lançamento</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $path; ?>pages/forms/fluxo-caixa.php">Fluxo de Caixa</a>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </nav>