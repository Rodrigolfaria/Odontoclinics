(function ($) {
  'use strict';

  // Gráfico de Visitas e Vendas
  if ($("#visit-sale-chart").length) {
    const ctx = document.getElementById('visit-sale-chart');

    var graphGradient1 = ctx.getContext("2d");
    var graphGradient2 = ctx.getContext("2d");
    var graphGradient3 = ctx.getContext("2d");

    var gradientStrokeViolet = graphGradient1.createLinearGradient(0, 0, 0, 181);
    gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
    gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');

    var gradientStrokeBlue = graphGradient2.createLinearGradient(0, 0, 0, 360);
    gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
    gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');

    var gradientStrokeRed = graphGradient3.createLinearGradient(0, 0, 0, 300);
    gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
    gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');

    const bgColor1 = ["rgba(218, 140, 255, 1)"];
    const bgColor2 = ["rgba(54, 215, 232, 1)"];
    const bgColor3 = ["rgba(255, 191, 150, 1)"];

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG'],
        datasets: [{
          label: "CHN",
          borderColor: gradientStrokeViolet,
          backgroundColor: gradientStrokeViolet,
          hoverBackgroundColor: gradientStrokeViolet,
          pointRadius: 0,
          fill: 'origin',
          borderWidth: 1,
          data: [20, 40, 15, 35, 25, 50, 30, 20],
          barPercentage: 0.5,
          categoryPercentage: 0.5,
        },
        {
          label: "USA",
          borderColor: gradientStrokeRed,
          backgroundColor: gradientStrokeRed,
          hoverBackgroundColor: gradientStrokeRed,
          pointRadius: 0,
          fill: 'origin',
          borderWidth: 1,
          data: [40, 30, 20, 10, 50, 15, 35, 40],
          barPercentage: 0.5,
          categoryPercentage: 0.5,
        },
        {
          label: "UK",
          borderColor: gradientStrokeBlue,
          backgroundColor: gradientStrokeBlue,
          hoverBackgroundColor: gradientStrokeBlue,
          pointRadius: 0,
          fill: 'origin',
          borderWidth: 1,
          data: [70, 10, 30, 40, 25, 50, 15, 30],
          barPercentage: 0.5,
          categoryPercentage: 0.5,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        elements: { line: { tension: 0.4 } },
        scales: {
          y: { display: false, grid: { display: true, drawOnChartArea: true, drawTicks: false } },
          x: { display: true, grid: { display: false } }
        },
        plugins: { legend: { display: false } }
      },
      plugins: [{
        afterDatasetUpdate: function (chart) {
          const chartId = chart.canvas.id;
          const legendId = `${chartId}-legend`;
          const existingLegend = document.getElementById(legendId);
          if (existingLegend) {
            existingLegend.innerHTML = '';
            const ul = document.createElement('ul');
            chart.data.datasets.forEach((dataset, i) => {
              ul.innerHTML += `<li><span style="background-color: ${dataset.borderColor}"></span>${dataset.label}</li>`;
            });
            existingLegend.appendChild(ul);
          }
        }
      }]
    });
  }

  // Gráfico de Tráfego (Doughnut)
  if ($("#traffic-chart").length) {
    const ctx = document.getElementById('traffic-chart');
    var graphGradient = ctx.getContext('2d');

    var gradientStrokeBlue = graphGradient.createLinearGradient(0, 0, 0, 181);
    gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
    gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');

    var gradientStrokeRed = graphGradient.createLinearGradient(0, 0, 0, 50);
    gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
    gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');

    var gradientStrokeGreen = graphGradient.createLinearGradient(0, 0, 0, 300);
    gradientStrokeGreen.addColorStop(0, 'rgba(6, 185, 157, 1)');
    gradientStrokeGreen.addColorStop(1, 'rgba(132, 217, 210, 1)');

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Limpeza', 'Clareamento', 'Ortodontia'],
        datasets: [{
          data: [30, 30, 40],
          backgroundColor: [gradientStrokeBlue, gradientStrokeGreen, gradientStrokeRed],
          hoverBackgroundColor: [gradientStrokeBlue, gradientStrokeGreen, gradientStrokeRed],
          borderColor: [gradientStrokeBlue, gradientStrokeGreen, gradientStrokeRed],
          legendColor: ['rgba(54, 215, 232, 1)', 'rgba(6, 185, 157, 1)', 'rgba(254, 112, 150, 1)']
        }]
      },
      options: {
        cutout: 50,
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } }
      },
      plugins: [{
        afterDatasetUpdate: function (chart) {
          const chartId = chart.canvas.id;
          const legendId = `${chartId}-legend`;
          const existingLegend = document.getElementById(legendId);
          if (existingLegend) {
            existingLegend.innerHTML = '';
            const ul = document.createElement('ul');
            chart.data.labels.forEach((label, i) => {
              ul.innerHTML += `<li><span style="background-color: ${chart.data.datasets[0].legendColor[i]}"></span>${label}</li>`;
            });
            existingLegend.appendChild(ul);
          }
        }
      }]
    });
  }

  // Datepicker
  if ($("#inline-datepicker").length) {
    $('#inline-datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
  }

  /**
   * Ajuste de Layout sem ProBanner
   * Forçamos a Navbar a ser fixed-top para manter o padrão do template
   */
  const navbar = document.querySelector('.navbar');
  const pageWrapper = document.querySelector('.page-body-wrapper');

  if (navbar && pageWrapper) {
    navbar.classList.add('fixed-top');
    navbar.classList.remove('pt-5', 'mt-3');
    pageWrapper.classList.remove('pt-0');
  }

})(jQuery);