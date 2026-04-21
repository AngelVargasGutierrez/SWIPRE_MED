-- =============================================
-- SWIPRE-MED Seed de datos para graficas
-- =============================================

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE notificaciones;
TRUNCATE TABLE movimientos;
TRUNCATE TABLE medicamentos;
SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- MEDICAMENTOS (30 items, stock variado)
-- Normal, bajo y critico para la grafica de estado
-- =============================================
INSERT INTO medicamentos (nombre, laboratorio, categoria, costo_unitario, stock_minimo, stock_actual, numero_lote, fecha_vencimiento) VALUES
-- Analgésicos
('Paracetamol 500mg',         'Laboratorios ABC',  'Analgésicos',         0.50,  500, 1850, 'PAR-2025-001', '2027-06-30'),
('Acetaminofen 500mg Jarabe', 'Laboratorios ABC',  'Analgésicos',         2.50,  200,  420, 'ACE-2025-002', '2026-12-15'),
('Tramadol 50mg',             'AlgoPharma',        'Analgésicos',         1.80,  150,   40, 'TRA-2025-003', '2026-08-20'),
-- Antiinflamatorios
('Ibuprofeno 400mg',          'Farmaceutica XYZ',  'Antiinflamatorios',   0.75,  300,  260, 'IBU-2025-004', '2026-05-10'),
('Diclofenaco 50mg',          'Farmaceutica XYZ',  'Antiinflamatorios',   0.65,  250,   70, 'DIC-2025-005', '2026-03-22'),
('Naproxeno 500mg',           'AntiPharm',         'Antiinflamatorios',   0.90,  200,  890, 'NAP-2025-006', '2027-01-14'),
('Diclofenaco Gel 1%',        'TopicalMed',        'Antiinflamatorios',   3.00,  150,  310, 'DCG-2025-007', '2026-07-28'),
-- Antibioticos
('Amoxicilina 500mg',         'MediLab',           'Antibioticos',        1.20,  200,   55, 'AMO-2025-008', '2026-09-15'),
('Ciprofloxacino 500mg',      'MediLab',           'Antibioticos',        1.80,  150,  170, 'CIP-2025-009', '2026-11-20'),
('Azitromicina 500mg',        'BioFarma',          'Antibioticos',        2.20,  100,  230, 'AZI-2025-010', '2027-02-10'),
('Metronidazol 500mg',        'GenericMed',        'Antibioticos',        0.80,  180,   48, 'MET-2025-011', '2025-11-30'),
-- Antiacidos
('Omeprazol 20mg',            'BioFarma',          'Antiacidos',          0.80,  400,  950, 'OME-2025-012', '2027-04-20'),
('Ranitidina 150mg',          'GastroLab',         'Antiacidos',          0.55,  300,  230, 'RAN-2025-013', '2026-06-10'),
('Pantoprazol 40mg',          'GastroLab',         'Antiacidos',          1.10,  250,  560, 'PAN-2025-014', '2027-08-07'),
-- Antihipertensivos
('Losartan 50mg',             'CardioLab',         'Antihipertensivos',   0.95,  350,  390, 'LOS-2025-015', '2026-10-10'),
('Amlodipino 5mg',            'Cardiopharma',      'Antihipertensivos',   0.70,  280,  320, 'AML-2025-016', '2027-03-18'),
('Enalapril 10mg',            'CardioLab',         'Antihipertensivos',   0.60,  320,   85, 'ENA-2025-017', '2026-01-25'),
-- Antidiabeticos
('Metformina 850mg',          'DiabetesCare',      'Antidiabeticos',      0.60,  600,  190, 'MFR-2025-018', '2026-08-25'),
('Glibenclamida 5mg',         'DiabetesCare',      'Antidiabeticos',      0.45,  350,  480, 'GLI-2025-019', '2027-05-25'),
('Insulina NPH 100UI',        'DiabetesCare',      'Antidiabeticos',     15.00,   80,  110, 'INS-2025-020', '2026-02-28'),
-- Hipolipemiantes
('Atorvastatina 20mg',        'CardioLab',         'Hipolipemiantes',     1.50,  300, 1200, 'ATO-2025-021', '2027-07-14'),
('Simvastatina 20mg',         'CardioLab',         'Hipolipemiantes',     1.20,  200,  730, 'SIM-2025-022', '2027-06-07'),
-- Broncodilatadores
('Salbutamol Inhalador',      'RespiraLab',        'Broncodilatadores',   5.00,  100,  145, 'SAL-2025-023', '2026-12-30'),
('Budesonida Inhalador',      'RespiraLab',        'Broncodilatadores',   8.50,   60,   22, 'BUD-2025-024', '2026-04-15'),
-- Antihistaminicos
('Loratadina 10mg',           'AlErgicosMed',      'Antihistaminicos',    0.40,  250,  690, 'LOR-2025-025', '2027-09-12'),
('Cetirizina 10mg',           'AlErgicosMed',      'Antihistaminicos',    0.50,  200,  410, 'CET-2025-026', '2027-08-22'),
-- Ansioliticos y Antidepresivos
('Alprazolam 0.5mg',          'NeuroPharma',       'Ansioliticos',        0.90,  120,  195, 'ALP-2025-027', '2025-10-05'),
('Sertralina 50mg',           'NeuroPharma',       'Antidepresivos',      1.10,  180,  440, 'SER-2025-028', '2027-03-22'),
('Fluoxetina 20mg',           'NeuroPharma',       'Antidepresivos',      0.85,  160,   30, 'FLU-2025-029', '2025-12-01'),
-- Vitaminas
('Vitamina C 500mg',          'VitaminasPlus',     'Vitaminas',           0.30,  400,  880, 'VIC-2025-030', '2027-10-01');

