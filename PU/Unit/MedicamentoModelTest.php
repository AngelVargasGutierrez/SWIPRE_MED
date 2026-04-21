<?php
// PU-001 — Pruebas Unitarias: Consultar Medicamento
use PHPUnit\Framework\TestCase;

class MedicamentoModelTest extends TestCase
{
    private MedicamentoModel $model;

    protected function setUp(): void
    {
        $this->model = new MedicamentoModel();
    }

    // PU-001-U01: search() con término válido retorna resultados
    public function testSearchConTerminoValidoRetornaResultados(): void
    {
        $results = $this->model->search('Paracetamol');

        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $this->assertStringContainsStringIgnoringCase(
            'Paracetamol',
            $results[0]['nombre']
        );
    }

    // PU-001-U02: search() sin resultados retorna array vacío
    public function testSearchSinResultadosRetornaArrayVacio(): void
    {
        $results = $this->model->search('xyzabc999medicamento');

        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    // PU-001-U03: incrementarBusquedas() suma 1 al contador
    public function testIncrementarBusquedasSumaUno(): void
    {
        $antes = $this->model->findById(1);
        $contadorAntes = (int) $antes['busquedas'];

        $this->model->incrementarBusquedas([1]);

        $despues = $this->model->findById(1);
        $this->assertEquals($contadorAntes + 1, (int) $despues['busquedas']);
    }

    // PU-001-U04: search() por laboratorio retorna resultados
    public function testSearchPorLaboratorioRetornaResultados(): void
    {
        $results = $this->model->search('MediLab');

        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }
}
