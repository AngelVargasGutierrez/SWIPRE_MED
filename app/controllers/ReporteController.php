<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/MedicamentoModel.php';
require_once BASE_PATH . '/app/models/MovimientoModel.php';
require_once BASE_PATH . '/app/models/NotificacionModel.php';

class ReporteController extends Controller {
    private MedicamentoModel $medModel;
    private MovimientoModel $movModel;
    private NotificacionModel $notifModel;

    public function __construct() {
        $this->medModel   = new MedicamentoModel();
        $this->movModel   = new MovimientoModel();
        $this->notifModel = new NotificacionModel();
    }

    public function index(): void {
        $this->requireAuth();
        $this->view('reportes.index', [
            'medicamentos'      => $this->medModel->getAllWithStatus(),
            'criticos'          => $this->medModel->getCriticos(),
            'porVencer'         => $this->medModel->getPorVencer(90),
            'movimientos'       => $this->movModel->getRecientes(50),
            'stockPorCategoria' => $this->medModel->getStockPorCategoria(),
            'valorTotal'        => $this->medModel->getValorTotal(),
            'notifCount'        => $this->notifModel->countNoLeidas(),
        ]);
    }

    public function exportar(): void {
        $this->requireAuth();
        $tipo = $_GET['tipo'] ?? 'medicamentos';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_' . $tipo . '_' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        if ($tipo === 'medicamentos') {
            fputcsv($out, ['ID', 'Nombre', 'Laboratorio', 'Categoría', 'Stock', 'Mín.', 'Costo', 'Lote', 'Vencimiento', 'Estado']);
            foreach ($this->medModel->getAllWithStatus() as $m) {
                fputcsv($out, [$m['id'], $m['nombre'], $m['laboratorio'], $m['categoria'],
                    $m['stock_actual'], $m['stock_minimo'], $m['costo_unitario'],
                    $m['numero_lote'], $m['fecha_vencimiento'], $m['estado_stock']]);
            }
        } elseif ($tipo === 'movimientos') {
            fputcsv($out, ['Fecha', 'Medicamento', 'Tipo', 'Cantidad', 'Motivo', 'Usuario']);
            foreach ($this->movModel->getRecientes(500) as $m) {
                fputcsv($out, [$m['fecha'], $m['medicamento_nombre'], $m['tipo'],
                    $m['cantidad'], $m['motivo'], $m['usuario_nombre']]);
            }
        }
        fclose($out);
        exit;
    }
}
