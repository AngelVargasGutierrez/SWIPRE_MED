# CP-001 — Consultar Medicamento

| Campo | Detalle |
|---|---|
| **ID** | CP-001 |
| **Módulo** | Medicamentos |
| **Rol** | Farmacia |
| **Tipo** | Funcional |
| **Prioridad** | Alta |
| **Fecha** | 2026-04-20 |

## Descripción

El personal de Caja Farmacia puede buscar medicamentos en el inventario para verificar precio y disponibilidad según el stock físico, durante la atención al paciente.

## Precondiciones

- XAMPP corriendo (Apache + MariaDB en puerto 3307)
- Base de datos `swipre_med` con al menos 5 medicamentos registrados
- Usuario `farmacia / farmacia123` activo en la BD
- Acceso a `http://localhost/swipre-med/public/`

## Ambiente de Prueba

| Ítem | Valor |
|---|---|
| URL base | `http://localhost/swipre-med/public` |
| Navegador | Chrome 124+ / Firefox 125+ |
| Resolución | 1280 × 720 mínimo |
| Servidor | XAMPP — PHP 8.x, MariaDB 10.4 (puerto 3307) |

## Datos de Prueba

| ID | Dato | Valor |
|---|---|---|
| D1 | Usuario válido | `farmacia` |
| D2 | Contraseña válida | `farmacia123` |
| D3 | Búsqueda exitosa | `Paracetamol` |
| D4 | Búsqueda parcial | `para` |
| D5 | Búsqueda sin resultados | `xyzabc999` |
| D6 | Búsqueda vacía | _(campo vacío)_ |
| D7 | Caracteres especiales | `<script>alert(1)</script>` |

---

## Ejecución Manual

### CP-001-M01 — Búsqueda exitosa de medicamento

**Objetivo:** Verificar que farmacia puede buscar y ver precio + stock de un medicamento existente.

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Abrir `http://localhost/swipre-med/public/login` | Se muestra el formulario de login |
| 2 | Ingresar usuario `farmacia` y contraseña `farmacia123` | Redirección al Dashboard de Farmacia |
| 3 | Verificar el sidebar | Solo se ven: **Dashboard** y **Medicamentos** |
| 4 | Hacer clic en **Medicamentos** en el sidebar | Se carga `/medicamentos` con la lista completa |
| 5 | En el campo de búsqueda escribir `Paracetamol` | La lista se filtra mostrando resultados con "Paracetamol" |
| 6 | Verificar columnas visibles en el resultado | Se muestran: Nombre, Categoría, Stock Actual, Costo Unitario, Estado |
| 7 | Verificar el Estado del stock | Muestra etiqueta "Normal", "Bajo" o "Crítico" según corresponda |
| 8 | Hacer clic en el botón **Ver** del medicamento | Se abre la vista detalle del medicamento |
| 9 | Verificar información completa en el detalle | Se muestran: nombre, laboratorio, lote, fecha de vencimiento, stock, precio |
| 10 | Verificar que el contador de búsquedas aumentó | En Analytics (desde admin) el medicamento sube en el top 5 |

**Resultado Obtenido:** _(completar en ejecución)_
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido
**Observaciones:** ___

---

### CP-001-M02 — Búsqueda parcial (mínimo 3 caracteres)

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Iniciar sesión como `farmacia` | Dashboard de Farmacia |
| 2 | Ir a Medicamentos | Lista completa visible |
| 3 | Escribir `par` en el buscador | Se filtran medicamentos que contienen "par" en nombre, laboratorio o categoría |
| 4 | Verificar que hay resultados | Al menos 1 resultado visible |
| 5 | Escribir `pa` (2 caracteres) | No se dispara búsqueda / no se incrementa contador |

**Resultado Obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

### CP-001-M03 — Búsqueda sin resultados

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Iniciar sesión como `farmacia` | Dashboard |
| 2 | Ir a Medicamentos | Lista cargada |
| 3 | Escribir `xyzabc999` en el buscador | Lista vacía o mensaje "Sin resultados" |
| 4 | Verificar que no lanza error 500 | La página responde correctamente |

**Resultado Obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

### CP-001-M04 — Control de acceso: farmacia no accede a rutas restringidas

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Iniciar sesión como `farmacia` | Dashboard Farmacia |
| 2 | Navegar manualmente a `/inventario` | Redirección a `/dashboard` |
| 3 | Navegar manualmente a `/usuarios` | Redirección a `/dashboard` |
| 4 | Navegar manualmente a `/analytics` | Redirección a `/dashboard` |
| 5 | Navegar manualmente a `/reportes` | Redirección a `/dashboard` |

**Resultado Obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

### CP-001-M05 — Seguridad: XSS en búsqueda

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Iniciar sesión como `farmacia` | Dashboard |
| 2 | Ir a Medicamentos | Lista cargada |
| 3 | Escribir `<script>alert(1)</script>` en el buscador | No se ejecuta ningún alert; el texto se muestra escapado o sin resultados |
| 4 | Verificar en la respuesta que el script no se ejecutó | Comportamiento normal, sin popup |

**Resultado Obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## Criterios de Aceptación

- [ ] El rol farmacia solo ve **Dashboard** y **Medicamentos** en el sidebar
- [ ] La búsqueda retorna resultados en menos de **2 segundos**
- [ ] Se muestran precio y stock en los resultados
- [ ] Las rutas restringidas redirigen correctamente
- [ ] La búsqueda con < 3 caracteres no incrementa el contador `busquedas`
- [ ] No hay vulnerabilidades XSS en el campo de búsqueda

## Referencia de Automatización

Ver archivo: [`tests/e2e/consultar-medicamento.spec.js`](../tests/e2e/consultar-medicamento.spec.js)
Ver workflow: [`.github/workflows/pruebas-automatizadas.yml`](../.github/workflows/pruebas-automatizadas.yml)
