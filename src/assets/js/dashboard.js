(function ($) {
  'use strict';

  // 1. Gráfico de Fluxo de Atendimento (Bar Chart)
  if ($("#visit-sale-chart").length) {
    const ctx = document.getElementById('visit-sale-chart');
    var graphGradient1 = ctx.getContext("2d");

    var gradientStrokeViolet = graphGradient1.createLinearGradient(0, 0, 0, 181);
    gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
    gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');

    new Chart(ctx, {
      type: 'bar',
      data: {
        // Usa as labels do PHP ou nomes genéricos se falhar
        labels: (typeof labelsFluxo !== 'undefined') ? labelsFluxo : ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
        datasets: [{
          label: "Atendimentos",
          borderColor: gradientStrokeViolet,
          backgroundColor: gradientStrokeViolet,
          hoverBackgroundColor: gradientStrokeViolet,
          borderWidth: 1,
          fill: 'origin',
          // Usa os dados vindos do PHP
          data: (typeof dadosFluxo !== 'undefined') ? dadosFluxo : [0, 0, 0, 0, 0, 0],
          barPercentage: 0.5,
          categoryPercentage: 0.5,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
          y: { display: true, beginAtZero: true, grid: { display: true } },
          x: { display: true, grid: { display: false } }
        },
        plugins: { legend: { display: false } }
      }
    });
  }

  // 2. Gráfico de Top Especialidades (Doughnut)
  if ($("#traffic-chart").length) {
    const ctx = document.getElementById('traffic-chart');
    var graphGradient = ctx.getContext('2d');

    // Cores padrão para o Top 3
    var colors = ['rgba(182, 109, 255, 1)', 'rgba(6, 185, 157, 1)', 'rgba(254, 112, 150, 1)'];

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: (typeof labelsEspecialidades !== 'undefined' && labelsEspecialidades.length > 0) ? labelsEspecialidades : ['Sem Dados'],
        datasets: [{
          data: (typeof dadosEspecialidades !== 'undefined' && dadosEspecialidades.length > 0) ? dadosEspecialidades : [1],
          backgroundColor: colors,
          hoverBackgroundColor: colors,
          borderColor: colors,
        }]
      },
      options: {
        cutout: 70,
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } }
      }
    });
  }

  // Manter funções de layout e datepicker
  if ($("#inline-datepicker").length) {
    $('#inline-datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
  }

  const navbar = document.querySelector('.navbar');
  const pageWrapper = document.querySelector('.page-body-wrapper');
  if (navbar && pageWrapper) {
    navbar.classList.add('fixed-top');
    navbar.classList.remove('pt-5', 'mt-3');
    pageWrapper.classList.remove('pt-0');
  }

})(jQuery);