<?php $pageTitle = 'Medicamentos'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <h1>Gestión de Medicamentos</h1>
      <p>Administración completa del inventario farmacéutico</p>
    </div>
    <a href="<?= BASE_URL ?>/medicamentos/create" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Nuevo Medicamento
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

  <!-- AI SEARCH -->
  <div class="ai-search-box">
    <div class="ai-search-title"><i class="fa-solid fa-wand-magic-sparkles"></i> Búsqueda Inteligente con IA</div>
    <p class="ai-search-desc">Encuentra medicamentos con autocompletado inteligente, corrección de errores tipográficos y detección de sinónimos.</p>
    <div class="ai-search-input" style="position:relative">
      <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
      <input type="text" id="searchInput" placeholder="Buscar medicamento..." autocomplete="off">
      <div class="ai-dropdown hidden" id="searchDropdown"></div>
    </div>
    <p style="font-size:11px;color:#6d28d9;margin-top:8px">
      <i class="fa-solid fa-circle-info"></i>
      Prueba la IA: intenta buscar "paracetamol", "acetaminofen", "ibuprofno" (con error), "tylenol", "advil", o "jarabe"
    </p>
  </div>

  <!-- FILTER BAR -->
  <div class="filter-bar">
    <label><i class="fa-solid fa-filter"></i> Filtrar por Laboratorio</label>
    <form method="GET" action="<?= BASE_URL ?>/medicamentos" style="display:flex;gap:8px;align-items:center">
      <select name="laboratorio" class="form-control" style="width:auto" onchange="this.form.submit()">
        <option value="">Todos los laboratorios</option>
        <?php foreach ($laboratorios as $lab): ?>
        <option value="<?= htmlspecialchars($lab) ?>" <?= $selectedLab === $lab ? 'selected' : '' ?>>
          <?= htmlspecialchars($lab) ?>
        </option>
        <?php endforeach; ?>
      </select>
      <?php if ($selectedLab): ?>
      <a href="<?= BASE_URL ?>/medicamentos" class="btn btn-ghost btn-sm">✕ Limpiar</a>
      <?php endif; ?>
    </form>
  </div>

  <!-- TABLE -->
  <div class="card">
    <div class="card-header">
      <h3>Mostrando <?= count($medicamentos) ?> medicamento<?= count($medicamentos) !== 1 ? 's' : '' ?></h3>
    </div>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Medicamento</th>
            <th>Laboratorio</th>
            <th>Lote</th>
            <th>Stock</th>
            <th>Costo Unit.</th>
            <th>Vencimiento</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($medicamentos as $m): ?>
          <tr>
            <td>
              <div class="med-name"><?= htmlspecialchars($m['nombre']) ?></div>
              <div class="med-cat"><?= htmlspecialchars($m['categoria']) ?></div>
            </td>
            <td><?= htmlspecialchars($m['laboratorio']) ?></td>
            <td><span style="font-family:monospace;font-size:12px"><?= htmlspecialchars($m['numero_lote']) ?></span></td>
            <td>
              <div class="stock-num <?= $m['estado_stock'] === 'critico' ? 'text-danger' : ($m['estado_stock'] === 'bajo' ? 'text-warning' : '') ?>"
                   style="color:<?= $m['estado_stock'] === 'critico' ? 'var(--danger)' : ($m['estado_stock'] === 'bajo' ? 'var(--warning-dark)' : 'inherit') ?>">
                <?= number_format($m['stock_actual']) ?>
              </div>
              <div class="stock-min">Min: <?= number_format($m['stock_minimo']) ?></div>
            </td>
            <td>$<?= number_format($m['costo_unitario'], 2) ?></td>
            <td><?= date('d/m/Y', strtotime($m['fecha_vencimiento'])) ?></td>
            <td>
              <?php if ($m['estado_stock'] === 'critico'): ?>
                <span class="badge badge-danger">Stock Crítico</span>
              <?php elseif ($m['estado_stock'] === 'bajo'): ?>
                <span class="badge badge-warning">Stock Bajo</span>
              <?php else: ?>
                <span class="badge badge-success">Normal</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="actions-cell">
                <a href="<?= BASE_URL ?>/medicamentos/show/<?= $m['id'] ?>" class="btn btn-ghost btn-icon btn-sm" title="Ver">
                  <i class="fa-solid fa-eye"></i>
                </a>
                <a href="<?= BASE_URL ?>/medicamentos/edit/<?= $m['id'] ?>" class="btn btn-ghost btn-icon btn-sm" title="Editar">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <?php if (($_SESSION['user']['rol'] ?? '') === 'admin'): ?>
                <form method="POST" action="<?= BASE_URL ?>/medicamentos/delete/<?= $m['id'] ?>"
                      onsubmit="return confirm('¿Eliminar este medicamento?')">
                  <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Eliminar">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($medicamentos)): ?>
          <tr><td colspan="8" class="text-center" style="padding:40px;color:var(--text-muted)">
            No se encontraron medicamentos
          </td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
const searchInput    = document.getElementById('searchInput');
const searchDropdown = document.getElementById('searchDropdown');
let   searchTimer;

searchInput.addEventListener('input', () => {
  clearTimeout(searchTimer);
  const q = searchInput.value.trim();
  if (q.length < 2) { searchDropdown.classList.add('hidden'); return; }
  searchTimer = setTimeout(() => fetchSearch(q), 280);
});

async function fetchSearch(q) {
  const res  = await fetch(`<?= BASE_URL ?>/medicamentos/search?q=${encodeURIComponent(q)}`);
  const data = await res.json();
  renderDropdown(data);
}

function renderDropdown(items) {
  if (!items.length) { searchDropdown.classList.add('hidden'); return; }
  searchDropdown.innerHTML = items.map(m => `
    <div class="ai-dropdown-item" onclick="window.location='<?= BASE_URL ?>/medicamentos/show/${m.id}'">
      <div>
        <div class="item-name">${m.nombre}</div>
        <div class="item-meta">${m.laboratorio} · ${m.categoria} · Stock: ${m.stock_actual}</div>
      </div>
      <span class="badge badge-${m.estado_stock === 'critico' ? 'danger' : m.estado_stock === 'bajo' ? 'warning' : 'success'}">${m.estado_stock}</span>
    </div>`).join('');
  searchDropdown.classList.remove('hidden');
}

document.addEventListener('click', e => {
  if (!searchInput.contains(e.target)) searchDropdown.classList.add('hidden');
});
</script>
