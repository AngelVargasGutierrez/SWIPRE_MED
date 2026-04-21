<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SWIPRE-MED — <?= htmlspecialchars($pageTitle ?? 'Panel') ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-wrapper">

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
      <div class="logo-text">
        <h2>SWIPRE-MED</h2>
        <span>Sistema de Inventario</span>
      </div>
    </div>

    <div class="sidebar-user">
      <div class="user-greeting">Bienvenido/a</div>
      <div class="user-name"><?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario') ?></div>
      <span class="user-role"><?= ucfirst($_SESSION['user']['rol'] ?? 'Usuario') ?></span>
    </div>

    <nav class="sidebar-nav">
      <?php
        $currentUri = '/' . trim($_GET['url'] ?? '', '/');
        $user = $_SESSION['user'] ?? [];
        function navItem(string $icon, string $label, string $path, string $current, ?int $badge = null): void {
          $active = strpos($current, $path) === 0 && ($path !== '/' || $current === '/') ? 'active' : '';
          echo "<li class='nav-item $active'><a href='" . BASE_URL . $path . "'>";
          echo "<span class='nav-icon'><i class='$icon'></i></span> $label";
          if ($badge) echo "<span class='nav-badge'>$badge</span>";
          echo "</a></li>";
        }
      ?>
      <ul>
        <?php navItem('fa-solid fa-gauge', 'Dashboard', '/dashboard', $currentUri); ?>
        <?php navItem('fa-solid fa-pills', 'Medicamentos', '/medicamentos', $currentUri); ?>
        <?php if (($user['rol'] ?? '') !== 'farmacia'): ?>
        <?php navItem('fa-solid fa-boxes-stacked', 'Control Inventario', '/inventario', $currentUri); ?>
        <?php navItem('fa-solid fa-bell', 'Notificaciones', '/notificaciones', $currentUri, ($notifCount ?? 0) > 0 ? ($notifCount ?? 0) : null); ?>
        <?php navItem('fa-solid fa-file-lines', 'Reportes', '/reportes', $currentUri); ?>
        <?php navItem('fa-solid fa-chart-line', 'Analytics', '/analytics', $currentUri); ?>
        <?php endif; ?>
        <?php if (($user['rol'] ?? '') === 'admin'): ?>
        <?php navItem('fa-solid fa-users', 'Usuarios', '/usuarios', $currentUri); ?>
        <?php endif; ?>
      </ul>
    </nav>

    <div class="sidebar-footer">
      <a href="<?= BASE_URL ?>/logout">
        <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
      </a>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main-content">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <?= $content ?>
  </main>

</div>
<script src="<?= BASE_URL ?>/js/app.js"></script>
</body>
</html>
