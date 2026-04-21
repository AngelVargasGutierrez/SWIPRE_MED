<?php $pageTitle = 'Dashboard'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <h1>Dashboard</h1>
      <p><?= $rolActual === 'farmacia' ? 'Panel de Farmacia' : 'Visión general del inventario farmacéutico' ?></p>
    </div>
    <span style="font-size:12px;color:var(--text-muted)"><?= date('d/m/Y H:i') ?></span>
  </div>
</div>

<div class="page-body">

  <?php if (!empty($flash)): ?>
  <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
    <span class="alert-icon"><?= $flash['type'] === 'error' ? '⚠️' : '✅' ?></span>
    <?= htmlspecialchars($flash['message']) ?>
  </div>
  <?php endif; ?>

  <?php if ($rolActual === 'farmacia'): ?>
  <!-- ==================== VISTA FARMACIA ==================== -->

  <div class="stats-grid" style="grid-template-columns:repeat(2,1fr)">
    <div class="stat-card blue">
      <div class="stat-info">
        <div class="label">Total Medicamentos</div>
        <div class="value"><?= $totalMedicamentos ?></div>
        <div class="sub">Activos en inventario</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-pills"></i></div>
    </div>
    <div class="stat-card red">
      <div class="stat-info">
        <div class="label">Stock Crítico</div>
        <div class="value"><?= $stockCritico ?></div>
        <div class="sub">Requieren atención</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    </div>
  </div>

  <div class="card" style="margin-top:24px">
    <div class="card-header">
      <h3>Top 5 Medicamentos más Buscados</h3>
      <span style="font-size:12px;color:var(--text-muted)">Basado en búsquedas en Medicamentos</span>
    </div>
    <div class="card-body">
      <div class="chart-container" style="height:260px">
        <canvas id="chartTop5Farmacia"></canvas>
      </div>
    </div>
  </div>

  <?php else: ?>
  <!-- ==================== VISTA ADMIN / JEFATURA ==================== -->

  <div class="stats-grid">
    <div class="stat-card blue">
      <div class="stat-info">
        <div class="label">Total Medicamentos</div>
        <div class="value"><?= $totalMedicamentos ?></div>
        <div class="sub">↑ Activos en inventario</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-box"></i></div>
    </div>
    <div class="stat-card red">
      <div class="stat-info">
        <div class="label">Stock Crítico</div>
        <div class="value"><?= $stockCritico ?></div>
        <div class="sub">↘ Requieren atención</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    </div>
    <div class="stat-card amber">
      <div class="stat-info">
        <div class="label">Por Vencer (90 días)</div>
        <div class="value"><?= $porVencer ?></div>
        <div class="sub">📅 Próximos a vencer</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
    </div>
    <div class="stat-card green">
      <div class="stat-info">
        <div class="label">Valor Total</div>
        <div class="value">$<?= number_format($valorTotal, 2, ',', '.') ?></div>
        <div class="sub">$ Inventario valorizado</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-dollar-sign"></i></div>
    </div>
  </div>

  <div class="dashboard-grid">
    <div class="card">
      <div class="card-header"><h3>Stock por Categoría</h3></div>
      <div class="card-body">
        <div class="chart-container" style="height:240px">
          <canvas id="chartCategoria"></canvas>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header"><h3>Estado del Stock</h3></div>
      <div class="card-body" style="display:flex;flex-direction:column;align-items:center;justify-content:center">
        <div class="chart-container" style="width:200px;height:200px">
          <canvas id="chartEstado"></canvas>
        </div>
        <div style="margin-top:16px;width:100%">
          <div class="legend-item"><span class="legend-dot" style="background:#10b981"></span> Normal: <?= $estadoStock['normal'] ?></div>
          <div class="legend-item"><span class="legend-dot" style="background:#f59e0b"></span> Bajo: <?= $estadoStock['bajo'] ?></div>
          <div class="legend-item"><span class="legend-dot" style="background:#ef4444"></span> Crítico: <?= $estadoStock['critico'] ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="dashboard-grid">
    <div class="card">
      <div class="card-header"><h3>Movimientos de la Semana</h3></div>
      <div class="card-body">
        <div class="chart-container" style="height:220px">
          <canvas id="chartMovimientos"></canvas>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header"><h3>Top 5 Medicamentos por Valor</h3></div>
      <div class="card-body">
        <div class="chart-container" style="height:220px">
          <canvas id="chartTop5"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="dashboard-grid thirds">
    <div class="card">
      <div class="card-header">
        <h3>Alertas Críticas</h3>
        <?php if (count($alertas) > 0): ?>
        <span class="badge badge-danger"><?= count($alertas) ?> Nuevas</span>
        <?php endif; ?>
      </div>
      <?php if (empty($alertas)): ?>
      <div class="card-body text-center" style="padding:32px;color:var(--text-muted)">
        <i class="fa-solid fa-check-circle" style="font-size:32px;color:var(--success);margin-bottom:8px"></i>
        <p>Sin alertas pendientes</p>
      </div>
      <?php else: ?>
      <?php foreach (array_slice($alertas, 0, 5) as $a): ?>
      <div class="notif-item unread <?= $a['tipo'] === 'por_vencer' ? 'warning' : '' ?>">
        <div class="notif-icon <?= $a['tipo'] === 'por_vencer' ? 'warning' : 'danger' ?>">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="notif-body">
          <div class="notif-msg"><?= $a['tipo'] === 'stock_critico' ? 'Stock Crítico' : 'Medicamento por Vencer' ?></div>
          <div class="notif-time"><?= htmlspecialchars($a['mensaje']) ?></div>
          <div class="notif-time" style="margin-top:2px"><?= date('d/m/Y, H:i:s', strtotime($a['created_at'])) ?></div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="card">
      <div class="card-header"><h3>Estadísticas Rápidas</h3></div>
      <div class="card-body">
        <div class="quick-stat">
          <div class="qs-label"><span>📊</span> Movimientos Hoy</div>
          <div class="qs-val"><?= $movimientosHoy ?></div>
        </div>
        <div class="quick-stat">
          <div class="qs-label"><span>📦</span> Entradas esta semana</div>
          <div class="qs-val"><?= $entradasSemana ?></div>
        </div>
        <div class="quick-stat">
          <div class="qs-label"><span>📤</span> Salidas esta semana</div>
          <div class="qs-val"><?= $salidasSemana ?></div>
        </div>
        <div class="quick-stat">
          <div class="qs-label"><span>📅</span> Vencimientos próximos</div>
          <div class="qs-val"><?= $porVencer ?></div>
        </div>
      </div>
    </div>
  </div>

  <?php endif; ?>
