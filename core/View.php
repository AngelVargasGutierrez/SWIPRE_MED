<?php
class View {
    public static function render(string $view, array $data = []): string {
        extract($data);
        $file = BASE_PATH . '/app/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($file)) {
            return '';
        }
        ob_start();
        require $file;
        return ob_get_clean();
    }

    public static function escape(mixed $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
