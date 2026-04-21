<?php $pageTitle = 'Editar Usuario'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <a href="<?= BASE_URL ?>/usuarios" style="font-size:13px;color:var(--text-muted);display:flex;align-items:center;gap:4px;margin-bottom:6px">
        <i class="fa-solid fa-arrow-left"></i> Volver
      </a>
      <h1>Editar Usuario</h1>
      <p><?= htmlspecialchars($usuario['username']) ?></p>
    </div>
  </div>
</div>

<div class="page-body">
  <div style="max-width:580px">
    <form action="<?= BASE_URL ?>/usuarios/update/<?= $usuario['id'] ?>" method="POST">
      <div class="form-section">
        <div class="form-section-title"><i class="fa-solid fa-user"></i> Información del Usuario</div>
        <div class="form-group">
          <label class="form-label">Nombre Completo <span class="req">*</span></label>
          <input type="text" name="nombre" class="form-control"
                 value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($usuario['email'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Rol <span class="req">*</span></label>
            <select name="rol" class="form-control" required>
              <option value="farmacia"  <?= $usuario['rol'] === 'farmacia'  ? 'selected' : '' ?>>Farmacia</option>
              <option value="jefatura"  <?= $usuario['rol'] === 'jefatura'  ? 'selected' : '' ?>>Jefatura</option>
              <option value="admin"     <?= $usuario['rol'] === 'admin'     ? 'selected' : '' ?>>Administrador</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Nueva Contraseña <span style="font-size:11px;color:var(--text-muted)">(dejar vacío para no cambiar)</span></label>
          <input type="password" name="password" class="form-control" minlength="6" placeholder="••••••">
        </div>
      </div>
      <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
        </button>
        <a href="<?= BASE_URL ?>/usuarios" class="btn btn-outline btn-lg">Cancelar</a>
      </div>
    </form>
  </div>
</div>
