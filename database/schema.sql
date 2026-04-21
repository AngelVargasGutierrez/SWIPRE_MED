-- =============================================
-- SWIPRE-MED Database Schema
-- =============================================

CREATE DATABASE IF NOT EXISTS swipre_med CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE swipre_med;

-- USUARIOS
CREATE TABLE IF NOT EXISTS usuarios (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nombre     VARCHAR(100) NOT NULL,
  username   VARCHAR(50) UNIQUE NOT NULL,
  password   VARCHAR(255) NOT NULL,
  rol        ENUM('admin','jefatura','farmacia') DEFAULT 'farmacia',
  email      VARCHAR(150),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- MEDICAMENTOS
CREATE TABLE IF NOT EXISTS medicamentos (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  nombre            VARCHAR(150) NOT NULL,
  laboratorio       VARCHAR(100) NOT NULL,
  categoria         VARCHAR(80)  NOT NULL,
  costo_unitario    DECIMAL(10,2) NOT NULL DEFAULT 0,
  stock_minimo      INT NOT NULL DEFAULT 0,
  stock_actual      INT NOT NULL DEFAULT 0,
  numero_lote       VARCHAR(50)  NOT NULL,
  fecha_vencimiento DATE         NOT NULL,
  created_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_nombre      (nombre),
  INDEX idx_laboratorio (laboratorio),
  INDEX idx_categoria   (categoria)
) ENGINE=InnoDB;

-- MOVIMIENTOS
CREATE TABLE IF NOT EXISTS movimientos (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  medicamento_id INT NOT NULL,
  tipo           ENUM('entrada','salida') NOT NULL,
  cantidad       INT NOT NULL,
  motivo         VARCHAR(200),
  usuario_id     INT NOT NULL,
  fecha          DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id)     REFERENCES usuarios(id),
  INDEX idx_fecha (fecha)
) ENGINE=InnoDB;

-- NOTIFICACIONES
CREATE TABLE IF NOT EXISTS notificaciones (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  tipo           ENUM('stock_critico','por_vencer','otro') NOT NULL,
  medicamento_id INT,
  mensaje        TEXT NOT NULL,
  leida          TINYINT(1) DEFAULT 0,
  leida_at       DATETIME,
  created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- DATOS DE PRUEBA
-- =============================================

-- Usuarios (passwords: admin123, farmacia123, jefatura123)
INSERT INTO usuarios (nombre, username, password, rol, email) VALUES
('Juan Pérez',      'admin',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',    'admin@hospital.com'),
('Ana García',      'farmacia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmacia', 'farmacia@hospital.com'),
('Carlos López',    'jefatura', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jefatura', 'jefatura@hospital.com');

-- Medicamentos de prueba
INSERT INTO medicamentos (nombre, laboratorio, categoria, costo_unitario, stock_minimo, stock_actual, numero_lote, fecha_vencimiento) VALUES
('Paracetamol 500mg',        'Laboratorios ABC',  'Analgésicos',             0.50, 500, 1500, 'PAR-2024-001', '2025-12-30'),
('Ibuprofeno 400mg',         'Farmacéutica XYZ',  'Antiinflamatorios',       0.75, 300,  250, 'IBU-2024-005', '2025-06-29'),
('Amoxicilina 500mg',        'MediLab',           'Antibióticos',            1.20, 200,   80, 'AMO-2024-012', '2026-03-15'),
('Omeprazol 20mg',           'BioFarma',          'Antiácidos',              0.80, 400,  420, 'OME-2024-003', '2026-08-20'),
('Losartán 50mg',            'CardioLab',         'Antihipertensivos',       0.95, 350,  380, 'LOS-2024-007', '2026-01-10'),
('Metformina 850mg',         'DiabetesCare',      'Antidiabéticos',          0.60, 600,  180, 'MET-2024-009', '2025-11-25'),
('Atorvastatina 20mg',       'CardioLab',         'Hipolipemiantes',         1.50, 300, 1200, 'ATO-2024-004', '2026-07-14'),
('Salbutamol Inhalador',     'RespiraLab',        'Broncodilatadores',       5.00, 100,  150, 'SAL-2024-011', '2026-04-30'),
('Loratadina 10mg',          'AlérgicosMed',      'Antihistamínicos',        0.40, 250,  700, 'LOR-2024-006', '2026-09-12'),
('Diclofenaco Gel 1%',       'TopicalMed',        'Antiinflamatorios tópicos',3.00,150,  320, 'DIC-2024-013', '2026-02-28'),
('Alprazolam 0.5mg',         'NeuroPharma',       'Ansiolíticos',            0.90, 120,  200, 'ALP-2024-008', '2025-10-05'),
('Sertralina 50mg',          'NeuroPharma',       'Antidepresivos',          1.10, 180,  450, 'SER-2024-015', '2026-05-22'),
('Acetaminofén 500mg Jarabe','Laboratorios ABC',  'Analgésicos',             2.50, 200, 2400, 'ACE-2024-002', '2025-12-15'),
('Ciprofloxacino 500mg',     'MediLab',           'Antibióticos',            1.80, 150,  160, 'CIP-2024-014', '2026-01-20'),
('Ranitidina 150mg',         'GastroLab',         'Antiácidos',              0.55, 300,  240, 'RAN-2024-010', '2026-04-10'),
('Amlodipino 5mg',           'Cardiopharma',      'Antihipertensivos',       0.70, 280,  310, 'AML-2024-016', '2026-06-18'),
('Glibenclamida 5mg',        'DiabetesCare',      'Antidiabéticos',          0.45, 350,  480, 'GLI-2024-017', '2026-03-25'),
('Simvastatina 20mg',        'CardioLab',         'Hipolipemiantes',         1.20, 200,  750, 'SIM-2024-018', '2026-08-07'),
('Vitamina C 500mg',         'AnalgésicosPro',    'Vitaminas',               0.30, 400,  900, 'VIT-2024-019', '2026-10-01'),
('Ibuprofeno Jarabe',        'Farmacéutica XYZ',  'Antiinflamatorios',       3.50, 100,  350, 'IBJ-2024-020', '2026-02-14');

-- Movimientos de prueba
INSERT INTO movimientos (medicamento_id, tipo, cantidad, motivo, usuario_id, fecha) VALUES
(1, 'entrada', 500, 'Compra mensual', 1, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(2, 'salida',  50,  'Despacho farmacia', 2, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(3, 'entrada', 100, 'Reposición urgente', 1, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 'salida',  80,  'Despacho diario', 2, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(4, 'entrada', 200, 'Compra programada', 1, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(5, 'salida',  30,  'Dispensación', 2, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(6, 'entrada', 300, 'Compra emergencia', 1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(7, 'salida',  60,  'Despacho pacientes', 3, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(8, 'entrada', 100, 'Reposición', 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(9, 'salida',  45,  'Dispensación alérgicos', 2, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(10,'entrada', 150, 'Compra mensual', 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(11,'salida',  20,  'Prescripción médica', 2, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(1, 'salida',  100, 'Despacho hoy', 2, NOW()),
(13,'entrada', 200, 'Compra jarabe', 1, NOW());
