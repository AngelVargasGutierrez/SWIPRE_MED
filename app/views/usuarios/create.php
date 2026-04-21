<?php $pageTitle = 'Nuevo Usuario'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <a href="<?= BASE_URL ?>/usuarios" style="font-size:13px;color:var(--text-muted);display:flex;align-items:center;gap:4px;margin-bottom:6px">
        <i class="fa-solid fa-arrow-left"></i> Volver
      </a>
      <h1>Nuevo Usuario</h1>
    </div>
  </div>
</div>

<div class="page-body">
  <div style="max-width:580px">
    <form action="<?= BASE_URL ?>/usuarios/store" method="POST">
      <div class="form-section">
        <div class="form-section-title"><i class="fa-solid fa-user"></i> Información del Usuario</div>
        <div class="form-group">
          <label class="form-label">Nombre Completo <span class="req">*</span></label>
          <input type="text" name="nombre" class="form-control" required placeholder="Ej: Juan Pérez">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nombre de Usuario <span class="req">*</span></label>
            <input type="text" name="username" class="form-control" required placeholder="Ej: jperez">
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="correo@hospital.com">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Contraseña <span class="req">*</span></label>
            <input type="password" name="password" class="form-control" required minlength="6">
          </div>
          <div class="form-group">
            <label class="form-label">Rol <span class="req">*</span></label>
            <select name="rol" class="form-control" required>
              <option value="farmacia">Farmacia</option>
              <option value="jefatura">Jefatura</option>
              <option value="admin">Administrador</option>
            </select>
          </div>
        </div>
      </div>
      <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-user-plus"></i> Crear Usuario
        </button>
        <a href="<?= BASE_URL ?>/usuarios" class="btn btn-outline btn-lg">Cancelar</a>
      </div>
    </form>
  </div>
</div>
