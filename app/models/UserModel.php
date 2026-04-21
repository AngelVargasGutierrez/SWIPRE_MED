<?php
require_once BASE_PATH . '/core/Model.php';

class UserModel extends Model {
    protected string $table = 'usuarios';

    public function findByUsername(string $username): ?array {
        return $this->findOneWhere(['username' => $username]);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function createUser(array $data): int {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function updatePassword(int $id, string $newPassword): bool {
        return $this->update($id, ['password' => password_hash($newPassword, PASSWORD_BCRYPT)]);
    }
}
