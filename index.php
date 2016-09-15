<?php

session_start();

require __DIR__ . "/vendor/autoload.php";

$config = require __DIR__ . "/config/config.php";

$app = new \Slim\App(["settings" => $config]);

require __DIR__ . "/config/routes.php";
require __DIR__ . "/config/additional.php";

$app->run();