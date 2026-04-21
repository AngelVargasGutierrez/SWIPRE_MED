<?php $pageTitle = 'Historial de Movimientos'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <a href="<?= BASE_URL ?>/inventario" style="font-size:13px;color:var(--text-muted);display:flex;align-items:center;gap:4px;margin-bottom:6px">
        <i class="fa-solid fa-arrow-left"></i> Volver a Inventario
      </a>
      <h1>Historial de Movimientos</h1>
      <p>Todos los movimientos de inventario</p>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="card">
    <div class="table-wrapper">
      <table>
        <thead>
          <tr><th>Fecha</th><th>Medicamento</th><th>Tipo</th><th>Cantidad</th><th>Motivo</th><th>Usuario</th></tr>
        </thead>
        <tbody>
          <?php foreach ($movimientos as $m): ?>
          <tr>
            <td><?= date('d/m/Y H:i', strtotime($m['fecha'])) ?></td>
            <td class="font-bold"><?= htmlspecialchars($m['medicamento_nombre']) ?></td>
            <td>
              <?php if ($m['tipo'] === 'entrada'): ?>
                <span class="badge badge-success"><i class="fa-solid fa-plus"></i> Entrada</span>
              <?php else: ?>
                <span class="badge badge-warning"><i class="fa-solid fa-minus"></i> Salida</span>
              <?php endif; ?>
            </td>
            <td class="font-bold"><?= number_format($m['cantidad']) ?></td>
            <td><?= htmlspecialchars($m['motivo']) ?></td>
            <td><?= htmlspecialchars($m['usuario_nombre']) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($movimientos)): ?>
          <tr><td colspan="6" class="text-center" style="padding:40px;color:var(--text-muted)">Sin movimientos</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
