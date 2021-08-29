<?php

declare(strict_types=1);

/**
 * Controller class
 *
 * @package CafeCladisCMS
 * @author Frau Cladis <hey@fraucladis.de>
 * @version 0.0.1
 * @copyright Copyright (c) 2021, Café Cladis
 * @license GPL
 */
class Controller
{
    /**
     * @param string $viewFileName Filename of view file to display
     * @param array $data Passed data from query string
     * @throws Exception Vie file doesn't exist
     */
    protected function view(string $view, string $layout, array $data = [])
    {
        $layoutContent = $this->getLayoutContent($layout, $data);
        $mainContent = $this->getContent($view, $data);

        echo str_replace('{{content}}', $mainContent, $layoutContent);
    }

    /**
     * Load content of layout file
     *
     * @param string $layout Layout file
     * @param array $data Data to use in layout
     * @return string Layout HTML
     * @throws Exception Layout file doesn't exist
     */
    private function getLayoutContent(string $layout, array $data): string
    {
        $layoutFile = APP_ROOT . '/view/layout/' . basename($layout) . '.php';

        // Check if layout file exists
        if (!is_file($layoutFile)) {
            throw new Exception('Layout file not found: ' . $layoutFile);
        }

        ob_start();
        include $layoutFile;
        return ob_get_clean();
    }

    /**
     * Load content of view file
     *
     * @param string $view View file name
     * @param array $data Data to use in view file
     * @return string Content HTML
     * @throws Exception View files doesn't exist
     */
    private function getContent(string $view, array $data)
    {
        // Path to view file
        $viewFile = APP_ROOT . '/view/' . basename($view) . '.php';

        // Check if view file exists
        if (!is_file($viewFile)) {
            throw new Exception('View file not found: ' . $viewFile);
        }

        ob_start();
        include $viewFile;
        return ob_get_clean();
    }
}
