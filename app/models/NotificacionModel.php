<?php
require_once BASE_PATH . '/core/Model.php';

class NotificacionModel extends Model {
    protected string $table = 'notificaciones';

    public function getNoLeidas(int $userId): array {
        return $this->findWhere(['leida' => 0]);
    }

    public function countNoLeidas(): int {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE leida = 0";
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    public function marcarLeida(int $id): bool {
        return $this->update($id, ['leida' => 1, 'leida_at' => date('Y-m-d H:i:s')]);
    }

    public function marcarTodas(): bool {
        return $this->execute(
            "UPDATE {$this->table} SET leida = 1, leida_at = ? WHERE leida = 0",
            [date('Y-m-d H:i:s')]
        );
    }

    public function generarAlertas(array $criticos, array $porVencer): void {
        foreach ($criticos as $med) {
            $exists = $this->queryOne(
                "SELECT id FROM {$this->table} WHERE medicamento_id = ? AND tipo = 'stock_critico' AND leida = 0",
                [$med['id']]
            );
            if (!$exists) {
                $this->insert([
                    'tipo'           => 'stock_critico',
                    'medicamento_id' => $med['id'],
                    'mensaje'        => "{$med['nombre']} tiene stock crítico ({$med['stock_actual']} unidades)",
                    'leida'          => 0,
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            }
        }
        foreach ($porVencer as $med) {
            $diasRestantes = (int) ceil((strtotime($med['fecha_vencimiento']) - time()) / 86400);
            $exists = $this->queryOne(
                "SELECT id FROM {$this->table} WHERE medicamento_id = ? AND tipo = 'por_vencer' AND leida = 0",
                [$med['id']]
            );
            if (!$exists) {
                $this->insert([
                    'tipo'           => 'por_vencer',
                    'medicamento_id' => $med['id'],
                    'mensaje'        => "{$med['nombre']} vence en $diasRestantes días",
                    'leida'          => 0,
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
