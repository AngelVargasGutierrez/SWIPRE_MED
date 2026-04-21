<?php
require_once BASE_PATH . '/core/Model.php';

class MedicamentoModel extends Model {
    protected string $table = 'medicamentos';

    public function getAllWithStatus(): array {
        $sql = "SELECT m.*,
                    CASE
                        WHEN m.stock_actual <= m.stock_minimo * 0.3 THEN 'critico'
                        WHEN m.stock_actual <= m.stock_minimo THEN 'bajo'
                        ELSE 'normal'
                    END AS estado_stock
                FROM {$this->table} m
                ORDER BY m.nombre ASC";
        return $this->query($sql);
    }

    public function search(string $term, ?string $laboratorio = null): array {
        $sql = "SELECT m.*,
                    CASE
                        WHEN m.stock_actual <= m.stock_minimo * 0.3 THEN 'critico'
                        WHEN m.stock_actual <= m.stock_minimo THEN 'bajo'
                        ELSE 'normal'
                    END AS estado_stock
                FROM {$this->table} m
                WHERE (m.nombre LIKE ? OR m.laboratorio LIKE ? OR m.categoria LIKE ?)";
        $params = ["%$term%", "%$term%", "%$term%"];
        if ($laboratorio) {
            $sql .= " AND m.laboratorio = ?";
            $params[] = $laboratorio;
        }
        $sql .= " ORDER BY m.nombre ASC";
        return $this->query($sql, $params);
    }

    public function getCriticos(): array {
        $sql = "SELECT * FROM {$this->table}
                WHERE stock_actual <= stock_minimo
                ORDER BY stock_actual ASC";
        return $this->query($sql);
    }

    public function getPorVencer(int $dias = 90): array {
        $sql = "SELECT * FROM {$this->table}
                WHERE fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
                ORDER BY fecha_vencimiento ASC";
        return $this->query($sql, [$dias]);
    }

    public function getStockPorCategoria(): array {
        $sql = "SELECT categoria, SUM(stock_actual) as total_stock
                FROM {$this->table}
                GROUP BY categoria
                ORDER BY total_stock DESC";
        return $this->query($sql);
    }

    public function getValorTotal(): float {
        $sql = "SELECT SUM(stock_actual * costo_unitario) as valor_total FROM {$this->table}";
        $result = $this->queryOne($sql);
        return (float) ($result['valor_total'] ?? 0);
    }

    public function getLaboratorios(): array {
        $sql = "SELECT DISTINCT laboratorio FROM {$this->table} ORDER BY laboratorio ASC";
        return array_column($this->query($sql), 'laboratorio');
    }

    public function getTop5PorValor(): array {
        $sql = "SELECT nombre, (stock_actual * costo_unitario) as valor_total
                FROM {$this->table}
                ORDER BY valor_total DESC
                LIMIT 5";
        return $this->query($sql);
    }

    public function getEstadoStock(): array {
        $sql = "SELECT
                    SUM(CASE WHEN stock_actual > stock_minimo THEN 1 ELSE 0 END) as normal,
                    SUM(CASE WHEN stock_actual > stock_minimo * 0.3 AND stock_actual <= stock_minimo THEN 1 ELSE 0 END) as bajo,
                    SUM(CASE WHEN stock_actual <= stock_minimo * 0.3 THEN 1 ELSE 0 END) as critico
                FROM {$this->table}";
        return $this->queryOne($sql) ?? ['normal' => 0, 'bajo' => 0, 'critico' => 0];
    }

    public function filterByLaboratorio(string $laboratorio): array {
        $sql = "SELECT m.*,
                    CASE
                        WHEN m.stock_actual <= m.stock_minimo * 0.3 THEN 'critico'
                        WHEN m.stock_actual <= m.stock_minimo THEN 'bajo'
                        ELSE 'normal'
                    END AS estado_stock
                FROM {$this->table} m
                WHERE m.laboratorio = ?
                ORDER BY m.nombre ASC";
        return $this->query($sql, [$laboratorio]);
    }
}
