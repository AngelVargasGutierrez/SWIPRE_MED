<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/UserModel.php';

class AuthController extends Controller {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function loginForm(): void {
        if (!empty($_SESSION['user'])) {
            $this->redirect('/dashboard');
        }
        $this->view('auth.login', ['flash' => $this->getFlash()], 'auth');
    }

    public function login(): void {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->flash('error', 'Complete todos los campos.');
            $this->redirect('/login');
            return;
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $this->flash('error', 'Credenciales incorrectas.');
            $this->redirect('/login');
            return;
        }

        $_SESSION['user'] = [
            'id'     => $user['id'],
            'nombre' => $user['nombre'],
            'username' => $user['username'],
            'rol'    => $user['rol'],
        ];

        $this->redirect('/dashboard');
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('/login');
    }
}
