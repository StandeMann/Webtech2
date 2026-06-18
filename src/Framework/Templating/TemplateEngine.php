<?php

namespace Framework\Templating;

class TemplateEngine implements TemplateEngineInterface
{

    function render(string $template, ...$params): string{

        $address = '/../../../views/' . $template . '.html';

        ob_start();

        require __DIR__ . $address;

        return ob_get_clean();
    }
}