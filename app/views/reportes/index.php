<?php $pageTitle = 'Reportes'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <h1>Reportes</h1>
      <p>Informes del inventario farmacéutico</p>
    </div>
    <div style="display:flex;gap:8px">
      <a href="<?= BASE_URL ?>/reportes/exportar?tipo=medicamentos" class="btn btn-primary">
        <i class="fa-solid fa-file-csv"></i> Exportar Medicamentos
      </a>
      <a href="<?= BASE_URL ?>/reportes/exportar?tipo=movimientos" class="btn btn-outline">
        <i class="fa-solid fa-file-csv"></i> Exportar Movimientos
      </a>
    </div>
  </div>
</div>

<div class="page-body">
  <!-- SUMMARY CARDS -->
  <div class="stats-grid" style="grid-template-columns:repeat(4,1fr)">
    <div class="stat-card blue">
      <div class="stat-info">
        <div class="label">Total Medicamentos</div>
        <div class="value"><?= count($medicamentos) ?></div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-pills"></i></div>
    </div>
    <div class="stat-card red">
      <div class="stat-info">
        <div class="label">Stock Crítico</div>
        <div class="value"><?= count($criticos) ?></div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    </div>
    <div class="stat-card amber">
      <div class="stat-info">
        <div class="label">Por Vencer</div>
        <div class="value"><?= count($porVencer) ?></div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
    </div>
    <div class="stat-card green">
      <div class="stat-info">
        <div class="label">Valor Total</div>
        <div class="value" style="font-size:18px">$<?= number_format($valorTotal, 2, ',', '.') ?></div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-dollar-sign"></i></div>
    </div>
  </div>

  <!-- STOCK CRÍTICO -->
  <?php if (!empty($criticos)): ?>
  <div class="card mb-4">
    <div class="card-header">
      <h3 style="color:var(--danger)"><i class="fa-solid fa-triangle-exclamation"></i> Medicamentos con Stock Crítico</h3>
      <span class="badge badge-danger"><?= count($criticos) ?></span>
    </div>
    <div class="table-wrapper">
      <table>
        <thead><tr><th>Medicamento</th><th>Laboratorio</th><th>Stock Actual</th><th>Stock Mínimo</th><th>Déficit</th></tr></thead>
        <tbody>
          <?php foreach ($criticos as $m): ?>
          <tr>
            <td class="font-bold"><?= htmlspecialchars($m['nombre']) ?></td>
            <td><?= htmlspecialchars($m['laboratorio']) ?></td>
            <td style="color:var(--danger);font-weight:700"><?= number_format($m['stock_actual']) ?></td>
            <td><?= number_format($m['stock_minimo']) ?></td>
            <td style="color:var(--danger)"><?= number_format($m['stock_minimo'] - $m['stock_actual']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- STOCK POR CATEGORÍA -->
  <div class="card mb-4">
    <div class="card-header"><h3>Stock por Categoría</h3></div>
    <div class="table-wrapper">
      <table>
        <thead><tr><th>Categoría</th><th>Stock Total</th><th>Distribución</th></tr></thead>
        <tbody>
          <?php
            $maxStock = max(array_column($stockPorCategoria, 'total_stock') ?: [1]);
          ?>
          <?php foreach ($stockPorCategoria as $cat): ?>
          <tr>
            <td class="font-bold"><?= htmlspecialchars($cat['categoria']) ?></td>
            <td><?= number_format($cat['total_stock']) ?></td>
            <td style="width:40%">
              <div style="background:#f1f5f9;border-radius:20px;height:8px">
                <div style="background:linear-gradient(90deg,#1a4fa0,#3b82f6);height:8px;border-radius:20px;width:<?= round($cat['total_stock'] / $maxStock * 100) ?>%"></div>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- MOVIMIENTOS RECIENTES -->
  <div class="card">
    <div class="card-header"><h3>Movimientos Recientes</h3></div>
    <div class="table-wrapper">
      <table>
        <thead><tr><th>Fecha</th><th>Medicamento</th><th>Tipo</th><th>Cantidad</th><th>Motivo</th></tr></thead>
        <tbody>
          <?php foreach ($movimientos as $m): ?>
          <tr>
            <td><?= date('d/m/Y H:i', strtotime($m['fecha'])) ?></td>
            <td class="font-bold"><?= htmlspecialchars($m['medicamento_nombre']) ?></td>
            <td>
              <?php if ($m['tipo'] === 'entrada'): ?>
                <span class="badge badge-success">Entrada</span>
              <?php else: ?>
                <span class="badge badge-warning">Salida</span>
              <?php endif; ?>
            </td>
            <td><?= number_format($m['cantidad']) ?></td>
            <td><?= htmlspecialchars($m['motivo']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
