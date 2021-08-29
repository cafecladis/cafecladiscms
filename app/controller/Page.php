<?php

declare(strict_types=1);

/**
 * Controller class Page
 *
 * @package CafeCladisCMS
 * @author Frau Cladis <hey@fraucladis.de>
 * @version 0.0.1
 * @copyright Copyright (c) 2021, Café Cladis
 * @license GPL
 */
class Page extends Controller
{
    public function home()
    {
        $data['title'] = SITE_NAME . ' :: Home';
        $this->view('home', 'main', $data);
    }
}
