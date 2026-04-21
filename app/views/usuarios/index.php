<?php $pageTitle = 'Usuarios'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <h1>Gestión de Usuarios</h1>
      <p>Administración de cuentas del sistema</p>
    </div>
    <a href="<?= BASE_URL ?>/usuarios/create" class="btn btn-primary">
      <i class="fa-solid fa-user-plus"></i> Nuevo Usuario
    </a>
  </div>
</div>

<div class="page-body">
  <?php if (!empty($flash)): ?>
  <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
    <span class="alert-icon"><?= $flash['type'] === 'error' ? '⚠️' : '✅' ?></span>
    <?= htmlspecialchars($flash['message']) ?>
  </div>
  <?php endif; ?>

  <div class="card">
    <div class="table-wrapper">
      <table>
        <thead>
          <tr><th>Nombre</th><th>Usuario</th><th>Email</th><th>Rol</th><th>Creado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $u): ?>
          <tr>
            <td class="font-bold">
              <div style="display:flex;align-items:center;gap:10px">
                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#1a4fa0,#3b82f6);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px">
                  <?= strtoupper(substr($u['nombre'], 0, 1)) ?>
                </div>
                <?= htmlspecialchars($u['nombre']) ?>
              </div>
            </td>
            <td><code style="background:#f1f5f9;padding:2px 8px;border-radius:4px;font-size:12px"><?= htmlspecialchars($u['username']) ?></code></td>
            <td><?= htmlspecialchars($u['email'] ?? '-') ?></td>
            <td>
              <?php if ($u['rol'] === 'admin'): ?>
                <span class="badge badge-primary">Admin</span>
              <?php elseif ($u['rol'] === 'jefatura'): ?>
                <span class="badge badge-warning">Jefatura</span>
              <?php else: ?>
                <span class="badge badge-info">Farmacia</span>
              <?php endif; ?>
            </td>
            <td><?= isset($u['created_at']) ? date('d/m/Y', strtotime($u['created_at'])) : '-' ?></td>
            <td>
              <div class="actions-cell">
                <a href="<?= BASE_URL ?>/usuarios/edit/<?= $u['id'] ?>" class="btn btn-ghost btn-icon btn-sm">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <?php if ($u['id'] !== $_SESSION['user']['id']): ?>
                <form method="POST" action="<?= BASE_URL ?>/usuarios/delete/<?= $u['id'] ?>"
                      onsubmit="return confirm('¿Eliminar usuario <?= htmlspecialchars($u['nombre']) ?>?')">
                  <button type="submit" class="btn btn-danger btn-icon btn-sm">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
