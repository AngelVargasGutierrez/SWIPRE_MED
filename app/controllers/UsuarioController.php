<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/UserModel.php';
require_once BASE_PATH . '/app/models/NotificacionModel.php';

class UsuarioController extends Controller {
    private UserModel $model;
    private NotificacionModel $notifModel;

    public function __construct() {
        $this->model      = new UserModel();
        $this->notifModel = new NotificacionModel();
    }

    public function index(): void {
        $this->requireRole('admin');
        $this->view('usuarios.index', [
            'usuarios'   => $this->model->findAll('nombre ASC'),
            'notifCount' => $this->notifModel->countNoLeidas(),
            'flash'      => $this->getFlash(),
        ]);
    }

    public function create(): void {
        $this->requireRole('admin');
        $this->view('usuarios.create', ['notifCount' => $this->notifModel->countNoLeidas()]);
    }

    public function store(): void {
        $this->requireRole('admin');
        $data = [
            'nombre'   => trim($_POST['nombre'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'rol'      => $_POST['rol'] ?? 'farmacia',
            'email'    => trim($_POST['email'] ?? ''),
        ];

        if (in_array('', [$data['nombre'], $data['username'], $data['password']], true)) {
            $this->flash('error', 'Complete todos los campos.');
            $this->redirect('/usuarios/create');
            return;
        }

        if ($this->model->findByUsername($data['username'])) {
            $this->flash('error', 'El nombre de usuario ya existe.');
            $this->redirect('/usuarios/create');
            return;
        }

        $this->model->createUser($data);
        $this->flash('success', 'Usuario creado exitosamente.');
        $this->redirect('/usuarios');
    }

    public function edit(string $id): void {
        $this->requireRole('admin');
        $user = $this->model->findById((int)$id);
        if (!$user) { $this->redirect('/usuarios'); }
        $this->view('usuarios.edit', ['usuario' => $user, 'notifCount' => $this->notifModel->countNoLeidas()]);
    }

    public function update(string $id): void {
        $this->requireRole('admin');
        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'rol'    => $_POST['rol'] ?? 'farmacia',
            'email'  => trim($_POST['email'] ?? ''),
        ];
        if (!empty($_POST['password'])) {
            $this->model->updatePassword((int)$id, $_POST['password']);
        }
        $this->model->update((int)$id, $data);
        $this->flash('success', 'Usuario actualizado.');
        $this->redirect('/usuarios');
    }

    public function delete(string $id): void {
        $this->requireRole('admin');
        if ((int)$id === (int)$_SESSION['user']['id']) {
            $this->flash('error', 'No puedes eliminar tu propio usuario.');
            $this->redirect('/usuarios');
            return;
        }
        $this->model->delete((int)$id);
        $this->flash('success', 'Usuario eliminado.');
        $this->redirect('/usuarios');
    }
}
