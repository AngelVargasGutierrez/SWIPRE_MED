<?php $pageTitle = 'Nuevo Medicamento'; ?>

<div class="page-header">
  <div class="page-header-row">
    <div>
      <a href="<?= BASE_URL ?>/medicamentos" style="font-size:13px;color:var(--text-muted);display:flex;align-items:center;gap:4px;margin-bottom:6px">
        <i class="fa-solid fa-arrow-left"></i> Volver a Medicamentos
      </a>
      <h1>Registro Rápido de Medicamento</h1>
      <p>Busca el medicamento con IA y completa los datos faltantes</p>
    </div>
  </div>
</div>

<div class="page-body">
  <!-- AI SEARCH FOR QUICK FILL -->
  <div class="ai-search-box">
    <div class="ai-search-title"><i class="fa-solid fa-wand-magic-sparkles"></i> Búsqueda Inteligente con IA</div>
    <p class="ai-search-desc">La IA autocompletará nombre, laboratorio y categoría. Tú completas costo, stock mínimo, lote y vencimiento.</p>

    <div class="form-group">
      <label class="form-label">Paso 1: Selecciona el Laboratorio <span class="req">*</span></label>
      <select id="aiLaboratorio" class="form-control">
        <option value="">-- Seleccionar laboratorio --</option>
        <?php foreach ($laboratorios as $lab): ?>
        <option value="<?= htmlspecialchars($lab) ?>"><?= htmlspecialchars($lab) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label">Paso 2: Busca el Medicamento</label>
      <div class="ai-search-input" style="position:relative">
        <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="text" id="aiSearchInput" placeholder="Primero selecciona un laboratorio para habilitar la búsqueda"
               disabled autocomplete="off">
        <div class="ai-dropdown hidden" id="aiDropdown"></div>
      </div>
      <p style="font-size:11px;color:#6d28d9;margin-top:6px">
        <i class="fa-solid fa-lightbulb"></i>
        Tip: Selecciona el laboratorio y luego busca el medicamento. La IA te mostrará solo medicamentos de ese laboratorio.
      </p>
    </div>
  </div>

  <!-- FORM -->
  <form action="<?= BASE_URL ?>/medicamentos/store" method="POST">
    <div class="form-section">
      <div class="form-section-title"><i class="fa-solid fa-circle-info"></i> Información Básica</div>
      <div class="form-group">
        <label class="form-label">Nombre del Medicamento <span class="req">*</span></label>
        <input type="text" name="nombre" id="fNombre" class="form-control" placeholder="Ej: Paracetamol 500mg" required>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Laboratorio <span class="req">*</span></label>
          <input type="text" name="laboratorio" id="fLaboratorio" class="form-control" placeholder="Ej: Laboratorios ABC" required>
        </div>
        <div class="form-group">
          <label class="form-label">Categoría <span class="req">*</span></label>
          <select name="categoria" id="fCategoria" class="form-control" required>
            <option value="">Seleccionar categoría</option>
            <option>Analgésicos</option><option>Antiinflamatorios</option>
            <option>Antibióticos</option><option>Antiácidos</option>
            <option>Antihipertensivos</option><option>Antidiabéticos</option>
            <option>Hipolipemiantes</option><option>Broncodilatadores</option>
            <option>Antihistamínicos</option><option>Antiinflamatorios tópicos</option>
            <option>Ansiolíticos</option><option>Antidepresivos</option>
            <option>Vitaminas</option><option>Otros</option>
          </select>
        </div>
      </div>
    </div>

    <div class="form-section" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#86efac">
      <div class="form-section-title" style="color:#059669">
        <i class="fa-solid fa-note-sticky"></i> Completa estos datos manualmente (del lote físico y boleta)
        <span style="font-size:11px;font-weight:400;color:#047857">Información que el almacenero debe ingresar con los datos de la boleta y el lote físico</span>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Costo Unitario ($) <span class="req">*</span> <span style="font-size:10px;color:var(--text-muted)">(según boleta)</span></label>
          <input type="number" name="costo_unitario" class="form-control" value="0" min="0" step="0.01" required>
        </div>
        <div class="form-group">
          <label class="form-label">Stock Mínimo <span class="req">*</span> <span style="font-size:10px;color:var(--text-muted)">(política interna)</span></label>
          <input type="number" name="stock_minimo" class="form-control" value="0" min="0" required>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Stock Inicial de este Lote <span class="req">*</span></label>
        <input type="number" name="stock_actual" class="form-control" value="0" min="0" required>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Número de Lote <span class="req">*</span></label>
          <input type="text" name="numero_lote" class="form-control" placeholder="Ej: PAR-2024-001" required>
        </div>
        <div class="form-group">
          <label class="form-label">Fecha de Vencimiento <span class="req">*</span></label>
          <input type="date" name="fecha_vencimiento" class="form-control" required>
        </div>
      </div>
    </div>

    <div style="display:flex;gap:12px;margin-top:8px">
      <button type="submit" class="btn btn-primary btn-lg">
        <i class="fa-solid fa-floppy-disk"></i> Registrar Medicamento
      </button>
      <a href="<?= BASE_URL ?>/medicamentos" class="btn btn-outline btn-lg">Cancelar</a>
    </div>
  </form>
</div>

<script>
const aiLab   = document.getElementById('aiLaboratorio');
const aiInput = document.getElementById('aiSearchInput');
const aiDrop  = document.getElementById('aiDropdown');
let   timer;

aiLab.addEventListener('change', () => {
  if (aiLab.value) {
    aiInput.disabled = false;
    aiInput.placeholder = `Buscar en ${aiLab.value}... (ej: paracetamol, ibuprofeno)`;
  } else {
    aiInput.disabled = true;
    aiInput.placeholder = 'Primero selecciona un laboratorio para habilitar la búsqueda';
  }
});

aiInput.addEventListener('input', () => {
  clearTimeout(timer);
  const q = aiInput.value.trim();
  if (q.length < 2) { aiDrop.classList.add('hidden'); return; }
  timer = setTimeout(() => fetchAI(q), 280);
});

async function fetchAI(q) {
  const lab = aiLab.value ? `&lab=${encodeURIComponent(aiLab.value)}` : '';
  const res  = await fetch(`<?= BASE_URL ?>/medicamentos/search?q=${encodeURIComponent(q)}${lab}`);
  const data = await res.json();
  renderAIDrop(data);
}

function renderAIDrop(items) {
  if (!items.length) { aiDrop.classList.add('hidden'); return; }
  aiDrop.innerHTML = items.map(m => `
    <div class="ai-dropdown-item" onclick="fillForm(${JSON.stringify(m).replace(/"/g, '&quot;')})">
      <div>
        <div class="item-name">${m.nombre}</div>
        <div class="item-meta">${m.laboratorio} · ${m.categoria}</div>
      </div>
    </div>`).join('');
  aiDrop.classList.remove('hidden');
}

function fillForm(m) {
  document.getElementById('fNombre').value     = m.nombre;
  document.getElementById('fLaboratorio').value = m.laboratorio;
  const sel = document.getElementById('fCategoria');
  for (let o of sel.options) { if (o.value === m.categoria) { sel.value = m.categoria; break; } }
  aiDrop.classList.add('hidden');
  aiInput.value = m.nombre;
}

document.addEventListener('click', e => {
  if (!aiInput.contains(e.target)) aiDrop.classList.add('hidden');
});
</script>
