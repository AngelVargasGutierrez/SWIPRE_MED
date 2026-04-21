# PU-001 — Pruebas Unitarias: Consultar Medicamento

| Campo | Detalle |
|---|---|
| **ID** | PU-001 |
| **Tipo** | Prueba Unitaria |
| **Clase bajo prueba** | `MedicamentoModel` |
| **Métodos probados** | `search()`, `incrementarBusquedas()`, `findById()` |
| **Requerimiento** | Consultar Medicamento (rol: farmacia) |
| **Fecha** | 2026-04-20 |

---

## Cómo ejecutar las pruebas manualmente

### Requisitos previos

1. XAMPP corriendo con MariaDB en puerto 3307
2. Base de datos `swipre_med` con datos seed cargados
3. PHP disponible en terminal (`php --version`)
4. Composer instalado (`composer --version`)

### Paso 1 — Instalar PHPUnit

Abre una terminal en la raíz del proyecto (`c:\xampp\htdocs\swipre-med\`) y ejecuta:

```bash
composer install
```

Esto instala PHPUnit según el `composer.json` del proyecto.

### Paso 2 — Ejecutar todas las pruebas unitarias de PU-001

```bash
php vendor/bin/phpunit PU/Unit/MedicamentoModelTest.php --testdox
```

Deberías ver algo como:

```
MedicamentoModel (PU-001)
 ✔ Search con termino valido retorna resultados
 ✔ Search sin resultados retorna array vacio
 ✔ Incrementar busquedas suma uno al contador
 ✔ Search por laboratorio retorna resultados
```

### Paso 3 — Ejecutar una prueba específica

```bash
php vendor/bin/phpunit PU/Unit/MedicamentoModelTest.php --filter testSearchConTerminoValidoRetornaResultados --testdox
```

---

## PU-001-U01 — search() con término válido retorna resultados

**Método:** `MedicamentoModel::search(string $term)`
**Precondición:** BD con al menos un medicamento con nombre "Paracetamol"

### Ejecución manual paso a paso

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Abrir terminal en `c:\xampp\htdocs\swipre-med\` | Terminal lista |
| 2 | Ejecutar: `php vendor/bin/phpunit PU/Unit/MedicamentoModelTest.php --filter testSearchConTerminoValidoRetornaResultados` | Test corre |
| 3 | Verificar que retorna `OK (1 test, 3 assertions)` | Sin errores |
| 4 | Verificar internamente: el resultado es un array con `nombre` que contiene "Paracetamol" | Array no vacío con medicamento correcto |

**Datos de entrada:** `search('Paracetamol')`
**Resultado esperado:** `array` con al menos 1 elemento donde `$result[0]['nombre']` contiene "Paracetamol"
**Resultado obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## PU-001-U02 — search() sin resultados retorna array vacío

**Método:** `MedicamentoModel::search(string $term)`
**Precondición:** BD sin medicamentos llamados "xyzabc999"

### Ejecución manual paso a paso

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Ejecutar: `php vendor/bin/phpunit PU/Unit/MedicamentoModelTest.php --filter testSearchSinResultadosRetornaArrayVacio` | Test corre |
| 2 | Verificar que retorna `OK (1 test, 2 assertions)` | Sin errores |
| 3 | Confirmar que el array devuelto está vacío (`count = 0`) | `[]` |

**Datos de entrada:** `search('xyzabc999medicamento')`
**Resultado esperado:** `[]` (array vacío)
**Resultado obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## PU-001-U03 — incrementarBusquedas() suma 1 al contador

**Método:** `MedicamentoModel::incrementarBusquedas(array $ids)`
**Precondición:** Medicamento con `id=1` existe en BD

### Ejecución manual paso a paso

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | En HeidiSQL: `SELECT id, busquedas FROM medicamentos WHERE id = 1;` | Anotar valor de `busquedas` |
| 2 | Ejecutar: `php vendor/bin/phpunit PU/Unit/MedicamentoModelTest.php --filter testIncrementarBusquedasSumaUno` | Test corre |
| 3 | En HeidiSQL: `SELECT busquedas FROM medicamentos WHERE id = 1;` | Valor incrementado en 1 |
| 4 | Verificar que retorna `OK (1 test, 1 assertion)` | Sin errores |

**Datos de entrada:** `incrementarBusquedas([1])`
**Resultado esperado:** `busquedas` del medicamento id=1 aumenta en +1
**Resultado obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## PU-001-U04 — search() por nombre de laboratorio retorna resultados

**Método:** `MedicamentoModel::search(string $term)`

### Ejecución manual paso a paso

| Paso | Acción | Resultado Esperado |
|---|---|---|
| 1 | Ejecutar: `php vendor/bin/phpunit PU/Unit/MedicamentoModelTest.php --filter testSearchPorLaboratorioRetornaResultados` | Test corre |
| 2 | Verificar que retorna `OK (1 test, 2 assertions)` | Sin errores |
| 3 | Confirmar que todos los resultados tienen `laboratorio = 'MediLab'` | Resultados filtrados correctamente |

**Datos de entrada:** `search('MediLab')`
**Resultado esperado:** Array con medicamentos cuyo laboratorio es "MediLab"
**Resultado obtenido:** ___
**Estado:** ⬜ Pendiente / ✅ Aprobado / ❌ Fallido

---

## Referencia de automatización

Ver: [`PU/Unit/MedicamentoModelTest.php`](Unit/MedicamentoModelTest.php)
Workflow: [`.github/workflows/pruebas-automatizadas.yml`](../.github/workflows/pruebas-automatizadas.yml) — Job `unit-tests`
