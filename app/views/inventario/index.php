<?php $pageTitle = 'Control de Inventario'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <h1>Control de Inventario</h1>
      <p>Registro de entradas y salidas de stock</p>
    </div>
    <div style="display:flex;gap:8px">
      <button onclick="openModal('entrada')" class="btn btn-success">
        <i class="fa-solid fa-arrow-down"></i> Entrada
      </button>
      <button onclick="openModal('salida')" class="btn btn-warning">
        <i class="fa-solid fa-arrow-up"></i> Salida
      </button>
    </div>
  </div>
</div>

<div class="page-body">
  <?php if (!empty($flash)): ?>
  <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
    <span class="alert-icon"><?= $flash['type'] === 'error' ? '⚠️' : '✅' ?></span>
    <?= htmlspecialchars($flash['message']) ?>
  </div>
  <?php endif; ?>

  <!-- RECENT MOVEMENTS -->
  <div class="card">
    <div class="card-header">
      <h3>Movimientos Recientes</h3>
      <a href="<?= BASE_URL ?>/inventario/movimientos" class="btn btn-ghost btn-sm">Ver todos</a>
    </div>
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
          <tr><td colspan="6" class="text-center" style="padding:32px;color:var(--text-muted)">Sin movimientos registrados</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- MODAL ENTRADA -->
<div class="modal-overlay" id="modalEntrada">
  <div class="modal">
    <div class="modal-header">
      <h3 style="color:var(--success)"><i class="fa-solid fa-arrow-down"></i> Registrar Entrada</h3>
      <button onclick="closeModal('entrada')" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--text-muted)">✕</button>
    </div>
    <form action="<?= BASE_URL ?>/inventario/entrada" method="POST">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Medicamento <span class="req">*</span></label>
          <select name="medicamento_id" class="form-control" required>
            <option value="">Seleccionar medicamento</option>
            <?php foreach ($medicamentos as $med): ?>
            <option value="<?= $med['id'] ?>"><?= htmlspecialchars($med['nombre']) ?> (Stock: <?= $med['stock_actual'] ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Cantidad <span class="req">*</span></label>
          <input type="number" name="cantidad" class="form-control" min="1" required placeholder="Unidades a ingresar">
        </div>
        <div class="form-group">
          <label class="form-label">Motivo</label>
          <input type="text" name="motivo" class="form-control" placeholder="Ej: Compra, Devolución..." value="Entrada de stock">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="closeModal('entrada')" class="btn btn-outline">Cancelar</button>
        <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Confirmar Entrada</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL SALIDA -->
<div class="modal-overlay" id="modalSalida">
  <div class="modal">
    <div class="modal-header">
      <h3 style="color:var(--warning-dark)"><i class="fa-solid fa-arrow-up"></i> Registrar Salida</h3>
      <button onclick="closeModal('salida')" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--text-muted)">✕</button>
    </div>
    <form action="<?= BASE_URL ?>/inventario/salida" method="POST">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Medicamento <span class="req">*</span></label>
          <select name="medicamento_id" class="form-control" required>
            <option value="">Seleccionar medicamento</option>
            <?php foreach ($medicamentos as $med): ?>
            <option value="<?= $med['id'] ?>"><?= htmlspecialchars($med['nombre']) ?> (Stock: <?= $med['stock_actual'] ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Cantidad <span class="req">*</span></label>
          <input type="number" name="cantidad" class="form-control" min="1" required placeholder="Unidades a retirar">
        </div>
        <div class="form-group">
          <label class="form-label">Motivo</label>
          <input type="text" name="motivo" class="form-control" placeholder="Ej: Despacho, Vencimiento..." value="Salida de stock">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="closeModal('salida')" class="btn btn-outline">Cancelar</button>
        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-check"></i> Confirmar Salida</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(tipo) {
  document.getElementById('modal' + tipo.charAt(0).toUpperCase() + tipo.slice(1)).classList.add('active');
}
function closeModal(tipo) {
  document.getElementById('modal' + tipo.charAt(0).toUpperCase() + tipo.slice(1)).classList.remove('active');
}
</script>
