<?php
ini_set('display_errors', 0);
$app = require_once __DIR__ . '/app/app.php';
$app->mount('/', new Provider\Controllers\Starter\StarterController());
$app->mount('/', new Provider\Services\Users\MeController());
$app->run();