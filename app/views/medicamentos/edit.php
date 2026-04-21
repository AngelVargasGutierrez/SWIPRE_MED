<?php $pageTitle = 'Editar Medicamento'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <a href="<?= BASE_URL ?>/medicamentos" style="font-size:13px;color:var(--text-muted);display:flex;align-items:center;gap:4px;margin-bottom:6px">
        <i class="fa-solid fa-arrow-left"></i> Volver a Medicamentos
      </a>
      <h1>Editar Medicamento</h1>
      <p>Modifica los datos del medicamento</p>
    </div>
  </div>
</div>

<div class="page-body">
  <form action="<?= BASE_URL ?>/medicamentos/update/<?= $medicamento['id'] ?>" method="POST">
    <div class="form-section">
      <div class="form-section-title"><i class="fa-solid fa-circle-info"></i> Información Básica</div>
      <div class="form-group">
        <label class="form-label">Nombre del Medicamento <span class="req">*</span></label>
        <input type="text" name="nombre" class="form-control"
               value="<?= htmlspecialchars($medicamento['nombre']) ?>" required>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Laboratorio <span class="req">*</span></label>
          <input type="text" name="laboratorio" class="form-control"
                 value="<?= htmlspecialchars($medicamento['laboratorio']) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Categoría <span class="req">*</span></label>
          <select name="categoria" class="form-control" required>
            <?php foreach (['Analgésicos','Antiinflamatorios','Antibióticos','Antiácidos','Antihipertensivos','Antidiabéticos','Hipolipemiantes','Broncodilatadores','Antihistamínicos','Antiinflamatorios tópicos','Ansiolíticos','Antidepresivos','Vitaminas','Otros'] as $cat): ?>
            <option value="<?= $cat ?>" <?= $medicamento['categoria'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>

    <div class="form-section">
      <div class="form-section-title"><i class="fa-solid fa-warehouse"></i> Stock y Precios</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Costo Unitario ($) <span class="req">*</span></label>
          <input type="number" name="costo_unitario" class="form-control"
                 value="<?= $medicamento['costo_unitario'] ?>" min="0" step="0.01" required>
        </div>
        <div class="form-group">
          <label class="form-label">Stock Mínimo <span class="req">*</span></label>
          <input type="number" name="stock_minimo" class="form-control"
                 value="<?= $medicamento['stock_minimo'] ?>" min="0" required>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Stock Actual <span class="req">*</span></label>
        <input type="number" name="stock_actual" class="form-control"
               value="<?= $medicamento['stock_actual'] ?>" min="0" required>
        <p class="form-hint">Para ajustes de stock usa Control de Inventario (entradas/salidas)</p>
      </div>
    </div>

    <div class="form-section">
      <div class="form-section-title"><i class="fa-solid fa-barcode"></i> Lote y Vencimiento</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Número de Lote <span class="req">*</span></label>
          <input type="text" name="numero_lote" class="form-control"
                 value="<?= htmlspecialchars($medicamento['numero_lote']) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Fecha de Vencimiento <span class="req">*</span></label>
          <input type="date" name="fecha_vencimiento" class="form-control"
                 value="<?= $medicamento['fecha_vencimiento'] ?>" required>
        </div>
      </div>
    </div>

    <div style="display:flex;gap:12px">
      <button type="submit" class="btn btn-primary btn-lg">
        <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
      </button>
      <a href="<?= BASE_URL ?>/medicamentos" class="btn btn-outline btn-lg">Cancelar</a>
    </div>
  </form>
</div>
