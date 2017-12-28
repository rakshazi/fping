<?php

declare(strict_types=1);
require 'vendor/autoload.php';

$handler = new fping\Handler();
$handler(fgets(STDIN));