-- =============================================
-- MOVIMIENTOS (ultimos 7 dias)
-- =============================================
INSERT INTO movimientos (medicamento_id, tipo, cantidad, motivo, usuario_id, fecha) VALUES
-- Hace 7 dias
(1,  'entrada', 600, 'Compra mensual',              1, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(4,  'entrada', 200, 'Reposicion programada',        1, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(12, 'entrada', 400, 'Compra mensual',              1, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(2,  'salida',   80, 'Despacho farmacia',           2, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(8,  'salida',   30, 'Dispensacion antibiotico',    2, DATE_SUB(NOW(), INTERVAL 7 DAY)),
-- Hace 6 dias
(1,  'salida',  120, 'Despacho diario',             2, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(3,  'entrada', 150, 'Reposicion urgente',           1, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(15, 'entrada', 200, 'Compra programada',           1, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(21, 'entrada', 300, 'Compra mensual',              1, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(5,  'salida',   40, 'Dispensacion',                2, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(18, 'salida',   60, 'Despacho diabeticos',         3, DATE_SUB(NOW(), INTERVAL 6 DAY)),
-- Hace 5 dias
(6,  'entrada', 250, 'Reposicion naproxeno',        1, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(10, 'entrada', 120, 'Compra antibioticos',         1, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(25, 'entrada', 300, 'Compra antihistaminico',      1, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1,  'salida',  150, 'Despacho diario',             2, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(9,  'salida',   45, 'Prescripcion medica',         2, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(16, 'salida',   55, 'Despacho hipertension',       3, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(22, 'salida',   70, 'Despacho hipolipemiante',     2, DATE_SUB(NOW(), INTERVAL 5 DAY)),
-- Hace 4 dias
(13, 'entrada', 150, 'Compra antiacidos',           1, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(19, 'entrada', 200, 'Reposicion glibenclamida',    1, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(28, 'entrada', 180, 'Compra antidepresivo',        1, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(4,  'salida',   90, 'Despacho antiinflamatorio',   2, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(12, 'salida',  130, 'Dispensacion omeprazol',      2, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(20, 'salida',   15, 'Prescripcion insulina',       3, DATE_SUB(NOW(), INTERVAL 4 DAY)),
-- Hace 3 dias
(7,  'entrada', 180, 'Compra gel diclofenaco',      1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(23, 'entrada', 100, 'Reposicion inhaladores',      1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(30, 'entrada', 400, 'Compra vitaminas',            1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(1,  'salida',  200, 'Despacho diario alto',        2, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(15, 'salida',   60, 'Despacho antihipertensivo',   2, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(27, 'salida',   25, 'Prescripcion ansiolitico',    2, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(8,  'salida',   40, 'Despacho amoxicilina',        3, DATE_SUB(NOW(), INTERVAL 3 DAY)),
-- Hace 2 dias
(2,  'entrada', 200, 'Reposicion jarabe',           1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(11, 'entrada', 100, 'Reposicion metronidazol',     1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(17, 'entrada', 150, 'Compra enalapril',            1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(6,  'salida',   80, 'Despacho naproxeno',          2, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(14, 'salida',   90, 'Dispensacion pantoprazol',    2, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(21, 'salida',  100, 'Despacho atorvastatina',      3, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(26, 'salida',   50, 'Despacho cetirizina',         2, DATE_SUB(NOW(), INTERVAL 2 DAY)),
-- Hace 1 dia
(5,  'entrada', 200, 'Reposicion diclofenaco',      1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(24, 'entrada',  50, 'Compra budesonida',           1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(29, 'entrada', 100, 'Compra fluoxetina',           1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(1,  'salida',  180, 'Despacho diario',             2, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(10, 'salida',   35, 'Despacho azitromicina',       2, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(18, 'salida',   70, 'Despacho metformina',         3, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(25, 'salida',   60, 'Despacho loratadina',         2, DATE_SUB(NOW(), INTERVAL 1 DAY)),
-- Hoy
(3,  'entrada', 200, 'Compra tramadol hoy',         1, NOW()),
(12, 'entrada', 300, 'Reposicion omeprazol hoy',    1, NOW()),
(1,  'salida',  160, 'Despacho hoy',                2, NOW()),
(4,  'salida',   75, 'Dispensacion hoy',            2, NOW()),
(20, 'salida',   10, 'Prescripcion insulina hoy',   3, NOW()),
(23, 'salida',   20, 'Despacho inhaladores hoy',    2, NOW());

-- =============================================
-- NOTIFICACIONES
-- =============================================
INSERT INTO notificaciones (tipo, medicamento_id, mensaje, leida, created_at) VALUES
('stock_critico',  3,  'ALERTA: Tramadol 50mg tiene stock critico (40 uds, minimo 150)',        0, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('stock_critico',  5,  'ALERTA: Diclofenaco 50mg tiene stock critico (70 uds, minimo 250)',     0, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('stock_critico',  8,  'ALERTA: Amoxicilina 500mg tiene stock critico (55 uds, minimo 200)',    0, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('stock_critico', 11,  'ALERTA: Metronidazol 500mg tiene stock critico (48 uds, minimo 180)',   0, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('stock_critico', 17,  'ALERTA: Enalapril 10mg tiene stock bajo (85 uds, minimo 320)',          0, NOW()),
('stock_critico', 24,  'ALERTA: Budesonida Inhalador tiene stock critico (22 uds, minimo 60)',  0, NOW()),
('stock_critico', 29,  'ALERTA: Fluoxetina 20mg tiene stock critico (30 uds, minimo 160)',      0, NOW()),
('por_vencer',    11,  'VENCIMIENTO: Metronidazol 500mg vence el 2025-11-30',                   0, NOW()),
('por_vencer',    27,  'VENCIMIENTO: Alprazolam 0.5mg vence el 2025-10-05',                     0, NOW()),
('por_vencer',    29,  'VENCIMIENTO: Fluoxetina 20mg vence el 2025-12-01',                      0, NOW());
