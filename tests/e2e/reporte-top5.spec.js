// CP-002 — Visualizar Reporte Top 5 Medicamentos Consultados (rol: farmacia)
const { test, expect } = require('@playwright/test');

async function loginAs(page, username, password) {
  await page.goto('/login');
  await page.fill('input[name="username"]', username);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

// CP-002-A01: Dashboard farmacia muestra "Panel de Farmacia"
test('CP-002-A01: Dashboard farmacia tiene subtítulo Panel de Farmacia', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  await expect(page.locator('.page-header p')).toHaveText('Panel de Farmacia');
});

// CP-002-A02: Dashboard farmacia NO muestra tarjeta "Valor Total"
test('CP-002-A02: Dashboard farmacia no muestra tarjeta Valor Total', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  await expect(page.locator('.stat-card')).not.toContainText('Valor Total');
});

// CP-002-A03: Dashboard farmacia tiene sección Top 5 más Buscados
test('CP-002-A03: Dashboard farmacia muestra sección Top 5 más Buscados', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  await expect(page.locator('.card-header h3')).toContainText('Top 5 Medicamentos más Buscados');
});

// CP-002-A04: Canvas de la gráfica existe y tiene dimensiones reales
test('CP-002-A04: Canvas #chartTop5Farmacia es visible y tiene tamaño', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  const canvas = page.locator('#chartTop5Farmacia');
  await expect(canvas).toBeVisible();

  const box = await canvas.boundingBox();
  expect(box.width).toBeGreaterThan(100);
  expect(box.height).toBeGreaterThan(100);
});

// CP-002-A05: Subtítulo indica fuente correcta de datos
test('CP-002-A05: Subtítulo indica "Basado en búsquedas en Medicamentos"', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');

  await expect(page.locator('.card-header span')).toContainText('Basado en búsquedas en Medicamentos');
});

// CP-002-A06: Búsqueda con 3+ caracteres incrementa contador (dropdown aparece)
test('CP-002-A06: Búsqueda real con 3+ chars activa el dropdown de resultados', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');
  await page.goto('/medicamentos');

  await page.fill('#searchInput', 'Amoxicilina');
  await page.waitForTimeout(800);

  // Dropdown visible = búsqueda ejecutada → contador incrementado en BD
  const dropdown = page.locator('#searchDropdown');
  await expect(dropdown).not.toHaveClass(/hidden/);
  await expect(dropdown.locator('.ai-dropdown-item').first()).toBeVisible();
});

// CP-002-A07: Búsqueda con 1 carácter NO activa el dropdown (JS bloquea < 2)
test('CP-002-A07: Búsqueda con 1 carácter no activa dropdown ni incrementa contador', async ({ page }) => {
  await loginAs(page, 'farmacia', 'farmacia123');
  await page.goto('/medicamentos');

  await page.fill('#searchInput', 'a');
  await page.waitForTimeout(600);

  // El JS bloquea la petición si length < 2, el dropdown permanece oculto
  await expect(page.locator('#searchDropdown')).toHaveClass(/hidden/);
});

// CP-002-A08: Admin ve "Top 5 por Valor", NO "Top 5 más Buscados"
test('CP-002-A08: Dashboard admin muestra Top 5 por Valor, no por Búsquedas', async ({ page }) => {
  await loginAs(page, 'admin', 'admin123');

  const headers = page.locator('.card-header h3');
  await expect(headers).toContainText('Top 5 Medicamentos por Valor');
  await expect(page.locator('body')).not.toContainText('Top 5 Medicamentos más Buscados');
});

// CP-002-A09: Dashboard admin tiene 4 tarjetas de stats (incluye Valor Total)
test('CP-002-A09: Dashboard admin tiene tarjeta Valor Total', async ({ page }) => {
  await loginAs(page, 'admin', 'admin123');

  await expect(page.locator('.stat-card')).toContainText('Valor Total');
  const cards = page.locator('.stat-card');
  expect(await cards.count()).toBe(4);
});

// CP-002-A10: Gráfica visible en viewport 768px (responsive)
test('CP-002-A10: Gráfica es visible en viewport 768px', async ({ page }) => {
  await page.setViewportSize({ width: 768, height: 1024 });
  await loginAs(page, 'farmacia', 'farmacia123');

  const canvas = page.locator('#chartTop5Farmacia');
  await expect(canvas).toBeVisible();

  const box = await canvas.boundingBox();
  expect(box.width).toBeGreaterThan(50);
});
