function abrirModal(tecnico, tipo, horas, archivo, observaciones, pedido) {
  document.getElementById('modalTecnico').innerText = tecnico;
  document.getElementById('modalTipo').innerText = tipo;
  document.getElementById('modalHoras').innerText = horas;
  document.getElementById('modalObservaciones').innerText = observaciones || 'Sin observaciones';
  document.getElementById('modalPedido').innerText = pedido || 'Sin pedido';

  let contenido = '';
  if (archivo) {
      let ext = archivo.split('.').pop().toLowerCase();
      if (['jpg', 'jpeg', 'png'].includes(ext)) {
          contenido = `<img src="uploads/${archivo}" style="max-width:100%;">`;
      } else if (ext === 'pdf') {
          contenido = `<iframe src="uploads/${archivo}" width="100%" height="400px" style="border:none;"></iframe>`;
      } else {
          contenido = 'Archivo no disponible.';
      }
  } else {
      contenido = 'Sin archivo adjunto.';
  }

  document.getElementById('modalArchivo').innerHTML = contenido;
  document.getElementById('modalOrden').style.display = 'flex';
}

function cerrarModal() {
  document.getElementById('modalOrden').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
  crearGraficoCircular('pendientesChart', porcentajePendientes, 'Pendientes', '#c40000');
  crearGraficoCircular('procesandoChart', porcentajeProcesando, 'Procesando', '#ffa500');
  crearGraficoCircular('finalizadasChart', porcentajeFinalizadas, 'Finalizadas', '#4caf50');
});

function crearGraficoCircular(idCanvas, porcentaje, titulo, color) {
  const ctx = document.getElementById(idCanvas).getContext('2d');

  if (ctx.chartInstance) {
      ctx.chartInstance.destroy();
  }

  const chart = new Chart(ctx, {
      type: 'doughnut',
      data: {
          labels: [titulo, 'Restante'],
          datasets: [{
              data: [porcentaje, 100 - porcentaje],
              backgroundColor: [color, '#e0e0e0'],
              borderWidth: 2
          }]
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '70%',
          plugins: {
              legend: { display: false },
              tooltip: {
                  callbacks: {
                      label: function(context) {
                          return `${context.label}: ${context.parsed}%`;
                      }
                  }
              }
          }
      }
  });

  ctx.chartInstance = chart;
}