</div>

<?php
$catLabels   = json_encode(array_column($stockPorCategoria, 'categoria'));
$catValues   = json_encode(array_map('intval', array_column($stockPorCategoria, 'total_stock')));
$estadoData  = json_encode([(int)$estadoStock['normal'], (int)$estadoStock['bajo'], (int)$estadoStock['critico']]);
$semDias     = json_encode(array_map(fn($m) => date('D', strtotime($m['dia'])), $movimientosSemana));
$semEntradas = json_encode(array_map('intval', array_column($movimientosSemana, 'entradas')));
$semSalidas  = json_encode(array_map('intval', array_column($movimientosSemana, 'salidas')));
$top5Names   = json_encode(array_column($top5PorValor, 'nombre'));
$top5Values  = json_encode(array_map('floatval', array_column($top5PorValor, 'valor_total')));
$busqNames   = json_encode(array_column($top5MasBuscados, 'nombre'));
$busqValues  = json_encode(array_map('intval', array_column($top5MasBuscados, 'busquedas')));
?>
<script>
<?php if ($rolActual === 'farmacia'): ?>
new Chart(document.getElementById('chartTop5Farmacia'), {
  type: 'bar',
  data: {
    labels: <?= $busqNames ?>,
    datasets: [{
      label: 'Búsquedas',
      data: <?= $busqValues ?>,
      backgroundColor: ['#1a4fa0','#3b82f6','#60a5fa','#93c5fd','#bfdbfe'],
      borderRadius: 6, borderWidth: 0
    }]
  },
  options: {
    indexAxis: 'y', responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { x: { beginAtZero: true, grid: { color: '#f1f5f9' } }, y: { grid: { display: false } } }
  }
});
<?php else: ?>
new Chart(document.getElementById('chartCategoria'), {
  type: 'bar',
  data: {
    labels: <?= $catLabels ?>,
    datasets: [{ label: 'Stock', data: <?= $catValues ?>,
      backgroundColor: 'rgba(26,79,160,.75)', borderColor: '#1a4fa0',
      borderWidth: 1, borderRadius: 4 }]
  },
  options: { responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
  }
});
new Chart(document.getElementById('chartEstado'), {
  type: 'doughnut',
  data: {
    labels: ['Normal','Bajo','Crítico'],
    datasets: [{ data: <?= $estadoData ?>, backgroundColor: ['#10b981','#f59e0b','#ef4444'], borderWidth: 0 }]
  },
  options: { responsive: true, maintainAspectRatio: false, cutout: '68%',
    plugins: { legend: { display: false } }
  }
});
new Chart(document.getElementById('chartMovimientos'), {
  type: 'line',
  data: {
    labels: <?= $semDias ?>,
    datasets: [
      { label: 'entradas', data: <?= $semEntradas ?>, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,.1)', tension: .4, fill: true, pointRadius: 4 },
      { label: 'salidas',  data: <?= $semSalidas  ?>, borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,.1)',  tension: .4, fill: true, pointRadius: 4 }
    ]
  },
  options: { responsive: true, maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12 } } },
    scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
  }
});
new Chart(document.getElementById('chartTop5'), {
  type: 'bar',
  data: {
    labels: <?= $top5Names ?>,
    datasets: [{ label: 'Valor ($)', data: <?= $top5Values ?>,
      backgroundColor: 'rgba(59,130,246,.8)', borderRadius: 4 }]
  },
  options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { x: { grid: { color: '#f1f5f9' } }, y: { grid: { display: false } } }
  }
});
<?php endif; ?>
</script>
