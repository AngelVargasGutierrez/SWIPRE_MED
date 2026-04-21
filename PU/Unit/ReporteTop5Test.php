<?php
// PU-002 — Pruebas Unitarias: Visualizar Reporte Top 5 Medicamentos
use PHPUnit\Framework\TestCase;

class ReporteTop5Test extends TestCase
{
    private MedicamentoModel $model;

    protected function setUp(): void
    {
        $this->model = new MedicamentoModel();
    }

    // PU-002-U01: getTop5MasBuscados() retorna exactamente 5 registros
    public function testGetTop5MasBuscadosRetornaMaximoCinco(): void
    {
        $results = $this->model->getTop5MasBuscados();

        $this->assertIsArray($results);
        $this->assertCount(5, $results);
    }

    // PU-002-U02: getTop5MasBuscados() está ordenado de mayor a menor
    public function testGetTop5MasBuscadosEstaOrdenadoDescendente(): void
    {
        $results = $this->model->getTop5MasBuscados();

        for ($i = 0; $i < count($results) - 1; $i++) {
            $this->assertGreaterThanOrEqual(
                (int) $results[$i + 1]['busquedas'],
                (int) $results[$i]['busquedas'],
                "Posición $i tiene menos búsquedas que la posición siguiente"
            );
        }
    }

    // PU-002-U03: getTop5MasBuscados() tiene claves 'nombre' y 'busquedas'
    public function testGetTop5MasBuscadosTieneEstructuraCorrecta(): void
    {
        $results = $this->model->getTop5MasBuscados();

        $this->assertArrayHasKey('nombre',    $results[0]);
        $this->assertArrayHasKey('busquedas', $results[0]);
    }

    // PU-002-U04: getEstadoStock() retorna las claves normal, bajo, critico
    public function testGetEstadoStockRetornaEstructuraCorrecta(): void
    {
        $estado = $this->model->getEstadoStock();

        $this->assertArrayHasKey('normal',  $estado);
        $this->assertArrayHasKey('bajo',    $estado);
        $this->assertArrayHasKey('critico', $estado);
    }

    // PU-002-U05: normal + bajo + critico == total de medicamentos
    public function testSumaEstadosIgualTotalMedicamentos(): void
    {
        $estado = $this->model->getEstadoStock();
        $suma   = (int) $estado['normal'] + (int) $estado['bajo'] + (int) $estado['critico'];
        $total  = $this->model->count();

        $this->assertEquals($total, $suma);
    }
}
