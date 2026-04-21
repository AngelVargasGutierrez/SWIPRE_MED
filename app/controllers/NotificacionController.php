<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/NotificacionModel.php';

class NotificacionController extends Controller {
    private NotificacionModel $model;

    public function __construct() {
        $this->model = new NotificacionModel();
    }

    public function index(): void {
        $this->requireAuth();
        $this->view('notificaciones.index', [
            'notificaciones' => $this->model->findAll('created_at DESC'),
            'notifCount'     => $this->model->countNoLeidas(),
            'flash'          => $this->getFlash(),
        ]);
    }

    public function marcarLeida(string $id): void {
        $this->requireAuth();
        $this->model->marcarLeida((int)$id);
        $this->redirect('/notificaciones');
    }

    public function marcarTodas(): void {
        $this->requireAuth();
        $this->model->marcarTodas();
        $this->flash('success', 'Todas las notificaciones marcadas como leídas.');
        $this->redirect('/notificaciones');
    }
}
