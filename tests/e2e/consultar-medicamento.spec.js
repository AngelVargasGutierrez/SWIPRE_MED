// CP-001 — Consultar Medicamento (rol: farmacia)
const { test, expect } = require('@playwright/test');

// BASE_URL viene de playwright.config.js → process.env.BASE_URL
const BASE = '';   // rutas relativas al baseURL del config

async function loginFarmacia(page) {
  await page.goto('/login');
  await page.fill('input[name="username"]', 'farmacia');
  await page.fill('input[name="password"]', 'farmacia123');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

// CP-001-A01: Login exitoso con rol farmacia
test('CP-001-A01: Login exitoso con rol farmacia', async ({ page }) => {
  await page.goto('/login');
  await page.fill('input[name="username"]', 'farmacia');
  await page.fill('input[name="password"]', 'farmacia123');
  await page.click('button[type="submit"]');

  await page.waitForURL('**/dashboard');
  await expect(page.locator('.sidebar-nav')).toBeVisible();
});

// CP-001-A02: Sidebar farmacia solo muestra Dashboard y Medicamentos
test('CP-001-A02: Sidebar solo muestra Dashboard y Medicamentos para farmacia', async ({ page }) => {
  await loginFarmacia(page);

  const hrefs = await page.locator('.sidebar-nav a').evaluateAll(
    links => links.map(l => l.getAttribute('href'))
  );

  expect(hrefs.some(h => h.includes('/dashboard'))).toBeTruthy();
  expect(hrefs.some(h => h.includes('/medicamentos'))).toBeTruthy();
  expect(hrefs.some(h => h.includes('/inventario'))).toBeFalsy();
  expect(hrefs.some(h => h.includes('/analytics'))).toBeFalsy();
  expect(hrefs.some(h => h.includes('/usuarios'))).toBeFalsy();
  expect(hrefs.some(h => h.includes('/reportes'))).toBeFalsy();
});

// CP-001-A03: Búsqueda con 3+ caracteres muestra dropdown con resultados
test('CP-001-A03: Búsqueda exitosa muestra dropdown con resultados', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto('/medicamentos');

  await page.fill('#searchInput', 'Paracetamol');
  // Esperar debounce (280ms) + render
  await page.waitForTimeout(800);

  const dropdown = page.locator('#searchDropdown');
  await expect(dropdown).not.toHaveClass(/hidden/);
  await expect(dropdown.locator('.ai-dropdown-item').first()).toBeVisible();
});

// CP-001-A04: Búsqueda sin resultados oculta el dropdown sin error
test('CP-001-A04: Búsqueda sin resultados oculta dropdown y no lanza error', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto('/medicamentos');

  await page.fill('#searchInput', 'xyzabc999');
  await page.waitForTimeout(800);

  // Dropdown oculto o sin items
  const dropdown = page.locator('#searchDropdown');
  const isHidden = await dropdown.evaluate(el => el.classList.contains('hidden'));
  expect(isHidden).toBe(true);

  // Sin error de servidor
  await expect(page.locator('body')).not.toContainText('Fatal error');
  await expect(page.locator('body')).not.toContainText('500');
});

// CP-001-A05: Búsqueda con 1 carácter no activa dropdown (JS bloquea < 2)
test('CP-001-A05: Búsqueda con 1 carácter no activa el dropdown', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto('/medicamentos');

  await page.fill('#searchInput', 'p');
  await page.waitForTimeout(600);

  const dropdown = page.locator('#searchDropdown');
  await expect(dropdown).toHaveClass(/hidden/);
});

// CP-001-A06: Farmacia no puede acceder a /inventario (redirige a /dashboard)
test('CP-001-A06: Acceso denegado a /inventario redirige a dashboard', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto('/inventario');

  await page.waitForURL('**/dashboard');
  await expect(page).not.toHaveURL(/inventario/);
});

// CP-001-A07: Farmacia no puede acceder a /analytics (redirige a /dashboard)
test('CP-001-A07: Acceso denegado a /analytics redirige a dashboard', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto('/analytics');

  await page.waitForURL('**/dashboard');
  await expect(page).not.toHaveURL(/analytics/);
});

// CP-001-A08: XSS en campo de búsqueda no ejecuta script
test('CP-001-A08: Campo de búsqueda no ejecuta XSS', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto('/medicamentos');

  let alertFired = false;
  page.on('dialog', async dialog => {
    alertFired = true;
    await dialog.dismiss();
  });

  await page.fill('#searchInput', '<script>alert(1)</script>');
  await page.waitForTimeout(600);

  expect(alertFired).toBe(false);
});
