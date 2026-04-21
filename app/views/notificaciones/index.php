<?php $pageTitle = 'Notificaciones'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <h1>Notificaciones</h1>
      <p>Alertas del sistema de inventario</p>
    </div>
    <?php if (array_filter($notificaciones, fn($n) => !$n['leida'])): ?>
    <form method="POST" action="<?= BASE_URL ?>/notificaciones/marcar-todas">
      <button type="submit" class="btn btn-ghost">
        <i class="fa-solid fa-check-double"></i> Marcar todas como leídas
      </button>
    </form>
    <?php endif; ?>
  </div>
</div>

<div class="page-body">
  <?php if (!empty($flash)): ?>
  <div class="alert alert-success"><span class="alert-icon">✅</span> <?= htmlspecialchars($flash['message']) ?></div>
  <?php endif; ?>

  <div class="card">
    <?php if (empty($notificaciones)): ?>
    <div class="card-body text-center" style="padding:48px">
      <i class="fa-solid fa-bell-slash" style="font-size:40px;color:var(--text-light);margin-bottom:12px"></i>
      <p style="color:var(--text-muted)">Sin notificaciones</p>
    </div>
    <?php else: ?>
    <?php foreach ($notificaciones as $n): ?>
    <div class="notif-item <?= !$n['leida'] ? 'unread' : '' ?> <?= $n['tipo'] === 'por_vencer' ? 'warning' : '' ?>">
      <div class="notif-icon <?= $n['tipo'] === 'por_vencer' ? 'warning' : 'danger' ?>">
        <i class="fa-solid fa-triangle-exclamation"></i>
      </div>
      <div class="notif-body" style="flex:1">
        <div class="notif-msg"><?= htmlspecialchars($n['mensaje']) ?></div>
        <div class="notif-time"><?= date('d/m/Y H:i', strtotime($n['created_at'])) ?></div>
      </div>
      <div style="display:flex;align-items:center;gap:8px">
        <?php if (!$n['leida']): ?>
        <span class="badge badge-danger">Nueva</span>
        <form method="POST" action="<?= BASE_URL ?>/notificaciones/marcar-leida/<?= $n['id'] ?>">
          <button type="submit" class="btn btn-ghost btn-sm">
            <i class="fa-solid fa-check"></i> Leída
          </button>
        </form>
        <?php else: ?>
        <span class="badge badge-info">Leída</span>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
