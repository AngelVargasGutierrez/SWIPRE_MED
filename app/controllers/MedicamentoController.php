<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/MedicamentoModel.php';
require_once BASE_PATH . '/app/models/NotificacionModel.php';

class MedicamentoController extends Controller {
    private MedicamentoModel $model;
    private NotificacionModel $notifModel;

    public function __construct() {
        $this->model      = new MedicamentoModel();
        $this->notifModel = new NotificacionModel();
    }

    public function index(): void {
        $this->requireAuth();
        $laboratorio  = $_GET['laboratorio'] ?? null;
        $medicamentos = $laboratorio
            ? $this->model->filterByLaboratorio($laboratorio)
            : $this->model->getAllWithStatus();

        $this->view('medicamentos.index', [
            'medicamentos'  => $medicamentos,
            'laboratorios'  => $this->model->getLaboratorios(),
            'selectedLab'   => $laboratorio,
            'notifCount'    => $this->notifModel->countNoLeidas(),
            'flash'         => $this->getFlash(),
        ]);
    }

    public function create(): void {
        $this->requireAuth();
        $this->view('medicamentos.create', [
            'laboratorios' => $this->model->getLaboratorios(),
            'notifCount'   => $this->notifModel->countNoLeidas(),
        ]);
    }

    public function store(): void {
        $this->requireAuth();
        $campos = ['nombre','laboratorio','categoria','costo_unitario','stock_minimo','stock_actual','numero_lote','fecha_vencimiento'];
        $data   = [];
        foreach ($campos as $campo) {
            $data[$campo] = trim($_POST[$campo] ?? '');
        }

        if (in_array('', array_values($data), true)) {
            $this->flash('error', 'Complete todos los campos obligatorios.');
            $this->redirect('/medicamentos/create');
            return;
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $this->model->insert($data);
        $this->flash('success', 'Medicamento registrado exitosamente.');
        $this->redirect('/medicamentos');
    }

    public function show(string $id): void {
        $this->requireAuth();
        $med = $this->model->findById((int)$id);
        if (!$med) {
            $this->flash('error', 'Medicamento no encontrado.');
            $this->redirect('/medicamentos');
        }

        $this->model->incrementarConsultas((int)$id);

        require_once BASE_PATH . '/app/models/MovimientoModel.php';
        $movModel = new MovimientoModel();

        $this->view('medicamentos.show', [
            'medicamento' => $med,
            'movimientos' => $movModel->getByMedicamento((int)$id),
            'notifCount'  => $this->notifModel->countNoLeidas(),
        ]);
    }

    public function edit(string $id): void {
        $this->requireAuth();
        $med = $this->model->findById((int)$id);
        if (!$med) {
            $this->flash('error', 'Medicamento no encontrado.');
            $this->redirect('/medicamentos');
        }
        $this->view('medicamentos.edit', [
            'medicamento'  => $med,
            'laboratorios' => $this->model->getLaboratorios(),
            'notifCount'   => $this->notifModel->countNoLeidas(),
        ]);
    }

    public function update(string $id): void {
        $this->requireAuth();
        $campos = ['nombre','laboratorio','categoria','costo_unitario','stock_minimo','stock_actual','numero_lote','fecha_vencimiento'];
        $data   = [];
        foreach ($campos as $campo) {
            $data[$campo] = trim($_POST[$campo] ?? '');
        }
        $this->model->update((int)$id, $data);
        $this->flash('success', 'Medicamento actualizado correctamente.');
        $this->redirect('/medicamentos');
    }

    public function delete(string $id): void {
        $this->requireRole('admin');
        $this->model->delete((int)$id);
        $this->flash('success', 'Medicamento eliminado.');
        $this->redirect('/medicamentos');
    }

    public function search(): void {
        $this->requireAuth();
        $term        = $_GET['q'] ?? '';
        $laboratorio = $_GET['lab'] ?? null;

        if (empty($term)) {
            $this->json([]);
            return;
        }

        $resultados = $this->model->search($term, $laboratorio ?: null);

        if (strlen($term) >= 3 && !empty($resultados)) {
            $ids = array_column($resultados, 'id');
            $this->model->incrementarBusquedas($ids);
        }

        $this->json($resultados);
    }
}
