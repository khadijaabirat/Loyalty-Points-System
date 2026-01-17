<?php
namespace App\Controllers;

use Twig\Environment;

class BaseController {
    protected Environment $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    protected function render(string $template, array $data = []): void {
        echo $this->twig->render($template, $data);
    }
}
