<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/MedicamentoModel.php';
require_once BASE_PATH . '/app/models/MovimientoModel.php';
require_once BASE_PATH . '/app/models/NotificacionModel.php';

class AnalyticsController extends Controller {
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
        $this->view('analytics.index', [
            'stockPorCategoria'  => $this->medModel->getStockPorCategoria(),
            'estadoStock'        => $this->medModel->getEstadoStock(),
            'movimientosSemana'  => $this->movModel->getMovimientosSemana(),
            'top5Consultados'    => $this->medModel->getTop5MasBuscados(),
            'valorTotal'         => $this->medModel->getValorTotal(),
            'notifCount'         => $this->notifModel->countNoLeidas(),
        ]);
    }

    public function getData(): void {
        $this->requireAuth();
        $this->json([
            'stockPorCategoria' => $this->medModel->getStockPorCategoria(),
            'estadoStock'       => $this->medModel->getEstadoStock(),
            'movimientosSemana' => $this->movModel->getMovimientosSemana(),
            'top5Consultados'   => $this->medModel->getTop5MasBuscados(),
        ]);
    }
}
