<?php

$loader = @include __DIR__ . '/../../vendor/autoload.php';
if (!$loader) {
    echo 'Install Nette Tester using `composer update --dev`';
    exit(1);
}

require __DIR__ . "/TestCase.php";

Tester\Environment::setup();

date_default_timezone_set('Europe/Prague');