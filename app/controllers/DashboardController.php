<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/MedicamentoModel.php';
require_once BASE_PATH . '/app/models/MovimientoModel.php';
require_once BASE_PATH . '/app/models/NotificacionModel.php';

class DashboardController extends Controller {
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

        $criticos   = $this->medModel->getCriticos();
        $porVencer  = $this->medModel->getPorVencer(90);
        $this->notifModel->generarAlertas($criticos, $porVencer);

        $rol  = $_SESSION['user']['rol'] ?? '';

        $data = [
            'totalMedicamentos'  => $this->medModel->count(),
            'stockCritico'       => count($criticos),
            'porVencer'          => count($porVencer),
            'valorTotal'         => $this->medModel->getValorTotal(),
            'stockPorCategoria'  => $this->medModel->getStockPorCategoria(),
            'estadoStock'        => $this->medModel->getEstadoStock(),
            'movimientosSemana'  => $this->movModel->getMovimientosSemana(),
            'top5PorValor'       => $this->medModel->getTop5PorValor(),
            'top5MasBuscados'    => $this->medModel->getTop5MasBuscados(),
            'alertas'            => $this->notifModel->findWhere(['leida' => 0]),
            'movimientosHoy'     => $this->movModel->getTotalHoy(),
            'entradasSemana'     => $this->movModel->getEntradasSemana(),
            'salidasSemana'      => $this->movModel->getSalidasSemana(),
            'notifCount'         => $this->notifModel->countNoLeidas(),
            'flash'              => $this->getFlash(),
            'rolActual'          => $rol,
        ];

        $this->view('dashboard.index', $data);
    }
}
