<?php

use Psr7Middlewares\Middleware\TrailingSlash;
use Slim\Views\PhpRenderer;

/**
 * Remove trailing slash from the url
 */
$app->add(new TrailingSlash(false));

/**
 * Register view component
 */
$container = $app->getContainer();
$container['view'] = function () {
    return new PhpRenderer('src/templates/');
};

// Register flash messages
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};