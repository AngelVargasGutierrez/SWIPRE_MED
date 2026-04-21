<?php
require_once BASE_PATH . '/core/Model.php';

class MovimientoModel extends Model {
    protected string $table = 'movimientos';

    public function registrar(int $medicamentoId, string $tipo, int $cantidad, string $motivo, int $usuarioId): int {
        return $this->insert([
            'medicamento_id' => $medicamentoId,
            'tipo'           => $tipo,
            'cantidad'       => $cantidad,
            'motivo'         => $motivo,
            'usuario_id'     => $usuarioId,
            'fecha'          => date('Y-m-d H:i:s'),
        ]);
    }

    public function getRecientes(int $limit = 50): array {
        $sql = "SELECT mv.*, m.nombre AS medicamento_nombre, u.nombre AS usuario_nombre
                FROM {$this->table} mv
                JOIN medicamentos m ON m.id = mv.medicamento_id
                JOIN usuarios u ON u.id = mv.usuario_id
                ORDER BY mv.fecha DESC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    public function getMovimientosSemana(): array {
        $sql = "SELECT
                    DATE(fecha) as dia,
                    SUM(CASE WHEN tipo = 'entrada' THEN cantidad ELSE 0 END) as entradas,
                    SUM(CASE WHEN tipo = 'salida' THEN cantidad ELSE 0 END) as salidas
                FROM {$this->table}
                WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(fecha)
                ORDER BY dia ASC";
        return $this->query($sql);
    }

    public function getTotalHoy(): int {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE DATE(fecha) = CURDATE()";
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    public function getEntradasSemana(): int {
        $sql = "SELECT SUM(cantidad) FROM {$this->table}
                WHERE tipo = 'entrada' AND fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    public function getSalidasSemana(): int {
        $sql = "SELECT SUM(cantidad) FROM {$this->table}
                WHERE tipo = 'salida' AND fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    public function getByMedicamento(int $medicamentoId): array {
        $sql = "SELECT mv.*, u.nombre AS usuario_nombre
                FROM {$this->table} mv
                JOIN usuarios u ON u.id = mv.usuario_id
                WHERE mv.medicamento_id = ?
                ORDER BY mv.fecha DESC";
        return $this->query($sql, [$medicamentoId]);
    }
}
