<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/MedicamentoModel.php';
require_once BASE_PATH . '/app/models/MovimientoModel.php';
require_once BASE_PATH . '/app/models/NotificacionModel.php';
require_once BASE_PATH . '/core/Database.php';

class InventarioController extends Controller {
    private MedicamentoModel $medModel;
    private MovimientoModel $movModel;
    private NotificacionModel $notifModel;

    public function __construct() {
        $this->medModel   = new MedicamentoModel();
        $this->movModel   = new MovimientoModel();
        $this->notifModel = new NotificacionModel();
    }

    public function index(): void {
        $this->requireRole('admin', 'jefatura');
        $this->view('inventario.index', [
            'medicamentos' => $this->medModel->getAllWithStatus(),
            'movimientos'  => $this->movModel->getRecientes(30),
            'notifCount'   => $this->notifModel->countNoLeidas(),
            'flash'        => $this->getFlash(),
        ]);
    }

    public function entrada(): void {
        $this->requireRole('admin', 'jefatura');
        $medId    = (int)($_POST['medicamento_id'] ?? 0);
        $cantidad = (int)($_POST['cantidad'] ?? 0);
        $motivo   = trim($_POST['motivo'] ?? 'Entrada de stock');

        if ($medId <= 0 || $cantidad <= 0) {
            $this->flash('error', 'Datos inválidos.');
            $this->redirect('/inventario');
            return;
        }

        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            $med = $this->medModel->findById($medId);
            $this->medModel->update($medId, ['stock_actual' => $med['stock_actual'] + $cantidad]);
            $this->movModel->registrar($medId, 'entrada', $cantidad, $motivo, $_SESSION['user']['id']);
            $db->commit();
            $this->flash('success', "Entrada de $cantidad unidades registrada.");
        } catch (Exception $e) {
            $db->rollBack();
            $this->flash('error', 'Error al registrar entrada.');
        }
        $this->redirect('/inventario');
    }

    public function salida(): void {
        $this->requireRole('admin', 'jefatura');
        $medId    = (int)($_POST['medicamento_id'] ?? 0);
        $cantidad = (int)($_POST['cantidad'] ?? 0);
        $motivo   = trim($_POST['motivo'] ?? 'Salida de stock');

        if ($medId <= 0 || $cantidad <= 0) {
            $this->flash('error', 'Datos inválidos.');
            $this->redirect('/inventario');
            return;
        }

        $db  = Database::getInstance();
        $med = $this->medModel->findById($medId);

        if ($med['stock_actual'] < $cantidad) {
            $this->flash('error', 'Stock insuficiente para realizar la salida.');
            $this->redirect('/inventario');
            return;
        }

        $db->beginTransaction();
        try {
            $this->medModel->update($medId, ['stock_actual' => $med['stock_actual'] - $cantidad]);
            $this->movModel->registrar($medId, 'salida', $cantidad, $motivo, $_SESSION['user']['id']);
            $db->commit();
            $this->flash('success', "Salida de $cantidad unidades registrada.");
        } catch (Exception $e) {
            $db->rollBack();
            $this->flash('error', 'Error al registrar salida.');
        }
        $this->redirect('/inventario');
    }

    public function movimientos(): void {
        $this->requireRole('admin', 'jefatura');
        $this->view('inventario.movimientos', [
            'movimientos' => $this->movModel->getRecientes(100),
            'notifCount'  => $this->notifModel->countNoLeidas(),
        ]);
    }
}
