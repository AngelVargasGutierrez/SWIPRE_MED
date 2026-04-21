# PU-002 — Pruebas Unitarias: Visualizar Reporte Top 5 Medicamentos

| Campo | Detalle |
|---|---|
| **ID** | PU-002 |
| **Tipo** | Prueba Unitaria |
| **Clase bajo prueba** | `MedicamentoModel` |
| **Métodos probados** | `getTop5MasBuscados()`, `getEstadoStock()`, `count()` |
| **Requerimiento** | Visualizar Reporte de Medicamento (Top 5 consultados) |
| **Fecha** | 2026-04-20 |

---

## Cómo ejecutar las pruebas manualmente

### Requisitos previos

Mismos que PU-001 (XAMPP, MariaDB, PHP, Composer instalado).

### Paso 1 — Ejecutar todas las pruebas de PU-002

```bash
php vendor/bin/phpunit PU/Unit/ReporteTop5Test.php --testdox
```

Salida esperada:

```
ReporteTop5 (PU-002)
 ✔ Get top5 mas buscados retorna maximo cinco
 ✔ Get top5 mas buscados esta ordenado descendente
 ✔ Get top5 mas buscados tiene estructura correcta
 ✔ Get estado stock retorna estructura correcta
 ✔ Suma estados igual total medicamentos
```

### Paso 2 — Ejecutar suite completa (PU-001 + PU-002)

```bash
php vendor/bin/phpunit --testdox
```

---

## PU-002-U01 — getTop5MasBuscados() retorna exactamente 5 registros

**Método:** `MedicamentoModel::getTop5MasBuscados()`
**Precondición:** BD con al menos 5 medicamentos con `busquedas > 0`

### Ejecución manual paso a paso

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | En HeidiSQL ejecutar: `SELECT COUNT(*) FROM medicamentos WHERE busquedas > 0;` | Valor ≥ 5 |
| 2 | Ejecutar: `php vendor/bin/phpunit PU/Unit/ReporteTop5Test.php --filter testGetTop5MasBuscadosRetornaMaximoCinco` | Test corre |
| 3 | Verificar que retorna `OK (1 test, 2 assertions)` | Sin errores |
| 4 | Confirmar manualmente: `SELECT nombre, busquedas FROM medicamentos ORDER BY busquedas DESC LIMIT 5;` | Exactamente 5 filas |

**Datos de entrada:** `getTop5MasBuscados()` (sin parámetros)
**Resultado esperado:** Array de exactamente 5 elementos
**Resultado obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## PU-002-U02 — getTop5MasBuscados() está ordenado de mayor a menor

**Método:** `MedicamentoModel::getTop5MasBuscados()`

### Ejecución manual paso a paso

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Ejecutar: `php vendor/bin/phpunit PU/Unit/ReporteTop5Test.php --filter testGetTop5MasBuscadosEstaOrdenadoDescendente` | Test corre |
| 2 | Verificar `OK (1 test, 4 assertions)` (una por cada par de elementos) | Sin errores |
| 3 | Confirmar en BD: `SELECT busquedas FROM medicamentos ORDER BY busquedas DESC LIMIT 5;` | Cada fila tiene valor ≥ a la siguiente |

**Datos de entrada:** `getTop5MasBuscados()`
**Resultado esperado:** `$result[0]['busquedas'] >= $result[1]['busquedas'] >= ... >= $result[4]['busquedas']`
**Resultado obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## PU-002-U03 — getTop5MasBuscados() tiene la estructura de datos correcta

**Método:** `MedicamentoModel::getTop5MasBuscados()`

### Ejecución manual paso a paso

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Ejecutar: `php vendor/bin/phpunit PU/Unit/ReporteTop5Test.php --filter testGetTop5MasBuscadosTieneEstructuraCorrecta` | Test corre |
| 2 | Verificar `OK (1 test, 2 assertions)` | Sin errores |
| 3 | Confirmar que cada elemento tiene clave `nombre` (string) y `busquedas` (int) | Estructura correcta para la gráfica |

**Datos de entrada:** `getTop5MasBuscados()`
**Resultado esperado:** Cada elemento tiene claves `nombre` y `busquedas`
**Resultado obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## PU-002-U04 — getEstadoStock() retorna las tres categorías

**Método:** `MedicamentoModel::getEstadoStock()`

### Ejecución manual paso a paso

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Ejecutar: `php vendor/bin/phpunit PU/Unit/ReporteTop5Test.php --filter testGetEstadoStockRetornaEstructuraCorrecta` | Test corre |
| 2 | Verificar `OK (1 test, 3 assertions)` | Sin errores |
| 3 | Confirmar en BD: `SELECT SUM(stock_actual > stock_minimo) AS normal, SUM(stock_actual > stock_minimo*0.3 AND stock_actual <= stock_minimo) AS bajo, SUM(stock_actual <= stock_minimo*0.3) AS critico FROM medicamentos;` | Los tres valores son ≥ 0 |

**Datos de entrada:** `getEstadoStock()` (sin parámetros)
**Resultado esperado:** Array con claves `normal`, `bajo`, `critico` con valores enteros
**Resultado obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## PU-002-U05 — Suma de estados = total de medicamentos

**Métodos:** `getEstadoStock()` + `count()`

### Ejecución manual paso a paso

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | En HeidiSQL: `SELECT COUNT(*) FROM medicamentos;` | Anotar total (ej: 30) |
| 2 | Ejecutar: `php vendor/bin/phpunit PU/Unit/ReporteTop5Test.php --filter testSumaEstadosIgualTotalMedicamentos` | Test corre |
| 3 | Verificar `OK (1 test, 1 assertion)` | Sin errores |
| 4 | Confirmar: `normal + bajo + critico = total medicamentos` | Suma correcta, ningún medicamento sin clasificar |

**Datos de entrada:** `getEstadoStock()`, `count()`
**Resultado esperado:** `(int)normal + (int)bajo + (int)critico === count()`
**Resultado obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## Referencia de automatización

Ver: [`PU/Unit/ReporteTop5Test.php`](Unit/ReporteTop5Test.php)
Workflow: [`.github/workflows/pruebas-automatizadas.yml`](../.github/workflows/pruebas-automatizadas.yml) — Job `unit-tests`
