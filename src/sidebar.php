<?php
// Configuração do caminho base
$path = "http://localhost:8888/odontoclinics/src/";
$nome_dentista = "Dra. Priscilla"; 
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
      
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $path; ?>index.php">
          <span class="menu-title">Dashboard</span>
          <i class="mdi mdi-monitor-dashboard menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-pacientes" aria-expanded="false" aria-controls="ui-pacientes">
          <span class="menu-title">Pacientes</span>
          <i class="menu-arrow"></i>
          <i class="mdi mdi-account-group menu-icon"></i>
        </a>
        <div class="collapse" id="ui-pacientes">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $path; ?>pages/forms/cadastrar-paciente.php">Novo Paciente</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Listar Todos</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="#">
          <span class="menu-title">Agenda</span>
          <i class="mdi mdi-calendar-clock menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="#">
          <span class="menu-title">Financeiro</span>
          <i class="mdi mdi-currency-usd menu-icon"></i>
        </a>
      </li>
    </ul>
  </nav>