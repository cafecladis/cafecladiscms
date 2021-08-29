<?php

declare(strict_types=1);

/**
 * Main layout
 *
 * @package CafeCladisCMS
 * @author Frau Cladis <hey@fraucladis.de>
 * @version 0.0.1
 * @copyright Copyright (c) 2021, Café Cladis
 * @license GPL
 */
?>
<!DOCTYPE html>
<html lang="<?= SITE_LANGUAGE ?>">
<head>
    <meta charset="<?= SITE_CHARSET ?>>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
</head>
<body>
{{content}}
</body>
</html>
