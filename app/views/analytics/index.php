<?php $pageTitle = 'Analytics'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <h1>Analytics</h1>
      <p>Análisis avanzado del inventario farmacéutico</p>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="stats-grid" style="grid-template-columns:repeat(3,1fr)">
    <div class="stat-card blue">
      <div class="stat-info">
        <div class="label">Valor Total Inventario</div>
        <div class="value" style="font-size:20px">$<?= number_format($valorTotal, 2, ',', '.') ?></div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
    </div>
    <div class="stat-card green">
      <div class="stat-info">
        <div class="label">Stock Normal</div>
        <div class="value"><?= $estadoStock['normal'] ?></div>
        <div class="sub">medicamentos</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
    </div>
    <div class="stat-card red">
      <div class="stat-info">
        <div class="label">Requieren Atención</div>
        <div class="value"><?= (int)$estadoStock['bajo'] + (int)$estadoStock['critico'] ?></div>
        <div class="sub">bajo + crítico</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    </div>
  </div>

  <div class="dashboard-grid">
    <div class="card">
      <div class="card-header"><h3>Stock por Categoría</h3></div>
      <div class="card-body">
        <div class="chart-container" style="height:280px"><canvas id="cCategoria"></canvas></div>
      </div>
    </div>
    <div class="card">
      <div class="card-header"><h3>Distribución de Estado</h3></div>
      <div class="card-body" style="display:flex;flex-direction:column;align-items:center">
        <div class="chart-container" style="width:220px;height:220px"><canvas id="cEstado"></canvas></div>
        <div style="margin-top:16px;width:100%">
          <div class="legend-item"><span class="legend-dot" style="background:#10b981"></span> Normal: <?= $estadoStock['normal'] ?> medicamentos</div>
          <div class="legend-item"><span class="legend-dot" style="background:#f59e0b"></span> Bajo: <?= $estadoStock['bajo'] ?> medicamentos</div>
          <div class="legend-item"><span class="legend-dot" style="background:#ef4444"></span> Crítico: <?= $estadoStock['critico'] ?> medicamentos</div>
        </div>
      </div>
    </div>
  </div>

  <div class="dashboard-grid">
    <div class="card">
      <div class="card-header"><h3>Movimientos de la Semana</h3></div>
      <div class="card-body">
        <div class="chart-container" style="height:240px"><canvas id="cMovimientos"></canvas></div>
      </div>
    </div>
    <div class="card">
      <div class="card-header"><h3>Top 5 Medicamentos más Consultados</h3></div>
      <div class="card-body">
        <div class="chart-container" style="height:240px"><canvas id="cTop5"></canvas></div>
      </div>
    </div>
  </div>
</div>

<?php
$catL  = json_encode(array_column($stockPorCategoria, 'categoria'));
$catV  = json_encode(array_map('intval', array_column($stockPorCategoria, 'total_stock')));
$estD  = json_encode([(int)$estadoStock['normal'], (int)$estadoStock['bajo'], (int)$estadoStock['critico']]);
$semD  = json_encode(array_map(fn($m) => date('D', strtotime($m['dia'])), $movimientosSemana));
$semE  = json_encode(array_map('intval', array_column($movimientosSemana, 'entradas')));
$semS  = json_encode(array_map('intval', array_column($movimientosSemana, 'salidas')));
$t5N   = json_encode(array_column($top5Consultados, 'nombre'));
$t5V   = json_encode(array_map('intval', array_column($top5Consultados, 'busquedas')));
?>
<script>
new Chart(document.getElementById('cCategoria'), {
  type: 'bar',
  data: { labels: <?= $catL ?>, datasets: [{ label: 'Stock', data: <?= $catV ?>,
    backgroundColor: ['#1a4fa0','#3b82f6','#60a5fa','#93c5fd','#10b981','#34d399','#f59e0b','#fbbf24','#ef4444','#f87171','#7c3aed','#a78bfa'],
    borderRadius: 5, borderWidth: 0 }]},
  options: { responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
  }
});
new Chart(document.getElementById('cEstado'), {
  type: 'doughnut',
  data: { labels: ['Normal','Bajo','Crítico'], datasets: [{ data: <?= $estD ?>, backgroundColor: ['#10b981','#f59e0b','#ef4444'], borderWidth: 0 }]},
  options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('cMovimientos'), {
  type: 'line',
  data: { labels: <?= $semD ?>, datasets: [
    { label: 'Entradas', data: <?= $semE ?>, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,.1)', tension: .4, fill: true },
    { label: 'Salidas',  data: <?= $semS ?>, borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,.1)',  tension: .4, fill: true }
  ]},
  options: { responsive: true, maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' } },
    scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
  }
});
new Chart(document.getElementById('cTop5'), {
  type: 'bar',
  data: { labels: <?= $t5N ?>, datasets: [{ label: 'Consultas', data: <?= $t5V ?>,
    backgroundColor: 'rgba(37,99,235,.8)', borderRadius: 5 }]},
  options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } }, scales: { x: {}, y: { grid: { display: false } } }
  }
});
</script>
