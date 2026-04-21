# CP-002 — Visualizar Reporte de Medicamento (Top 5 Más Consultados)

| Campo | Detalle |
|---|---|
| **ID** | CP-002 |
| **Módulo** | Dashboard / Analytics |
| **Rol** | Farmacia |
| **Tipo** | Funcional / Estadístico |
| **Prioridad** | Media |
| **Fecha** | 2026-04-20 |

## Descripción

El sistema genera una vista estadística con el **Top 5 de medicamentos más consultados** (búsquedas realizadas en `/medicamentos`), mostrando tendencias de demanda mediante una gráfica de barras horizontales en el Dashboard de Farmacia, actualizada automáticamente con cada búsqueda real.

## Precondiciones

- XAMPP corriendo (Apache + MariaDB en puerto 3307)
- Base de datos `swipre_med` con datos seed cargados (`seed_data.sql`)
- Columna `busquedas` presente en la tabla `medicamentos`
- Usuario `farmacia / farmacia123` activo
- Al menos una búsqueda realizada previamente (3+ caracteres)

## Ambiente de Prueba

| Ítem | Valor |
|---|---|
| URL base | `http://localhost/swipre-med/public` |
| Navegador | Chrome 124+ / Firefox 125+ |
| Librería de gráficas | Chart.js 4.4.0 (CDN) |
| Servidor | XAMPP — PHP 8.x, MariaDB 10.4 (puerto 3307) |

## Datos de Prueba

| ID | Dato | Valor |
|---|---|---|
| D1 | Usuario | `farmacia / farmacia123` |
| D2 | Medicamento con más búsquedas | `Paracetamol 500mg` (busquedas = 310, seed) |
| D3 | Búsquedas a ejecutar antes del test | `Paracetamol`, `Ibuprofeno`, `Omeprazol` |
| D4 | Top 5 esperado (seed) | Paracetamol, Ibuprofeno, Omeprazol, Amoxicilina, Metformina |

---

## Ejecución Manual

### CP-002-M01 — Visualizar gráfica Top 5 en Dashboard de Farmacia

**Objetivo:** Verificar que el Dashboard muestra la gráfica de Top 5 más buscados correctamente.

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Abrir `http://localhost/swipre-med/public/login` | Formulario de login visible |
| 2 | Ingresar `farmacia / farmacia123` | Redirección al Dashboard de Farmacia |
| 3 | Verificar título de la página | Muestra "Panel de Farmacia" |
| 4 | Verificar tarjetas de estadísticas | Solo aparecen **Total Medicamentos** y **Stock Crítico** (no Valor Total) |
| 5 | Desplazarse hacia la gráfica | Aparece la sección **"Top 5 Medicamentos más Buscados"** |
| 6 | Verificar que la gráfica renderiza | Se muestra un gráfico de barras horizontales con 5 barras |
| 7 | Verificar los nombres en la gráfica | Aparecen los 5 medicamentos con más búsquedas registradas |
| 8 | Verificar el subtítulo de la sección | Dice _"Basado en búsquedas en Medicamentos"_ |
| 9 | Verificar que NO aparece "Top 5 por Valor" | Esa sección no existe en el dashboard de farmacia |

**Resultado Obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido
**Observaciones:** ___

---

### CP-002-M02 — Verificar actualización del Top 5 tras una búsqueda nueva

**Objetivo:** Confirmar que una búsqueda real en Medicamentos incrementa el ranking.

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Iniciar sesión como `farmacia` | Dashboard visible |
| 2 | Anotar el Top 5 actual visible en la gráfica | Registro de medicamentos y posiciones actuales |
| 3 | Ir a **Medicamentos** | Lista completa cargada |
| 4 | Buscar `Metronidazol` (3+ caracteres) | Aparece en la lista de resultados |
| 5 | Repetir la búsqueda `Metronidazol` 10 veces | Cada búsqueda incrementa su contador en BD |
| 6 | Regresar al **Dashboard** | Página recargada |
| 7 | Verificar que `Metronidazol` escaló posiciones | El medicamento aparece más arriba o ingresó al Top 5 |

**Resultado Obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

### CP-002-M03 — Búsqueda con menos de 3 caracteres no altera el Top 5

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Iniciar sesión como `farmacia` | Dashboard visible |
| 2 | Ir a **Medicamentos** | Lista cargada |
| 3 | Anotar el valor actual de `busquedas` en BD para cualquier medicamento | Valor inicial registrado |
| 4 | Escribir `am` (2 caracteres) en el buscador | No hay incremento en la BD |
| 5 | Regresar al Dashboard y verificar gráfica | El ranking no cambia respecto al anotado en paso 3 |

**Resultado Obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

### CP-002-M04 — Admin/Jefatura NO ve el Top 5 Buscados en Dashboard

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Cerrar sesión de farmacia | Pantalla de login |
| 2 | Iniciar sesión como `admin / admin123` | Dashboard Admin |
| 3 | Verificar la sección de Top 5 en el Dashboard | Muestra **"Top 5 por Valor"**, NO "por Búsquedas" |
| 4 | Verificar que aparecen 4 tarjetas de stats | Total, Stock Crítico, Por Vencer y **Valor Total** |
| 5 | Verificar el sidebar del admin | Muestra todas las secciones (Inventario, Analytics, Reportes, Usuarios) |

**Resultado Obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

### CP-002-M05 — Consistencia de datos entre Dashboard y BD

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | En HeidiSQL ejecutar: `SELECT nombre, busquedas FROM medicamentos ORDER BY busquedas DESC LIMIT 5;` | Lista de Top 5 con sus conteos |
| 2 | Iniciar sesión como `farmacia` | Dashboard visible |
| 3 | Comparar el Top 5 de la gráfica con el resultado de la BD | Los 5 medicamentos y su orden son idénticos |
| 4 | Verificar que los nombres en la gráfica no están truncados | Legibles completos o con tooltip al hacer hover |

**Resultado Obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## Criterios de Aceptación

- [ ] La gráfica se renderiza en menos de **3 segundos** al cargar el Dashboard
- [ ] Se muestran exactamente **5 medicamentos** en la gráfica
- [ ] El orden de la gráfica coincide exactamente con `ORDER BY busquedas DESC` en BD
- [ ] Búsquedas con ≥ 3 caracteres **incrementan** el contador `busquedas`
- [ ] Búsquedas con < 3 caracteres **no incrementan** el contador
- [ ] El admin/jefatura **no ve** la gráfica de búsquedas en su dashboard
- [ ] La gráfica es responsive (funciona en resoluciones desde 768px)

## Referencia de Automatización

Ver archivo: [`tests/e2e/reporte-top5.spec.js`](../tests/e2e/reporte-top5.spec.js)
Ver workflow: [`.github/workflows/pruebas-automatizadas.yml`](../.github/workflows/pruebas-automatizadas.yml)
