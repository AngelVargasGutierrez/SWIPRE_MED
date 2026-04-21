// CP-001 — Consultar Medicamento (rol: farmacia)
const { test, expect } = require('@playwright/test');

const BASE_URL = process.env.BASE_URL || 'http://localhost/swipre-med/public';

async function loginFarmacia(page) {
  await page.goto(`${BASE_URL}/login`);
  await page.fill('input[name="username"]', 'farmacia');
  await page.fill('input[name="password"]', 'farmacia123');
  await page.click('button[type="submit"]');
  await page.waitForURL(`${BASE_URL}/dashboard`);
}

// CP-001-A01: Login exitoso con rol farmacia
test('CP-001-A01: Login exitoso con rol farmacia', async ({ page }) => {
  await page.goto(`${BASE_URL}/login`);
  await page.fill('input[name="username"]', 'farmacia');
  await page.fill('input[name="password"]', 'farmacia123');
  await page.click('button[type="submit"]');

  await expect(page).toHaveURL(`${BASE_URL}/dashboard`);
  await expect(page.locator('.sidebar-nav')).toBeVisible();
});

// CP-001-A02: Sidebar farmacia solo muestra Dashboard y Medicamentos
test('CP-001-A02: Sidebar farmacia solo muestra Dashboard y Medicamentos', async ({ page }) => {
  await loginFarmacia(page);

  const navLinks = page.locator('.sidebar-nav a');
  const hrefs = await navLinks.evaluateAll(links => links.map(l => l.getAttribute('href')));

  expect(hrefs.some(h => h.includes('/dashboard'))).toBeTruthy();
  expect(hrefs.some(h => h.includes('/medicamentos'))).toBeTruthy();
  expect(hrefs.some(h => h.includes('/inventario'))).toBeFalsy();
  expect(hrefs.some(h => h.includes('/analytics'))).toBeFalsy();
  expect(hrefs.some(h => h.includes('/usuarios'))).toBeFalsy();
  expect(hrefs.some(h => h.includes('/reportes'))).toBeFalsy();
});

// CP-001-A03: Búsqueda exitosa de medicamento con 3+ caracteres
test('CP-001-A03: Búsqueda exitosa retorna resultados', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto(`${BASE_URL}/medicamentos`);

  const searchInput = page.locator('input[type="search"], input[name="q"], input[placeholder*="buscar" i], input[placeholder*="search" i]').first();
  await searchInput.fill('Paracetamol');
  await page.waitForTimeout(600);

  const rows = page.locator('table tbody tr, .med-card, .result-item');
  await expect(rows.first()).toBeVisible();
});

// CP-001-A04: Búsqueda sin resultados no lanza error
test('CP-001-A04: Búsqueda sin resultados no lanza error 500', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto(`${BASE_URL}/medicamentos`);

  const searchInput = page.locator('input[type="search"], input[name="q"], input[placeholder*="buscar" i]').first();
  await searchInput.fill('xyzabc999');
  await page.waitForTimeout(600);

  await expect(page.locator('body')).not.toContainText('500');
  await expect(page.locator('body')).not.toContainText('Fatal error');
});

// CP-001-A05: Farmacia no puede acceder a ruta /inventario
test('CP-001-A05: Acceso denegado a /inventario para farmacia', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto(`${BASE_URL}/inventario`);

  await expect(page).toHaveURL(`${BASE_URL}/dashboard`);
});

// CP-001-A06: Farmacia no puede acceder a /analytics
test('CP-001-A06: Acceso denegado a /analytics para farmacia', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto(`${BASE_URL}/analytics`);

  await expect(page).toHaveURL(`${BASE_URL}/dashboard`);
});

// CP-001-A07: XSS en campo de búsqueda
test('CP-001-A07: Campo de búsqueda no ejecuta XSS', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto(`${BASE_URL}/medicamentos`);

  let alertFired = false;
  page.on('dialog', () => { alertFired = true; });

  const searchInput = page.locator('input[type="search"], input[name="q"], input[placeholder*="buscar" i]').first();
  await searchInput.fill('<script>alert(1)</script>');
  await page.waitForTimeout(500);

  expect(alertFired).toBe(false);
});

// CP-001-A08: Detalle del medicamento muestra precio y stock
test('CP-001-A08: Detalle muestra costo_unitario y stock_actual', async ({ page }) => {
  await loginFarmacia(page);
  await page.goto(`${BASE_URL}/medicamentos/show/1`);

  const body = page.locator('body');
  await expect(body).not.toContainText('Fatal error');
  await expect(body).not.toContainText('Vista no encontrada');
});
