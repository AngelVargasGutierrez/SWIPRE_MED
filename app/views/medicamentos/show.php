<?php $pageTitle = 'Detalle Medicamento'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <a href="<?= BASE_URL ?>/medicamentos" style="font-size:13px;color:var(--text-muted);display:flex;align-items:center;gap:4px;margin-bottom:6px">
        <i class="fa-solid fa-arrow-left"></i> Volver
      </a>
      <h1><?= htmlspecialchars($medicamento['nombre']) ?></h1>
      <p><?= htmlspecialchars($medicamento['laboratorio']) ?> · <?= htmlspecialchars($medicamento['categoria']) ?></p>
    </div>
    <div style="display:flex;gap:8px">
      <a href="<?= BASE_URL ?>/medicamentos/edit/<?= $medicamento['id'] ?>" class="btn btn-primary">
        <i class="fa-solid fa-pen-to-square"></i> Editar
      </a>
    </div>
  </div>
</div>

<div class="page-body">
  <?php
    $total = $medicamento['stock_actual'];
    $min   = $medicamento['stock_minimo'];
    $estado = $total <= $min * 0.3 ? 'critico' : ($total <= $min ? 'bajo' : 'normal');
  ?>
  <div class="stats-grid" style="grid-template-columns:repeat(4,1fr)">
    <div class="stat-card <?= $estado === 'critico' ? 'red' : ($estado === 'bajo' ? 'amber' : 'blue') ?>">
      <div class="stat-info">
        <div class="label">Stock Actual</div>
        <div class="value"><?= number_format($total) ?></div>
        <div class="sub">Mín: <?= number_format($min) ?></div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
    </div>
    <div class="stat-card green">
      <div class="stat-info">
        <div class="label">Valor en Stock</div>
        <div class="value">$<?= number_format($total * $medicamento['costo_unitario'], 2, ',', '.') ?></div>
        <div class="sub">@ $<?= $medicamento['costo_unitario'] ?> / u</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-dollar-sign"></i></div>
    </div>
    <div class="stat-card purple">
      <div class="stat-info">
        <div class="label">Lote</div>
        <div class="value" style="font-size:16px"><?= htmlspecialchars($medicamento['numero_lote']) ?></div>
        <div class="sub">Número de lote</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-barcode"></i></div>
    </div>
    <div class="stat-card <?= strtotime($medicamento['fecha_vencimiento']) < strtotime('+90 days') ? 'amber' : 'cyan' ?>">
      <div class="stat-info">
        <div class="label">Vencimiento</div>
        <div class="value" style="font-size:16px"><?= date('d/m/Y', strtotime($medicamento['fecha_vencimiento'])) ?></div>
        <div class="sub">Fecha de vencimiento</div>
      </div>
      <div class="stat-icon"><i class="fa-solid fa-calendar"></i></div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h3>Historial de Movimientos</h3></div>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Fecha</th><th>Tipo</th><th>Cantidad</th><th>Motivo</th><th>Usuario</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($movimientos as $m): ?>
          <tr>
            <td><?= date('d/m/Y H:i', strtotime($m['fecha'])) ?></td>
            <td>
              <?php if ($m['tipo'] === 'entrada'): ?>
                <span class="badge badge-success"><i class="fa-solid fa-arrow-down"></i> Entrada</span>
              <?php else: ?>
                <span class="badge badge-warning"><i class="fa-solid fa-arrow-up"></i> Salida</span>
              <?php endif; ?>
            </td>
            <td class="font-bold"><?= number_format($m['cantidad']) ?></td>
            <td><?= htmlspecialchars($m['motivo']) ?></td>
            <td><?= htmlspecialchars($m['usuario_nombre']) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($movimientos)): ?>
          <tr><td colspan="5" class="text-center" style="padding:30px;color:var(--text-muted)">Sin movimientos registrados</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
