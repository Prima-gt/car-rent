<?php

class Controller
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function view(string $view, array $data = []): string
    {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!is_file($viewFile)) {
            return '';
        }
        extract($data, EXTR_SKIP);
        ob_start();
        require $viewFile;
        return ob_get_clean();
    }
}

?>

