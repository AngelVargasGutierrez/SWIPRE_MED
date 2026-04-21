<?php $pageTitle = 'Iniciar Sesión'; ?>
<div class="auth-card">
  <div class="auth-logo">
    <div class="logo-circle"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h1>SWIPRE-MED</h1>
    <p>Sistema de Gestión de <strong style="color:#1a4fa0">Inventario Farmacéutico</strong></p>
  </div>

  <?php if (!empty($flash)): ?>
  <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
    <span class="alert-icon"><?= $flash['type'] === 'error' ? '⚠️' : '✅' ?></span>
    <?= htmlspecialchars($flash['message']) ?>
  </div>
  <?php endif; ?>

  <h2 class="auth-form-title">Iniciar Sesión</h2>

  <form action="<?= BASE_URL ?>/login" method="POST">
    <div class="form-group">
      <label class="form-label">Usuario</label>
      <div class="input-group">
        <span class="input-icon"><i class="fa-solid fa-user"></i></span>
        <input type="text" name="username" class="form-control"
               placeholder="Ingrese su usuario" required autocomplete="username">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Contraseña</label>
      <div class="input-group">
        <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
        <input type="password" name="password" id="passInput" class="form-control"
               placeholder="Ingrese su contraseña" required autocomplete="current-password">
        <button type="button" class="toggle-pass" onclick="togglePass()">
          <i class="fa-solid fa-eye" id="passIcon"></i>
        </button>
      </div>
    </div>

    <button type="submit" class="btn btn-primary w-full btn-lg" style="margin-top:8px">
      <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
    </button>
  </form>

  <div class="auth-credentials">
    <strong>Credenciales de prueba:</strong>
    <div>
      <span>Admin:</span> admin / admin123<br>
      <span>Farmacia:</span> farmacia / farmacia123<br>
      <span>Jefatura:</span> jefatura / jefatura123
    </div>
  </div>
</div>

<script>
function togglePass() {
  const input = document.getElementById('passInput');
  const icon  = document.getElementById('passIcon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.className = 'fa-solid fa-eye-slash';
  } else {
    input.type = 'password';
    icon.className = 'fa-solid fa-eye';
  }
}
</script>
