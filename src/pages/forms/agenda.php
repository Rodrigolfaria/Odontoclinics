<?php
require_once "../../conexao.php";

// Busca as consultas e formata para o calendário
$stmt = $conn->query("SELECT c.id, p.nome as title, c.data_consulta as start, c.horario_consulta, c.status 
                      FROM consultas c 
                      INNER JOIN pacientes p ON c.paciente_id = p.id");
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$eventos = [];
foreach($consultas as $con) {
    // Definir cor baseada no status
    $cor = '#b66dff'; // Roxo padrão Purple Admin
    if($con['status'] == 'Realizado') $cor = '#1bcfb4'; // Verde
    if($con['status'] == 'Cancelado') $cor = '#fe7c96'; // Rosa/Vermelho

    $eventos[] = [
        'id' => $con['id'],
        'title' => date('H:i', strtotime($con['horario_consulta'])) . " - " . $con['title'],
        'start' => $con['start'],
        'backgroundColor' => $cor,
        'borderColor' => $cor,
        'textColor' => '#ffffff'
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include_once "../../header.php"; ?>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        /* Customizando para o estilo Purple Admin */
        .fc-header-toolbar {
            margin-bottom: 2rem !important;
            text-transform: capitalize;
        }
        .fc-button-primary {
            background: linear-gradient(to right, #da8cff, #9a55ff) !important;
            border: none !important;
            box-shadow: 0 3px 3px 0 rgba(182, 109, 255, 0.4) !important;
        }
        .fc-button-primary:hover {
            opacity: 0.8;
        }
        .fc-daygrid-day-number {
            color: #343a40;
            font-weight: 600;
            text-decoration: none !important;
        }
        .fc-col-header-cell-cushion {
            color: #4b49ac;
            text-decoration: none !important;
        }
        .fc-event {
            border-radius: 4px !important;
            padding: 2px 5px !important;
            font-size: 0.85rem !important;
            border: none !important;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
        }
        #calendar {
            background: #fff;
            padding: 15px;
            border-radius: 15px;
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
            <span class="page-title-icon bg-gradient-primary text-white me-2">
              <i class="mdi mdi-calendar"></i>
            </span> Agenda do Consultório
          </h3>
        </div>

        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div id='calendar'></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include_once "../../footer.php"; ?>
    </div>
  </div>

  <?php include_once "../../scripts.php"; ?>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js'></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek'
        },
        events: <?php echo json_encode($eventos); ?>,
        eventClick: function(info) {
            // Abre a edição ao clicar no evento
            window.location.href = 'editar-consulta.php?id=' + info.event.id;
        },
        height: 'auto'
      });
      calendar.render();
    });
  </script>
</body>
</html>