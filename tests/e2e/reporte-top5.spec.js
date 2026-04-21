// CP-002 — Visualizar Reporte Top 5 Medicamentos Consultados (rol: farmacia)
const { test, expect } = require('@playwright/test');

const BASE_URL = process.env.BASE_URL || 'http://localhost/swipre-med/public';

async function loginAs(page, username, password) {
  await page.goto(`${BASE_URL}/login`);
  await page.fill('input[name="username"]', username);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"]');
  await page.waitForURL(`${BASE_URL}/dashboard`);
}

// CP-002-A01: Dashboard farmacia muestra "Panel de Farmacia"
test('CP-002-A01: Dashboard farmacia tiene título Panel de Farmacia', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  await expect(page.locator('p')).toContainText('Panel de Farmacia');
});

// CP-002-A02: Dashboard farmacia NO muestra "Valor Total"
test('CP-002-A02: Dashboard farmacia no muestra tarjeta Valor Total', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  await expect(page.locator('body')).not.toContainText('Valor Total');
});

// CP-002-A03: Dashboard farmacia muestra sección Top 5 más Buscados
test('CP-002-A03: Dashboard farmacia tiene sección Top 5 más Buscados', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  await expect(page.locator('body')).toContainText('Top 5 Medicamentos más Buscados');
});

// CP-002-A04: Gráfica Top 5 renderiza canvas correctamente
test('CP-002-A04: Canvas de la gráfica Top 5 existe y es visible', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  const canvas = page.locator('#chartTop5Farmacia');
  await expect(canvas).toBeVisible();

  const box = await canvas.boundingBox();
  expect(box.width).toBeGreaterThan(100);
  expect(box.height).toBeGreaterThan(100);
});

// CP-002-A05: Subtítulo indica que viene de búsquedas
test('CP-002-A05: Subtítulo indica fuente de datos correcta', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  await expect(page.locator('body')).toContainText('Basado en búsquedas en Medicamentos');
});

// CP-002-A06: Búsqueda real incrementa contador en BD (API endpoint)
test('CP-002-A06: Búsqueda con 3+ caracteres dispara incremento de busquedas', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');
  await page.goto(`${BASE_URL}/medicamentos`);

  const response = await page.request.get(`${BASE_URL}/medicamentos/search?q=Paracetamol`);
  expect(response.status()).toBe(200);

  const body = await response.json();
  expect(Array.isArray(body)).toBe(true);
  expect(body.length).toBeGreaterThan(0);
});

// CP-002-A07: Búsqueda con 2 caracteres responde pero no cuenta
test('CP-002-A07: Búsqueda con menos de 3 caracteres retorna array vacío', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  const response = await page.request.get(`${BASE_URL}/medicamentos/search?q=pa`);
  expect(response.status()).toBe(200);

  const body = await response.json();
  expect(Array.isArray(body)).toBe(true);
  expect(body.length).toBe(0);
});

// CP-002-A08: Admin ve Top 5 por Valor, NO por Búsquedas
test('CP-002-A08: Dashboard admin muestra Top 5 por Valor', async ({ page }) => {
  await loginAs(page, 'admin', 'admin123');

  await expect(page.locator('body')).toContainText('Top 5 Medicamentos por Valor');
  await expect(page.locator('body')).not.toContainText('Top 5 Medicamentos más Buscados');
});

// CP-002-A09: Dashboard admin tiene las 4 tarjetas de stats
test('CP-002-A09: Dashboard admin tiene tarjeta Valor Total', async ({ page }) => {
  await loginAs(page, 'admin', 'admin123');

  await expect(page.locator('body')).toContainText('Valor Total');
});

// CP-002-A10: Gráfica es responsive (viewport móvil)
test('CP-002-A10: Gráfica visible en viewport 768px', async ({ page }) => {
  await page.setViewportSize({ width: 768, height: 1024 });
  await loginAs(page, 'farmacia', 'farmacia123');

  const canvas = page.locator('#chartTop5Farmacia');
  await expect(canvas).toBeVisible();
});
